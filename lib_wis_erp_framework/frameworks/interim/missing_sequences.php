 <?php


$tables = [
    'account_doc',
    'account_doc_item',
    'api_history',
    'bill_sim_item',
    'bill_simulate',
    'bill_simulate_display',
    'cash_account',
    'cash_doc',
    'commission',
    'commission_batch',
    'commission_batch_detail',
    'commission_prof_detail',
    'commission_profile',
    'company_comm_profile',
    'company_package',
    'company_profile',
    'customer_package',
    'cycle',
    'document_number',
    'employee',
    'entity',
    'frw_bal_accumulate',
    'frw_bal_document',
    'frw_bal_document_detail',
    'frw_bal_item',
    'frw_bal_lot',
    'frw_bal_owner',
    'frw_patch_history',
    'group_permission',
    'inventory_doc',
    'inventory_tx',
    'item',
    'item_barcode',
    'item_category',
    'location',
    'login_history',
    'login_session',
    'master_ref',
    'package',
    'package_bonus',
    'package_branch',
    'package_bundle',
    'package_customer',
    'package_discount',
    'package_final_discount',
    'package_period',
    'package_price',
    'package_tray_price',
    'package_type_map',
    'package_voucher',
    'patch_history',
    'permission',
    'sequence',
    'service',
    'user_group',
    'user_variable',
    'users',
    'voucher_template'
];

 $sequences = [
 'account_doc_approved_seq',
 'account_doc_item_seq',
 'account_doc_seq',
 'bill_sim_item_seq',
 'bill_simulate_display_seq',
 'bill_simulate_seq',
 'cash_account_seq',
 'cash_balance_seq',
 'cash_doc_approved_seq',
 'cash_doc_seq',
 'cash_tx_seq',
 'commission_batch_approved_seq',
 'commission_batch_detail_seq',
 'commission_batch_seq',
 'commission_prof_detail_seq',
 'commission_profile_seq',
 'commission_seq',
 'company_comm_profile_seq',
 'company_package_seq',
 'company_profile_seq',
 'customer_package_seq',
 'cycle_seq',
 'debug1_seq',
 'employee_seq',
 'frw_bal_accumulate_seq',
 'frw_bal_document_detail_seq',
 'frw_bal_document_seq',
 'frw_bal_item_seq',
 'frw_bal_lot_seq',
 'frw_bal_owner_seq',
 'group_permission_seq',
 'package_bonus_seq',
 'package_branch_seq',
 'package_bundle_seq',
 'package_customer_seq',
 'package_discount_seq',
 'package_final_discount_seq',
 'package_period_seq',
 'package_price_seq',
 'package_seq',
 'package_tray_price_seq',
 'package_voucher_seq',
 'service_seq',
 'user_group_seq',
 'users_seq'
 ];

 $seqHash = [];

 foreach ($sequences as $seq)
 {
    $seqHash[$seq] = $seq;
 }

 foreach ($tables as $table)
 {
     $seq = $table . '_seq';
     if (array_key_exists($seq, $seqHash))
     {
         continue;
     }

     $seq = strtoupper($table . '_seq');
     printf("CREATE SEQUENCE $seq START 1;\n");
 }

 ?>