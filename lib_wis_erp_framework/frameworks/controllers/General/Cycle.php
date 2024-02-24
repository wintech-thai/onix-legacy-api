<?php
/* 
    Purpose : Controller for Cycle
    Created By : Seubpong Monsar
    Created Date : 09/19/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class Cycle extends CBaseController
{
    private static $cfg = NULL;

    private static $orderByConfig = [
        'cycle_code' => 'CYCLE_CODE',
        'cycle_description' => 'DESCRIPTION',
        'cycle_type' => 'CYCLE_TYPE',
    ];

    private static function initSqlConfig($db)
    {
        $config = [
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
        ];

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MCycle($db);
        return($u);
    }

    public static function GetCycleList($db, $param, $data)
    {
        $u = self::createObject($db);

        CHelper::OverrideOrderBy($u, 0, $data, self::$orderByConfig);                
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(0, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'CYCLE_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetCycleInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 0);

        if (!isset($obj))
        {
            throw new Exception("No cycle in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsCycleExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "CYCLE_CODE", "CYCLE_CODE", 0);
        
        return(array($param, $o));        
    }

    public static function CreateCycle($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateCycle($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteCycle($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));
    }

    public static function CopyCycle($db, $param, $data)
    {
        list($p, $d) = self::GetCycleInfo($db, $param, $data);
        self::PopulateNewCode($d, 'CYCLE_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateCycle($db, $param, $d);
        list($p, $d) = self::GetCycleInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>