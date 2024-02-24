<?php
/* 
Purpose : Model for ACCOUNT_DOC_ITEM
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MAuxilaryDocItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'AUXILARY_DOC_ITEM_ID:SPK:AUXILARY_DOC_ITEM_ID:Y',
                    'AUXILARY_DOC_ID:REFID:AUXILARY_DOC_ID:Y',
                    'SELECTION_TYPE:NZ:SELECTION_TYPE:Y',
                    'SERVICE_ID:REFID:SERVICE_ID:Y',
                    'ITEM_ID:REFID:ITEM_ID:Y',
                    'QUANTITY:NZ:QUANTITY:N',
                    'UNIT_PRICE:NZ:UNIT_PRICE:N',
                    'AMOUNT:NZ:AMOUNT:N',
                    'DISCOUNT_PCT:NZ:DISCOUNT_PCT:N',
                    'DISCOUNT_AMT:NZ:DISCOUNT_AMT:N',
                    'TOTAL_AMT:NZ:TOTAL_AMT:N',
                    'TOTAL_AFTER_DISCOUNT:NZ:TOTAL_AFTER_DISCOUNT:N',
                    'ITEM_NOTE:S:ITEM_NOTE:N',
                    'WH_TAX_FLAG:S:WH_TAX_FLAG:N',
                    'WH_TAX_PCT:NZ:WH_TAX_PCT:N',
                    'WH_TAX_AMT:NZ:WH_TAX_AMT:N',
                    'VAT_TAX_FLAG:S:VAT_TAX_FLAG:N',
                    'VAT_TAX_PCT:NZ:VAT_TAX_PCT:N',
                    'VAT_TAX_AMT:NZ:VAT_TAX_AMT:N',
                    'PRIMARY_REVENUE_EXPENSE_AMT:NZ:PRIMARY_REVENUE_EXPENSE_AMT:N',
                    'PRIMARY_ITEM_DISCOUNT_AMT:NZ:PRIMARY_ITEM_DISCOUNT_AMT:N',
                    'PRIMARY_FINAL_DISCOUNT_AVG_AMT:NZ:PRIMARY_FINAL_DISCOUNT_AVG_AMT:N',
                    'REVENUE_EXPENSE_AMT:NZ:REVENUE_EXPENSE_AMT:N',
                    'AR_AP_AMT:NZ:AR_AP_AMT:N',                                        
                    'FINAL_DISCOUNT_AVG:NZ:FINAL_DISCOUNT_AVG:N',
                    'FREE_TEXT:S:FREE_TEXT:N',
                    'REF_BY_ID:REFID:REF_BY_ID:N',
                    'ITEM_DETAIL:S:ITEM_DETAIL:N',
                    'DISCOUNT_PCT_FLAG:S:DISCOUNT_PCT_FLAG:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get Account Aux Item List
                    'AI.AUXILARY_DOC_ITEM_ID:SPK:AUXILARY_DOC_ITEM_ID:Y',
                    'AI.AUXILARY_DOC_ID:REFID:AUXILARY_DOC_ID:Y',
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
                    'AI.FINAL_DISCOUNT_AVG:NZ:FINAL_DISCOUNT_AVG:N',
                    'AI.PRIMARY_REVENUE_EXPENSE_AMT:NZ:PRIMARY_REVENUE_EXPENSE_AMT:N',
                    'AI.PRIMARY_ITEM_DISCOUNT_AMT:NZ:PRIMARY_ITEM_DISCOUNT_AMT:N',
                    'AI.PRIMARY_FINAL_DISCOUNT_AVG_AMT:NZ:PRIMARY_FINAL_DISCOUNT_AVG_AMT:N',
                    'AI.TOTAL_AFTER_DISCOUNT:NZ:TOTAL_AFTER_DISCOUNT:N',
                    'AI.ITEM_NOTE:S:ITEM_NOTE:N',
                    'AI.DISCOUNT_PCT:NZ:DISCOUNT_PCT:N',
                    'AI.FREE_TEXT:S:FREE_TEXT:N',
                    'AI.REF_BY_ID:REFID:REF_BY_ID:N',
                    'AI.ITEM_DETAIL:S:ITEM_DETAIL:N',
                    'AI.DISCOUNT_PCT_FLAG:S:DISCOUNT_PCT_FLAG:N',

                    'IT.ITEM_CODE:S:ITEM_CODE:Y', 
                    'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:N', 
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',
                    'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
                    'IT.ITEM_CATEGORY:S:ITEM_CATEGORY:Y', 
                    'IT.VAT_FLAG:S:VAT_FLAG:Y', 

                    'SV.SERVICE_CODE:S:SERVICE_CODE:N', 
                    'SV.SERVICE_NAME:S:SERVICE_NAME:N', 
                    'SV.PRICING_DEFINITION:S:SERVICE_PRICING_DEFINITION:N',
                    'SV.WH_GROUP:REFID:WH_GROUP:N', 

                    'ADR.DOCUMENT_NO:S:REF_BY_DOC_NO:N', 

                    'UN1.DESCRIPTION:S:SERVICE_UNIT_NAME:N',
                    'UN1.DESCRIPTION_ENG:S:SERVICE_UNIT_NAME_ENG:N',
                    'UN2.DESCRIPTION:S:ITEM_UNIT_NAME:N',
                    'UN2.DESCRIPTION_ENG:S:ITEM_UNIT_NAME_ENG:N',                    
                  ],

                  [ # 2 For Delete by parent
                      'AUXILARY_DOC_ID:SPK:AUXILARY_DOC_ID:Y',
                  ],

                  [ # 3
                    'AI.AUXILARY_DOC_ITEM_ID:SPK:AUXILARY_DOC_ITEM_ID:Y',
                    'AI.AUXILARY_DOC_ID:REFID:AUXILARY_DOC_ID:Y',
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
                    'AI.FINAL_DISCOUNT_AVG:NZ:FINAL_DISCOUNT_AVG:N',
                    'AI.PRIMARY_REVENUE_EXPENSE_AMT:NZ:PRIMARY_REVENUE_EXPENSE_AMT:N',
                    'AI.PRIMARY_ITEM_DISCOUNT_AMT:NZ:PRIMARY_ITEM_DISCOUNT_AMT:N',
                    'AI.PRIMARY_FINAL_DISCOUNT_AVG_AMT:NZ:PRIMARY_FINAL_DISCOUNT_AVG_AMT:N',
                    'AI.TOTAL_AFTER_DISCOUNT:NZ:TOTAL_AFTER_DISCOUNT:N',
                    'AI.ITEM_NOTE:S:ITEM_NOTE:N',
                    'AI.DISCOUNT_PCT:NZ:DISCOUNT_PCT:N',
                    'AI.FREE_TEXT:S:FREE_TEXT:N',
                    'AI.REF_BY_ID:REFID:REF_BY_ID:N',
                    'AI.DISCOUNT_PCT_FLAG:S:DISCOUNT_PCT_FLAG:N',
                    
                    'AD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'AD.DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                    'AD.PO_INVOICE_REF_TYPE:REFID:PO_INVOICE_REF_TYPE:Y',
                    
                    'PJ.PROJECT_ID:REFID:PROJECT_ID:Y',
                    'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
                    'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',
                    'PJ.PROJECT_DESC:S:PROJECT_DESC:Y',
                    'PG.DESCRIPTION:S:PROJECT_GROUP_NAME:N',

                    'IT.ITEM_CODE:S:ITEM_CODE:Y', 
                    'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:N', 
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',
                    'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
                    'IT.ITEM_CATEGORY:S:ITEM_CATEGORY:Y', 
                    'IT.VAT_FLAG:S:VAT_FLAG:Y', 

                    'EN.ENTITY_ID:REFID:ENTITY_ID:Y',
                    'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
                    'EN.ENTITY_NAME:S:ENTITY_NAME:Y',

                    'SV.SERVICE_CODE:S:SERVICE_CODE:N', 
                    'SV.SERVICE_NAME:S:SERVICE_NAME:N', 
                    'SV.PRICING_DEFINITION:S:SERVICE_PRICING_DEFINITION:N',                
                    'SV.WH_GROUP:REFID:WH_GROUP:N', 

                    # Always put these at the end
                    'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',        
                    'AI.REF_BY_ID:IS_NULL:INCLUDE_ABLE_FLAG:Y',            
                  ],
                  
                  [ # 4 For updating REF_BY_ID
                    'AUXILARY_DOC_ITEM_ID:SPK:AUXILARY_DOC_ITEM_ID:Y',
                    'REF_BY_ID:REFID:REF_BY_ID:N',        
                  ],                          
    );

    private $froms = array(

                'FROM AUXILARY_DOC_ITEM ',

                'FROM AUXILARY_DOC_ITEM AI '.
                    'LEFT OUTER JOIN AUXILARY_DOC AD ON (AD.AUXILARY_DOC_ID = AI.AUXILARY_DOC_ID) '.
                    'LEFT OUTER JOIN ACCOUNT_DOC ADR ON (AI.REF_BY_ID = ADR.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = AI.ITEM_ID) '.
                    'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = AI.SERVICE_ID) '.
                    'LEFT OUTER JOIN MASTER_REF UN1 ON (UN1.MASTER_ID = SV.SERVICE_UOM) '.
                    'LEFT OUTER JOIN MASTER_REF UN2 ON (UN2.MASTER_ID = IT.ITEM_UOM) ',

                'FROM AUXILARY_DOC_ITEM ',

                'FROM AUXILARY_DOC_ITEM AI '.
                    'LEFT OUTER JOIN AUXILARY_DOC AD ON (AD.AUXILARY_DOC_ID = AI.AUXILARY_DOC_ID) ' .
                    'LEFT OUTER JOIN PROJECT PJ ON (AD.PROJECT_ID = PJ.PROJECT_ID) '.      
                    'LEFT OUTER JOIN MASTER_REF PG ON (PG.MASTER_ID = PJ.PROJECT_GROUP) '.
                    'LEFT OUTER JOIN ENTITY EN ON (EN.ENTITY_ID = AD.ENTITY_ID) '.
                    'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = AI.ITEM_ID) '.
                    'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = AI.SERVICE_ID) ',   
                    
                'FROM AUXILARY_DOC_ITEM ',
    );

    private $orderby = array(

                'ORDER BY AUXILARY_DOC_ITEM_ID ASC ',
                
                'ORDER BY AI.AUXILARY_DOC_ITEM_ID ASC ',
                
                'ORDER BY AUXILARY_DOC_ITEM_ID ASC ',  
                
                'ORDER BY AUXILARY_DOC_ID ASC, AUXILARY_DOC_ITEM_ID ASC ',  

                'ORDER BY AUXILARY_DOC_ITEM_ID ASC ',  
    );

    function __construct($db) 
    {
        parent::__construct($db, 'AUXILARY_DOC_ITEM', 'AUXILARY_DOC_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>