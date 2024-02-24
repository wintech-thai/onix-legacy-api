<?php
/* 
    Purpose : Controller for Search Filtering
    Created By : Seubpong Monsar
    Created Date : 01/19/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class SearchFilter extends CBaseController
{
    private static $cfg = NULL;

    private static $NS_CONFIG = [
        #NameSpace => Model, QueryIndex, CodeField, DescField, HashConfig

        'CustomerCodeNS' => ["MEntity", 0, 'ENTITY_CODE', 'ENTITY_NAME', ['CATEGORY' => '1']],
        'CustomerNameNS' => ["MEntity", 0, 'ENTITY_NAME', 'ENTITY_NAME', ['CATEGORY' => '1']],
        'SupplierCodeNS' => ["MEntity", 0, 'ENTITY_CODE', 'ENTITY_NAME', ['CATEGORY' => '2']],
        'SupplierNameNS' => ["MEntity", 0, 'ENTITY_NAME', 'ENTITY_NAME', ['CATEGORY' => '2']],
        'MasterRefCodeNS' => ["MMasterRef", 0, 'CODE', 'DESCRIPTION', []],
        'MasterRefDescNS' => ["MMasterRef", 0, 'DESCRIPTION', 'DESCRIPTION', []],
        'ServiceCodeNS' => ["MService", 0, 'SERVICE_CODE', 'SERVICE_NAME', []],
        'ServiceCodeSaleNS' => ["MService", 0, 'SERVICE_CODE', 'SERVICE_NAME', ['IS_FOR_SALE' => 'Y']],
        'ServiceCodePurchaseNS' => ["MService", 0, 'SERVICE_CODE', 'SERVICE_NAME', ['IS_FOR_PURCHASE' => 'Y']],
        'ServiceCodeRegularSaleNS' => ["MService", 0, 'SERVICE_CODE', 'SERVICE_NAME', ['IS_FOR_SALE' => 'Y', 'SERVICE_LEVEL' => '1']],
        'ServiceCodeRegularPurchaseNS' => ["MService", 0, 'SERVICE_CODE', 'SERVICE_NAME', ['IS_FOR_PURCHASE' => 'Y', 'SERVICE_LEVEL' => '1']],
        'ServiceCodeOtherSaleNS' => ["MService", 0, 'SERVICE_CODE', 'SERVICE_NAME', ['IS_FOR_SALE' => 'Y', 'SERVICE_LEVEL' => '2']],
        'ServiceCodeOtherPurchaseNS' => ["MService", 0, 'SERVICE_CODE', 'SERVICE_NAME', ['IS_FOR_PURCHASE' => 'Y', 'SERVICE_LEVEL' => '2']],        
        'ServiceNameNS' => ["MService", 0, 'SERVICE_NAME', 'SERVICE_NAME', []],    
        'ItemCodeNS' => ["MItem", 2, 'ITEM_CODE', 'ITEM_NAME_THAI', []],
        'ItemCodeBorrowReturnNS' => ["MItem", 2, 'ITEM_CODE', 'ITEM_NAME_THAI', ['BORROW_FLAG' => 'Y']],
        'ItemNameThaiNS' => ["MItem", 2, 'ITEM_NAME_THAI', 'ITEM_NAME_THAI', []],
        'ItemNameEngNS' => ["MItem", 2, 'ITEM_NAME_ENG', 'ITEM_NAME_ENG', []],  
        'ProjectCodeNS' => ["MProject", 0, 'PROJECT_CODE', 'PROJECT_NAME', []],
        'ProjectNameNS' => ["MProject", 0, 'PROJECT_NAME', 'PROJECT_NAME', []],    
        'LocationCodeNS' => ["MLocation", 0, 'LOCATION_CODE', 'DESCRIPTION', []],
        'LocationNameNS' => ["MLocation", 0, 'DESCRIPTION', 'DESCRIPTION', []],        
        'EmployeeCodeNS' => ["MEmployee", 1, 'EMPLOYEE_CODE', 'EMPLOYEE_NAME_LASTNAME', []],

        'EmployeeDailyCodeNS' => ["MEmployee", 1, 'EMPLOYEE_CODE', 'EMPLOYEE_NAME_LASTNAME', ['EMPLOYEE_TYPE' => '1']],
        'EmployeeMonthlyCodeNS' => ["MEmployee", 1, 'EMPLOYEE_CODE', 'EMPLOYEE_NAME_LASTNAME', ['EMPLOYEE_TYPE' => '2']],

        'EmployeeNameNS' => ["MEmployee", 1, 'EMPLOYEE_NAME', 'EMPLOYEE_NAME_LASTNAME', []],                  
        'CashAccountCodeNS' => ["MCashAccount", 0, 'ACCOUNT_NO', 'ACCOUNT_NNAME', []],
        'CashAccountNameNS' => ["MCashAccount", 0, 'ACCOUNT_NNAME', 'ACCOUNT_NNAME', []], 
        'CashAccountBranchNS' => ["MCashAccount", 0, 'BANK_BRANCH_NAME', 'ACCOUNT_NNAME', []],
        'PromotionCodeNS' => ["MPackage", 0, 'PACKAGE_CODE', 'PACKAGE_NAME', []],
        'PromotionNameNS' => ["MPackage", 0, 'PACKAGE_NAME', 'PACKAGE_NAME', []],
    ];

    private static function populateAdditionalFields($obj, $fldsHash)
    {
        foreach ($fldsHash as $name => $value)
        {
            $obj->SetFieldValue($name, $value);
        }
    }

    public static function GetSearchTextList($db, $param, $data)
    {
        $ns = $data->GetFieldValue('DESCRIPTION');

        if ($ns == 'ItemCategoryPathNS')
        {
            //No need to filter
            list($p, $d) = ItemCategory::GetItemCategoryPathList($db, $param, new CTable(''));
            $paths = $d->GetChildArray('ITEM_CATEGORY_LIST');
            $arr = [];
            foreach ($paths as $p)
            {
                $o = new CTable('');
                $o->SetFieldValue('CODE', $p->GetFieldValue('CATEGORY_NAME'));
                $o->SetFieldValue('DESCRIPTION', $p->GetFieldValue('PATH'));
    
                array_push($arr, $o);
            }

            $result = new CTable('ITEM_CATEGORY');
            self::PopulateRow($result, count($paths), 1, 'SEARCH_TEXT_LIST', $arr);
            return(array($param, $result));
        }

        $cfg = self::$NS_CONFIG["$ns"];
        if (!isset($cfg))
        {
            throw new Exception("Name space [$ns] not found!!!!!");
        }

        list($model, $idx, $codeName, $descName, $fieldsHash) = $cfg;

        $filter = new CTable('');
        $filter->SetFieldValue($codeName, $data->GetFieldValue('CODE'));
        if ($model == 'MMasterRef')
        {
            $filter->SetFieldValue('REF_TYPE', $data->GetFieldValue('REF_TYPE'));
        }
        self::populateAdditionalFields($filter, $fieldsHash);

        $u = new $model($db);
        $u->OverideOrderBy($idx, "ORDER BY $codeName DESC ");        
        list($cnt, $rows) = $u->Query($idx, $filter);

        $arr = [];
        foreach ($rows as $r)
        {
            $o = new CTable('');
            $o->SetFieldValue('CODE', $r->GetFieldValue($codeName));
            $o->SetFieldValue('DESCRIPTION', $r->GetFieldValue($descName));

            array_push($arr, $o);
        }

        $result = new CTable($u->GetTableName());
        self::PopulateRow($result, $cnt, 1, 'SEARCH_TEXT_LIST', $arr);
        
        return(array($param, $result));
    }
}

?>