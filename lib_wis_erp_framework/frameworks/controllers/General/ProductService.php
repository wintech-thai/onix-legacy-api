<?php
/* 
    Purpose : Controller for Bill SImulate
    Created By : Seubpong Monsar
    Created Date : 09/12/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class ProductService extends CBaseController
{
    private static $cfg = NULL;

    private static $orderByConfig = [
        'service_code' => 'SERVICE_CODE',
        'service_name' => 'SERVICE_NAME',
        'service_type' => 'SERVICE_TYPE_NAME',
        'service_uom' => 'SERVICE_UOM_NAME',
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
        $u = new MService($db);
        return($u);
    }

    public static function GetServiceList($db, $param, $data)
    {
        self::populateCategoryFlagForQuery($data);

        $u = self::createObject($db);

        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig);        
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'SERVICE_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetServiceInfo($db, $param, $data)
    {        
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No service in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsServiceExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "SERVICE_CODE", "SERVICE_CODE", 0);
        
        return(array($param, $o));        
    }

    private static function populateCategoryFlag($data)
    {
        $category = $data->GetFieldValue('CATEGORY');
        $level = $data->GetFieldValue('SERVICE_LEVEL');

        $isForSale = '';
        $isForPurchase = '';

        if ($category == '0')
        {
            $isForSale = 'Y';
            $isForPurchase = 'Y';     
        }        
        elseif ($category == '1')
        {
            $isForSale = 'Y';
            $isForPurchase = 'N';            
        }
        elseif ($category == '2')
        {
            $isForSale = 'N';
            $isForPurchase = 'Y';              
        }

        if ($level == '')
        {
            $data->SetFieldValue('SERVICE_LEVEL', '1');
        }

        $data->SetFieldValue('IS_FOR_SALE', $isForSale);
        $data->SetFieldValue('IS_FOR_PURCHASE', $isForPurchase);
    }

    public static function populateCategoryFlagForQuery($data)
    {
        $category = $data->GetFieldValue('CATEGORY');

        $isForSale = '';
        $isForPurchase = '';
      
        if ($category == '1')
        {
            $isForSale = 'Y';
            $isForPurchase = '';            
        }
        elseif ($category == '2')
        {
            $isForSale = '';
            $isForPurchase = 'Y';              
        }

        $data->SetFieldValue('IS_FOR_SALE', $isForSale);
        $data->SetFieldValue('IS_FOR_PURCHASE', $isForPurchase);
    }

    public static function CreateService($db, $param, $data)
    {
        self::populateCategoryFlag($data);

        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateService($db, $param, $data)
    {
        self::populateCategoryFlag($data);

        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteService($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));
    }

    public static function CopyService($db, $param, $data)
    {
        list($p, $d) = self::GetServiceInfo($db, $param, $data);
        self::PopulateNewCode($d, 'SERVICE_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateService($db, $param, $d);
        list($p, $d) = self::GetServiceInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>