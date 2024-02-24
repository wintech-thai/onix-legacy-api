<?php
/* 
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CustomerSupplier extends CBaseController
{
    private static $cfg = NULL;

    private static $orderByConfig = [
        'customer_code' => 'ENTITY_CODE',
        'customer_name' => 'ENTITY_NAME',
        'customer_type' => 'ENTITY_TYPE_NAME',
        'customer_group' => 'ENTITY_GROUP_NAME',

        'supplier_code' => 'ENTITY_CODE',
        'supplier_name' => 'ENTITY_NAME',
        'supplier_type' => 'ENTITY_TYPE_NAME',
        'supplier_group' => 'ENTITY_GROUP_NAME',

        'telephone' => 'PHONE',
    ];

    private static function initSqlConfig($db)
    {
        $config = [
            //Array name, model, query ind, insert/update ind, delete ind
            ['ADDRESS_ITEM', new MEntityAddress($db), 0, 0, 1],
            ['ACCOUNT_ITEM', new MEntityBankAccount($db), 1, 0, 2],            
        ];

        self::$cfg = $config;

        return($config);
    }

    public static function GetEntityList($db, $param, $data)
    {
        $u = new MEntity($db);

        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig);        
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'ENTITY_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetEntityInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MEntity($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No entity in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsEntityExist($db, $param, $data)
    {
        $u = new MEntity($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "ENTITY_CODE", "ENTITY_CODE", 0);
        
        return(array($param, $o));        
    }

    private static function categoryToOwnerType($data)
    {
        $type = '';

        $category = $data->GetFieldValue('CATEGORY');
        if ($category == '1')
        {
            $type = BalanceAPI::BAL_OWNER_TYPE_CUSTOMER;            
        }
        else
        {
            $type = BalanceAPI::BAL_OWNER_TYPE_SUPPLIER;            
        } 
        
        return($type);
    }

    public static function RegisterBalanceOwner($db, $data)
    {
        $it = new CTable("");
        //No actual ID for cash
        $it->SetFieldValue("ACTUAL_ID", $data->GetFieldValue('ENTITY_ID'));
        $it->SetFieldValue("BAL_OWNER_CODE", $data->GetFieldValue('ENTITY_CODE'));
        $it->SetFieldValue("BAL_OWNER_NAME", $data->GetFieldValue('ENTITY_NAME'));
        $it->SetFieldValue("REF1", $data->GetFieldValue('ENTITY_TYPE'));

        $type = self::categoryToOwnerType($data);
        $it->SetFieldValue("BAL_OWNER_TYPE", $type);

        BalanceAPI::RegisterOwner($db, $it);
    }

    public static function RegisterBalanceItem($db)
    {
        $it = new CTable("");
        //No actual ID for cash
        $it->SetFieldValue("ACTUAL_ID", "");
        $it->SetFieldValue("BAL_ITEM_CODE", AccountDocument::AR_ITEM_CODE);
        $it->SetFieldValue("BAL_ITEM_NAME", AccountDocument::AR_ITEM_CODE);
        $it->SetFieldValue("BAL_ITEM_TYPE", BalanceAPI::BAL_ITEM_TYPE_AR);

        BalanceAPI::RegisterItem($db, $it);

        $it->SetFieldValue("BAL_ITEM_CODE", AccountDocument::AP_ITEM_CODE);
        $it->SetFieldValue("BAL_ITEM_NAME", AccountDocument::AP_ITEM_CODE);
        $it->SetFieldValue("BAL_ITEM_TYPE", BalanceAPI::BAL_ITEM_TYPE_AP);  
        
        BalanceAPI::RegisterItem($db, $it);
    }

    public static function CreateEntity($db, $param, $data)
    {
        $u = new MEntity($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);
        self::RegisterBalanceOwner($db, $data);
        self::RegisterBalanceItem($db);

        return(array($param, $data));        
    }    

    public static function UpdateEntity($db, $param, $data)
    {
        $u = new MEntity($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);
        self::RegisterBalanceOwner($db, $data);
        self::RegisterBalanceItem($db);

        return(array($param, $data));        
    }      

    public static function DeleteEntity($db, $param, $data)
    {
        $u = new MEntity($db);
        
        $db->beginTransaction();

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        $data->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('ENTITY_ID'));   
        $type = self::categoryToOwnerType($data);
        $data->SetFieldValue("BAL_OWNER_TYPE", $type);
        BalanceAPI::UnRegisterOwner($db, $data);

        $db->commit();

        return(array($param, $data));        
    }     

    public static function CopyEntity($db, $param, $data)
    {
        list($p, $d) = self::GetEntityInfo($db, $param, $data);
        self::PopulateNewCode($d, 'ENTITY_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateEntity($db, $param, $d);
        list($p, $d) = self::GetEntityInfo($db, $param, $d);
        
        return(array($param, $d));        
    }
}

?>