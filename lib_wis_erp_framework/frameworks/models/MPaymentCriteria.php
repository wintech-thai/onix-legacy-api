<?php
/* 
Purpose : Model for PAYMENT_CRITERIA
Created By : Seubpong Monsar
Created Date : 01/14/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPaymentCriteria extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'PAYMENT_CRITERIA_ID:SPK:PAYMENT_CRITERIA_ID:Y',
                    'AUXILARY_DOC_ID:REFID:AUXILARY_DOC_ID:Y',
                    'DESCRIPTION:S:DESCRIPTION:Y',
                    'PERCENT:NZ:PERCENT:Y',
                    'PAYMENT_AMT:NZ:PAYMENT_AMT:N',
                    'VAT_AMT:NZ:VAT_AMT:N',
                    'WH_TAX_AMT:NZ:WH_TAX_AMT:N',
                    'VAT_INCLUDE_AMT:NZ:VAT_INCLUDE_AMT:N',
                    'REMAIN_AMT:NZ:REMAIN_AMT:N',
                    'WH_PERCENT:NZ:WH_PERCENT:N',
                    'VAT_PERCENT:NZ:VAT_PERCENT:N',
                    'MANUAL_CALCULATE_FLAG:S:MANUAL_CALCULATE_FLAG:Y',
                    'WH_GROUP:REFID:WH_GROUP:Y', 
                    'REF_BY_ID:REFID:REF_BY_ID:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get List
                    'PC.PAYMENT_CRITERIA_ID:SPK:PAYMENT_CRITERIA_ID:Y',
                    'PC.AUXILARY_DOC_ID:REFID:AUXILARY_DOC_ID:Y',
                    'PC.DESCRIPTION:S:DESCRIPTION:Y',
                    'PC.PERCENT:NZ:PERCENT:Y',
                    'PC.PAYMENT_AMT:NZ:PAYMENT_AMT:N',
                    'PC.VAT_AMT:NZ:VAT_AMT:N',
                    'PC.WH_TAX_AMT:NZ:WH_TAX_AMT:N', 
                    'PC.VAT_INCLUDE_AMT:NZ:VAT_INCLUDE_AMT:N',
                    'PC.REMAIN_AMT:NZ:REMAIN_AMT:N',
                    'PC.WH_PERCENT:NZ:WH_PERCENT:N',
                    'PC.VAT_PERCENT:NZ:VAT_PERCENT:N',
                    'PC.MANUAL_CALCULATE_FLAG:S:MANUAL_CALCULATE_FLAG:Y',
                    'PC.WH_GROUP:REFID:WH_GROUP:Y',
                    'PC.REF_BY_ID:REFID:REF_BY_ID:N',

                    'ADR.DOCUMENT_NO:S:REF_BY_DOC_NO:N', 
                  ],

                  [ # 2 For Delete by parent
                      'AUXILARY_DOC_ID:SPK:AUXILARY_DOC_ID:Y',
                  ],
                  
                  [ # 3
                    'PC.PAYMENT_CRITERIA_ID:SPK:PAYMENT_CRITERIA_ID:Y',
                    'PC.AUXILARY_DOC_ID:REFID:AUXILARY_DOC_ID:Y',
                    'PC.DESCRIPTION:S:DESCRIPTION:Y',
                    'PC.PERCENT:NZ:PERCENT:Y',
                    'PC.PAYMENT_AMT:NZ:PAYMENT_AMT:N',
                    'PC.VAT_AMT:NZ:VAT_AMT:N',
                    'PC.WH_TAX_AMT:NZ:WH_TAX_AMT:N', 
                    'PC.VAT_INCLUDE_AMT:NZ:VAT_INCLUDE_AMT:N',
                    'PC.REMAIN_AMT:NZ:REMAIN_AMT:N',
                    'PC.WH_PERCENT:NZ:WH_PERCENT:N',
                    'PC.VAT_PERCENT:NZ:VAT_PERCENT:N',
                    'PC.MANUAL_CALCULATE_FLAG:S:MANUAL_CALCULATE_FLAG:Y', 
                    'PC.WH_GROUP:REFID:WH_GROUP:Y',                                                    
                    'PC.REF_BY_ID:REFID:REF_BY_ID:N',

                    'AD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'AD.DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                    'AD.PO_INVOICE_REF_TYPE:REFID:PO_INVOICE_REF_TYPE:Y',
                    
                    'MR1.DESCRIPTION:S:WH_GROUP_NAME:N',
                    'MR1.DESCRIPTION_ENG:S:WH_GROUP_NAME_ENG:N',

                    'PJ.PROJECT_ID:REFID:PROJECT_ID:Y',
                    'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
                    'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',
                    'PJ.PROJECT_DESC:S:PROJECT_DESC:Y',
                    'PG.DESCRIPTION:S:PROJECT_GROUP_NAME:N',

                    'EN.ENTITY_ID:REFID:ENTITY_ID:Y',
                    'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
                    'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
                    
                    # Always put these at the end
                    'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                    'PC.REF_BY_ID:IS_NULL:INCLUDE_ABLE_FLAG:Y',
                  ],
                  
                  [ # 4 For updating REF_BY_ID
                    'PAYMENT_CRITERIA_ID:SPK:PAYMENT_CRITERIA_ID:Y',
                    'REF_BY_ID:REFID:REF_BY_ID:N',        
                  ],                   
    );

    private $froms = array(

                'FROM PAYMENT_CRITERIA ',

                'FROM PAYMENT_CRITERIA PC '.
                    'LEFT OUTER JOIN ACCOUNT_DOC ADR ON (PC.REF_BY_ID = ADR.ACCOUNT_DOC_ID) ',

                'FROM PAYMENT_CRITERIA ',

                'FROM PAYMENT_CRITERIA PC '.
                    'LEFT OUTER JOIN AUXILARY_DOC AD ON (AD.AUXILARY_DOC_ID = PC.AUXILARY_DOC_ID) ' .
                    'LEFT OUTER JOIN PROJECT PJ ON (AD.PROJECT_ID = PJ.PROJECT_ID) '.      
                    'LEFT OUTER JOIN MASTER_REF PG ON (PG.MASTER_ID = PJ.PROJECT_GROUP) '.    
                    'LEFT OUTER JOIN MASTER_REF MR1 ON (PC.WH_GROUP = MR1.MASTER_ID) ' .           
                    'LEFT OUTER JOIN ENTITY EN ON (EN.ENTITY_ID = AD.ENTITY_ID) ', 

                'FROM PAYMENT_CRITERIA ',
    );

    private $orderby = array(

                'ORDER BY PAYMENT_CRITERIA_ID ASC ',
                
                'ORDER BY PC.PAYMENT_CRITERIA_ID ASC ',  
                
                'ORDER BY PAYMENT_CRITERIA_ID ASC ',

                'ORDER BY AUXILARY_DOC_ID ASC, PAYMENT_CRITERIA_ID ASC ', 
                 
                'ORDER BY PAYMENT_CRITERIA_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PAYMENT_CRITERIA', 'PAYMENT_CRITERIA_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>