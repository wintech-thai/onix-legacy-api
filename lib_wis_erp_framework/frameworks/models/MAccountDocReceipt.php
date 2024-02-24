<?php
/* 
Purpose : Model for ACCOUNT_DOC_PAYMENT
Created By : Seubpong Monsar
Created Date : 11/26/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MAccountDocReceipt extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'ACT_DOC_RECEIPT_ID:SPK:ACT_DOC_RECEIPT_ID:Y',
                    'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'DOCUMENT_ID:REFID:DOCUMENT_ID:Y',
                    'DOCUMENT_TYPE:NZ:DOCUMENT_TYPE:Y',
                    'DOCUMENT_NO:S:DOCUMENT_NO:N',
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'DUE_DATE:S:DUE_DATE:N',
                    'AR_AP_AMT:NZ:AR_AP_AMT:N',
                    'WH_TAX_AMT:NZ:WH_TAX_AMT:N',
                    'DAY_OVERDUE:NZ:DAY_OVERDUE:N',
                    'DOCUMENT_NOTE:S:DOCUMENT_NOTE:N',
                    'REVENUE_EXPENSE_AMT:NZ:REVENUE_EXPENSE_AMT:N',
                    'REVENUE_EXPENSE_FOR_WH_AMT:NZ:REVENUE_EXPENSE_FOR_WH_AMT:N',
                    'REVENUE_EXPENSE_FOR_VAT_AMT:NZ:REVENUE_EXPENSE_FOR_VAT_AMT:N',                    
                    'VAT_AMT:NZ:VAT_AMT:N',
                    'CASH_RECEIPT_AMT:NZ:CASH_RECEIPT_AMT:N',
                    'WH_DEFINITION:S:WH_DEFINITION:N',
                    'PROJECT_ID:REFID:PROJECT_ID:Y',
                    'REF_PO_NO:S:REF_PO_NO:N',
                    'FINAL_DISCOUNT:NZ:FINAL_DISCOUNT:N',
                    'PRICING_AMT:NZ:PRICING_AMT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get Account Doc Payment List
                    'AP.ACT_DOC_RECEIPT_ID:SPK:ACT_DOC_RECEIPT_ID:Y',
                    'AP.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'AP.DOCUMENT_ID:REFID:DOCUMENT_ID:Y',
                    'AP.DOCUMENT_TYPE:NZ:DOCUMENT_TYPE:Y',
                    'AP.DOCUMENT_NO:S:DOCUMENT_NO:N',
                    'AP.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'AP.DUE_DATE:S:DUE_DATE:N',
                    'AP.AR_AP_AMT:NZ:AR_AP_AMT:N',
                    'AP.WH_TAX_AMT:NZ:WH_TAX_AMT:N',
                    'AP.DAY_OVERDUE:NZ:DAY_OVERDUE:N',
                    'AP.DOCUMENT_NOTE:S:DOCUMENT_NOTE:N',
                    'AP.REVENUE_EXPENSE_AMT:NZ:REVENUE_EXPENSE_AMT:N',
                    'AP.REVENUE_EXPENSE_FOR_WH_AMT:NZ:REVENUE_EXPENSE_FOR_WH_AMT:N',
                    'AP.REVENUE_EXPENSE_FOR_VAT_AMT:NZ:REVENUE_EXPENSE_FOR_VAT_AMT:N',                    
                    'AP.VAT_AMT:NZ:VAT_AMT:N',
                    'AP.CASH_RECEIPT_AMT:NZ:CASH_RECEIPT_AMT:N',
                    'AP.WH_DEFINITION:S:WH_DEFINITION:N',
                    'AP.PROJECT_ID:REFID:PROJECT_ID:Y',
                    'AP.REF_PO_NO:S:REF_PO_NO:N',
                    'AP.FINAL_DISCOUNT:NZ:FINAL_DISCOUNT:N',
                    'AP.PRICING_AMT:NZ:PRICING_AMT:N',

                    'EN.ENTITY_ID:REFID:ENTITY_ID:N',

                    'AD.INDEX_PROJECT:S:INDEX_PROJECT:N',
                    'IV.NOTE:S:NOTE:N',

                    'PJ.PROJECT_CODE:S:PROJECT_CODE:N',
                    'PJ.PROJECT_NAME:S:PROJECT_NAME:N',
                  ],

                  [ # 2 For Delete by parent
                    'ACCOUNT_DOC_ID:SPK:ACCOUNT_DOC_ID:Y',
                  ], 
    );

    private $froms = array(

                'FROM ACCOUNT_DOC_RECEIPT ',

                'FROM ACCOUNT_DOC_RECEIPT AP '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AD.ACCOUNT_DOC_ID = AP.DOCUMENT_ID) '.
                    'LEFT OUTER JOIN ENTITY EN ON (AD.ENTITY_ID = EN.ENTITY_ID) '.
                    'LEFT OUTER JOIN ACCOUNT_DOC IV ON (AP.DOCUMENT_ID = IV.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN PROJECT PJ ON (AP.PROJECT_ID = PJ.PROJECT_ID) ',

                'FROM ACCOUNT_DOC_RECEIPT ',                               
    );

    private $orderby = array(

                'ORDER BY ACT_DOC_RECEIPT_ID ASC ',
                
                'ORDER BY AP.DOCUMENT_DATE ASC, AP.DOCUMENT_NO ASC ',
                
                'ORDER BY ACT_DOC_RECEIPT_ID ASC ',         
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ACCOUNT_DOC_RECEIPT', 'ACT_DOC_RECEIPT_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>