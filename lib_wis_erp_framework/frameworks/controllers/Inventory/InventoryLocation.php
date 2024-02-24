<?php
/* 
    Purpose : Controller for item
    Created By : Seubpong Monsar
    Created Date : 09/08/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class InventoryLocation extends CBaseController
{
    private static $cfg = NULL;

    private static $orderByConfig = [
        'location_code' => 'LOCATION_CODE',
        'location_name' => 'DESCRIPTION',
        'location_type' => 'TYPE_NAME',
        'location_for_sale' => 'SALE_FLAG',
    ];

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            //['BARCODE_ITEM', new MItemBarcode($db), 0, 0, 1],
        );

        self::$cfg = $config;

        return($config);
    }

    public static function RegisterBalanceOwner($db, $data)
    {
        $it = new CTable("");
        //No actual ID for cash
        $it->SetFieldValue("ACTUAL_ID", $data->GetFieldValue('LOCATION_ID'));
        $it->SetFieldValue("BAL_OWNER_CODE", $data->GetFieldValue('LOCATION_CODE'));
        $it->SetFieldValue("BAL_OWNER_NAME", $data->GetFieldValue('DESCRIPTION'));
        $it->SetFieldValue("REF1", $data->GetFieldValue('LOCATION_TYPE'));
        $it->SetFieldValue("BAL_OWNER_TYPE", BalanceAPI::BAL_OWNER_TYPE_LOCATION);

        BalanceAPI::RegisterOwner($db, $it);
    }

    public static function GetLocationList($db, $param, $data)
    {
        $u = new MLocation($db);

        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $item = new CTable("LOCATION");
        self::PopulateRow($item, $item_cnt, $chunk_cnt, 'LOCATION_LIST', $rows);
        
        return(array($param, $item));        
    }

    public static function GetLocationInfo($db, $param, $data)
    {
        $cfg = self::initSqlConfig($db);

        $u = new MLocation($db);
        $obj = self::GetRowByID($data, $u, 0);
        //printf("[%s]\n", CSql::GetLastSQL());

        if (!isset($obj))
        {
            throw new Exception("No this location in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsLocationExist($db, $param, $data)
    {
        $u = new MLocation($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "LOCATION_CODE", "LOCATION_CODE", 0);
        
        return(array($param, $o));        
    }

    public static function CreateLocation($db, $param, $data)
    {
        $u = new MLocation($db);
        
        $db->beginTransaction();

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);
        self::RegisterBalanceOwner($db, $data);

        $db->commit();

        return(array($param, $data));        
    }    

    public static function UpdateLocation($db, $param, $data)
    {
        $u = new MLocation($db);

        $db->beginTransaction();

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);
        self::RegisterBalanceOwner($db, $data);

        $db->commit();

        return(array($param, $data));        
    }      

    public static function DeleteLocation($db, $param, $data)
    {
        $u = new MLocation($db);
        
        $db->beginTransaction();

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        $data->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('LOCATION_ID'));   
        $data->SetFieldValue("BAL_OWNER_TYPE", BalanceAPI::BAL_OWNER_TYPE_LOCATION);     
        BalanceAPI::UnRegisterOwner($db, $data);

        $db->commit();

        return(array($param, $data));        
    }     

    public static function CopyLocation($db, $param, $data)
    {
        list($p, $d) = self::GetLocationInfo($db, $param, $data);
        self::PopulateNewCode($d, 'LOCATION_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateLocation($db, $param, $d);
        list($p, $d) = self::GetLocationInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>