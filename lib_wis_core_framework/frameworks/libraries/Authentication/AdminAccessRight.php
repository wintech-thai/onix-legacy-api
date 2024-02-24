<?php
/* 
    Purpose : Controller for Access Right
    Created By : Seubpong Monsar
    Created Date : 10/30/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class AdminAccessRight extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MFrwAccessRight($db);
        return($u);
    }

    public static function GetAccessRightList($db, $param, $data)
    {
        $u = self::createObject($db);
        $chunkFlag = $data->GetFieldValue('CHUNK_FLAG');

        $pkg = new CTable($u->GetTableName());
        
        if ($chunkFlag != 'N')
        {
            list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(0, $data);        
            self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'ACCESS_RIGHT_LIST', $rows);
        }
        else
        {
            list($cnt, $rows) = $u->Query(0, $data);
            self::PopulateRow($pkg, $cnt, 1, 'ACCESS_RIGHT_LIST', $rows);   
        }

        return(array($param, $pkg));
    }

    public static function GetAccessRightInfo($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 0);

        if (!isset($obj))
        {
            throw new Exception("No access right in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function CreateAccessRight($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateAccessRight($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteAccessRight($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    } 

    public static function GetUserContextAccessRight($db, $param, $data)
    {
        $accessRightCode = $data->getFieldValue('ACCESS_RIGHT_CODE');

        $sessionObj = CUtils::GetSessionObject($param);
        if (!isset($sessionObj))
        {
            throw new Exception("Session not found!!!!");
        }

        $gid = $sessionObj->getFieldValue('GROUP_ID');

        $u = new MFrwAccessRight($db);

        $temp1 = new CTable('');
        $temp1->setFieldValue('ACCESS_RIGHT_CODE', $accessRightCode);
        $acr = self::GetFirstRow($temp1, $u, 0, 'ACCESS_RIGHT_CODE');

        $acrID = $acr->getFieldValue('ACCESS_RIGHT_ID');
        $defaultValue = $acr->getFieldValue('DEFAULT_VALUE');

        $u = new MFrwGroupPermission($db);

        $temp2 = new CTable('');
        $temp2->setFieldValue('ACCESS_RIGHT_ID', $acrID);
        $temp2->setFieldValue('GROUP_ID', $gid);

        $obj = new CTable('');
        
        list($cnt, $rows) = $u->Query(0, $temp2);
        
        if ($cnt <= 0)
        {
            //Has not been set
            $obj->setFieldValue('IS_ENABLE', $defaultValue);
        }
        else
        {
            $obj = $rows[0];            
        }

        $obj->setFieldValue('ACCESS_RIGHT_CODE', $accessRightCode);

        return(array($param, $obj));  
    }

    public static function UpdateGroupAccessRight($db, $param, $data)
    {
        $id = $data->getFieldValue('GROUP_PERMISSION_ID');
        
        $u = new MFrwGroupPermission($db);

        if ($id == '')
        {
            self::CreateData($db, $data, $u, 0, []);
        }
        else
        {            
            self::UpdateData($db, $data, $u, 0, []);
        }

        return(array($param, $data));          
    } 
    
    public static function GetGroupAccessRightList($db, $param, $data)
    {
        $gid = $data->getFieldValue('GROUP_ID');
        $enableFlag = $data->getFieldValue('IS_ENABLE');

        if ($gid == '')
        {
            throw new Exception("GROUP_ID is required !!!");
        }
//CSql::SetDumpSQL(true);
        $u = new MFrwGroupPermission($db);
        $data->setFieldValue('IS_ENABLE', ''); //Don't use it for query right now
        list($cnt, $groupPermissions) = $u->Query(0, $data);
//CSql::SetDumpSQL(false);
        $assignedPermissions = CUtils::RowToHash($groupPermissions, 'ACCESS_RIGHT_ID');

        $u = new MFrwAccessRight($db);
        list($cnt, $definedPermissions) = $u->Query(0, $data);

        $groupAccessRights = [];

        foreach ($definedPermissions as $pm)
        {
            $accessRightID = $pm->getFieldValue('ACCESS_RIGHT_ID');
            $defaultValue = $pm->getFieldValue('DEFAULT_VALUE');

            $pm->setFieldValue('GROUP_ID', $gid);

            if (!array_key_exists($accessRightID, $assignedPermissions))
            {
                $pm->setFieldValue('IS_ENABLE', $defaultValue);
                $isEnable = $defaultValue;
            }
            else
            {
                //Exist in the group permission

                $gp = $assignedPermissions[$accessRightID];

                $isEnable = $gp->getFieldValue('IS_ENABLE');
                $id = $gp->getFieldValue('GROUP_PERMISSION_ID');

                $pm->setFieldValue('IS_ENABLE', $isEnable);
                $pm->setFieldValue('GROUP_PERMISSION_ID', $id);
            }
        
            if ($enableFlag == '')
            {
                //All
                array_push($groupAccessRights, $pm);
            } 
            else if ($enableFlag == $isEnable)
            {
                //Manually filter
                array_push($groupAccessRights, $pm);
            }          
        }

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, count($groupAccessRights), 1, 'GROUP_ACCESS_RIGHTS', $groupAccessRights);
        
        return(array($param, $pkg));        
    }      
}

?>