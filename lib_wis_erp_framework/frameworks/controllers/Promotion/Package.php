<?php
/* 
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class Package extends CBaseController
{
    private static $cfg = NULL;

    private static $orderByConfig = [
        'package_type' => 'PACKAGE_TYPE',
        'package_code' => 'PACKAGE_CODE',
        'package_name' => 'PACKAGE_NAME',
        'effective_date' => 'EFFECTIVE_DATE',
        'expire_date' => 'EXPIRE_DATE',
        'is_enable' => 'ENABLE_FLAG',
    ];

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['PACKAGE_PERIOD_ITEM', new MPackagePeriod($db), 0, 0, 1],
            ['PACKAGE_ITEM_PRICE_ITEM', new MPackagePrice($db), 2, 0, 1],
            ['PACKAGE_BUNDLE_ITEM', new MPackageBundle($db), 2, 0, 1],
            ['PACKAGE_CUSTOMER_ITEM', new MPackageCustomer($db), 2, 0, 1],                    
            ['PACKAGE_BONUS_ITEM', new MPackageBonus($db), 2, 0, 1],
            ['PACKAGE_BRANCH_ITEM', new MPackageBranch($db), 2, 0, 1],
            ['PACKAGE_DISCOUNT_ITEM', new MPackageDiscount($db), 2, 0, 1],
            ['PACKAGE_VOUCHER_ITEM', new MPackageVoucher($db), 2, 0, 1],
            ['PACKAGE_FINAL_DISCOUNT_ITEM', new MPackageFinalDiscount($db), 2, 0, 1],
            ['PACKAGE_TRAY_PRICE_ITEM', new MPackageTrayPrice($db), 2, 0, 1],            
        );

        self::$cfg = $config;

        return($config);
    }

    public static function GetPackageList($db, $param, $data)
    {
        self::PopulateDayRange($data, 'FROM_EFFECTIVE_DATE', 'TO_EFFECTIVE_DATE', 'DAY_EFFECTIVE');
        self::PopulateDayRange($data, 'FROM_EXPIRE_DATE', 'TO_EXPIRE_DATE', 'DAY_EXPIRE');                     

        $u = new MPackage($db);

        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig); 
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable("PACKAGE");
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'PACKAGE_LIST', $rows);
        
        return(array($param, $pkg));        
    }

    public static function GetPackageInfo($db, $param, $data)
    {
        $cfg = self::initSqlConfig($db);

        $u = new MPackage($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No this package in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsPackageExist($db, $param, $data)
    {
        $u = new MPackage($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "PACKAGE_CODE", "PK.PACKAGE_CODE", 1);
        
        return(array($param, $o));        
    }

    public static function CreatePackage($db, $param, $data)
    {
        $u = new MPackage($db);
        
        self::PopulateStartEndDate($data, 'EFFECTIVE_DATE', true);
        self::PopulateStartEndDate($data, 'EXPIRE_DATE', false);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdatePackage($db, $param, $data)
    {
        $u = new MPackage($db);
        
        self::PopulateStartEndDate($data, 'EFFECTIVE_DATE', true);
        self::PopulateStartEndDate($data, 'EXPIRE_DATE', false);        

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeletePackage($db, $param, $data)
    {
        $u = new MPackage($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     

    public static function CopyPackage($db, $param, $data)
    {
        list($p, $d) = self::GetPackageInfo($db, $param, $data);
        self::PopulateNewCode($d, 'PACKAGE_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreatePackage($db, $param, $d);
        list($p, $d) = self::GetPackageInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>