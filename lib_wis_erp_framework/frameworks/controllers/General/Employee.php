<?php
/* 
    Purpose : Controller for Bill SImulate
    Created By : Seubpong Monsar
    Created Date : 09/12/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class Employee extends CBaseController
{
    private static $cfg = NULL;
    private static $orderByConfig = [
        'employee_code' => 'EMPLOYEE_CODE',
        'employee_name' => 'EMPLOYEE_NAME',
        'employee_type' => 'EMPLOYEE_TYPE_NAME',
        'employee_group' => 'EMPLOYEE_GROUP_NAME',
        'telephone' => 'PHONE',
    ];

    private static $fieldMapStorage = [
        ['EMPLOYEE_PROFILE_IMAGE', 'EMPLOYEE_PROFILE_IMAGE_WIP', '/employee/profile'],
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
        $u = new MEmployee($db);
        return($u);
    }

    public static function GetEmployeeList($db, $param, $data)
    {
// CSql::SetDumpSQL(true);        
        $u = self::createObject($db);
        
        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig);                
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'EMPLOYEE_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetEmployeeInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No employee in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsEmployeeExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "EMPLOYEE_CODE", "EMPLOYEE_CODE", 0);
        
        return(array($param, $o));        
    }

    public static function CreateEmployee($db, $param, $data)
    {
        $u = self::createObject($db);
        
        self::PreprocessStoragesH($data, 'DUMMY_EMPLOYEE_IMAGES', self::$fieldMapStorage); //Put this before CreateData

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        self::manipulateImages($db, $data, 'DUMMY_EMPLOYEE_IMAGES', 'IMAGE_NAME_WIP', 'IMAGE_NAME_DEST', 'IMAGE_NAME');

        return(array($param, $data));        
    }    

    public static function UpdateEmployee($db, $param, $data)
    {
        $u = self::createObject($db);
        
        self::PreprocessStoragesH($data, 'DUMMY_EMPLOYEE_IMAGES', self::$fieldMapStorage); //Put this before UpdateData

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        self::manipulateImages($db, $data, 'DUMMY_EMPLOYEE_IMAGES', 'IMAGE_NAME_WIP', 'IMAGE_NAME_DEST', 'IMAGE_NAME');

        return(array($param, $data));        
    }      

    public static function DeleteEmployee($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));
    }

    public static function CopyEmployee($db, $param, $data)
    {
        list($p, $d) = self::GetEmployeeInfo($db, $param, $data);
        self::PopulateNewCode($d, 'EMPLOYEE_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateEmployee($db, $param, $d);
        list($p, $d) = self::GetEmployeeInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>