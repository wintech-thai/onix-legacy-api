<?php
/* 
    Purpose : Controller for Project
    Created By : Seubpong Monsar
    Created Date : 01/12/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class Project extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = [];

        self::$cfg = $config;

        return($config);
    }

    public static function GetProjectList($db, $param, $data)
    {
        $u = new MProject($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'PROJECT_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetProjectInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MProject($db);
        $obj = self::GetRowByID($data, $u, 0);
        
        if (!isset($obj))
        {
            throw new Exception("No project in database!!!");
        }

        return(array($param, $obj));  
    }

    public static function IsProjectExist($db, $param, $data)
    {
        $u = new MProject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "PROJECT_CODE", "PROJECT_CODE", 0);
        
        return(array($param, $o));
    }

    public static function CreateProject($db, $param, $data)
    {
        $u = new MProject($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    }    

    public static function UpdateProject($db, $param, $data)
    {
        $u = new MProject($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }

    public static function DeleteProject($db, $param, $data)
    {
        $u = new MProject($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     

    public static function CopyProject($db, $param, $data)
    {
        list($p, $d) = self::GetProjectInfo($db, $param, $data);
        self::PopulateNewCode($d, 'PROJECT_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateProject($db, $param, $d);
        list($p, $d) = self::GetProjectInfo($db, $param, $d);
        
        return(array($param, $d));        
    }

    public static function SaveProject($db, $param, $data)
    {
        $id = $data->GetFieldValue("PROJECT_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateProject($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateProject($db, $param, $data);
        }
        
        return(array($p, $d));
    }    
}

?>