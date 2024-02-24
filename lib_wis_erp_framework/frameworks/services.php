<?php
/*
    Purpose : Custom services list
    Created By : Seubpong Monsar
    Created Date : 09/04/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

$bp = "phar://onix_erp_framework.phar/";

$userLvl = ['WhoCanRun' => 'user',    'Channel' => 'all'];
$admnLvl = ['WhoCanRun' => 'admin',   'Channel' => 'all'];
$initLvl = ['WhoCanRun' => 'nologin', 'Channel' => 'cli'];

$ONIX_SERVICES_LIST = [

    'ExportDataFile' => [$bp . 'controllers/POS/Pos.php', 'ExportDataFile', $userLvl],
    'CreatePatchFile' => [$bp . 'controllers/POS/Pos.php', 'CreatePatchFile', $userLvl],
    'PosImportDocuments' => [$bp . 'controllers/POS/Pos.php', 'PosImportDocuments', $userLvl],    
    'GetStaticPatchHistoryList' => [$bp . 'controllers/POS/Pos.php', 'GetStaticPatchHistoryList', $userLvl],

    'Login' => [$bp . 'controllers/Admin/AdminAuthen.php', 'Login', $userLvl],
    'Logout' => [$bp . 'controllers/Admin/AdminAuthen.php', 'Logout', $userLvl],
    'ChangeUserPassword' => [$bp . 'controllers/Admin/AdminAuthen.php', 'ChangeUserPassword', $admnLvl],
    'ChangePassword' => [$bp . 'controllers/Admin/AdminAuthen.php', 'ChangePassword', $userLvl],

    'CopyUser' => [$bp . 'controllers/Admin/AdminUser.php', 'CopyUser', $admnLvl],
    'GetUserList' => [$bp . 'controllers/Admin/AdminUser.php', 'GetUserList', $admnLvl],
    'GetUserInfo' => [$bp . 'controllers/Admin/AdminUser.php', 'GetUserInfo', $userLvl],
    'IsUserExist' => [$bp . 'controllers/Admin/AdminUser.php', 'IsUserExist', $admnLvl],
    'DeleteUser' => [$bp . 'controllers/Admin/AdminUser.php', 'DeleteUser', $admnLvl],
    'UpdateUser' => [$bp . 'controllers/Admin/AdminUser.php', 'UpdateUser', $admnLvl],
    'CreateUser' => [$bp . 'controllers/Admin/AdminUser.php', 'CreateUser', $admnLvl],
    'CreateInitAdminUser' => [$bp . 'controllers/Admin/AdminUser.php', 'CreateInitAdminUser', $initLvl],
    'UpdateUserVariables' => [$bp . 'controllers/Admin/AdminUser.php', 'UpdateUserVariables', $userLvl],
    'GetLoginHistoryList' => [$bp . 'controllers/Admin/AdminUser.php', 'GetLoginHistoryList', $admnLvl],
    'CheckPermission' => [$bp . 'controllers/Admin/AdminUser.php', 'CheckPermission', $userLvl],

    //User Group
    'GetUserGroupList' => [$bp . 'controllers/Admin/UserGroup.php', 'GetUserGroupList', $admnLvl],
    'GetUserGroupInfo' => [$bp . 'controllers/Admin/UserGroup.php', 'GetUserGroupInfo', $userLvl],
    'IsUserGroupExist' => [$bp . 'controllers/Admin/UserGroup.php', 'IsUserGroupExist', $admnLvl],
    'CreateUserGroup' => [$bp . 'controllers/Admin/UserGroup.php', 'CreateUserGroup', $admnLvl],
    'UpdateUserGroup' => [$bp . 'controllers/Admin/UserGroup.php', 'UpdateUserGroup', $admnLvl],
    'DeleteUserGroup' => [$bp . 'controllers/Admin/UserGroup.php', 'DeleteUserGroup', $admnLvl],
    'CopyUserGroup' => [$bp . 'controllers/Admin/UserGroup.php', 'CopyUserGroup', $admnLvl],
    'GetPermissionList' => [$bp . 'controllers/Admin/UserGroup.php', 'GetPermissionList', $userLvl],

    //Access Right
    'GetAccessRightList' => [$bp . 'controllers/Admin/AccessRight.php', 'GetAccessRightList', $userLvl],
    'GetAccessRightInfo' => [$bp . 'controllers/Admin/AccessRight.php', 'GetAccessRightInfo', $userLvl],
    'CreateAccessRight' => [$bp . 'controllers/Admin/AccessRight.php', 'CreateAccessRight', $userLvl],
    'UpdateAccessRight' => [$bp . 'controllers/Admin/AccessRight.php', 'UpdateAccessRight', $admnLvl],
    'DeleteAccessRight' => [$bp . 'controllers/Admin/AccessRight.php', 'DeleteAccessRight', $admnLvl],
    'GetUserContextAccessRight' => [$bp . 'controllers/Admin/AccessRight.php', 'GetUserContextAccessRight', $userLvl],
    'UpdateGroupAccessRight' => [$bp . 'controllers/Admin/AccessRight.php', 'UpdateGroupAccessRight', $admnLvl],
    'GetGroupAccessRightList' => [$bp . 'controllers/Admin/AccessRight.php', 'GetGroupAccessRightList', $admnLvl],

    //Package
    'GetPackageList' => [$bp . 'controllers/Promotion/Package.php', 'GetPackageList', $userLvl],
    'GetPackageInfo' => [$bp . 'controllers/Promotion/Package.php', 'GetPackageInfo', $userLvl],
    'DeletePackage' => [$bp . 'controllers/Promotion/Package.php', 'DeletePackage', $userLvl],
    'IsPackageExist' => [$bp . 'controllers/Promotion/Package.php', 'IsPackageExist', $userLvl],
    'CreatePackage' => [$bp . 'controllers/Promotion/Package.php', 'CreatePackage', $userLvl],
    'UpdatePackage' => [$bp . 'controllers/Promotion/Package.php', 'UpdatePackage', $userLvl],
    'CopyPackage' => [$bp . 'controllers/Promotion/Package.php', 'CopyPackage', $userLvl],

    //Inventory Item
    'GetInventoryItemList' => [$bp . 'controllers/Inventory/InventoryItem.php', 'GetInventoryItemList', $userLvl],
    'GetInventoryItemInfo' => [$bp . 'controllers/Inventory/InventoryItem.php', 'GetInventoryItemInfo', $userLvl],
    'DeleteInventoryItem' => [$bp . 'controllers/Inventory/InventoryItem.php', 'DeleteInventoryItem', $userLvl],
    'IsInventoryItemExist' => [$bp . 'controllers/Inventory/InventoryItem.php', 'IsInventoryItemExist', $userLvl],
    'CreateInventoryItem' => [$bp . 'controllers/Inventory/InventoryItem.php', 'CreateInventoryItem', $userLvl],
    'UpdateInventoryItem' => [$bp . 'controllers/Inventory/InventoryItem.php', 'UpdateInventoryItem', $userLvl],
    'CopyInventoryItem' => [$bp . 'controllers/Inventory/InventoryItem.php', 'CopyInventoryItem', $userLvl],
    'MoveServiceToItem' => [$bp . 'controllers/Inventory/InventoryItem.php', 'MoveServiceToItem', $userLvl],

    //Item Category
    'GetItemCategoryPathList' => [$bp . 'controllers/Inventory/ItemCategory.php', 'GetItemCategoryPathList', $userLvl],
    'GetItemCategoryList' => [$bp . 'controllers/Inventory/ItemCategory.php', 'GetItemCategoryList', $userLvl],
    'GetItemCategoryInfo' => [$bp . 'controllers/Inventory/ItemCategory.php', 'GetItemCategoryInfo', $userLvl],
    'CreateItemCategory' => [$bp . 'controllers/Inventory/ItemCategory.php', 'CreateItemCategory', $userLvl],
    'UpdateItemCategory' => [$bp . 'controllers/Inventory/ItemCategory.php', 'UpdateItemCategory', $userLvl],
    'DeleteItemCategory' => [$bp . 'controllers/Inventory/ItemCategory.php', 'DeleteItemCategory', $userLvl],
    'IsItemCategoryExist' => [$bp . 'controllers/Inventory/ItemCategory.php', 'IsItemCategoryExist', $userLvl],

    //Location
    'GetLocationList' => [$bp . 'controllers/Inventory/InventoryLocation.php', 'GetLocationList', $userLvl],
    'GetLocationInfo' => [$bp . 'controllers/Inventory/InventoryLocation.php', 'GetLocationInfo', $userLvl],
    'DeleteLocation' => [$bp . 'controllers/Inventory/InventoryLocation.php', 'DeleteLocation', $userLvl],
    'IsLocationExist' => [$bp . 'controllers/Inventory/InventoryLocation.php', 'IsLocationExist', $userLvl],
    'CreateLocation' => [$bp . 'controllers/Inventory/InventoryLocation.php', 'CreateLocation', $userLvl],
    'UpdateLocation' => [$bp . 'controllers/Inventory/InventoryLocation.php', 'UpdateLocation', $userLvl],
    'CopyLocation' => [$bp . 'controllers/Inventory/InventoryLocation.php', 'CopyLocation', $userLvl],

    //Company Package
    'GetCompanyPackageInfo' => [$bp . 'controllers/Promotion/CompanyPackage.php', 'GetCompanyPackageInfo', $userLvl],
    'UpdateCompanyPackage' => [$bp . 'controllers/Promotion/CompanyPackage.php', 'UpdateCompanyPackage', $userLvl],
    'CreateCompanyPackage' => [$bp . 'controllers/Promotion/CompanyPackage.php', 'CreateCompanyPackage', $userLvl],
    'DeleteCompanyPackage' => [$bp . 'controllers/Promotion/CompanyPackage.php', 'DeleteCompanyPackage', $userLvl],
    'GetCompanyPackageAll' => [$bp . 'controllers/Promotion/CompanyPackage.php', 'GetCompanyPackageAll', $userLvl],

    //Master Reference
    'GetMasterRefList' => [$bp . 'controllers/General/MasterRef.php', 'GetMasterRefList', $userLvl],
    'GetAllMasterRefList' => [$bp . 'controllers/General/MasterRef.php', 'GetAllMasterRefList', $userLvl],
    'GetMasterRefInfo' => [$bp . 'controllers/General/MasterRef.php', 'GetMasterRefInfo', $userLvl],
    'IsMasterRefExist' => [$bp . 'controllers/General/MasterRef.php', 'IsMasterRefExist', $userLvl],
    'CreateMasterRef' => [$bp . 'controllers/General/MasterRef.php', 'CreateMasterRef', $userLvl],
    'UpdateMasterRef' => [$bp . 'controllers/General/MasterRef.php', 'UpdateMasterRef', $userLvl],
    'DeleteMasterRef' => [$bp . 'controllers/General/MasterRef.php', 'DeleteMasterRef', $userLvl],
    'CopyMasterRef' => [$bp . 'controllers/General/MasterRef.php', 'CopyMasterRef', $userLvl],
    
    //Virtual Directory
    'GetVirtualDirectoryList' => [$bp . 'controllers/General/VirtualDirectory.php', 'GetVirtualDirectoryList', $userLvl],
    'GetVirtualDirectoryInfo' => [$bp . 'controllers/General/VirtualDirectory.php', 'GetVirtualDirectoryInfo', $userLvl],
    'IsVirtualDirectoryExist' => [$bp . 'controllers/General/VirtualDirectory.php', 'IsVirtualDirectoryExist', $userLvl],
    'CreateVirtualDirectory' => [$bp . 'controllers/General/VirtualDirectory.php', 'CreateVirtualDirectory', $userLvl],
    'UpdateVirtualDirectory' => [$bp . 'controllers/General/VirtualDirectory.php', 'UpdateVirtualDirectory', $userLvl],
    'DeleteVirtualDirectory' => [$bp . 'controllers/General/VirtualDirectory.php', 'DeleteVirtualDirectory', $userLvl],    
    'GetVirtualPathList' => [$bp . 'controllers/General/VirtualDirectory.php', 'GetVirtualPathList', $userLvl], 

    //TaxDocument
    'GetTaxDocList' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'GetTaxDocList', $userLvl],
    'GetTaxDocInfo' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'GetTaxDocInfo', $userLvl],
    'IsTaxDocExist' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'IsTaxDocExist', $userLvl],
    'CreateTaxDoc' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'CreateTaxDoc', $userLvl],
    'UpdateTaxDoc' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'UpdateTaxDoc', $userLvl],
    'DeleteTaxDoc' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'DeleteTaxDoc', $userLvl],    
    'PopulateWhItems' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'PopulateWhItems', $userLvl],
    'GetTaxDocRv3Rv53List' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'GetTaxDocRv3Rv53List', $userLvl],
    'ApproveTaxDoc' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'ApproveTaxDoc', $userLvl],
    'PopulatePayrollItems' => [$bp . 'controllers/SalePurchase/TaxDocument.php', 'PopulatePayrollItems', $userLvl],
    
    //Company Commission Profile
    'GetCompanyCommProfileInfo' => [$bp . 'controllers/Promotion/CompanyCommProfile.php', 'GetCompanyCommProfileInfo', $userLvl],
    'UpdateCompanyCommProfile' => [$bp . 'controllers/Promotion/CompanyCommProfile.php', 'UpdateCompanyCommProfile', $userLvl],
    'CreateCompanyCommProfile' => [$bp . 'controllers/Promotion/CompanyCommProfile.php', 'CreateCompanyCommProfile', $userLvl],
    'DeleteCompanyCommProfile' => [$bp . 'controllers/Promotion/CompanyCommProfile.php', 'DeleteCompanyCommProfile', $userLvl],

    //Company Profile
    'GetCompanyProfileList' => [$bp . 'controllers/General/CompanyProfile.php', 'GetCompanyProfileList', $userLvl],
    'GetCompanyProfileInfo' => [$bp . 'controllers/General/CompanyProfile.php', 'GetCompanyProfileInfo', $userLvl],
    'CreateCompanyProfile' => [$bp . 'controllers/General/CompanyProfile.php', 'CreateCompanyProfile', $userLvl],
    'UpdateCompanyProfile' => [$bp . 'controllers/General/CompanyProfile.php', 'UpdateCompanyProfile', $userLvl],
    'DeleteCompanyProfile' => [$bp . 'controllers/General/CompanyProfile.php', 'DeleteCompanyProfile', $userLvl],

    //Entity (Customer and Supplier)
    'GetEntityList' => [$bp . 'controllers/General/CustomerSupplier.php', 'GetEntityList', $userLvl],
    'GetEntityInfo' => [$bp . 'controllers/General/CustomerSupplier.php', 'GetEntityInfo', $userLvl],
    'IsEntityExist' => [$bp . 'controllers/General/CustomerSupplier.php', 'IsEntityExist', $userLvl],
    'CreateEntity' => [$bp . 'controllers/General/CustomerSupplier.php', 'CreateEntity', $userLvl],
    'UpdateEntity' => [$bp . 'controllers/General/CustomerSupplier.php', 'UpdateEntity', $userLvl],
    'DeleteEntity' => [$bp . 'controllers/General/CustomerSupplier.php', 'DeleteEntity', $userLvl],
    'CopyEntity' => [$bp . 'controllers/General/CustomerSupplier.php', 'CopyEntity', $userLvl],

    //Bill Simulate
    'GetBillSimulateList' => [$bp . 'controllers/Promotion/BillSimulate.php', 'GetBillSimulateList', $userLvl],
    'GetBillSimulateInfo' => [$bp . 'controllers/Promotion/BillSimulate.php', 'GetBillSimulateInfo', $userLvl],
    'IsBillSimulateExist' => [$bp . 'controllers/Promotion/BillSimulate.php', 'IsBillSimulateExist', $userLvl],
    'CreateBillSimulate' => [$bp . 'controllers/Promotion/BillSimulate.php', 'CreateBillSimulate', $userLvl],
    'UpdateBillSimulate' => [$bp . 'controllers/Promotion/BillSimulate.php', 'UpdateBillSimulate', $userLvl],
    'DeleteBillSimulate' => [$bp . 'controllers/Promotion/BillSimulate.php', 'DeleteBillSimulate', $userLvl],
    'CopyBillSimulate' => [$bp . 'controllers/Promotion/BillSimulate.php', 'CopyBillSimulate', $userLvl],

    //Service
    'GetServiceList' => [$bp . 'controllers/General/ProductService.php', 'GetServiceList', $userLvl],
    'GetServiceInfo' => [$bp . 'controllers/General/ProductService.php', 'GetServiceInfo', $userLvl],
    'IsServiceExist' => [$bp . 'controllers/General/ProductService.php', 'IsServiceExist', $userLvl],
    'CreateService' => [$bp . 'controllers/General/ProductService.php', 'CreateService', $userLvl],
    'UpdateService' => [$bp . 'controllers/General/ProductService.php', 'UpdateService', $userLvl],
    'DeleteService' => [$bp . 'controllers/General/ProductService.php', 'DeleteService', $userLvl],
    'CopyService' => [$bp . 'controllers/General/ProductService.php', 'CopyService', $userLvl],
    'GetServiceListAll' => [$bp . 'controllers/General/GeneralReport.php', 'GetServiceListAll', $userLvl],

    //Employee
    'GetEmployeeList' => [$bp . 'controllers/General/Employee.php', 'GetEmployeeList', $userLvl],
    'GetEmployeeInfo' => [$bp . 'controllers/General/Employee.php', 'GetEmployeeInfo', $userLvl],
    'IsEmployeeExist' => [$bp . 'controllers/General/Employee.php', 'IsEmployeeExist', $userLvl],
    'CreateEmployee' => [$bp . 'controllers/General/Employee.php', 'CreateEmployee', $userLvl],
    'UpdateEmployee' => [$bp . 'controllers/General/Employee.php', 'UpdateEmployee', $userLvl],
    'DeleteEmployee' => [$bp . 'controllers/General/Employee.php', 'DeleteEmployee', $userLvl],
    'CopyEmployee' => [$bp . 'controllers/General/Employee.php', 'CopyEmployee', $userLvl],
    
    //Voucher Template
    'GetVoucherTemplateList' => [$bp . 'controllers/Promotion/VoucherTemplate.php', 'GetVoucherTemplateList', $userLvl],
    'GetVoucherTemplateInfo' => [$bp . 'controllers/Promotion/VoucherTemplate.php', 'GetVoucherTemplateInfo', $userLvl],
    'IsVoucherTemplateExist' => [$bp . 'controllers/Promotion/VoucherTemplate.php', 'IsVoucherTemplateExist', $userLvl],
    'CreateVoucherTemplate' => [$bp . 'controllers/Promotion/VoucherTemplate.php', 'CreateVoucherTemplate', $userLvl],
    'UpdateVoucherTemplate' => [$bp . 'controllers/Promotion/VoucherTemplate.php', 'UpdateVoucherTemplate', $userLvl],
    'DeleteVoucherTemplate' => [$bp . 'controllers/Promotion/VoucherTemplate.php', 'DeleteVoucherTemplate', $userLvl],
    'CopyVoucherTemplate' => [$bp . 'controllers/Promotion/VoucherTemplate.php', 'CopyVoucherTemplate', $userLvl],

    //Cycle
    'GetCycleList' => [$bp . 'controllers/General/Cycle.php', 'GetCycleList', $userLvl],
    'GetCycleInfo' => [$bp . 'controllers/General/Cycle.php', 'GetCycleInfo', $userLvl],
    'IsCycleExist' => [$bp . 'controllers/General/Cycle.php', 'IsCycleExist', $userLvl],
    'CreateCycle' => [$bp . 'controllers/General/Cycle.php', 'CreateCycle', $userLvl],
    'UpdateCycle' => [$bp . 'controllers/General/Cycle.php', 'UpdateCycle', $userLvl],
    'DeleteCycle' => [$bp . 'controllers/General/Cycle.php', 'DeleteCycle', $userLvl],
    'CopyCycle' => [$bp . 'controllers/General/Cycle.php', 'CopyCycle', $userLvl],

    //Cash Account
    'GetCashAccountList' => [$bp . 'controllers/Cash/CashAccount.php', 'GetCashAccountList', $userLvl],
    'GetCashAccountInfo' => [$bp . 'controllers/Cash/CashAccount.php', 'GetCashAccountInfo', $userLvl],
    'IsCashAccountExist' => [$bp . 'controllers/Cash/CashAccount.php', 'IsCashAccountExist', $userLvl],
    'CreateCashAccount' => [$bp . 'controllers/Cash/CashAccount.php', 'CreateCashAccount', $userLvl],
    'UpdateCashAccount' => [$bp . 'controllers/Cash/CashAccount.php', 'UpdateCashAccount', $userLvl],
    'DeleteCashAccount' => [$bp . 'controllers/Cash/CashAccount.php', 'DeleteCashAccount', $userLvl],
    'CopyCashAccount' => [$bp . 'controllers/Cash/CashAccount.php', 'CopyCashAccount', $userLvl],
    'UpdateCashAccountTotalAmount' => [$bp . 'controllers/Cash/CashAccount.php', 'UpdateCashAccountTotalAmount', $userLvl],

    //Cash Doc
    'GetCashDocList' => [$bp . 'controllers/Cash/CashDocument.php', 'GetCashDocList', $userLvl],
    'GetCashDocInfo' => [$bp . 'controllers/Cash/CashDocument.php', 'GetCashDocInfo', $userLvl],
    'IsCashDocExist' => [$bp . 'controllers/Cash/CashDocument.php', 'IsCashDocExist', $userLvl],
    'CreateCashDoc' => [$bp . 'controllers/Cash/CashDocument.php', 'CreateCashDoc', $userLvl],
    'UpdateCashDoc' => [$bp . 'controllers/Cash/CashDocument.php', 'UpdateCashDoc', $userLvl],
    'DeleteCashDoc' => [$bp . 'controllers/Cash/CashDocument.php', 'DeleteCashDoc', $userLvl],
    'CopyCashDoc' => [$bp . 'controllers/Cash/CashDocument.php', 'CopyCashDoc', $userLvl],
    'ApproveCashDoc' => [$bp . 'controllers/Cash/CashDocument.php', 'ApproveCashDoc', $userLvl],
    'VerifyCashDoc' => [$bp . 'controllers/Cash/CashDocument.php', 'VerifyCashDoc', $userLvl],

    'GetCashTransactionMovementList' => [$bp . 'controllers/Cash/CashReport.php', 'GetCashTransactionMovementList', $userLvl],
    'GetCashTransactionList' => [$bp . 'controllers/Cash/CashReport.php', 'GetCashTransactionList', $userLvl],
    'GetCashBalanceDailyList' => [$bp . 'controllers/Cash/CashReport.php', 'GetCashBalanceDailyList', $userLvl],
    'GetCashBalanceSummaryList' => [$bp . 'controllers/Cash/CashReport.php', 'GetCashBalanceSummaryList', $userLvl],
    'GetCashMovementList' => [$bp . 'controllers/Cash/CashReport.php', 'GetCashMovementList', $userLvl],

    // Document Number
    'CreateDocumentNumber' => [$bp . 'controllers/General/DocumentNumber.php', 'CreateDocumentNumber', $userLvl],
    'UpdateDocumentNumber' => [$bp . 'controllers/General/DocumentNumber.php', 'UpdateDocumentNumber', $userLvl],
    'DeleteDocumentNumber' => [$bp . 'controllers/General/DocumentNumber.php', 'DeleteDocumentNumber', $userLvl],
    'GetDocumentNumberList' => [$bp . 'controllers/General/DocumentNumber.php', 'GetDocumentNumberList', $userLvl],
    'GetDocumentNumberInfo' => [$bp . 'controllers/General/DocumentNumber.php', 'GetDocumentNumberInfo', $userLvl],
    'GenerateDocumentNumber' => [$bp . 'controllers/General/DocumentNumber.php', 'GenerateDocumentNumber', $userLvl],
    'TestGenerateDocumentNumber' => [$bp . 'controllers/General/DocumentNumber.php', 'TestGenerateDocumentNumber', $userLvl],
    'GenerateCustomDocumentNumber' => [$bp . 'controllers/General/DocumentNumber.php', 'GenerateCustomDocumentNumber', $userLvl],

    //Inventory Doc
    'GetInventoryDocList' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'GetInventoryDocList', $userLvl],
    'GetInventoryDocInfo' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'GetInventoryDocInfo', $userLvl],
    'IsInventoryDocExist' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'IsInventoryDocExist', $userLvl],
    'CreateInventoryDoc' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'CreateInventoryDoc', $userLvl],
    'UpdateInventoryDoc' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'UpdateInventoryDoc', $userLvl],
    'DeleteInventoryDoc' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'DeleteInventoryDoc', $userLvl],
    'CopyInventoryDoc' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'CopyInventoryDoc', $userLvl],
    'ApproveInventoryDoc' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'ApproveInventoryDoc', $userLvl],
    'VerifyInventoryDoc' => [$bp . 'controllers/Inventory/InventoryDocument.php', 'VerifyInventoryDoc', $userLvl],

    'GetInventoryBalanceList' => [$bp . 'controllers/Inventory/InventoryReport.php', 'GetInventoryBalanceList', $userLvl],
    'GetInventoryItemMovementList' => [$bp . 'controllers/Inventory/InventoryReport.php', 'GetInventoryItemMovementList', $userLvl],
    'GetInventoryBalanceSummaryList' => [$bp . 'controllers/Inventory/InventoryReport.php', 'GetInventoryBalanceSummaryList', $userLvl],
    'GetInventoryItemBalanceInfo' => [$bp . 'controllers/Inventory/InventoryReport.php', 'GetInventoryItemBalanceInfo', $userLvl],
    'GetCurrentBalanceInfo' => [$bp . 'controllers/Inventory/InventoryReport.php', 'GetCurrentBalanceInfo', $userLvl],
    'GetInventoryTransactionList' => [$bp . 'controllers/Inventory/InventoryReport.php', 'GetInventoryTransactionList', $userLvl],
    'GetInventoryMovementSummaryList' => [$bp . 'controllers/Inventory/InventoryReport.php', 'GetInventoryMovementSummaryList', $userLvl],
    'GetBorrowedItemList' => [$bp . 'controllers/Inventory/InventoryReport.php', 'GetBorrowedItemList', $userLvl],

    //Account Doc
    'GetAccountDocList' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'GetAccountDocList', $userLvl],
    'GetAccountDocInfo' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'GetAccountDocInfo', $userLvl],
    'IsAccountDocExist' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'IsAccountDocExist', $userLvl],
    'CreateAccountDoc' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'CreateAccountDoc', $userLvl],
    'UpdateAccountDoc' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'UpdateAccountDoc', $userLvl],
    'DeleteAccountDoc' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'DeleteAccountDoc', $userLvl],
    'CopyAccountDoc' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'CopyAccountDoc', $userLvl],
    'ApproveAccountDoc' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'ApproveAccountDoc', $userLvl],
    'VerifyAccountDoc' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'VerifyAccountDoc', $userLvl],
    'GetReceivableDocList' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'GetReceivableDocList', $userLvl],
    'GetArApInvoiceList' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'GetArApInvoiceList', $userLvl],
    'AdjustApproveAccountDoc' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'AdjustApproveAccountDoc', $userLvl],
    'GetBillSummaryAbleDocList' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'GetBillSummaryAbleDocList', $userLvl],
    'UnlinkSaleOrderFromInvoice' => [$bp . 'controllers/SalePurchase/AccountDocument.php', 'UnlinkSaleOrderFromInvoice', $userLvl],
    
    //AR & AP
    'GetArApTransactionMovementList' => [$bp . 'controllers/SalePurchase/ArApReport.php', 'GetArApTransactionMovementList', $userLvl],
    'GetArApTransactionList' => [$bp . 'controllers/SalePurchase/ArApReport.php', 'GetArApTransactionList', $userLvl],
    'GetArApBalanceDailyList' => [$bp . 'controllers/SalePurchase/ArApReport.php', 'GetArApBalanceDailyList', $userLvl],
    'GetArApBalanceSummaryList' => [$bp . 'controllers/SalePurchase/ArApReport.php', 'GetArApBalanceSummaryList', $userLvl],        

    //Sale & Purchase
    'GetSalePurchaseTransactionList' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSalePurchaseTransactionList', $userLvl],
    'GetSalePurchaseByDateProdct' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSalePurchaseByDateProdct', $userLvl],
    'GetSalePurchaseHistoryList' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSalePurchaseHistoryList', $userLvl],
    'GetSalePurchaseDocumentList' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSalePurchaseDocumentList', $userLvl],
    'GetSalePurchaseDocItemList' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSalePurchaseDocItemList', $userLvl],
    'GetSalePurchaseWhDocList' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSalePurchaseWhDocList', $userLvl],    
    'GetPaymentTransactionList' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetPaymentTransactionList', $userLvl],    
    'GetInvoiceListByProject' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetInvoiceListByProject', $userLvl],    
    'GetSalePurchaseTxList' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSalePurchaseTxList', $userLvl],        
    'GetSalePurchaseTxByPoProjectGroup' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSalePurchaseTxByPoProjectGroup', $userLvl],
    'GetSaleInvoicePurchaseTxByPoProjectGroup' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetSaleInvoicePurchaseTxByPoProjectGroup', $userLvl],
    'GetProfitByDocTypeMonth' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetProfitByDocTypeMonth', $userLvl],
    'GetProfitByDocTypeProject' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetProfitByDocTypeProject', $userLvl],
    'GetProfitByDocTypeProjectGroup' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetProfitByDocTypeProjectGroup', $userLvl],
    'GetVatAmountByDocTypeInMonth' => [$bp . 'controllers/SalePurchase/SalePurchaseReport.php', 'GetVatAmountByDocTypeInMonth', $userLvl],
    
    //Global Variable
    'GetGlobalVariableList' => [$bp . 'controllers/General/GlobalVariable.php', 'GetGlobalVariableList', $userLvl],
    'GetGlobalVariableInfo' => [$bp . 'controllers/General/GlobalVariable.php', 'GetGlobalVariableInfo', $userLvl],
    'CreateGlobalVariable' => [$bp . 'controllers/General/GlobalVariable.php', 'CreateGlobalVariable', $userLvl],
    'UpdateGlobalVariable' => [$bp . 'controllers/General/GlobalVariable.php', 'UpdateGlobalVariable', $userLvl],
    
    'ImportCustomerCSV' => [$bp . 'utils/CHelper.php', 'ImportCustomerCSV', $userLvl],

    //Report Config
    'GetReportConfigList' => [$bp . 'controllers/General/ReportConfig.php', 'GetReportConfigList', $userLvl],
    'GetReportConfigInfo' => [$bp . 'controllers/General/ReportConfig.php', 'GetReportConfigInfo', $userLvl],
    'CreateReportConfig' => [$bp . 'controllers/General/ReportConfig.php', 'CreateReportConfig', $userLvl],
    'UpdateReportConfig' => [$bp . 'controllers/General/ReportConfig.php', 'UpdateReportConfig', $userLvl],
    'DeleteReportConfig' => [$bp . 'controllers/General/ReportConfig.php', 'DeleteReportConfig', $userLvl],
    'SaveReportConfig' => [$bp . 'controllers/General/ReportConfig.php', 'SaveReportConfig', $userLvl],

    //Report Filter
    'GetSingleReportFilterInfo' => [$bp . 'controllers/General/ReportFilter.php', 'GetSingleReportFilterInfo', $userLvl],
    'GetReportFilterInfo' => [$bp . 'controllers/General/ReportFilter.php', 'GetReportFilterInfo', $userLvl],
    'CreateReportFilter' => [$bp . 'controllers/General/ReportFilter.php', 'CreateReportFilter', $userLvl],
    'UpdateReportFilter' => [$bp . 'controllers/General/ReportFilter.php', 'UpdateReportFilter', $userLvl],
    
    //Auxilary Doc
    'GetAuxilaryDocList' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'GetAuxilaryDocList', $userLvl],
    'GetAuxilaryDocInfo' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'GetAuxilaryDocInfo', $userLvl],
    'IsAuxilaryDocExist' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'IsAuxilaryDocExist', $userLvl],
    'CreateAuxilaryDoc' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'CreateAuxilaryDoc', $userLvl],
    'UpdateAuxilaryDoc' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'UpdateAuxilaryDoc', $userLvl],
    'DeleteAuxilaryDoc' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'DeleteAuxilaryDoc', $userLvl],
    'CopyAuxilaryDoc' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'CopyAuxilaryDoc', $userLvl],
    'SaveAuxilaryDoc' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'SaveAuxilaryDoc', $userLvl],
    'ApproveAuxilaryDoc' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'ApproveAuxilaryDoc', $userLvl],
    'GetAuxilaryDocItemList' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'GetAuxilaryDocItemList', $userLvl],
    'GetAuxilaryDocCriteriaList' => [$bp . 'controllers/SalePurchase/AuxilaryDocument.php', 'GetAuxilaryDocCriteriaList', $userLvl],

    //Project
    'GetProjectList' => [$bp . 'controllers/General/Project.php', 'GetProjectList', $userLvl],
    'GetProjectInfo' => [$bp . 'controllers/General/Project.php', 'GetProjectInfo', $userLvl],
    'CreateProject' => [$bp . 'controllers/General/Project.php', 'CreateProject', $userLvl],
    'UpdateProject' => [$bp . 'controllers/General/Project.php', 'UpdateProject', $userLvl],
    'DeleteProject' => [$bp . 'controllers/General/Project.php', 'DeleteProject', $userLvl],
    'SaveProject' => [$bp . 'controllers/General/Project.php', 'SaveProject', $userLvl],
    'IsProjectExist' => [$bp . 'controllers/General/Project.php', 'IsProjectExist', $userLvl],
    'CopyProject' => [$bp . 'controllers/General/Project.php', 'CopyProject', $userLvl],

    //Voided Doc
    'ApproveVoidedDoc' => [$bp . 'controllers/SalePurchase/VoidedDocument.php', 'ApproveVoidedDoc', $userLvl],    
    'VerifyVoidedDoc' => [$bp . 'controllers/SalePurchase/VoidedDocument.php', 'VerifyVoidedDoc', $userLvl],    

    //Search Text List
    'GetSearchTextList' => [$bp . 'controllers/General/SearchFilter.php', 'GetSearchTextList', $userLvl],

    //Cheque
    'GetChequeList' => [$bp . 'controllers/Cash/Cheque.php', 'GetChequeList', $userLvl],
    'GetChequeInfo' => [$bp . 'controllers/Cash/Cheque.php', 'GetChequeInfo', $userLvl],
    'IsChequeExist' => [$bp . 'controllers/Cash/Cheque.php', 'IsChequeExist', $userLvl],
    'CreateCheque' => [$bp . 'controllers/Cash/Cheque.php', 'CreateCheque', $userLvl],
    'UpdateCheque' => [$bp . 'controllers/Cash/Cheque.php', 'UpdateCheque', $userLvl],
    'DeleteCheque' => [$bp . 'controllers/Cash/Cheque.php', 'DeleteCheque', $userLvl],
    'CopyCheque' => [$bp . 'controllers/Cash/Cheque.php', 'CopyCheque', $userLvl],
    'ApproveCheque' => [$bp . 'controllers/Cash/Cheque.php', 'ApproveCheque', $userLvl],
    'VerifyCheque' => [$bp . 'controllers/Cash/Cheque.php', 'VerifyCheque', $userLvl],    
    'GetChequeListAll' => [$bp . 'controllers/Cash/ChequeReport.php', 'GetChequeListAll', $userLvl],
    
    //EmployeeLeave
    'GetEmployeeLeaveDocList' => [$bp . 'controllers/HR/EmployeeLeave.php', 'GetEmployeeLeaveDocList', $userLvl],
    'GetEmployeeLeaveDocInfo' => [$bp . 'controllers/HR/EmployeeLeave.php', 'GetEmployeeLeaveDocInfo', $userLvl],
    'CreateEmployeeLeaveDoc' => [$bp . 'controllers/HR/EmployeeLeave.php', 'CreateEmployeeLeaveDoc', $userLvl],
    'UpdateEmployeeLeaveDoc' => [$bp . 'controllers/HR/EmployeeLeave.php', 'UpdateEmployeeLeaveDoc', $userLvl],
    'SaveEmployeeLeaveDoc' => [$bp . 'controllers/HR/EmployeeLeave.php', 'SaveEmployeeLeaveDoc', $userLvl],

    //PayrollDocument
    'GetPayrollDocList' => [$bp . 'controllers/HR/PayrollDocument.php', 'GetPayrollDocList', $userLvl],
    'GetPayrollDocInfo' => [$bp . 'controllers/HR/PayrollDocument.php', 'GetPayrollDocInfo', $userLvl],
    'CreatePayrollDoc' => [$bp . 'controllers/HR/PayrollDocument.php', 'CreatePayrollDoc', $userLvl],
    'UpdatePayrollDoc' => [$bp . 'controllers/HR/PayrollDocument.php', 'UpdatePayrollDoc', $userLvl],
    'DeletePayrollDoc' => [$bp . 'controllers/HR/PayrollDocument.php', 'DeletePayrollDoc', $userLvl],    
    'ApprovePayrollDoc' => [$bp . 'controllers/HR/PayrollDocument.php', 'ApprovePayrollDoc', $userLvl],
    'GetEmployeeAccumulate' => [$bp . 'controllers/HR/PayrollDocument.php', 'GetEmployeeAccumulate', $userLvl],

    //OtDocument
    'GetOtDocList' => [$bp . 'controllers/HR/OtDocument.php', 'GetOtDocList', $userLvl],
    'GetOtDocInfo' => [$bp . 'controllers/HR/OtDocument.php', 'GetOtDocInfo', $userLvl],
    'CreateOtDoc' => [$bp . 'controllers/HR/OtDocument.php', 'CreateOtDoc', $userLvl],
    'UpdateOtDoc' => [$bp . 'controllers/HR/OtDocument.php', 'UpdateOtDoc', $userLvl],
    'DeleteOtDoc' => [$bp . 'controllers/HR/OtDocument.php', 'DeleteOtDoc', $userLvl],    
    'ApproveOtDoc' => [$bp . 'controllers/HR/OtDocument.php', 'ApproveOtDoc', $userLvl],

    //Payroll Report
    'GetEmployeePayrollByDateList' => [$bp . 'controllers/HR/HrPayrollReport.php', 'GetEmployeePayrollByDateList', $userLvl],
    'GetEmployeePayrollByEmployeeList' => [$bp . 'controllers/HR/HrPayrollReport.php', 'GetEmployeePayrollByEmployeeList', $userLvl],
    'GetEmployeePayrollAccumulate' => [$bp . 'controllers/HR/HrPayrollReport.php', 'GetEmployeePayrollAccumulate', $userLvl],

    //Custom for ACDesign
    'AcdGetProfitByDocTypeMonth' => [$bp . 'controllers/SalePurchase/ACDesign/AcdSalePurchaseReport.php', 'AcdGetProfitByDocTypeMonth', $userLvl],

    //Cost Document
    'GetCostDocumentList' => [$bp . 'controllers/SalePurchase/CostDocument.php', 'GetCostDocumentList', $userLvl],
    'GetCostDocumentInfo' => [$bp . 'controllers/SalePurchase/CostDocument.php', 'GetCostDocumentInfo', $userLvl],
    'CreateCostDocument' => [$bp . 'controllers/SalePurchase/CostDocument.php', 'CreateCostDocument', $userLvl],
    'UpdateCostDocument' => [$bp . 'controllers/SalePurchase/CostDocument.php', 'UpdateCostDocument', $userLvl],
    'DeleteCostDocument' => [$bp . 'controllers/SalePurchase/CostDocument.php', 'DeleteCostDocument', $userLvl],    
    'ApproveCostDocument' => [$bp . 'controllers/SalePurchase/CostDocument.php', 'ApproveCostDocument', $userLvl],    
];

CDispatcher::RegisterServices($ONIX_SERVICES_LIST);

?>