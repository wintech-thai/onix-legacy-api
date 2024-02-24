<?php
/* 
Purpose : Model for PROJECT
Created By : Seubpong Monsar
Created Date : 01/12/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MTaxDocument extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'TAX_DOC_ID:SPK:TAX_DOC_ID:Y',
                    'TAX_YEAR:S:TAX_YEAR:Y',
                    'TAX_MONTH:S:TAX_MONTH:Y',
                    'NOTE:S:NOTE:N',
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'DOCUMENT_TYPE:REFID:DOCUMENT_TYPE:Y',
                    'DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'CHEQUE_ID:REFID:CHEQUE_ID:Y',
                    'ITEM_COUNT:NZ:ITEM_COUNT:Y',
                    'WH_AMOUNT:NZ:WH_AMOUNT:Y',
                    'EXPENSE_REVENUE_AMT:NZ:EXPENSE_REVENUE_AMT:Y',
                    'PREVIOUS_RUN_YEAR:NZ:PREVIOUS_RUN_YEAR:Y',
                    'PREVIOUS_RUN_MONTH:NZ:PREVIOUS_RUN_MONTH:Y',
                    'IS_TAX_DEDUCTABLE:S:IS_TAX_DEDUCTABLE:N',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get List
                    'TD.TAX_DOC_ID:SPK:TAX_DOC_ID:Y',
                    'TD.TAX_YEAR:S:TAX_YEAR:Y',
                    'TD.TAX_MONTH:S:TAX_MONTH:Y',
                    'TD.NOTE:S:NOTE:N',
                    'TD.DOCUMENT_DATE:S:DOCUMENT_DATE:Y',
                    'TD.DOCUMENT_TYPE:REFID:DOCUMENT_TYPE:Y',
                    'TD.DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'TD.CHEQUE_ID:REFID:CHEQUE_ID:Y',
                    'TD.ITEM_COUNT:NZ:ITEM_COUNT:Y',
                    'TD.WH_AMOUNT:NZ:WH_AMOUNT:Y',
                    'TD.EXPENSE_REVENUE_AMT:NZ:EXPENSE_REVENUE_AMT:Y',
                    'TD.PREVIOUS_RUN_YEAR:NZ:PREVIOUS_RUN_YEAR:Y',
                    'TD.PREVIOUS_RUN_MONTH:NZ:PREVIOUS_RUN_MONTH:Y',
                    'TD.IS_TAX_DEDUCTABLE:S:IS_TAX_DEDUCTABLE:N',

                    'TD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y',

                    'TD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'TD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',                    
                  ],

                  [ # 2 For update document type and status
                    'TAX_DOC_ID:SPK:TAX_DOC_ID:Y',
                    'DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:N',
                    'DOCUMENT_TYPE:NZ:DOCUMENT_TYPE:N',            
                  ],                    
    );

    private $froms = array(

                'FROM TAX_DOCUMENT ',

                'FROM TAX_DOCUMENT TD ' ,

                'FROM TAX_DOCUMENT ' ,
    );

    private $orderby = array(

                'ORDER BY TAX_DOC_ID DESC ',
                
                'ORDER BY TD.TAX_DOC_ID DESC ',   
                
                'ORDER BY TAX_DOC_ID DESC ',   
    );

    function __construct($db) 
    {
        parent::__construct($db, 'TAX_DOCUMENT', 'TAX_DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>