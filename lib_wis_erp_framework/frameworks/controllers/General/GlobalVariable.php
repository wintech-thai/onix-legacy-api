<?php
/* 
    Purpose : Controller for Global Variables
    Created By : Seubpong Monsar
    Created Date : 11/14/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class GlobalVariable extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['GLOBAL_VARIABLE_ITEM', new MGlobalVariable($db), 0, 0, 0],
        );

        self::$cfg = $config;

        return($config);
    }

    public static function GetGlobalVariableInfo($db, $param, $data)
    {
        $cfg = self::initSqlConfig($db);

        $u = new MVirtualModel('VARIABLE_CATEGORY');
        $obj = new CTable("");
        $obj->SetFieldValue($u->GetPKName(), "1");

        self::PopulateChildItems($obj, $u, $cfg);

        return([$param, $obj]);        
    }

    public static function UpdateGlobalVariable($db, $param, $data)
    {
        $u = new MVirtualModel('VARIABLE_CATEGORY');
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return([$param, $data]);        
    }  

    public static function CreateGlobalVariable($db, $param, $data)
    {
        $u = new MVirtualModel('VARIABLE_CATEGORY');
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return([$param, $data]);        
    } 

    public static function DeleteGlobalVariable($db, $param, $data)
    {
        $u = new MVirtualModel('VARIABLE_CATEGORY');
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        return([$param, $data]);        
    }

    public static function GetSingleGlobalVariableInfo($db, $param, $data)
    {
        $id = $data->GetFieldValue('GLOBAL_VARIABLE_ID');
        $nm = $data->GetFieldValue('VARIABLE_NAME');

        $u = new MGlobalVariable($db);
        if ($id != '')
        {
            //By ID            
            $obj = self::GetRowByID($data, $u, 0);
            
            if (!isset($obj))
            {
                throw new Exception("No global variable ID [$id] in database!!!");
            }
        }
        elseif ($nm != '')
        {
            //By Name                        
            $obj = self::GetFirstRow($data, $u, 0, 'VARIABLE_NAME');
    
            if (!isset($obj))
            {
                throw new Exception("No global variable name [$nm] in database!!!");
            }               
        }
        else
        {
            throw new Exception("Name or ID must be specified for getting a global variable!!!");
        }    
        
        return([$param, $obj]);       
    }    
}

?>