<?php
/* 
    Purpose : Controller for User Group
    Created By : Seubpong Monsar
    Created Date : 01/22/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class AdminAuthenticationGroup extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            //['PERM_ITEM', new MGroupPermission($db), 2, 0, 1],
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MFrwUserGroup($db);
        return($u);
    }

    public static function GetUserGroupList($db, $param, $data)
    {
        $u = self::createObject($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(0, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'GROUP_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetUserGroupInfo($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 0);

        if (!isset($obj))
        {
            throw new Exception("No user group in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsUserGroupExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "GROUP_NAME", "GROUP_NAME", 0);
        
        return(array($param, $o));        
    }

    public static function CreateUserGroup($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateUserGroup($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteUserGroup($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     

    public static function CopyUserGroup($db, $param, $data)
    {
        list($p, $d) = self::GetUserGroupInfo($db, $param, $data);
        self::PopulateNewCode($d, 'GROUP_NAME');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateUserGroup($db, $param, $d);
        list($p, $d) = self::GetUserGroupInfo($db, $param, $d);
        
        return(array($param, $d));        
    }    
    
    public static function GetPermissionList($db, $param, $data)
    {
/*        
        $u = new MPermission($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(0, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'PERM_ITEM', $rows);
*/
        return(array($param, $data));         
    }
}

?>