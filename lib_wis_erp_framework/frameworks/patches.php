<?php

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";
require_once "phar://onix_erp_framework.phar/build.php";

CDispatcher::AddParamVariable('ONIX_ERP_FRAMEWORK_VERSION', "1.0.4 built on $ONIX_ERP_FRAMEWORK_BUILT_DATE (MM/DD/YYYY)");

$ONIX_PATCH_MODEL = 'MPatchHistory';
$ONIX_PATCH_PHP = "phar://onix_erp_framework.phar/models/MPatchHistory.php";
$ONIX_SQL_PATH = "phar://onix_erp_framework.phar/patch";

$ONIX_PATCH_LIST = [    
    ['0.0', 'DBOS_0.0.20170321.sql'],
    ['1.0', 'DBOS_1.0.20170321.sql'],
    ['1.1', 'DBOS_1.1.20170322.sql'],
    ['1.2', 'DBOS_inventory_1.2.20170521.sql'],
    ['1.3', 'DBOS_generic_1.3.20170525.sql'],
    ['1.4', 'DBOS_admin_1.4.20170530.sql'],
    ['1.5', 'DBOS_inventory_1.5.20170531.sql'],
    ['1.5.1', 'DBOS_admin_1.5.1.20170531.sql'],
    ['1.5.2', 'DBOS_inventory_1.5.2.20170602.sql'],
    ['1.5.3', 'DBOS_sale_1.5.3.20170609.sql'],
    ['1.5.4', 'DBOS_sale_1.5.4.20170905.sql'],
    ['1.5.5', 'ONIX_1.5.5.20170910.sql'],        
    ['1.5.6', 'ONIX_1.5.6.20172310.sql'],
    ['1.5.7', 'ONIX_1.5.7.20171127.sql'],
    ['1.5.8', 'ONIX_1.5.8.20180111.sql'],
    ['1.5.9', 'ONIX_1.5.9.20180306.sql'],
    ['1.5.10', 'ONIX_1.5.10.20180501.sql'],
    ['1.5.11', 'ONIX_1.5.11.20181006.sql'],
    ['1.5.12', 'ONIX_1.5.12.20181222.sql'],
    ['1.5.13', 'ONIX_1.5.13.20190208.sql'],
    ['1.5.14', 'ONIX_1.5.14.20190317.sql'],
    ['1.5.15', 'ONIX_1.5.15.20190608.sql'],
    ['1.5.16', 'ONIX_1.5.16.20190926.sql'],
    ['1.5.17', 'ONIX_1.5.17.20240906.sql'],    
    ['1.5.17', 'ONIX_1.5.17.20240926.sql'],
    ['1.5.17', 'ONIX_1.5.17.20240927.sql'],  
    ['1.5.18', 'ONIX_1.5.18.20241120.sql'],  
    ['1.5.19', 'ONIX_1.5.19.20241125.sql'],
    ['1.5.20', 'ONIX_1.5.20.20241125.sql'],
];

//DO NOT use CUSTOM PATCH if possible, please use POST PATCH instead.
$ONIX_CUSTOM_PATCH_LIST = [
    ['001', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
    ['002', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
    ['RegisterItemAndOwnerTest', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
    ['RegisterItemAndOwnerInventory', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],       
    ['RegisterItemAndOwnerCash', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
    ['RegisterItemAndOwnerAR', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
    ['CreateMissingSequences', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
    ['MigrateDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
    ['PopulateEntityAddress', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
    ['CreateSaleDocumentNumberNV', "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php"],
];

$ONIX_POST_PATCH_LIST = [
    ['TestPostPatch', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['TestPostPatch2', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreatePurchaseDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreatePurchasePoDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['MigrateAdminToFramework', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateSaleQuotationDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateReceiptDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateRevExpDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateDepositDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateWhDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateSoDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],    
    ['CreateReceiptAccountDocItem', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreatePaymentIndex', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreatePaymentIndex2', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreatePaymentIndex3', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateBillSummaryDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['PopulatePoID', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['PopulatePoIDByPoNum', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateProjectIndex', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreateBorrowReturnDocumentNumber', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['ApproveCheque', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['UpdateAccessRight', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['UpdateAccessRight2', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['UpdateAccessRight3', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['UpdateAccessRight4', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['CreatePoItemIndex', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['UpdateAccessRight5', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['UpdateAccountDocRedeemed', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['UpdateAccessRight6', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
    ['UpdateAccessRight7', "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php"],
];

//Is being used in GetStaticPatchHistoryList()
$_ENV['ONIX_PATCH_LIST'] = $ONIX_PATCH_LIST;

CPatchDB::RegisterExtraPatch($ONIX_PATCH_LIST, $ONIX_PATCH_MODEL, $ONIX_PATCH_PHP, $ONIX_SQL_PATH, $ONIX_CUSTOM_PATCH_LIST, $ONIX_POST_PATCH_LIST);

?>