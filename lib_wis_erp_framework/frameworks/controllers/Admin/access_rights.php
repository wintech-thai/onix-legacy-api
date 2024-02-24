<?php

#Access Right | Access Description | Default Value | Product hopper (bit 0=Onix, 1=Lotto, 2=Sass) 
$cfg_access_right = 
[
    ['ADMIN_GROUP_ADD', 'Enable/Disable adding user group', 'Y', 'YYY'],
    ['ADMIN_GROUP_DELETE', 'Enable/Disable deleting user group', 'Y', 'YYY'],
    ['ADMIN_GROUP_EDIT', 'Enable/Disable editing user group', 'Y', 'YYY'],
    ['ADMIN_GROUP_VIEW', 'Enable/Disable viewing user group', 'Y', 'YYY'],

    ['ADMIN_USER_ADD', 'Enable/Disable adding user', 'Y', 'YYY'],
    ['ADMIN_USER_DELETE', 'Enable/Disable deleting user', 'Y', 'YYY'],
    ['ADMIN_USER_EDIT', 'Enable/Disable editing user', 'Y', 'YYY'],
    ['ADMIN_USER_VIEW', 'Enable/Disable viewing user', 'Y', 'YYY'],

    ['CASH_CHEQUE_ADD', 'Enable/Disable adding cheque', 'Y', 'YYY'],
    ['CASH_CHEQUE_DELETE', 'Enable/Disable deleting cheque', 'Y', 'YYY'],
    ['CASH_CHEQUE_EDIT', 'Enable/Disable editing cheque', 'Y', 'YYY'],
    ['CASH_CHEQUE_VIEW', 'Enable/Disable viewing cheque', 'Y', 'YYY'],

    ['CASH_ACCOUNT_ADD', 'Enable/Disable adding cash account', 'Y', 'YYY'],
    ['CASH_ACCOUNT_DELETE', 'Enable/Disable deleting cash account', 'Y', 'YYY'],
    ['CASH_ACCOUNT_EDIT', 'Enable/Disable editing cash account', 'Y', 'YYY'],
    ['CASH_ACCOUNT_VIEW', 'Enable/Disable viewing cash account', 'Y', 'YYY'],

    ['CASH_IN_ADD', 'Enable/Disable adding cash in account', 'Y', 'YYY'],
    ['CASH_IN_DELETE', 'Enable/Disable deleting cash in account', 'Y', 'YYY'],
    ['CASH_IN_EDIT', 'Enable/Disable editing cash in account', 'Y', 'YYY'],
    ['CASH_IN_VIEW', 'Enable/Disable viewing cash in account', 'Y', 'YYY'],

    ['CASH_OUT_ADD', 'Enable/Disable adding cash out account', 'Y', 'YYY'],
    ['CASH_OUT_DELETE', 'Enable/Disable deleting cash out account', 'Y', 'YYY'],
    ['CASH_OUT_EDIT', 'Enable/Disable editing cash out account', 'Y', 'YYY'],
    ['CASH_OUT_VIEW', 'Enable/Disable viewing cash out account', 'Y', 'YYY'],

    ['CASH_XFER_ADD', 'Enable/Disable adding cash xfer account', 'Y', 'YYY'],
    ['CASH_XFER_DELETE', 'Enable/Disable deleting cash xfer account', 'Y', 'YYY'],
    ['CASH_XFER_EDIT', 'Enable/Disable editing cash xfer account', 'Y', 'YYY'],
    ['CASH_XFER_VIEW', 'Enable/Disable viewing cash xfer account', 'Y', 'YYY'],
    
    ['GENERAL_COMPANY_EDIT', 'Enable/Disable editing company profile', 'Y', 'YYY'],

    ['GENERAL_CYCLE_ADD', 'Enable/Disable adding cycle', 'Y', 'YYY'],
    ['GENERAL_CYCLE_DELETE', 'Enable/Disable deleting cycle', 'Y', 'YYY'],
    ['GENERAL_CYCLE_EDIT', 'Enable/Disable editing cycle', 'Y', 'YYY'],
    ['GENERAL_CYCLE_VIEW', 'Enable/Disable viewing cycle', 'Y', 'YYY'],

    ['HR_EMPLOYEE_ADD', 'Enable/Disable adding employee', 'Y', 'YYY'],
    ['HR_EMPLOYEE_DELETE', 'Enable/Disable deleting employee', 'Y', 'YYY'],
    ['HR_EMPLOYEE_EDIT', 'Enable/Disable editing employee', 'Y', 'YYY'],
    ['HR_EMPLOYEE_VIEW', 'Enable/Disable viewing employee', 'Y', 'YYY'],

    ['GENERAL_CUSTOMER_ADD', 'Enable/Disable adding customer', 'Y', 'YYY'],
    ['GENERAL_CUSTOMER_DELETE', 'Enable/Disable deleting customer', 'Y', 'YYY'],
    ['GENERAL_CUSTOMER_EDIT', 'Enable/Disable editing customer', 'Y', 'YYY'],
    ['GENERAL_CUSTOMER_VIEW', 'Enable/Disable viewing customer', 'Y', 'YYY'],

    ['GENERAL_SUPPLIER_ADD', 'Enable/Disable adding supplier', 'Y', 'YYY'],
    ['GENERAL_SUPPLIER_DELETE', 'Enable/Disable deleting supplier', 'Y', 'YYY'],
    ['GENERAL_SUPPLIER_EDIT', 'Enable/Disable editing supplier', 'Y', 'YYY'],
    ['GENERAL_SUPPLIER_VIEW', 'Enable/Disable viewing supplier', 'Y', 'YYY'],

    ['GENERAL_SERVICE_ADD', 'Enable/Disable adding supplier', 'Y', 'YYY'],
    ['GENERAL_SERVICE_DELETE', 'Enable/Disable deleting supplier', 'Y', 'YYY'],
    ['GENERAL_SERVICE_EDIT', 'Enable/Disable editing supplier', 'Y', 'YYY'],
    ['GENERAL_SERVICE_VIEW', 'Enable/Disable viewing supplier', 'Y', 'YYY'],

    ['GENERAL_VARIABLE_EDIT', 'Enable/Disable editing global variable', 'Y', 'YY'],

    ['GENERAL_MASTER_ADD', 'Enable/Disable adding master reference', 'Y', 'YYY'],
    ['GENERAL_MASTER_DELETE', 'Enable/Disable deleting master reference', 'Y', 'YYY'],
    ['GENERAL_MASTER_EDIT', 'Enable/Disable editing master reference', 'Y', 'YYY'],
    ['GENERAL_MASTER_VIEW', 'Enable/Disable viewing master reference', 'Y', 'YYY'],

    ['GENERAL_PROJECT_ADD', 'Enable/Disable adding project', 'Y', 'YYY'],
    ['GENERAL_PROJECT_DELETE', 'Enable/Disable deleting project', 'Y', 'YYY'],
    ['GENERAL_PROJECT_EDIT', 'Enable/Disable editing project', 'Y', 'YYY'],
    ['GENERAL_PROJECT_VIEW', 'Enable/Disable viewing project', 'Y', 'YYY'],

    ['INVENTORY_ITEM_ADD', 'Enable/Disable adding inventory item', 'Y', 'YYY'],
    ['INVENTORY_ITEM_DELETE', 'Enable/Disable deleting inventory item', 'Y', 'YYY'],
    ['INVENTORY_ITEM_EDIT', 'Enable/Disable editing inventory item', 'Y', 'YYY'],
    ['INVENTORY_ITEM_VIEW', 'Enable/Disable viewing inventory item', 'Y', 'YYY'],

    ['INVENTORY_LOCATION_ADD', 'Enable/Disable adding inventory location', 'Y', 'YYY'],
    ['INVENTORY_LOCATION_DELETE', 'Enable/Disable deleting inventory location', 'Y', 'YYY'],
    ['INVENTORY_LOCATION_EDIT', 'Enable/Disable deleting inventory location', 'Y', 'YYY'],
    ['INVENTORY_LOCATION_VIEW', 'Enable/Disable deleting inventory location', 'Y', 'YYY'],

    ['INVENTORY_IMPORT_ADD', 'Enable/Disable adding inventory import document', 'Y', 'YYY'],
    ['INVENTORY_IMPORT_DELETE', 'Enable/Disable deleting inventory import document', 'Y', 'YYY'],
    ['INVENTORY_IMPORT_EDIT', 'Enable/Disable editing inventory import document', 'Y', 'YYY'],
    ['INVENTORY_IMPORT_VIEW', 'Enable/Disable viewing inventory import document', 'Y', 'YYY'],

    ['INVENTORY_EXPORT_ADD', 'Enable/Disable adding inventory export document', 'Y', 'YYY'],
    ['INVENTORY_EXPORT_DELETE', 'Enable/Disable deleting inventory export document', 'Y', 'YYY'],
    ['INVENTORY_EXPORT_EDIT', 'Enable/Disable editing inventory export document', 'Y', 'YYY'],
    ['INVENTORY_EXPORT_VIEW', 'Enable/Disable viewing inventory export document', 'Y', 'YYY'],

    ['INVENTORY_XFER_ADD', 'Enable/Disable adding inventory xfer document', 'Y', 'YYY'],
    ['INVENTORY_XFER_DELETE', 'Enable/Disable deleting inventory xfer document', 'Y', 'YYY'],
    ['INVENTORY_XFER_EDIT', 'Enable/Disable editing inventory xfer document', 'Y', 'YYY'],
    ['INVENTORY_XFER_VIEW', 'Enable/Disable viewing inventory xfer document', 'Y', 'YYY'],

    ['INVENTORY_ADJUST_ADD', 'Enable/Disable adding inventory adjust document', 'Y', 'YYY'],
    ['INVENTORY_ADJUST_DELETE', 'Enable/Disable deleting inventory adjust document', 'Y', 'YYY'],
    ['INVENTORY_ADJUST_EDIT', 'Enable/Disable editing inventory adjust document', 'Y', 'YYY'],
    ['INVENTORY_ADJUST_VIEW', 'Enable/Disable viewing inventory adjust document', 'Y', 'YYY'],

    ['INVENTORY_BORROW_ADD', 'Enable/Disable adding inventory borrow document', 'Y', 'YYY'],
    ['INVENTORY_BORROW_DELETE', 'Enable/Disable deleting inventory borrow document', 'Y', 'YYY'],
    ['INVENTORY_BORROW_EDIT', 'Enable/Disable editing inventory borrow document', 'Y', 'YYY'],
    ['INVENTORY_BORROW_VIEW', 'Enable/Disable viewing inventory borrow document', 'Y', 'YYY'],
    
    ['INVENTORY_RETURN_ADD', 'Enable/Disable adding inventory return document', 'Y', 'YYY'],
    ['INVENTORY_RETURN_DELETE', 'Enable/Disable deleting inventory return document', 'Y', 'YYY'],
    ['INVENTORY_RETURN_EDIT', 'Enable/Disable editing inventory return document', 'Y', 'YYY'],
    ['INVENTORY_RETURN_VIEW', 'Enable/Disable viewing inventory return document', 'Y', 'YYY'],
/*
    ['PROMOTION_BILLSIM_DELETE', '', 'Y', 'YYYY'],
    ['PROMOTION_BILLSIM_VIEW', '', 'Y', 'YYYY'],
    ['PROMOTION_PROMOTION_DELETE', '', 'Y', 'YYYY'],
    ['PROMOTION_PROMOTION_VIEW', '', 'Y', 'YYYY'],
*/
    ['PURCHASE_BYCASH_ADD', 'Enable/Disable adding purchase by cash document', 'Y', 'YYY'],
    ['PURCHASE_BYCASH_DELETE', 'Enable/Disable deleting purchase by cash document', 'Y', 'YYY'],
    ['PURCHASE_BYCASH_EDIT', 'Enable/Disable editing purchase by cash document', 'Y', 'YYY'],
    ['PURCHASE_BYCASH_VIEW', 'Enable/Disable viewing purchase by cash document', 'Y', 'YYY'],

    ['PURCHASE_BYCREDIT_ADD', 'Enable/Disable adding purchase by credit document', 'Y', 'YYY'],
    ['PURCHASE_BYCREDIT_DELETE', 'Enable/Disable deleting purchase by credit document', 'Y', 'YYY'],
    ['PURCHASE_BYCREDIT_EDIT', 'Enable/Disable editing purchase by credit document', 'Y', 'YYY'],
    ['PURCHASE_BYCREDIT_VIEW', 'Enable/Disable viewing purchase by credit document', 'Y', 'YYY'],

    ['PURCHASE_CRNOTE_ADD', 'Enable/Disable adding purchase credit note', 'Y', 'YYY'],
    ['PURCHASE_CRNOTE_DELETE', 'Enable/Disable deleting purchase credit note', 'Y', 'YYY'],
    ['PURCHASE_CRNOTE_EDIT', 'Enable/Disable editing purchase credit note', 'Y', 'YYY'],
    ['PURCHASE_CRNOTE_VIEW', 'Enable/Disable viewing purchase credit note', 'Y', 'YYY'],

    ['PURCHASE_DRNOTE_ADD', 'Enable/Disable adding purchase dedit note', 'Y', 'YYY'],
    ['PURCHASE_DRNOTE_DELETE', 'Enable/Disable deleting purchase dedit note', 'Y', 'YYY'],
    ['PURCHASE_DRNOTE_EDIT', 'Enable/Disable editing purchase dedit note', 'Y', 'YYY'],
    ['PURCHASE_DRNOTE_VIEW', 'Enable/Disable viewing purchase dedit note', 'Y', 'YYY'],

    ['PURCHASE_MISC_ADD', 'Enable/Disable adding purchase misc document', 'Y', 'YYY'],
    ['PURCHASE_MISC_DELETE', 'Enable/Disable deleting purchase misc document', 'Y', 'YYY'],
    ['PURCHASE_MISC_EDIT', 'Enable/Disable editing purchase misc document', 'Y', 'YYY'],
    ['PURCHASE_MISC_VIEW', 'Enable/Disable viewing purchase misc document', 'Y', 'YYY'],

    ['PURCHASE_RECEIPT_ADD', 'Enable/Disable adding purchase receipt document', 'Y', 'YYY'],
    ['PURCHASE_RECEIPT_DELETE', 'Enable/Disable deleting purchase receipt document', 'Y', 'YYY'],
    ['PURCHASE_RECEIPT_EDIT', 'Enable/Disable editing purchase receipt document', 'Y', 'YYY'],
    ['PURCHASE_RECEIPT_VIEW', 'Enable/Disable viewing purchase receipt document', 'Y', 'YYY'],

    ['PURCHASE_PO_ADD', 'Enable/Disable adding purchase order', 'Y', 'YYY'],
    ['PURCHASE_PO_DELETE', 'Enable/Disable deleting purchase order', 'Y', 'YYY'],
    ['PURCHASE_PO_EDIT', 'Enable/Disable editing purchase order', 'Y', 'YYY'],
    ['PURCHASE_PO_VIEW', 'Enable/Disable viewing purchase order', 'Y', 'YYY'],

    ['PURCHASE_TAXDOC_ADD', 'Enable/Disable adding tax document', 'Y', 'YYY'],
    ['PURCHASE_TAXDOC_DELETE', 'Enable/Disable deleting tax document', 'Y', 'YYY'],
    ['PURCHASE_TAXDOC_EDIT', 'Enable/Disable editing tax document', 'Y', 'YYY'],
    ['PURCHASE_TAXDOC_VIEW', 'Enable/Disable viewing tax document', 'Y', 'YYY'],    
    
    ['SALE_BYCASH_ADD', 'Enable/Disable adding sale by cash document', 'Y', 'YYY'],
    ['SALE_BYCASH_DELETE', 'Enable/Disable deleting sale by cash document', 'Y', 'YYY'],
    ['SALE_BYCASH_EDIT', 'Enable/Disable editing sale by cash document', 'Y', 'YYY'],
    ['SALE_BYCASH_VIEW', 'Enable/Disable viewing sale by cash document', 'Y', 'YYY'],

    ['SALE_BYCREDIT_ADD', 'Enable/Disable adding sale by credit document', 'Y', 'YYY'],
    ['SALE_BYCREDIT_DELETE', 'Enable/Disable deleting sale by credit document', 'Y', 'YYY'],
    ['SALE_BYCREDIT_EDIT', 'Enable/Disable editing sale by credit document', 'Y', 'YYY'],
    ['SALE_BYCREDIT_VIEW', 'Enable/Disable viewing sale by credit document', 'Y', 'YYY'],

    ['SALE_CRNOTE_ADD', 'Enable/Disable adding purchase credit note', 'Y', 'YYY'],
    ['SALE_CRNOTE_DELETE', 'Enable/Disable deleting purchase credit note', 'Y', 'YYY'],
    ['SALE_CRNOTE_EDIT', 'Enable/Disable editing purchase credit note', 'Y', 'YYY'],
    ['SALE_CRNOTE_VIEW', 'Enable/Disable viewing purchase credit note', 'Y', 'YYY'],

    ['SALE_DRNOTE_ADD', 'Enable/Disable adding sale dedit note', 'Y', 'YYY'],
    ['SALE_DRNOTE_DELETE', 'Enable/Disable deleting sale dedit note', 'Y', 'YYY'],
    ['SALE_DRNOTE_EDIT', 'Enable/Disable editing sale dedit note', 'Y', 'YYY'],
    ['SALE_DRNOTE_VIEW', 'Enable/Disable viewing sale dedit note', 'Y', 'YYY'],

    ['SALE_MISC_ADD', 'Enable/Disable adding sale misc document', 'Y', 'YYY'],
    ['SALE_MISC_DELETE', 'Enable/Disable deleting sale misc document', 'Y', 'YYY'],
    ['SALE_MISC_EDIT', 'Enable/Disable editing sale misc document', 'Y', 'YYY'],
    ['SALE_MISC_VIEW', 'Enable/Disable viewing sale misc document', 'Y', 'YYY'],

    ['SALE_RECEIPT_ADD', 'Enable/Disable adding sale receipt document', 'Y', 'YYY'],
    ['SALE_RECEIPT_DELETE', 'Enable/Disable deleting sale receipt document', 'Y', 'YYY'],
    ['SALE_RECEIPT_EDIT', 'Enable/Disable editing sale receipt document', 'Y', 'YYY'],
    ['SALE_RECEIPT_VIEW', 'Enable/Disable viewing sale receipt document', 'Y', 'YYY'],

    ['SALE_QUOTATION_ADD', 'Enable/Disable adding quotation', 'Y', 'YYY'],
    ['SALE_QUOTATION_DELETE', 'Enable/Disable deleting quotation', 'Y', 'YYY'],
    ['SALE_QUOTATION_EDIT', 'Enable/Disable editing quotation', 'Y', 'YYY'],
    ['SALE_QUOTATION_VIEW', 'Enable/Disable viewing quotation', 'Y', 'YYY'],

    ['SALE_ORDER_ADD', 'Enable/Disable adding sale order', 'Y', 'YYY'],
    ['SALE_ORDER_DELETE', 'Enable/Disable deleting sale order', 'Y', 'YYY'],
    ['SALE_ORDER_EDIT', 'Enable/Disable editing sale order', 'Y', 'YYY'],
    ['SALE_ORDER_VIEW', 'Enable/Disable viewing sale order', 'Y', 'YYY'],

    ['SALE_BILLSUM_ADD', 'Enable/Disable adding bill summary', 'Y', 'YYY'],
    ['SALE_BILLSUM_DELETE', 'Enable/Disable deleting bill summary', 'Y', 'YYY'],
    ['SALE_BILLSUM_EDIT', 'Enable/Disable editing bill summary', 'Y', 'YYY'],
    ['SALE_BILLSUM_VIEW', 'Enable/Disable viewing bill summary', 'Y', 'YYY'],

    ['HR_PAYROLL_ADD', 'Enable/Disable adding payroll document', 'N', 'YYY'],
    ['HR_PAYROLL_DELETE', 'Enable/Disable deleting payroll document', 'N', 'YYY'],
    ['HR_PAYROLL_EDIT', 'Enable/Disable editing payroll document', 'N', 'YYY'],
    ['HR_PAYROLL_VIEW', 'Enable/Disable viewing payroll document', 'N', 'YYY'],  
    ['HR_PAYROLL_REPORT', 'Enable/Disable generating payroll report', 'N', 'YYY'], 

    ['HR_ORGCHART_ADD', 'Enable/Disable adding org chart', 'Y', 'YYY'],
    ['HR_ORGCHART_DELETE', 'Enable/Disable deleting org chart', 'Y', 'YYY'],
    ['HR_ORGCHART_EDIT', 'Enable/Disable editing org chart', 'Y', 'YYY'],
    ['HR_ORGCHART_VIEW', 'Enable/Disable viewing org chart', 'Y', 'YYY'],     
    
    ['HR_OT_ADD', 'Enable/Disable adding OT document', 'N', 'YYY'],
    ['HR_OT_DELETE', 'Enable/Disable deleting OT document', 'N', 'YYY'],
    ['HR_OT_EDIT', 'Enable/Disable editing OT document', 'N', 'YYY'],
    ['HR_OT_VIEW', 'Enable/Disable viewing OT document', 'N', 'YYY'],  
    ['HR_OT_REPORT', 'Enable/Disable generating OT report', 'N', 'YYY'],
    
    ['HR_TAXFORM_ADD', 'Enable/Disable adding OT document', 'N', 'YYY'],
    ['HR_TAXFORM_DELETE', 'Enable/Disable deleting OT document', 'N', 'YYY'],
    ['HR_TAXFORM_EDIT', 'Enable/Disable editing OT document', 'N', 'YYY'],
    ['HR_TAXFORM_VIEW', 'Enable/Disable viewing OT document', 'N', 'YYY'],      
];
