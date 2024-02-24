<?php
/* 
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CommissionProfile extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['COMMISSION_DETAIL_ITEM', new MCommissionProfileDetail($db), 1, 0, 2],
        );

        self::$cfg = $config;

        return($config);
    }

    public static function GetCommissionProfileList($db, $param, $data)
    {
        self::PopulateDayRange($data, 'FROM_EFFECTIVE_DATE', 'TO_EFFECTIVE_DATE', 'DAY_EFFECTIVE');
        self::PopulateDayRange($data, 'FROM_EXPIRE_DATE', 'TO_EXPIRE_DATE', 'DAY_EXPIRE'); 
                
        $u = new MCommissionProfile($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable("COMMISSION_PROFILE");
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'COMMISSION_PROFILE_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetCommissionProfileInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MCommissionProfile($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No commission profile in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsCommissionProfileExist($db, $param, $data)
    {
        $u = new MCommissionProfile($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "PROFILE_CODE", "PROFILE_CODE", 1);
        
        return(array($param, $o));        
    }

    public static function CreateCommissionProfile($db, $param, $data)
    {
        $u = new MCommissionProfile($db);
        
        self::PopulateStartEndDate($data, 'EFFECTIVE_DATE', true);
        self::PopulateStartEndDate($data, 'EXPIRE_DATE', false);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateCommissionProfile($db, $param, $data)
    {
        $u = new MCommissionProfile($db);
        
        self::PopulateStartEndDate($data, 'EFFECTIVE_DATE', true);
        self::PopulateStartEndDate($data, 'EXPIRE_DATE', false);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteCommissionProfile($db, $param, $data)
    {
        $u = new MCommissionProfile($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     

    public static function CopyCommissionProfile($db, $param, $data)
    {
        list($p, $d) = self::GetCommissionProfileInfo($db, $param, $data);
        self::PopulateNewCode($d, 'PROFILE_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateCommissionProfile($db, $param, $d);
        list($p, $d) = self::GetCommissionProfileInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>