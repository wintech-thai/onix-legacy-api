<?php
/* 
Purpose : Model for ACCOUNT_DOC_ITEM
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MAccountDocItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'ACCOUNT_DOC_ITEM_ID:SPK:ACCOUNT_DOC_ITEM_ID:Y',
                    'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'SELECTION_TYPE:NZ:SELECTION_TYPE:Y',
                    'SERVICE_ID:REFID:SERVICE_ID:Y',
                    'ITEM_ID:REFID:ITEM_ID:Y',
                    'QUANTITY:NZ:QUANTITY:N',
                    'UNIT_PRICE:NZ:UNIT_PRICE:N',
                    'AMOUNT:NZ:AMOUNT:N',
                    'DISCOUNT_AMT:NZ:DISCOUNT_AMT:N',
                    'WH_TAX_FLAG:S:WH_TAX_FLAG:N',
                    'WH_TAX_PCT:NZ:WH_TAX_PCT:N',
                    'WH_TAX_AMT:NZ:WH_TAX_AMT:N',
                    'VAT_TAX_FLAG:S:VAT_TAX_FLAG:N',
                    'VAT_TAX_PCT:NZ:VAT_TAX_PCT:N',
                    'VAT_TAX_AMT:NZ:VAT_TAX_AMT:N',
                    'REVENUE_EXPENSE_AMT:NZ:REVENUE_EXPENSE_AMT:N',
                    'AR_AP_AMT:NZ:AR_AP_AMT:N',                                        
                    'TOTAL_AMT:NZ:TOTAL_AMT:N',
                    'DISCOUNT:S:DISCOUNT:N',
                    'IS_VOUCH_FLAG:S:IS_VOUCH_FLAG:N',
                    'IS_TRAY_FLAG:S:IS_TRAY_FLAG:N',
                    'INVENTORY_TX_ID:REFID:INVENTORY_TX_ID:Y',
                    'FINAL_DISCOUNT_AVG:NZ:FINAL_DISCOUNT_AVG:N',
                    'PRIMARY_REVENUE_EXPENSE_AMT:NZ:PRIMARY_REVENUE_EXPENSE_AMT:N',
                    'PRIMARY_ITEM_DISCOUNT_AMT:NZ:PRIMARY_ITEM_DISCOUNT_AMT:N',
                    'PRIMARY_FINAL_DISCOUNT_AVG_AMT:NZ:PRIMARY_FINAL_DISCOUNT_AVG_AMT:N',
                    'TOTAL_AFTER_DISCOUNT:NZ:TOTAL_AFTER_DISCOUNT:N',
                    'ITEM_NOTE:S:ITEM_NOTE:N',
                    'FREE_TEXT:S:FREE_TEXT:N',
                    'REFERENCE_NUMBER:S:REFERENCE_NUMBER:N',
                    'PROJECT_ID:REFID:PROJECT_ID:Y',
                    'PO_PROJECT_ID:REFID:PO_PROJECT_ID:Y',
                    'DISCOUNT_PCT_FLAG:S:DISCOUNT_PCT_FLAG:N',
                    'DISCOUNT_PCT:NZ:DISCOUNT_PCT:N',
                    'REF_PO_NO:S:REF_PO_NO:N',
                    'ACTUAL_DOC_NO:S:ACTUAL_DOC_NO:N',
                    'ACTUAL_DOC_DATE:S:ACTUAL_DOC_DATE:N',
                    'WH_GROUP_CRITERIA:REFID:WH_GROUP_CRITERIA:N',                    
                    'PO_ITEM_ID:REFID:PO_ITEM_ID:Y',
                    'PO_CRITERIA_ID:REFID:PO_CRITERIA_ID:Y',
                    'REF_DOC_ID:REFID:REF_DOC_ID:Y',
                    'PO_ID:REFID:PO_ID:Y',
                    'ITEM_DETAIL:S:ITEM_DETAIL:N',
                    'FACTOR:NZ:FACTOR:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get Account Doc Item List
                    'AI.ACCOUNT_DOC_ITEM_ID:SPK:ACCOUNT_DOC_ITEM_ID:Y',
                    'AI.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'AI.SELECTION_TYPE:NZ:SELECTION_TYPE:Y',
                    'AI.SERVICE_ID:REFID:SERVICE_ID:Y',
                    'AI.ITEM_ID:REFID:ITEM_ID:Y',
                    'AI.QUANTITY:NZ:QUANTITY:N',
                    'AI.UNIT_PRICE:NZ:UNIT_PRICE:N',
                    'AI.AMOUNT:NZ:AMOUNT:N',
                    'AI.DISCOUNT_AMT:NZ:DISCOUNT_AMT:N',
                    'AI.WH_TAX_FLAG:S:WH_TAX_FLAG:N',
                    'AI.WH_TAX_PCT:NZ:WH_TAX_PCT:N',
                    'AI.WH_TAX_AMT:NZ:WH_TAX_AMT:N',
                    'AI.VAT_TAX_FLAG:S:VAT_TAX_FLAG:N',
                    'AI.VAT_TAX_PCT:NZ:VAT_TAX_PCT:N',
                    'AI.VAT_TAX_AMT:NZ:VAT_TAX_AMT:N',
                    'AI.REVENUE_EXPENSE_AMT:NZ:REVENUE_EXPENSE_AMT:N',
                    'AI.AR_AP_AMT:NZ:AR_AP_AMT:N',                    
                    'AI.TOTAL_AMT:NZ:TOTAL_AMT:N',
                    'AI.IS_VOUCH_FLAG:S:IS_VOUCH_FLAG:Y',
                    'AI.IS_TRAY_FLAG:S:IS_TRAY_FLAG:Y',
                    'AI.INVENTORY_TX_ID:REFID:INVENTORY_TX_ID:Y',
                    'AI.FINAL_DISCOUNT_AVG:NZ:FINAL_DISCOUNT_AVG:N',
                    'AI.PRIMARY_REVENUE_EXPENSE_AMT:NZ:PRIMARY_REVENUE_EXPENSE_AMT:N',
                    'AI.PRIMARY_ITEM_DISCOUNT_AMT:NZ:PRIMARY_ITEM_DISCOUNT_AMT:N',
                    'AI.PRIMARY_FINAL_DISCOUNT_AVG_AMT:NZ:PRIMARY_FINAL_DISCOUNT_AVG_AMT:N',
                    'AI.TOTAL_AFTER_DISCOUNT:NZ:TOTAL_AFTER_DISCOUNT:N',
                    'AI.ITEM_NOTE:S:ITEM_NOTE:N',
                    'AI.FREE_TEXT:S:FREE_TEXT:N',
                    'AI.REFERENCE_NUMBER:S:REFERENCE_NUMBER:N',
                    'AI.PROJECT_ID:REFID:PROJECT_ID:Y',
                    'AI.PO_PROJECT_ID:REFID:PO_PROJECT_ID:Y',
                    'AI.DISCOUNT_PCT_FLAG:S:DISCOUNT_PCT_FLAG:N',
                    'AI.DISCOUNT_PCT:NZ:DISCOUNT_PCT:N',
                    'AI.REF_PO_NO:S:REF_PO_NO:N',
                    'AI.ACTUAL_DOC_NO:S:ACTUAL_DOC_NO:N',
                    'AI.ACTUAL_DOC_DATE:S:ACTUAL_DOC_DATE:N',
                    'AI.WH_GROUP_CRITERIA:REFID:WH_GROUP_CRITERIA:N',
                    'AI.PO_ITEM_ID:REFID:PO_ITEM_ID:Y',
                    'AI.PO_CRITERIA_ID:REFID:PO_CRITERIA_ID:Y',
                    'AI.REF_DOC_ID:REFID:REF_DOC_ID:Y',
                    'AI.PO_ID:REFID:PO_ID:Y',
                    'AI.ITEM_DETAIL:S:ITEM_DETAIL:N',
                    'AI.FACTOR:NZ:FACTOR:N',

                    'IT.ITEM_CODE:S:ITEM_CODE:Y', 
                    'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:N', 
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',
                    'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
                    'IT.ITEM_CATEGORY:S:ITEM_CATEGORY:Y', 
                    'IT.VAT_FLAG:S:VAT_FLAG:Y', 

                    'IVT.ITEM_AMOUNT:N:COST_AMOUNT_AVG:N',

                    'SV.SERVICE_CODE:S:SERVICE_CODE:N', 
                    'SV.SERVICE_NAME:S:SERVICE_NAME:N', 
                    'SV.PRICING_DEFINITION:S:SERVICE_PRICING_DEFINITION:N',
                    'SV.WH_GROUP:REFID:WH_GROUP:N', 
                    'AI.DISCOUNT:S:DISCOUNT:N',

                    'PJ.PROJECT_CODE:S:PROJECT_CODE:N',
                    'PJ.PROJECT_NAME:S:PROJECT_NAME:N',

                    'UN1.DESCRIPTION:S:SERVICE_UNIT_NAME:N',
                    'UN1.DESCRIPTION_ENG:S:SERVICE_UNIT_NAME_ENG:N',
                    'UN2.DESCRIPTION:S:ITEM_UNIT_NAME:N',
                    'UN2.DESCRIPTION_ENG:S:ITEM_UNIT_NAME_ENG:N',    
                    
                    # Always put at the end
                    'AD.ACCOUNT_DOC_ID:INC_SET:ACCOUNT_DOC_ID_SET:Y', 
                  ],

                  [ # 2 For Delete by parent
                      'ACCOUNT_DOC_ID:SPK:ACCOUNT_DOC_ID:Y',
                  ],

                  [ # 3 For Get Account Doc Transaction List
                      'AI.ACCOUNT_DOC_ITEM_ID:SPK:ACCOUNT_DOC_ITEM_ID:Y',
                      'AI.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                      'AI.SELECTION_TYPE:NZ:SELECTION_TYPE:Y',
                      'AI.SERVICE_ID:REFID:SERVICE_ID:Y',
                      'AI.ITEM_ID:REFID:ITEM_ID:Y',
                      'AI.QUANTITY:NZ:QUANTITY:N',
                      'AI.UNIT_PRICE:NZ:UNIT_PRICE:N',
                      'AI.AMOUNT:NZ:AMOUNT:N',
                      'AI.DISCOUNT_AMT:NZ:DISCOUNT_AMT:N',
                      'AI.WH_TAX_FLAG:S:WH_TAX_FLAG:Y',
                      'AI.WH_TAX_PCT:NZ:WH_TAX_PCT:N',
                      'AI.WH_TAX_AMT:NZ:WH_TAX_AMT:N',
                      'AI.VAT_TAX_FLAG:S:VAT_TAX_FLAG:N',
                      'AI.VAT_TAX_PCT:NZ:VAT_TAX_PCT:N',
                      'AI.VAT_TAX_AMT:NZ:VAT_TAX_AMT:N',
                      'AI.REVENUE_EXPENSE_AMT:NZ:REVENUE_EXPENSE_AMT:N',
                      'AI.AR_AP_AMT:NZ:AR_AP_AMT:N',                    
                      'AI.TOTAL_AMT:NZ:TOTAL_AMT:N',
                      'AI.IS_VOUCH_FLAG:S:IS_VOUCH_FLAG:Y',
                      'AI.IS_TRAY_FLAG:S:IS_TRAY_FLAG:Y',
                      'AI.INVENTORY_TX_ID:REFID:INVENTORY_TX_ID:Y',
                      'AI.FINAL_DISCOUNT_AVG:NZ:FINAL_DISCOUNT_AVG:N',
                      'AI.PRIMARY_REVENUE_EXPENSE_AMT:NZ:PRIMARY_REVENUE_EXPENSE_AMT:N',
                      'AI.PRIMARY_ITEM_DISCOUNT_AMT:NZ:PRIMARY_ITEM_DISCOUNT_AMT:N',
                      'AI.PRIMARY_FINAL_DISCOUNT_AVG_AMT:NZ:PRIMARY_FINAL_DISCOUNT_AVG_AMT:N',
                      'AI.TOTAL_AFTER_DISCOUNT:NZ:TOTAL_AFTER_DISCOUNT:N',
                      'AI.ITEM_NOTE:S:ITEM_NOTE:N',
                      'AI.PROJECT_ID:REFID:PROJECT_ID:Y',
                      'AI.PO_PROJECT_ID:REFID:PO_PROJECT_ID:Y',
                      'AI.DISCOUNT_PCT_FLAG:S:DISCOUNT_PCT_FLAG:N',
                      'AI.DISCOUNT_PCT:NZ:DISCOUNT_PCT:N',
                      'AI.WH_GROUP_CRITERIA:REFID:WH_GROUP_CRITERIA:N',
                      'AI.PO_ITEM_ID:REFID:PO_ITEM_ID:Y',
                      'AI.PO_CRITERIA_ID:REFID:PO_CRITERIA_ID:Y',
                      'AI.FREE_TEXT:S:FREE_TEXT:N',
                      'AI.REF_DOC_ID:REFID:REF_DOC_ID:Y',
                      'AI.PO_ID:REFID:PO_ID:Y',
                      'AI.FACTOR:NZ:FACTOR:N',

                      'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                      'AD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                      'AD.BRANCH_ID:REFID:BRANCH_ID:Y',                    
                      'AD.DOCUMENT_TYPE:NZ:DOCUMENT_TYPE:Y',
                      'AD.DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:Y',
                      'AD.VAT_TYPE:NZ:VAT_TYPE:Y',
                      'AD.REF_DOCUMENT_NO:S:REF_DOCUMENT_NO:Y',
                      'AD.APPROVED_SEQ:N:APPROVED_SEQ:N',
                      'AD.REF_WH_DOC_NO:S:REF_WH_DOC_NO:Y',

                      'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
                      'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
                      'EN.ENTITY_TYPE:REFID:ENTITY_TYPE:Y',
                      'EN.ENTITY_GROUP:REFID:ENTITY_GROUP:Y',

                      'IVT.ITEM_AMOUNT:N:COST_AVG:N',
                      'IVT.FIFO_AMOUNT:PK:COST_VIFO:N', 
                      'IVT.LOT_NO:S:LOT_NO:N', 
                      'IVT.LOT_NOTE:S:LOT_NOTE:N',

                      'IT.ITEM_CODE:S:ITEM_CODE:Y', 
                      'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
                      'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y',
                      'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
                      'IT.ITEM_CATEGORY:REFID:ITEM_CATEGORY:Y', 
                      'IT.VAT_FLAG:S:VAT_FLAG:Y', 
                      
                      'BR.CODE:S:BRANCH_CODE:Y', 
                      'BR.DESCRIPTION:S:BRANCH_NAME:Y', 

                      'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
                      'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',

                      'SV.SERVICE_CODE:S:SERVICE_CODE:Y', 
                      'SV.SERVICE_NAME:S:SERVICE_NAME:Y', 
                      'SV.PRICING_DEFINITION:S:SERVICE_PRICING_DEFINITION:N',
                      'SV.WH_GROUP:REFID:WH_GROUP:N', 
                      'AI.DISCOUNT:S:DISCOUNT:N',

                      'UN1.DESCRIPTION:S:SERVICE_UNIT_NAME:N',
                      'UN1.DESCRIPTION_ENG:S:SERVICE_UNIT_NAME_ENG:N',
                      'UN2.DESCRIPTION:S:ITEM_UNIT_NAME:N',
                      'UN2.DESCRIPTION_ENG:S:ITEM_UNIT_NAME_ENG:N',    

                      # Always put these at the end
                      'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                      'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                      'AD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y',                                             
              ],
            

              [ # 4 Report
                  'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                  'AI.SELECTION_TYPE:NZ:SELECTION_TYPE:Y',
                  'SV.SERVICE_ID:REFID:SERVICE_ID:N', 
                  'SV.SERVICE_CODE:S:SERVICE_CODE:Y', 
                  'SV.SERVICE_NAME:S:SERVICE_NAME:Y',    
                  'IT.ITEM_ID:REFID:ITEM_ID:Y',                
                  'IT.ITEM_CODE:S:ITEM_CODE:Y', 
                  'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
                  'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y',
                  'SUM(ROUND(AI.REVENUE_EXPENSE_AMT, 2)):NZ:REVENUE_EXPENSE_AMT:N',
                  'SUM(ROUND(AI.QUANTITY, 2)):NZ:QUANTITY:N',
                  'SUM(ROUND(IVT.ITEM_AMOUNT, 2)):NZ:COST_AVG:N',

                  #Not used in the select column list
                  'BR.CODE:S:BRANCH_CODE:Y:N',
                  'BR.DESCRIPTION:S:BRANCH_NAME:Y:N',

                  # Always put these at the end
                  'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                  'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                  'AD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y',                      
              ],     
              
              [ # 5 Report WH & Expense
              'AI.ACCOUNT_DOC_ITEM_ID:SPK:ACCOUNT_DOC_ITEM_ID:Y',
              'AI.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
              'AI.SELECTION_TYPE:NZ:SELECTION_TYPE:Y',
              'AI.SERVICE_ID:REFID:SERVICE_ID:Y',
              'AI.ITEM_ID:REFID:ITEM_ID:Y',
              'AI.QUANTITY:NZ:QUANTITY:N',
              'AI.UNIT_PRICE:NZ:UNIT_PRICE:N',
              'AI.AMOUNT:NZ:AMOUNT:N',
              'AI.DISCOUNT_AMT:NZ:DISCOUNT_AMT:N',
              'AI.WH_TAX_FLAG:S:WH_TAX_FLAG:Y',
              'AI.WH_TAX_PCT:NZ:WH_TAX_PCT:N',
              'AI.WH_TAX_AMT:NZ:WH_TAX_AMT:N',
              'AI.VAT_TAX_FLAG:S:VAT_TAX_FLAG:N',
              'AI.VAT_TAX_PCT:NZ:VAT_TAX_PCT:N',
              'AI.VAT_TAX_AMT:NZ:VAT_TAX_AMT:N',
              'AI.REVENUE_EXPENSE_AMT:NZ:REVENUE_EXPENSE_AMT:N',
              'AI.AR_AP_AMT:NZ:AR_AP_AMT:N',                    
              'AI.TOTAL_AMT:NZ:TOTAL_AMT:N',
              'AI.IS_VOUCH_FLAG:S:IS_VOUCH_FLAG:Y',
              'AI.IS_TRAY_FLAG:S:IS_TRAY_FLAG:Y',
              'AI.INVENTORY_TX_ID:REFID:INVENTORY_TX_ID:Y',
              'AI.FINAL_DISCOUNT_AVG:NZ:FINAL_DISCOUNT_AVG:N',
              'AI.PRIMARY_REVENUE_EXPENSE_AMT:NZ:PRIMARY_REVENUE_EXPENSE_AMT:N',
              'AI.PRIMARY_ITEM_DISCOUNT_AMT:NZ:PRIMARY_ITEM_DISCOUNT_AMT:N',
              'AI.PRIMARY_FINAL_DISCOUNT_AVG_AMT:NZ:PRIMARY_FINAL_DISCOUNT_AVG_AMT:N',
              'AI.TOTAL_AFTER_DISCOUNT:NZ:TOTAL_AFTER_DISCOUNT:N',
              'AI.ITEM_NOTE:S:ITEM_NOTE:N',
              'AI.PROJECT_ID:REFID:PROJECT_ID:Y',
              'AI.PO_PROJECT_ID:REFID:PO_PROJECT_ID:Y',
              'AI.DISCOUNT_PCT_FLAG:S:DISCOUNT_PCT_FLAG:N',
              'AI.DISCOUNT_PCT:NZ:DISCOUNT_PCT:N',
              'AI.WH_GROUP_CRITERIA:REFID:WH_GROUP_CRITERIA:N',
              'AI.PO_ITEM_ID:REFID:PO_ITEM_ID:Y',
              'AI.PO_CRITERIA_ID:REFID:PO_CRITERIA_ID:Y',
              'AI.FREE_TEXT:S:FREE_TEXT:N',
              'AI.REF_DOC_ID:REFID:REF_DOC_ID:Y',
              'AI.PO_ID:REFID:PO_ID:Y',
              'AI.FACTOR:NZ:FACTOR:N',

              'AD.DOCUMENT_YYYY:S:DOCUMENT_YYYY:Y',              
              'AD.DOCUMENT_YYYYMM:S:DOCUMENT_YYYYMM:N',              

              'AD.BY_VOID_FLAG:S:BY_VOID_FLAG:Y',
              'AD.INTERNAL_DRCR_FLAG:S:INTERNAL_DRCR_FLAG:Y',
              'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
              'AD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
              'AD.BRANCH_ID:REFID:BRANCH_ID:Y',                    
              'AD.DOCUMENT_TYPE:NZ:DOCUMENT_TYPE:Y',
              'AD.DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:Y',
              'AD.VAT_TYPE:NZ:VAT_TYPE:Y',
              'AD.REF_DOCUMENT_NO:S:REF_DOCUMENT_NO:Y',
              'AD.REF_DOCUMENT_DATE:S:REF_DOCUMENT_DATE:Y',
              'AD.APPROVED_SEQ:N:APPROVED_SEQ:N',
              'AD.REF_WH_DOC_NO:S:REF_WH_DOC_NO:Y',
              'AD.WH_PAY_TYPE:REFID:WH_PAY_TYPE:N',
              'AD.ENTITY_ADDRESS_ID:REFID:ENTITY_ADDRESS_ID:N',              

              'EN.ENTITY_ID:REFID:ENTITY_ID:N',
              'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
              'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
              'EN.ENTITY_TYPE:REFID:ENTITY_TYPE:Y',
              'EN.ENTITY_GROUP:REFID:ENTITY_GROUP:Y',
              'EN.RV_TAX_TYPE:S:RV_TAX_TYPE:Y',
              'EN.ID_NUMBER:S:ID_NUMBER:N',

              'EA.ENTITY_ADDRESS:S:ADDRESS:N',

              'IVT.ITEM_AMOUNT:N:COST_AVG:N',
              'IVT.FIFO_AMOUNT:PK:COST_VIFO:N', 
              'IVT.LOT_NO:S:LOT_NO:N', 
              'IVT.LOT_NOTE:S:LOT_NOTE:N',

              'IT.ITEM_CODE:S:ITEM_CODE:Y', 
              'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
              'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y',
              'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
              'IT.ITEM_CATEGORY:REFID:ITEM_CATEGORY:Y', 
              'IT.VAT_FLAG:S:VAT_FLAG:Y', 
              
              'BR.CODE:S:BRANCH_CODE:Y', 
              'BR.DESCRIPTION:S:BRANCH_NAME:Y', 

              'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
              'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',

              'SV.SERVICE_CODE:S:SERVICE_CODE:Y', 
              'SV.SERVICE_NAME:S:SERVICE_NAME:Y', 
              'SV.PRICING_DEFINITION:S:SERVICE_PRICING_DEFINITION:N',
              'SV.WH_GROUP:REFID:WH_GROUP:N', 
              'AI.DISCOUNT:S:DISCOUNT:N',

              'UN1.DESCRIPTION:S:SERVICE_UNIT_NAME:N',
              'UN1.DESCRIPTION_ENG:S:SERVICE_UNIT_NAME_ENG:N',
              'UN2.DESCRIPTION:S:ITEM_UNIT_NAME:N',
              'UN2.DESCRIPTION_ENG:S:ITEM_UNIT_NAME_ENG:N',    

              'AD2.DOCUMENT_NO:S:ACTUAL_DOCUMENT_NO:N',
              'AD2.REF_DOCUMENT_NO:S:ACTUAL_INVOICE_NO:N',
              'AD2.REF_DOCUMENT_DATE:S:ACTUAL_INVOICE_DATE:Y',
              'AD2.DOCUMENT_TYPE:S:REF_DOCUMENT_TYPE:N',

              # Always put these at the end
              'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
              'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
              'AD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y', 
              'AD.DOCUMENT_STATUS:INC_SET:DOCUMENT_STATUS_SET:Y',                                            
        ],

        [ # 6 Report WH & Expense
            'AI.ACCOUNT_DOC_ITEM_ID:SPK:ACCOUNT_DOC_ITEM_ID:Y',
            'AI.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
            'AI.SELECTION_TYPE:NZ:SELECTION_TYPE:Y',
            'AI.SERVICE_ID:REFID:SERVICE_ID:Y',
            'AI.ITEM_ID:REFID:ITEM_ID:Y',
            'AI.QUANTITY:NZ:QUANTITY:N',
            'AI.UNIT_PRICE:NZ:UNIT_PRICE:N',
            'AI.AMOUNT:NZ:AMOUNT:N',
            'AI.DISCOUNT_AMT:NZ:DISCOUNT_AMT:N',
            'AI.WH_TAX_FLAG:S:WH_TAX_FLAG:Y',
            'AI.WH_TAX_PCT:NZ:WH_TAX_PCT:N',
            'AI.WH_TAX_AMT:NZ:WH_TAX_AMT:N',
            'AI.VAT_TAX_FLAG:S:VAT_TAX_FLAG:N',
            'AI.VAT_TAX_PCT:NZ:VAT_TAX_PCT:N',
            'AI.VAT_TAX_AMT:NZ:VAT_TAX_AMT:N',
            'AI.REVENUE_EXPENSE_AMT:NZ:REVENUE_EXPENSE_AMT:N',
            'AI.AR_AP_AMT:NZ:AR_AP_AMT:N',                    
            'AI.TOTAL_AMT:NZ:TOTAL_AMT:N',
            'AI.IS_VOUCH_FLAG:S:IS_VOUCH_FLAG:Y',
            'AI.IS_TRAY_FLAG:S:IS_TRAY_FLAG:Y',
            'AI.INVENTORY_TX_ID:REFID:INVENTORY_TX_ID:Y',
            'AI.FINAL_DISCOUNT_AVG:NZ:FINAL_DISCOUNT_AVG:N',
            'AI.PRIMARY_REVENUE_EXPENSE_AMT:NZ:PRIMARY_REVENUE_EXPENSE_AMT:N',
            'AI.PRIMARY_ITEM_DISCOUNT_AMT:NZ:PRIMARY_ITEM_DISCOUNT_AMT:N',
            'AI.PRIMARY_FINAL_DISCOUNT_AVG_AMT:NZ:PRIMARY_FINAL_DISCOUNT_AVG_AMT:N',
            'AI.TOTAL_AFTER_DISCOUNT:NZ:TOTAL_AFTER_DISCOUNT:N',
            'AI.ITEM_NOTE:S:ITEM_NOTE:N',
            'AI.PROJECT_ID:REFID:PROJECT_ID:Y',
            'AI.PO_PROJECT_ID:REFID:PO_PROJECT_ID:Y',
            'AI.DISCOUNT_PCT_FLAG:S:DISCOUNT_PCT_FLAG:N',
            'AI.DISCOUNT_PCT:NZ:DISCOUNT_PCT:N',
            'AI.WH_GROUP_CRITERIA:REFID:WH_GROUP_CRITERIA:N',
            'AI.PO_ITEM_ID:REFID:PO_ITEM_ID:Y',
            'AI.PO_CRITERIA_ID:REFID:PO_CRITERIA_ID:Y',
            'AI.FREE_TEXT:S:FREE_TEXT:N',
            'AI.REF_DOC_ID:REFID:REF_DOC_ID:Y',
            'AI.DISCOUNT:S:DISCOUNT:N',
            'AI.PO_ID:REFID:PO_ID:Y',
            'AI.FACTOR:NZ:FACTOR:N',
            
            'AD.DOCUMENT_YYYY:S:DOCUMENT_YYYY:Y',              
            'AD.DOCUMENT_YYYYMM:S:DOCUMENT_YYYYMM:N',              
            
            'AD.BY_VOID_FLAG:S:BY_VOID_FLAG:Y',
            'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
            'AD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
            'AD.BRANCH_ID:REFID:BRANCH_ID:Y',                    
            'AD.DOCUMENT_TYPE:NZ:DOCUMENT_TYPE:Y',
            'AD.DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:Y',
            'AD.VAT_TYPE:NZ:VAT_TYPE:Y',
            'AD.REF_DOCUMENT_NO:S:REF_DOCUMENT_NO:Y',            
            'AD.REF_DOCUMENT_DATE:S:REF_DOCUMENT_DATE:Y',            
            'AD.APPROVED_SEQ:N:APPROVED_SEQ:N',
            'AD.REF_WH_DOC_NO:S:REF_WH_DOC_NO:Y',
            'AD.INDEX_PAYMENT:S:INDEX_PAYMENT:Y',
            'AD.INTERNAL_DRCR_FLAG:S:INTERNAL_DRCR_FLAG:Y',

            'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
            'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
            'EN.ENTITY_TYPE:REFID:ENTITY_TYPE:Y',
            'EN.ENTITY_GROUP:REFID:ENTITY_GROUP:Y',

            'IT.ITEM_CODE:S:ITEM_CODE:Y', 
            'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
            'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y',
            'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
            'IT.ITEM_CATEGORY:REFID:ITEM_CATEGORY:Y', 
            'IT.VAT_FLAG:S:VAT_FLAG:Y', 
      
            'BR.CODE:S:BRANCH_CODE:Y', 
            'BR.DESCRIPTION:S:BRANCH_NAME:Y', 

            'POPJ.PROJECT_CODE:S:PO_PROJECT_CODE:Y',
            'POPJ.PROJECT_NAME:S:PO_PROJECT_NAME:Y',
            'POPG.CODE:S:PO_PROJECT_GROUP_CODE:Y',
            'POPG.DESCRIPTION:S:PO_PROJECT_GROUP_NAME:Y',

            'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
            'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',
            'PG.CODE:S:PROJECT_GROUP_CODE:Y',
            'PG.DESCRIPTION:S:PROJECT_GROUP_NAME:Y',

            'SV.SERVICE_CODE:S:SERVICE_CODE:Y', 
            'SV.SERVICE_NAME:S:SERVICE_NAME:Y', 
            'SV.PRICING_DEFINITION:S:SERVICE_PRICING_DEFINITION:N',
            'SV.WH_GROUP:REFID:WH_GROUP:N', 
            
            'AD2.DOCUMENT_NO:S:RECEIPT_NO:N',
            'AD2.DOCUMENT_DATE:S:RECEIPT_DATE:N',
            'AD2.INDEX_PAYMENT:S:RECEIPT_INDEX_PAYMENT:N',
            'AD2.REF_DOCUMENT_NO:S:ACTUAL_INVOICE_NO:N',
            'AD2.REF_DOCUMENT_DATE:S:ACTUAL_INVOICE_DATE:Y',

            # Always put these at the end
            'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
            'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
            'AD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y',     
            'AD.DOCUMENT_STATUS:INC_SET:DOCUMENT_STATUS_SET:Y',                                        
        ],

        [ # 7 For updating PO ID (from post patch)
            'ACCOUNT_DOC_ITEM_ID:SPK:ACCOUNT_DOC_ITEM_ID:Y',
            'PO_ID:REFID:PO_ID:N',        
        ],   
        
        [ # 8 Approved adjust
            'ACCOUNT_DOC_ITEM_ID:SPK:ACCOUNT_DOC_ITEM_ID:Y',
            'PROJECT_ID:REFID:PROJECT_ID:N',        
        ], 
        
        [ # 9 Profit By Project Report
            'AI.PROJECT_ID:N:PROJECT_ID:N',
            'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
            'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',
            'AD.DOCUMENT_YYYYMM:S:DOCUMENT_YYYYMM:Y',
            'AD.DOCUMENT_TYPE:N:DOCUMENT_TYPE:N',                        
            'AD.DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:Y:N',
            'AD.BY_VOID_FLAG:S:BY_VOID_FLAG:Y:N',
            'AD.INTERNAL_DRCR_FLAG:S:INTERNAL_DRCR_FLAG:Y:N',

            'SUM(ROUND(AI.REVENUE_EXPENSE_AMT, 2)):NZ:REVENUE_EXPENSE_AMT:N',
            'SUM(ROUND(AI.VAT_TAX_AMT, 2)):NZ:VAT_AMT:N',
            'SUM(ROUND(AI.WH_TAX_AMT, 2)):NZ:WH_TAX_AMT:N',
 
            # Always put these at the end
            'AD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y',
            'AD.DOCUMENT_STATUS:INC_SET:DOCUMENT_STATUS_SET:Y',                      
        ],

        [ # 10 Profit By Project Group
            'PJ.PROJECT_GROUP:N:PROJECT_GROUP:N',
            'PG.CODE:S:PROJECT_GROUP_CODE:Y',
            'PG.DESCRIPTION:S:PROJECT_GROUP_NAME:Y',
            'AD.DOCUMENT_YYYYMM:S:DOCUMENT_YYYYMM:Y',
            'AD.DOCUMENT_TYPE:N:DOCUMENT_TYPE:N',                        
            'AD.DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:Y:N',
            'AD.BY_VOID_FLAG:S:BY_VOID_FLAG:Y:N',
            'AD.INTERNAL_DRCR_FLAG:S:INTERNAL_DRCR_FLAG:Y:N',

            'SUM(ROUND(AI.REVENUE_EXPENSE_AMT, 2)):NZ:REVENUE_EXPENSE_AMT:N',
            'SUM(ROUND(AI.VAT_TAX_AMT, 2)):NZ:VAT_AMT:N',
            'SUM(ROUND(AI.WH_TAX_AMT, 2)):NZ:WH_TAX_AMT:N',
 
            # Always put these at the end
            'AD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y',
            'AD.DOCUMENT_STATUS:INC_SET:DOCUMENT_STATUS_SET:Y',                      
        ], 
        
        [ # 11 
            'SUM(AI.QUANTITY):NZ:QUANTITY:N',
            'SUM(AI.UNIT_PRICE):NZ:UNIT_PRICE:N',
            'SUM(AI.AMOUNT):NZ:AMOUNT:N',
            'SUM(AI.DISCOUNT_AMT):NZ:DISCOUNT_AMT:N',
            'SUM(AI.WH_TAX_AMT):NZ:WH_TAX_AMT:N',
            'SUM(AI.VAT_TAX_AMT):NZ:VAT_TAX_AMT:N',
            'SUM(AI.REVENUE_EXPENSE_AMT):NZ:REVENUE_EXPENSE_AMT:N',
            'SUM(AI.AR_AP_AMT):NZ:AR_AP_AMT:N',                    
            'SUM(AI.TOTAL_AMT):NZ:TOTAL_AMT:N',

            'AD.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:N',            
            'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
            'AD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
            'AD.DOCUMENT_DESC:S:DOCUMENT_DESC:N',
            'AD.DOCUMENT_TYPE:NZ:DOCUMENT_TYPE:Y',
            'AD.REF_DOCUMENT_NO:S:REF_DOCUMENT_NO:Y',            
            'AD.REF_DOCUMENT_DATE:S:REF_DOCUMENT_DATE:Y',            
            'AD.INDEX_PAYMENT:S:INDEX_PAYMENT:Y',
            'AD.BY_VOID_FLAG:S:BY_VOID_FLAG:Y',
            'AD.INTERNAL_DRCR_FLAG:S:INTERNAL_DRCR_FLAG:Y',
            'AD.DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:Y:N',
            'AD.REDEEM_DOCUMENT_ID:REFID:REDEEM_DOCUMENT_ID:N',        

            'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
            'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
            'EN.ENTITY_TYPE:REFID:ENTITY_TYPE:Y',
            'EN.ENTITY_GROUP:REFID:ENTITY_GROUP:Y',

            'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
            'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',
            'PG.CODE:S:PROJECT_GROUP_CODE:Y',
            'PG.DESCRIPTION:S:PROJECT_GROUP_NAME:Y',
            
            'AD2.DOCUMENT_NO:S:RECEIPT_NO:N',
            'AD2.DOCUMENT_DATE:S:RECEIPT_DATE:N',
            'AD2.INDEX_PAYMENT:S:RECEIPT_INDEX_PAYMENT:N',
            'AD2.REF_DOCUMENT_NO:S:ACTUAL_INVOICE_NO:N',
            'AD2.REF_DOCUMENT_DATE:S:ACTUAL_INVOICE_DATE:Y',

            # Always put these at the end
            'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
            'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
            'AD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y',     
            'AD.DOCUMENT_STATUS:INC_SET:DOCUMENT_STATUS_SET:Y',                                        
        ],        
    );

    private $froms = array(

                'FROM ACCOUNT_DOC_ITEM ',

                'FROM ACCOUNT_DOC_ITEM AI '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AD.ACCOUNT_DOC_ID = AI.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = AI.ITEM_ID) '.
                    'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = AI.SERVICE_ID) ' .
                    'LEFT OUTER JOIN INVENTORY_TX IVT ON (AI.INVENTORY_TX_ID = IVT.TX_ID) '.
                    'LEFT OUTER JOIN MASTER_REF UN1 ON (UN1.MASTER_ID = SV.SERVICE_UOM) '.
                    'LEFT OUTER JOIN PROJECT PJ ON (AI.PROJECT_ID = PJ.PROJECT_ID) '.
                    'LEFT OUTER JOIN MASTER_REF UN2 ON (UN2.MASTER_ID = IT.ITEM_UOM) ',                    

                'FROM ACCOUNT_DOC_ITEM ',

                'FROM ACCOUNT_DOC_ITEM AI '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AD.ACCOUNT_DOC_ID = AI.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN ENTITY EN ON (AD.ENTITY_ID = EN.ENTITY_ID) '.
                    'LEFT OUTER JOIN INVENTORY_TX IVT ON (AI.INVENTORY_TX_ID = IVT.TX_ID) '.                    
                    'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = AI.ITEM_ID) '.
                    'LEFT OUTER JOIN MASTER_REF UN2 ON (UN2.MASTER_ID = IT.ITEM_UOM) '.                    
                    'LEFT OUTER JOIN MASTER_REF BR ON (AD.BRANCH_ID = BR.MASTER_ID) '.
                    'LEFT OUTER JOIN PROJECT PJ ON (AI.PROJECT_ID = PJ.PROJECT_ID) '.
                    'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = AI.SERVICE_ID) '.
                    'LEFT OUTER JOIN MASTER_REF UN1 ON (UN1.MASTER_ID = SV.SERVICE_UOM) ',

                'FROM ACCOUNT_DOC_ITEM AI '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AD.ACCOUNT_DOC_ID = AI.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN ENTITY EN ON (AD.ENTITY_ID = EN.ENTITY_ID) '.
                    'LEFT OUTER JOIN INVENTORY_TX IVT ON (AI.INVENTORY_TX_ID = IVT.TX_ID) '.                    
                    'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = AI.ITEM_ID) '.
                    'LEFT OUTER JOIN MASTER_REF BR ON (AD.BRANCH_ID = BR.MASTER_ID) '.
                    'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = AI.SERVICE_ID) ',    
                    
                'FROM ACCOUNT_DOC_ITEM AI '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AD.ACCOUNT_DOC_ID = AI.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD2 ON (AD2.ACCOUNT_DOC_ID = AI.REF_DOC_ID) '.
                    'LEFT OUTER JOIN ENTITY EN ON (AD.ENTITY_ID = EN.ENTITY_ID) '.
                    'LEFT OUTER JOIN ENTITY_ADDRESS EA ON (AD.ENTITY_ADDRESS_ID = EA.ENTITY_ADDRESS_ID) '.
                    'LEFT OUTER JOIN INVENTORY_TX IVT ON (AI.INVENTORY_TX_ID = IVT.TX_ID) '.                    
                    'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = AI.ITEM_ID) '.
                    'LEFT OUTER JOIN MASTER_REF UN2 ON (UN2.MASTER_ID = IT.ITEM_UOM) '.                    
                    'LEFT OUTER JOIN MASTER_REF BR ON (AD.BRANCH_ID = BR.MASTER_ID) '.
                    'LEFT OUTER JOIN PROJECT PJ ON (AI.PROJECT_ID = PJ.PROJECT_ID) '.
                    'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = AI.SERVICE_ID) '.
                    'LEFT OUTER JOIN MASTER_REF UN1 ON (UN1.MASTER_ID = SV.SERVICE_UOM) ',                  

                'FROM ACCOUNT_DOC_ITEM AI '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AI.ACCOUNT_DOC_ID = AD.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD2 ON (AD.RECEIPT_ID = AD2.ACCOUNT_DOC_ID) '.
                    
                    'LEFT OUTER JOIN AUXILARY_DOC PO ON (AI.PO_ID = PO.AUXILARY_DOC_ID) '.
                    'LEFT OUTER JOIN PROJECT POPJ ON (PO.PROJECT_ID = POPJ.PROJECT_ID) '.
                    'LEFT OUTER JOIN MASTER_REF POPG ON (POPJ.PROJECT_GROUP = POPG.MASTER_ID) '.

                    'LEFT OUTER JOIN ENTITY EN ON (AD.ENTITY_ID = EN.ENTITY_ID) '.
                    'LEFT OUTER JOIN MASTER_REF BR ON (AD.BRANCH_ID = BR.MASTER_ID) '.
                    'LEFT OUTER JOIN PROJECT PJ ON (AI.PROJECT_ID = PJ.PROJECT_ID) '.
                    'LEFT OUTER JOIN MASTER_REF PG ON (PJ.PROJECT_GROUP = PG.MASTER_ID) '.
                    'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = AI.ITEM_ID) '.                    
                    'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = AI.SERVICE_ID) ',

                'FROM ACCOUNT_DOC_ITEM ',
                'FROM ACCOUNT_DOC_ITEM ',

                'FROM ACCOUNT_DOC_ITEM AI '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AI.ACCOUNT_DOC_ID = AD.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN PROJECT PJ ON (AI.PROJECT_ID = PJ.PROJECT_ID) ',


                'FROM ACCOUNT_DOC_ITEM AI '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AI.ACCOUNT_DOC_ID = AD.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN PROJECT PJ ON (AI.PROJECT_ID = PJ.PROJECT_ID) '.
                    'LEFT OUTER JOIN MASTER_REF PG ON (PJ.PROJECT_GROUP = PG.MASTER_ID) ',     
                    
                'FROM ACCOUNT_DOC_ITEM AI '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AI.ACCOUNT_DOC_ID = AD.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD2 ON (AD.RECEIPT_ID = AD2.ACCOUNT_DOC_ID) '.
                    
                    'LEFT OUTER JOIN AUXILARY_DOC PO ON (AI.PO_ID = PO.AUXILARY_DOC_ID) '.
                    'LEFT OUTER JOIN PROJECT POPJ ON (PO.PROJECT_ID = POPJ.PROJECT_ID) '.
                    'LEFT OUTER JOIN MASTER_REF POPG ON (POPJ.PROJECT_GROUP = POPG.MASTER_ID) '.

                    'LEFT OUTER JOIN ENTITY EN ON (AD.ENTITY_ID = EN.ENTITY_ID) '.
                    'LEFT OUTER JOIN MASTER_REF BR ON (AD.BRANCH_ID = BR.MASTER_ID) '.
                    'LEFT OUTER JOIN PROJECT PJ ON (AI.PROJECT_ID = PJ.PROJECT_ID) '.
                    'LEFT OUTER JOIN MASTER_REF PG ON (PJ.PROJECT_GROUP = PG.MASTER_ID) '.
                    'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = AI.ITEM_ID) '.                    
                    'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = AI.SERVICE_ID) ',                    
    );

    private $orderby = array(

                'ORDER BY ACCOUNT_DOC_ITEM_ID ASC ',
                
                'ORDER BY AI.ACCOUNT_DOC_ITEM_ID ASC ',
                
                'ORDER BY ACCOUNT_DOC_ITEM_ID ASC ',
                
                'ORDER BY ACCOUNT_DOC_ITEM_ID ASC ',

                'GROUP BY AD.DOCUMENT_DATE, IT.ITEM_ID, SV.SERVICE_ID, AI.SELECTION_TYPE '. 
                'ORDER BY AD.DOCUMENT_DATE ASC, IT.ITEM_CODE ASC, SV.SERVICE_CODE ASC ',  
                
                'ORDER BY ACCOUNT_DOC_ITEM_ID ASC ',
                
                'ORDER BY ACCOUNT_DOC_ITEM_ID ASC ',

                'ORDER BY ACCOUNT_DOC_ITEM_ID ASC ',

                'ORDER BY ACCOUNT_DOC_ITEM_ID ASC ',

                'GROUP BY AI.PROJECT_ID, PJ.PROJECT_CODE, PJ.PROJECT_NAME, AD.DOCUMENT_TYPE, AD.DOCUMENT_YYYYMM '. 
                'ORDER BY PJ.PROJECT_CODE ASC ',  
                    
                'GROUP BY PJ.PROJECT_GROUP, PG.CODE, PG.DESCRIPTION, AD.DOCUMENT_TYPE, AD.DOCUMENT_YYYYMM '. 
                'ORDER BY PG.CODE ASC ',
                    
                'GROUP BY PG.MASTER_ID, AD.ACCOUNT_DOC_ID, EN.ENTITY_ID, PJ.PROJECT_ID, AD2.ACCOUNT_DOC_ID '. 
                'ORDER BY PG.CODE ASC, AD.DOCUMENT_DATE ASC, AD.DOCUMENT_NO ASC ',   
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ACCOUNT_DOC_ITEM', 'ACCOUNT_DOC_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>