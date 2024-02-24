<?php
/* Put only class declaration here */

//From core_framework
require_once "phar://onix_core_framework.phar/onix_core_include.php";

//From erp_framework
require_once "phar://onix_erp_framework.phar/utils/CHelper.php";

require_once "phar://onix_erp_framework.phar/controllers/Admin/AdminAuthen.php";
require_once "phar://onix_erp_framework.phar/controllers/Admin/AdminUser.php";
require_once "phar://onix_erp_framework.phar/controllers/Admin/UserGroup.php";
require_once "phar://onix_erp_framework.phar/controllers/Admin/AccessRight.php";

require_once "phar://onix_erp_framework.phar/controllers/Cash/CashAccount.php";
require_once "phar://onix_erp_framework.phar/controllers/Cash/CashDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/Cash/CashReport.php";
require_once "phar://onix_erp_framework.phar/controllers/Cash/Cheque.php";
require_once "phar://onix_erp_framework.phar/controllers/Cash/ChequeReport.php";

require_once "phar://onix_erp_framework.phar/controllers/General/CompanyProfile.php";
require_once "phar://onix_erp_framework.phar/controllers/General/CustomerSupplier.php";
require_once "phar://onix_erp_framework.phar/controllers/General/Cycle.php";
require_once "phar://onix_erp_framework.phar/controllers/General/DocumentNumber.php";
require_once "phar://onix_erp_framework.phar/controllers/General/Employee.php";
require_once "phar://onix_erp_framework.phar/controllers/General/GlobalVariable.php";
require_once "phar://onix_erp_framework.phar/controllers/General/MasterRef.php";
require_once "phar://onix_erp_framework.phar/controllers/General/ProductService.php";
require_once "phar://onix_erp_framework.phar/controllers/General/ReportConfig.php";
require_once "phar://onix_erp_framework.phar/controllers/General/ReportFilter.php";
require_once "phar://onix_erp_framework.phar/controllers/General/Project.php";
require_once "phar://onix_erp_framework.phar/controllers/General/SearchFilter.php";
require_once "phar://onix_erp_framework.phar/controllers/General/GeneralReport.php";
require_once "phar://onix_erp_framework.phar/controllers/General/VirtualDirectory.php";

require_once "phar://onix_erp_framework.phar/controllers/Inventory/InventoryDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/Inventory/InventoryItem.php";
require_once "phar://onix_erp_framework.phar/controllers/Inventory/InventoryLocation.php";
require_once "phar://onix_erp_framework.phar/controllers/Inventory/InventoryReport.php";
require_once "phar://onix_erp_framework.phar/controllers/Inventory/ItemCategory.php";

require_once "phar://onix_erp_framework.phar/controllers/Patch/CustomPatch.php";
require_once "phar://onix_erp_framework.phar/controllers/Patch/PostPatch.php";

require_once "phar://onix_erp_framework.phar/controllers/Promotion/BillSimulate.php";
require_once "phar://onix_erp_framework.phar/controllers/Promotion/CalculateCommission.php";
require_once "phar://onix_erp_framework.phar/controllers/Promotion/Commission.php";
require_once "phar://onix_erp_framework.phar/controllers/Promotion/CommissionBatch.php";
require_once "phar://onix_erp_framework.phar/controllers/Promotion/CommissionProfile.php";
require_once "phar://onix_erp_framework.phar/controllers/Promotion/CompanyCommProfile.php";
require_once "phar://onix_erp_framework.phar/controllers/Promotion/CompanyPackage.php";
require_once "phar://onix_erp_framework.phar/controllers/Promotion/Package.php";
require_once "phar://onix_erp_framework.phar/controllers/Promotion/VoucherTemplate.php";

require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AccountDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AccountDocumentBase.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AccountDocumentDrCrNote.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AccountDocumentInvoiceCash.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AccountDocumentInvoiceDebt.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AccountDocumentReceipt.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AccountDocumentDeposit.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AccountDocumentDummy.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/ArApDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/ArApReport.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/SalePurchaseReport.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/AuxilaryDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/VoidedDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/VoidedDocumentBase.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/VoidedDocumentInvoiceCash.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/VoidedDocumentInvoiceDebt.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/VoidedDocumentReceipt.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/VoidedDocumentSaleOrder.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/VoidedDocumentBillSummary.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/TaxDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/CostDocument.php";

#HR
require_once "phar://onix_erp_framework.phar/controllers/HR/PayrollDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/HR/HrPayrollReport.php";
require_once "phar://onix_erp_framework.phar/controllers/HR/OtDocument.php";
require_once "phar://onix_erp_framework.phar/controllers/HR/EmployeeLeave.php";

#Customed
require_once "phar://onix_erp_framework.phar/controllers/SalePurchase/ACDesign/AcdSalePurchaseReport.php";

require_once "phar://onix_erp_framework.phar/models/MAccountDoc.php";
require_once "phar://onix_erp_framework.phar/models/MAccountDocItem.php";
require_once "phar://onix_erp_framework.phar/models/MAccountDocPayment.php";
require_once "phar://onix_erp_framework.phar/models/MAccountDocReceipt.php";
require_once "phar://onix_erp_framework.phar/models/MAccountDocDiscount.php";
require_once "phar://onix_erp_framework.phar/models/MAccountDocDeposit.php";
require_once "phar://onix_erp_framework.phar/models/MBalanceAccum.php";
require_once "phar://onix_erp_framework.phar/models/MBalanceTx.php";
require_once "phar://onix_erp_framework.phar/models/MBillSimulate.php";
require_once "phar://onix_erp_framework.phar/models/MBillSimulateDisplay.php";
require_once "phar://onix_erp_framework.phar/models/MBillSimulateItem.php";
require_once "phar://onix_erp_framework.phar/models/MCashAccount.php";
require_once "phar://onix_erp_framework.phar/models/MCashDoc.php";
require_once "phar://onix_erp_framework.phar/models/MCashXferDetail.php";
require_once "phar://onix_erp_framework.phar/models/MCompanyCommProfile.php";
require_once "phar://onix_erp_framework.phar/models/MCompanyPackage.php";
require_once "phar://onix_erp_framework.phar/models/MCompanyProfile.php";
require_once "phar://onix_erp_framework.phar/models/MCompanyImage.php";
require_once "phar://onix_erp_framework.phar/models/MCycle.php";
require_once "phar://onix_erp_framework.phar/models/MDocumentNumber.php";
require_once "phar://onix_erp_framework.phar/models/MEmployee.php";
require_once "phar://onix_erp_framework.phar/models/MEntity.php";
require_once "phar://onix_erp_framework.phar/models/MEntityAddress.php";
require_once "phar://onix_erp_framework.phar/models/MGlobalVariable.php";
require_once "phar://onix_erp_framework.phar/models/MGroupPermission.php";
require_once "phar://onix_erp_framework.phar/models/MInventoryAdjustment.php";
require_once "phar://onix_erp_framework.phar/models/MInventoryDoc.php";
require_once "phar://onix_erp_framework.phar/models/MInventoryTx.php";
require_once "phar://onix_erp_framework.phar/models/MItem.php";
require_once "phar://onix_erp_framework.phar/models/MItemBarcode.php";
require_once "phar://onix_erp_framework.phar/models/MItemCategory.php";
require_once "phar://onix_erp_framework.phar/models/MLocation.php";
require_once "phar://onix_erp_framework.phar/models/MLoginHistory.php";
require_once "phar://onix_erp_framework.phar/models/MMasterRef.php";
require_once "phar://onix_erp_framework.phar/models/MPackage.php";
require_once "phar://onix_erp_framework.phar/models/MPackageBonus.php";
require_once "phar://onix_erp_framework.phar/models/MPackageBranch.php";
require_once "phar://onix_erp_framework.phar/models/MPackageBundle.php";
require_once "phar://onix_erp_framework.phar/models/MPackageCustomer.php";
require_once "phar://onix_erp_framework.phar/models/MPackageDiscount.php";
require_once "phar://onix_erp_framework.phar/models/MPackageFinalDiscount.php";
require_once "phar://onix_erp_framework.phar/models/MPackagePeriod.php";
require_once "phar://onix_erp_framework.phar/models/MPackagePrice.php";
require_once "phar://onix_erp_framework.phar/models/MPackageTrayPrice.php";
require_once "phar://onix_erp_framework.phar/models/MPackageVoucher.php";
require_once "phar://onix_erp_framework.phar/models/MPatchHistory.php";
require_once "phar://onix_erp_framework.phar/models/MPermission.php";
require_once "phar://onix_erp_framework.phar/models/MReceiptItem.php";
require_once "phar://onix_erp_framework.phar/models/MReportConfig.php";
require_once "phar://onix_erp_framework.phar/models/MReportFilter.php";
require_once "phar://onix_erp_framework.phar/models/MService.php";
require_once "phar://onix_erp_framework.phar/models/MUser.php";
require_once "phar://onix_erp_framework.phar/models/MUserGroup.php";
require_once "phar://onix_erp_framework.phar/models/MUserVariable.php";
require_once "phar://onix_erp_framework.phar/models/MVoucherTemplate.php";
require_once "phar://onix_erp_framework.phar/models/MAuxilaryDoc.php";
require_once "phar://onix_erp_framework.phar/models/MAuxilaryDocItem.php";
require_once "phar://onix_erp_framework.phar/models/MAuxilaryDocRemark.php";
require_once "phar://onix_erp_framework.phar/models/MProject.php";
require_once "phar://onix_erp_framework.phar/models/MPaymentCriteria.php";
require_once "phar://onix_erp_framework.phar/models/MVoidedDoc.php";
require_once "phar://onix_erp_framework.phar/models/MEntityBankAccount.php";
require_once "phar://onix_erp_framework.phar/models/MCheque.php";
require_once "phar://onix_erp_framework.phar/models/MVirtualDirectory.php";
require_once "phar://onix_erp_framework.phar/models/MTaxDocument.php";
require_once "phar://onix_erp_framework.phar/models/MTaxDocumentPP30.php";
require_once "phar://onix_erp_framework.phar/models/MTaxDocumentRv3_53.php";
require_once "phar://onix_erp_framework.phar/models/MPayrollDocument.php";
require_once "phar://onix_erp_framework.phar/models/MPayrollDocumentItem.php";
require_once "phar://onix_erp_framework.phar/models/MOtDocument.php";
require_once "phar://onix_erp_framework.phar/models/MOtDocumentItem.php";
require_once "phar://onix_erp_framework.phar/models/MEmployeeExpenseItem.php";
require_once "phar://onix_erp_framework.phar/models/MPayrollDeductionItem.php";
require_once "phar://onix_erp_framework.phar/models/MCostDocument.php";
require_once "phar://onix_erp_framework.phar/models/MCostDocumentItem.php";
require_once "phar://onix_erp_framework.phar/models/MEmployeeLeaveDoc.php";
require_once "phar://onix_erp_framework.phar/models/MEmployeeLeaveRecord.php";

?>