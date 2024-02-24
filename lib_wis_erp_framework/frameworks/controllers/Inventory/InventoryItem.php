<?php
/* 
    Purpose : Controller for item
    Created By : Seubpong Monsar
    Created Date : 09/08/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class InventoryItem extends CBaseController
{
    private static $cfg = NULL;

    private static $orderByConfig = [
        'item_code' => 'ITEM_CODE',
        'item_name_thai' => 'ITEM_NAME_THAI',
        'item_uom' => 'UOM_NAME',
        'item_category' => 'CATEGORY_NAME',
        'item_type' => 'TYPE_NAME',
    ];

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['BARCODE_ITEM', new MItemBarcode($db), 0, 0, 1],
        );

        self::$cfg = $config;

        return($config);
    }

    public static function RegisterBalanceItem($db, $data)
    {
        $it = new CTable("");
        //No actual ID for cash
        $it->SetFieldValue("ACTUAL_ID", $data->GetFieldValue('ITEM_ID'));
        $it->SetFieldValue("BAL_ITEM_CODE", $data->GetFieldValue('ITEM_CODE'));
        $it->SetFieldValue("BAL_ITEM_NAME", $data->GetFieldValue('ITEM_NAME_THAI'));
        $it->SetFieldValue("BAL_ITEM_TYPE", BalanceAPI::BAL_ITEM_TYPE_INVENTORY);
        $it->SetFieldValue("REF1", $data->GetFieldValue('ITEM_TYPE'));
        $it->SetFieldValue("REF2", $data->GetFieldValue('ITEM_CATEGORY'));
        $it->SetFieldValue("REF3", $data->GetFieldValue('ITEM_UOM'));

        BalanceAPI::RegisterItem($db, $it);
    }

    public static function GetInventoryItemList($db, $param, $data)
    {
        $chunkFlag = $data->GetFieldValue('CHUNK_FLAG');
        $u = new MItem($db);
        $item = new CTable("ITEM");

        CHelper::OverrideOrderBy($u, 0, $data, self::$orderByConfig);

        if ($chunkFlag != 'N')
        {        
            list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(0, $data);        
            self::PopulateRow($item, $item_cnt, $chunk_cnt, 'ITEM_LIST', $rows);
        }
        else
        {
            list($cnt, $rows) = $u->Query(0, $data);        
            self::PopulateRow($item, $cnt, 1, 'ITEM_LIST', $rows);
        }

        return(array($param, $item));        
    }

    public static function GetInventoryItemInfo($db, $param, $data)
    {
        $cfg = self::initSqlConfig($db);

        $u = new MItem($db);
        $obj = self::GetRowByID($data, $u, 0);
        //printf("[%s]\n", CSql::GetLastSQL());

        if (!isset($obj))
        {
            throw new Exception("No this item in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsInventoryItemExist($db, $param, $data)
    {
        $u = new MItem($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "ITEM_CODE", "IT.ITEM_CODE", 0);
        
        return(array($param, $o));        
    }

    public static function CreateInventoryItem($db, $param, $data)
    {
        $u = new MItem($db);
        $db->beginTransaction();

        $childs = self::initSqlConfig($db);

        self::CreateData($db, $data, $u, 2, $childs);
        self::RegisterBalanceItem($db, $data);

        $db->commit();
        return(array($param, $data));        
    }    

    private static function migrateServiceToItem(CTable $service)
    {
        $item = new CTable('ITEM');

        $item->SetFieldValue("ITEM_CODE", $service->GetFieldValue("SERVICE_CODE"));
        $item->SetFieldValue("ITEM_NAME_ENG", $service->GetFieldValue("SERVICE_NAME"));
        $item->SetFieldValue("ITEM_NAME_THAI", $service->GetFieldValue("SERVICE_NAME"));
        $item->SetFieldValue("ITEM_TYPE", "330");
        $item->SetFieldValue("ITEM_CATEGORY", "7");
        $item->SetFieldValue("ITEM_UOM", "163");
        $item->SetFieldValue("FINISH_GOOD_FLAG", "N");
        $item->SetFieldValue("PART_FLAG", "N");
        $item->SetFieldValue("RM_FLAG", "N");
        $item->SetFieldValue("PURCHASE_FLAG", "Y");
        $item->SetFieldValue("SALE_FLAG", "Y");
        $item->SetFieldValue("PRODUCTION_FLAG", "N");
        $item->SetFieldValue("REFERENCE_CODE", "REF99999");
        $item->SetFieldValue("BRAND", "0");
        $item->SetFieldValue("NOTE", "");
        $item->SetFieldValue("DEFAULT_SELL_PRICE", "0.00");
        $item->SetFieldValue("PRICING_DEFINITION", "");
        $item->SetFieldValue("SALE_UOM", "0");
        $item->SetFieldValue("MINIMUM_ALLOW", "0.00");
        $item->SetFieldValue("PRICE_CATEGORY", "0");
        $item->SetFieldValue("ITEM_LINEAR", "0");
        $item->SetFieldValue("VAT_FLAG", "Y");
        $item->SetFieldValue("BORROW_FLAG", "N");

        return $item;
    }

    public static function MoveServiceToItem($db, $param, $dummy)
    {           
        $max = $dummy->GetFieldValue('MAX_MIGRAGE');

        $codes = ['200-', '201-', '800-', '801-', '802-'];
        $cnt = 0;
        
        foreach ($codes as $code)
        {
            $data = new CTable('SERVICE');
            $data->SetFieldValue("SERVICE_CODE", $code);            
            list($p, $d) = ProductService::GetServiceList($db, $param, $data);        

            $arr = $d->GetChildArray("SERVICE_LIST");
            foreach ($arr as $service)
            {
                $serviceID = $service->GetFieldValue("SERVICE_ID");
                $serviceCode = $service->GetFieldValue("SERVICE_CODE");
                
                $item = self::migrateServiceToItem($service);
           
                list($p, $d) = InventoryItem::CreateInventoryItem($db, $param, $item);

                $u = new MService($db);

                $svc = new CTable("");
                $svc->SetFieldValue("SERVICE_ID", $serviceID);
                $svc->SetFieldValue('SERVICE_CODE', "^^$serviceCode^^");
                $u->Update(2, $svc);

                $cnt++;
            }

            if ($cnt >= $max)
            {
                break;
            }

        }

        $dummy->SetFieldValue('MIGRATED_COUNT', $cnt);

        return(array($param, $dummy));
    }

    public static function UpdateInventoryItem($db, $param, $data)
    {
        $u = new MItem($db);
        $db->beginTransaction();

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 2, $childs);
        self::RegisterBalanceItem($db, $data);

        $db->commit();
        return(array($param, $data));        
    }      

    public static function DeleteInventoryItem($db, $param, $data)
    {
        $u = new MItem($db);
        
        $db->beginTransaction();

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 1, $childs);
        
        $data->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('ITEM_ID'));   
        $data->SetFieldValue("BAL_ITEM_TYPE", BalanceAPI::BAL_ITEM_TYPE_INVENTORY);     
        BalanceAPI::UnRegisterItem($db, $data);

        $db->commit();

        return(array($param, $data));        
    }     

    public static function CopyInventoryItem($db, $param, $data)
    {
        list($p, $d) = self::GetInventoryItemInfo($db, $param, $data);
        self::PopulateNewCode($d, 'ITEM_CODE');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateInventoryItem($db, $param, $d);
        list($p, $d) = self::GetInventoryItemInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>