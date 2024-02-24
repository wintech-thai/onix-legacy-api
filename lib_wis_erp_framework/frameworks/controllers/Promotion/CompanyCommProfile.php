<?php
/* 
    Purpose : Controller for Company Commission Profile
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CompanyCommProfile extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = [
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['COMPANY_COMM_PROFILE_ITEM', new MCompanyCommProfile($db), 1, 0, 2],
        ];

        self::$cfg = $config;

        return($config);
    }

    public static function GetCompanyCommProfileInfo($db, $param, $data)
    {
        //CSql::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MVirtualModel('COMPANY_ID');
        $obj = new CTable("");
        $obj->SetFieldValue($u->GetPKName(), "1");

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function UpdateCompanyCommProfile($db, $param, $data)
    {   
        //CSql::SetDumpSQL(true);
        $u = new MVirtualModel('COMPANY_ID');
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }  

    public static function CreateCompanyCommProfile($db, $param, $data)
    {
        $u = new MVirtualModel('COMPANY_ID');
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    } 

    public static function DeleteCompanyCommProfile($db, $param, $data)
    {
        $u = new MVirtualModel('COMPANY_ID');
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }

}

?>