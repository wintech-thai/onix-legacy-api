<?php
/* 
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CompanyPackage extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['COMPANY_PACKAGE_ITEM', new MCompanyPackage($db), 2, 0, 1],
        );

        self::$cfg = $config;

        return($config);
    }

    public static function GetCompanyPackageInfo($db, $param, $data)
    {
        $cfg = self::initSqlConfig($db);

        $u = new MVirtualModel('COMPANY_ID');
        $obj = new CTable("");
        $obj->SetFieldValue($u->GetPKName(), "1");

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function UpdateCompanyPackage($db, $param, $data)
    {
        $u = new MVirtualModel('COMPANY_ID');
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }  

    public static function CreateCompanyPackage($db, $param, $data)
    {
        $u = new MVirtualModel('COMPANY_ID');
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    } 

    public static function DeleteCompanyPackage($db, $param, $data)
    {
        $u = new MVirtualModel('COMPANY_ID');
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }     

    public static function GetCompanyPackageAll($db, $param, $data)
    {
        $arr = array();
        $t = new CTable("");        
        $u = new MPackage($db);
        list($cnt, $rows) = $u->Query(2, $data);
        
        foreach ($rows as $row)
        {                
            list($p, $m) = Package::GetPackageInfo($db, $param, $row);        
            array_push($arr, $m);
        }
        
        $t->AddChildArray("PACKAGE_ITEM_FULL_LIST", $arr);

        return(array($param, $t));        
    }  
  
}

?>