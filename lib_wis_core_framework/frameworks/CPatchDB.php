<?php
/* 
    Purpose : Controller for patching database
    Created By : Seubpong Monsar
    Created Date : 09/10/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class CPatchDB
{
    private static $sqlDir = "";
    private static $patchModel = NULL;
    private static $modelName = '';
    private static $staticPatches = [];
    private static $modelPHP = '';
    private static $customPatches = [];
    private static $postPatches = [];

    private static $extraPatches = [];
    private static $postPatchHistory = NULL;

    private static function getPatchHistoryList($db)
    {
        $tb = new CTable("");
        try
        {
            list($cnt, $rows) = self::$patchModel->Query(0, $tb);
        }
        catch (Exception $e)
        {
            //It is possible that the PATCH_HISTORY does not exist at the beginning
            $cnt = 0;
            $rows = array();
        }

        $lastPatch = NULL;
        foreach ($rows as $row)
        {
            $lastPatch = $row;
        }

        return(array($cnt, $rows, $lastPatch));
    }

    private static function createPatchList($start)
    {
        $temp_arr = array();
    
        $idx = 0;
        foreach (self::$staticPatches as $v)
        {
            if ($idx >= $start)
            {
                array_push($temp_arr, $v);
            }
    
            $idx++;
        }
    
        return($temp_arr);
    }

    private static function patchDatabase($db, $patched_cnt, $lastPatch)
    {
        $items = array();

        if ($patched_cnt > 0)
        {
            #Has already been patched at lease 1
            $items = self::createPatchList($patched_cnt-1);
        }
        else
        {
            #Never been patched
            $items = self::createPatchList(0);
        }
        
        $offset = 1;
        foreach ($items as $v)
        {
            $new_row_id = $patched_cnt + $offset;
            self::patchScript($db, $v, $lastPatch, $new_row_id);
        
            $offset++;
        }
    }

    private static function patchScript($db, $arr, $r, $new_rowid)
    {    
        $script = $arr[1];
        $version = $arr[0];
        $rowid = 0;
    
        $row = array();
    
        $mode = "A";
        $last_point = 0;
    
        $db->beginTransaction();

        if (isset($r))
        {
            $last_version = $r->GetFieldValue('VERSION');
//printf("DEBUG0 : [%s] [%s]\n", $last_version, $version);            
            if ($version == $last_version)
            {
                #Resume
                $mode = "U";
                $last_point = $r->GetFieldValue('LAST_PATCH_POINT');
                $rowid = $r->GetFieldValue('PATCH_ID');
            }
        }
        
        $script_file = self::$sqlDir . "/$script";
    
    #print("[$script_file][$last_point]\n");
        $sql = "";
        $cmd_seq = 0;
    
        $fh = fopen($script_file, 'r');
        if (!$fh)
        {
            throw new Exception("Unable to open SQL file [$script_file]!!!!");
        }

        while ($row = fgets($fh))
        {
            $org_line = $row;
//            chomp $row;
    
            $sql = $sql . $org_line;
    
            if (preg_match('/^.*;.*$/', $row))
            {
                #print("$sql\n");
                $cmd_seq++;
//print("cmdseq=[$cmd_seq] last_point=[$last_point]\n");
                if ($cmd_seq <= $last_point)
                {
                    $sql = "";
                    continue;
                }

                $rv = 0;
    
                if (preg_match_all('/^patch_by_script_(.*)\(.*\);.*$/', $row, $matches))
                {
                    $func_key = $matches[1][0];
                    $rv = self::patchByScript($db, $func_key, 1);
    #print("DEBIG1 [$rv]\n");
                }
                else
                {
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    $rv = $stmt->rowCount();

    //print("EXECUTED [$sql] [$rv]\n");
                }
    
                if ($rv < 0)
                {
    #print("ROLLBACK [$rv]\n");
                    $db->rollBack();
                    fclose($fh);
                    throw new Exception("Unable to execute command [$sql] from SQL file [$script_file]!!!!");
                }
 
                $sql = "";
            }
        }
    
        fclose($fh);
    
        if ($cmd_seq > $last_point)
        {
            $t = new CTable(self::$patchModel->GetTableName());
            $t->SetFieldValue("VERSION", $version);
            $t->SetFieldValue("LAST_PATCH_POINT", $cmd_seq);
            $t->SetFieldValue("PATCH_FILE", $script);
    
            if ($mode == "A")
            {
                #Has not been patched
                $t->SetFieldValue("PATCH_ID", $new_rowid);
                self::$patchModel->Insert(0, $t, false);
            }
            else
            {
                $t->SetFieldValue("PATCH_ID", $rowid);
                self::$patchModel->Update(0, $t);
            }
        }
    #print("COMMIT \n");
        $db->commit();
    }    

    private static function getCustomPatch($funcKey)
    {
        foreach (self::$customPatches as $patch)
        {
            list($func, $phpFile) = $patch;

            if ($func == $funcKey)
            {
                return($patch);
            }
        }

        throw new Exception("Custom patch error, no entry point for [$funcKey]");
    }

    private static function getPostPatch($funcKey)
    {
        foreach (self::$postPatches as $patch)
        {
            list($func, $phpFile) = $patch;

            if ($func == $funcKey)
            {
                return($patch);
            }
        }

        throw new Exception("Post patch error, no entry point for [$funcKey]");
    }

    private static function patchByScript($db, $funcKey, $type)
    {
        if ($type == 1)
        {
            //Custom Patch
            list($method, $file) = self::getCustomPatch($funcKey);
        }
        else
        {
            //2 - Post Patch
            list($method, $file) = self::getPostPatch($funcKey);
        }

        $className = str_replace('.php', '', $file);
        $className = basename($className);

        $absoluteFile = "$file";

        if (!is_file($absoluteFile))
        {           
            throw new Exception("File [$absoluteFile] does not exist!!!");           
        }   

        require_once($absoluteFile);

        if (!class_exists($className))
        {
            throw new Exception("Class [$className] does not exist!!!");   
        }

        $method = "Patch$method";
        if (!method_exists("$className", "$method"))
        {
            throw new Exception("Method [$className::$method] does not exist!!!");   
        } 
                
        $result = $className::$method($db);
        return($result);
    }

    //=== Begin use internally by core package
    public static function RegisterCustomPatchConfig($patchList)
    {
        self::$customPatches = $patchList;
    }

    public static function RegisterPatchConfig($patchList, $mdName, $phpFile, $sqlPath, $postPatches)
    {
        self::$staticPatches = $patchList;
        self::$modelName = $mdName;
        self::$modelPHP = $phpFile;
        self::$sqlDir = $sqlPath;
        self::$postPatches = $postPatches;
    }

    public static function GetCurrentPatchRegistered()
    {
        $arr = [self::$staticPatches, self::$modelName, self::$modelPHP, self::$sqlDir, self::$customPatches];
        return($arr);
    }
    //=== End use internally by core package

    public static function RegisterExtraPatch($patchList, $mdName, $phpFile, $sqlPath, $customPatchList, $postPatchList)
    {
        $extra = [$patchList, $mdName, $phpFile, $sqlPath, $customPatchList, $postPatchList];
        array_push(self::$extraPatches, $extra);
    }

    public static function GetExtraPatchList()
    {
        return(self::$extraPatches);
    }    

    private static function hasBeenPatched($db, $cfgArr)
    {
        if (!isset(self::$postPatchHistory))
        {
            $tb =  new CTable("");
            $m = new MFrameworkPostPatch($db);
                
            list($cnt, $rows) = $m->Query(0, $tb);
            self::$postPatchHistory = $rows;
        }
        
        list($funcName, $fileName) = $cfgArr;

        foreach (self::$postPatchHistory as $obj)
        {
            $func = $obj->GetFieldValue('FUNCTION_NAME');
            $file = $obj->GetFieldValue('FILE_NAME');

            if (($func == $funcName) && (basename($file) == basename($fileName)))
            {
                return(true);
            }
        }

        return(false);
    }

    private static function performPostPatches($db)
    {
        $m = new MFrameworkPostPatch($db);

        $arr = self::$postPatches;
        foreach ($arr as $cfgArr)
        {
            list($funcName, $fileName) = $cfgArr;
            if (!self::hasBeenPatched($db, $cfgArr))
            {
                $db->beginTransaction();

                //This function will thorw exception internally if error
                $rv = self::patchByScript($db, $funcName, 2);

                $t = new CTable('');
                $t->SetFieldValue("FUNCTION_NAME", $funcName);
                $t->SetFieldValue("FILE_NAME", $fileName);

                $m->Insert(0, $t, true);

                $db->commit();
            }            
        }
    }

    public static function Patch($db, $param, $data)
    {
        require_once(self::$modelPHP);

        self::$patchModel = new self::$modelName($db);

        list($cnt, $rows, $lastPatch) = self::getPatchHistoryList($db);
        self::patchDatabase($db, $cnt, $lastPatch);

        //Perform Post Patches here
        self::performPostPatches($db);

        return(array($param, $data));        
    }
}

?>