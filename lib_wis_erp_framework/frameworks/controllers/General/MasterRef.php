<?php
/* 
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MasterRef extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = [];

        self::$cfg = $config;

        return($config);
    }

    public static function GetMasterRefList($db, $param, $data)
    {
        $u = new MMasterRef($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(0, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'MASTER_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetAllMasterRefList($db, $param, $data)
    {
        $u = new MMasterRef($db);
        list($cnt, $rows) = $u->Query(0, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $cnt, 1, 'MASTER_LIST', $rows);

        return(array($param, $pkg));
    }

    public static function GetMasterRefInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MMasterRef($db);
        $obj = self::GetRowByID($data, $u, 0);

        if (!isset($obj))
        {
            throw new Exception("No master ref in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsMasterRefExist($db, $param, $data)
    {
        $u = new MMasterRef($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "CODE", "CODE", 0);
        
        return(array($param, $o));        
    }

    public static function CreateMasterRef($db, $param, $data)
    {
        $u = new MMasterRef($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateMasterRef($db, $param, $data)
    {
        $u = new MMasterRef($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteMasterRef($db, $param, $data)
    {
        $u = new MMasterRef($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     

    public static function CopyMasterRef($db, $param, $data)
    {
        list($p, $d) = self::GetMasterRefInfo($db, $param, $data);
        self::PopulateNewCode($d, 'CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateMasterRef($db, $param, $d);
        list($p, $d) = self::GetMasterRefInfo($db, $param, $d);
        
        return(array($param, $d));        
    }
}

?>