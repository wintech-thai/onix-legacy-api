<?php
/* 
    Purpose : One base class for Controller
    Created By : Seubpong Monsar
    Created Date : 09/03/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class CBaseController
{
    protected static function PopulateNewCode($data, $fldname)
    {            
        $old_name = $data->GetFieldValue($fldname);
        $pid = getmypid();
        $new_name = "**$old_name:$pid**";
        $data->SetFieldValue($fldname, $new_name);
    }

    protected static function PopulateChildItem($pdata, $pmodel, $arrName, $cmodel, $ind)
    {
        //Use primary key of parent to get child items
        $parentPK = $pmodel->GetPKName();
        $pid = $pdata->GetFieldValue($parentPK);

        $data = new CTable("");
        $data->SetFieldValue($parentPK, $pid);
        
        list($cnt, $rows) = $cmodel->query($ind, $data);
            
        $pdata->AddChildArray($arrName, $rows);
    }

    protected static function PopulateRow($table, $itemCnt, $chunkCnt, $arrName, $arr)
    {
        if ($itemCnt > 0)
        {
            $table->SetFieldValue("EXT_RECORD_COUNT", $itemCnt);
        }

        if ($chunkCnt > 0)
        {
            $table->SetFieldValue("EXT_CHUNK_COUNT", $chunkCnt);        
        }
        
        $table->AddChildArray($arrName, $arr);
    }

    protected static function UnregisterSession($db, $param, $data)
    {
        $session = $param->GetFieldValue("SESSION");

        //Fore security reason we should use only basename of $session
        $session_file =  $_ENV['SESSION_DIR'] . '/' . basename($session);
        if (file_exists($session_file) && (trim($session) != ''))
        {
            unlink($session_file);
        }

        $param->SetFieldValue("SESSION", '');
    }
    
    protected static function GetRowByID($data, $model, $ind)
    {
        $idfield = $model->GetPKName();

        $o = new CTable("");
        $o->SetFieldValue($idfield, $data->GetFieldValue($idfield));

        $td = NULL;

        list($cnt, $rows) = $model->Query($ind, $o);
        if ($cnt == 1) 
        {
            //Must be only 1 row returned
            $td = $rows[0];
        }

        return($td);
    }

    protected static function GetFirstRow($data, $model, $ind, $nolikeFields)
    {
        $td = NULL;

        if ($nolikeFields != '')
        {
            $data->SetFieldValue("!EXT_EQUAL_STRING_COMPARE_FIELDS", "$nolikeFields");  
        }

        list($cnt, $rows) = $model->Query($ind, $data);
        if ($cnt == 1) 
        {
            $td = $rows[0];
        }

        return($td);
    }

    protected static function ValidateForDuplicate($db, $data, $model, $keyCompare, $keyWhere, $ind)
    {
        //Use only Key for query
        $code = $data->GetFieldValue($keyCompare);
        $pkField = $model->GetPKName();

        $d = new CTable("");
        $d->SetFieldValue($keyCompare, $code);

        $obj = self::GetFirstRow($d, $model, $ind, $keyWhere);

        if (!isset($obj))
        {
            //Not already been in database
            return($data);
        }

        $req_id = $obj->GetFieldValue($pkField);
        $org_id = $data->GetFieldValue($pkField);

        if ($org_id != $req_id)
        {
            #item already exists
            throw new Exception("Item [$code] already exist!!!");
        }

        return($obj);
    }    

    protected static function DeleteData($db, $data, $model, $ind, $childCfgArr)
    {
        $tx = false;

        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        foreach ($childCfgArr as $childCfg)
        {
            list($arrName, $m, $queryInd, $addEditInd, $delInd) = $childCfg;
            $m->Delete($delInd, $data);    
        }

        $deleted = $model->Delete($ind, $data);

        if ($deleted <= 0)
        {
            if ($tx || $db->inTransaction())
            {
                $db->rollBack();
            }

            throw new Exception("Unable to delete data, it might be referenced by other items!!!");
        }

        if ($tx)
        {
            $db->commit();
        }        
    }    

    protected static function ProcessChildArray($db, $data, $arrName, $pmodel, $m, $addEditDelInd)
    {
        $arr = $data->GetChildArray($arrName);
        foreach ($arr as $t)
        {        
            $pkname = $pmodel->GetPKName();

            $id = $data->GetFieldValue($pkname);
            $t->SetFieldValue($pkname, $id);
            
            $flag = $t->GetFieldValue("EXT_FLAG");
            $cid = $t->GetFieldValue($m->GetPKName());
            if ($flag == 'D')
            {
                if ($cid != '')
                {
                    $m->Delete($addEditDelInd, $t);
                }
                
                //Continue set to 'D'
            }
            elseif ($flag == 'E')
            {
                $m->Update($addEditDelInd, $t);

                //Item might be reused so reset the flag is the good idea
                $t->SetFieldValue("EXT_FLAG", '');            
            }
            elseif ($flag == 'A')
            {
                $m->Insert($addEditDelInd, $t, true);

                //Item might be reused so reset the flag is the good idea
                $t->SetFieldValue("EXT_FLAG", '');            
            }
        }            
    }

    protected static function manipulateImages($db, $data, $arrName, $wipNameField, $dstNameField, $originalNameField)
    {
        $param = new CTable('');

        $imageArr = $data->getChildArray($arrName);
        $storage = new CTable('');

        foreach ($imageArr as $image)
        {
            //If no change, the orgName and wipName will be identical
            //User will update wipName to indicate new image uploaded

            $flag = $image->getFieldValue('IMAGE_EXT_FLAG');

            $orgName = $image->getFieldValue($originalNameField);
            $wipName = $image->getFieldValue($wipNameField);

            $dstName = $image->getFieldValue($dstNameField);

            $storage->setFieldValue('SRC_FILE_NAME', $wipName);
            $storage->setFieldValue('DST_FILE_NAME', $dstName);

            if ($flag == 'D')
            {
                StorageAPI::StorageDeleteFile($db, $param, $storage);
            }
            else if ($flag == 'A')
            {
                if (($wipName != '') && ($dstName != ''))
                {
                    StorageAPI::StorageCreateFile($db, $param, $storage);
                }
            }
            else if ($flag == 'E') 
            {
                if (($orgName != $wipName) && ($dstName != ''))
                {
//CLog::WriteLn("DEBUG1 : wipName=[$wipName], dstName=[$dstName]");                                    
                    StorageAPI::StorageUpdateFile($db, $param, $storage);
                }
            }
        }
    }

    protected static function CreateData($db, $data, $model, $ind, $childCfgArr)
    {
        $tx = false;

        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        $inserted = $model->Insert($ind, $data, true);

        if ($inserted <= 0)
        {
            if ($tx || $db->inTransaction())
            {
                $db->rollBack();
            }

            throw new Exception("Unable to insert data !!!");
        }

        foreach ($childCfgArr as $childCfg)
        {
            //Create child tables here
            list($arrName, $m, $queryInd, $addEditInd, $delInd) = $childCfg;
            self::ProcessChildArray($db, $data, $arrName, $model, $m, $addEditInd);        
        }

        if ($tx)
        {
            $db->commit();
        }        
    }      

    protected static function UpdateData($db, $data, $model, $ind, $childCfgArr)
    {
        $tx = false;

        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        $updated = $model->Update($ind, $data);
        if ($updated <= 0)
        {
            if ($tx || $db->inTransaction())
            {
                $db->rollBack();
            }

            throw new Exception("Unable to update data, ID might be set incorrectly !!!");
        }

        foreach ($childCfgArr as $childCfg)
        {
            //Create/Update/Delete child tables here
            list($arrName, $m, $queryInd, $addEditInd, $delInd) = $childCfg;
            self::ProcessChildArray($db, $data, $arrName, $model, $m, $addEditInd);        
        }

        if ($tx)
        {
            $db->commit();
        }        
    }  

    protected static function RegisterSession($db, $param, $data)
    {
        //Modify $param->login_id, and session back
        //Create session and login ID
        
        $md5 = "";
        if (!array_key_exists('SESSION_DIR', $_ENV))
        {
            return("");
        }

        #Added hash password to make it harder to guess the session
        $loginDtm = date('Y-m-d H:i:s');
        $txt = $loginDtm . $data->GetFieldValue('PASSWORD');
        $pid = getmypid();
        $md5 = sprintf('X%sX', md5("$txt$pid"));
        
        $filename = $_ENV['SESSION_DIR'] . '/' . $md5;

        $fh = fopen($filename, "w");
        if (!isset($fh))
        {
            throw new Exception("Unable to open file [$filename]!!!");
        }

        $data->SetFieldValue('LOGIN_DATE', $loginDtm);
        $data->SetFieldValue('PID', $pid);

        $prm = new CTable("PARAM");
        $xml = CUtils::CreateResultXML($prm, $data);    

        fwrite($fh, $xml);        
        fclose($fh);
        
        $param->SetFieldValue("SESSION", $md5);
    }

    protected static function PopulateStartEndDate($data, $fld_name, $start_flag)
    {
        $dt = $data->GetFieldValue($fld_name);
    
        $new_dt = CUtils::GetDateEnd($dt);
        if ($start_flag)
        {
            $new_dt = CUtils::GetDateStart($dt);
        }
    
        $data->SetFieldValue($fld_name, $new_dt);
    }

    protected static function PopulateDayRange($data, $fromFld, $toFld, $daynumFld)
    {
        $current_dtm = CUtils::GetCurrentDateTimeInternal();
        $start_curr_dtm =  CUtils::GetDateStart($current_dtm);

        $day_cnt = $data->GetFieldValue($daynumFld);

        if ($day_cnt == '')
        {
            return;
        }

        $tmp = CUtils::DateAdd($current_dtm, $day_cnt);
        $end_date = CUtils::GetDateEnd($tmp);

        $data->SetFieldValue($fromFld, $start_curr_dtm);
        $data->SetFieldValue($toFld, $end_date);     
        
//printf("CURRENT[%s] EFFECTIVE_END[%s] EXPIRE_END[%s]\n", $current_dtm, $start_curr_dtm, $end_date);        
    }    

    protected static function PopulateChildItems($data, $pmodel, $cfgArr)
    {
        foreach($cfgArr as $cfg)
        {
            list($arrName, $m, $queryInd, $addEditInd, $delInd) = $cfg;
            self::PopulateChildItem($data, $pmodel, $arrName, $m, $queryInd);            
        }
    }    

    protected static function InitCopyChildItem($data, $arrName)
    {
        $arr = $data->GetChildArray($arrName);
        foreach ($arr as $t)
        {        
            $t->SetFieldValue('EXT_FLAG', 'A');            
        }
    }

    protected static function InitCopyItems($data, $cfgArr)
    {
        foreach($cfgArr as $cfg)
        {
            list($arrName, $m, $queryInd, $addEditInd, $delInd) = $cfg;
            self::InitCopyChildItem($data, $arrName);            
        }        
    }

    protected static function PreprocessStoragesH($data, $arrName, $fldMapArr)
    {
        $arr = [];
        foreach ($fldMapArr as $tuple)
        {
            list($imgNameFld, $wipImgNameFld, $path) = $tuple;

            $origName = $data->getFieldValue($imgNameFld);
            $wipName = $data->getFieldValue($wipImgNameFld);

            $mode = 'I';
            if (($origName == '') && ($wipName != ''))
            {
                $mode = 'A';

                //Derive image name here
                $dtm = date('Y-m-d H:i:s');
                $msTime = round(microtime(true) * 1000);
                $pid = getmypid();
                $md5 = sprintf('X%sX', md5("$dtm$msTime$pid"));

                $origName = "$path/$md5";          
                $data->setFieldValue($imgNameFld, $origName); //Assigned back      
            }
            else if (($origName != '') && ($wipName != '') && ($wipName != $origName))
            {
                $mode = 'E';
            }
            else if (($origName != '') && ($wipName == ''))
            {
                $mode = 'D';
                $data->setFieldValue($imgNameFld, ''); //Assigned back  
            }            

            $image = new CTable('');
            $image->setFieldValue('IMAGE_NAME_WIP', $wipName);            
            $image->setFieldValue('IMAGE_NAME_DEST', $origName);    
            $image->setFieldValue('IMAGE_EXT_FLAG', $mode);

            array_push($arr, $image);
        } 

        $data->addChildArray($arrName, $arr);
    }    
}

?>