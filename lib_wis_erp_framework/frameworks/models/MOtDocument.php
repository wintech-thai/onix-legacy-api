<?php
/* 
Purpose : Model for OT_DOCUMENT
Created By : Seubpong Monsar
Created Date : 03/17/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MOtDocument extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'OT_DOC_ID:SPK:OT_DOC_ID:Y',
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'NOTE:S:NOTE:N',
                    'RECEIVE_AMOUNT:NZ:RECEIVE_AMOUNT:Y',
                    'ITEM_COUNT:NZ:ITEM_COUNT:Y',
                    'OT_RATE:NZ:OT_RATE:N',
                    'EMPLOYEE_TYPE:REFID:EMPLOYEE_TYPE:Y',
                    'DEDUCTION_MINUTE_TOTAL:NZ:DEDUCTION_MINUTE_TOTAL:N',                    
                    'DEDUCTION_AMOUNT:NZ:DEDUCTION_AMOUNT:N',                   
                    'DEDUCTION_HOUR_ROUNDED_TOTAL:NZ:DEDUCTION_HOUR_ROUNDED_TOTAL:N',
                    'WORKED_AMOUNT_TOTAL:NZ:WORKED_AMOUNT_TOTAL:N',
                    'ADJUST_AMOUNT:NZ:ADJUST_AMOUNT:N', 
                    'OT_ADJUST_AMOUNT:NZ:OT_ADJUST_AMOUNT:N', 

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get List
                    'OD.OT_DOC_ID:SPK:OT_DOC_ID:Y',
                    'OD.DOCUMENT_DATE:S:DOCUMENT_DATE:Y',
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'OD.DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'OD.NOTE:S:NOTE:N',
                    'OD.RECEIVE_AMOUNT:NZ:RECEIVE_AMOUNT:Y',
                    'OD.ITEM_COUNT:NZ:ITEM_COUNT:Y',
                    'OD.OT_RATE:NZ:OT_RATE:N',
                    'OD.EMPLOYEE_TYPE:REFID:EMPLOYEE_TYPE:Y',
                    'OD.DEDUCTION_MINUTE_TOTAL:NZ:DEDUCTION_MINUTE_TOTAL:N',
                    'OD.DEDUCTION_AMOUNT:NZ:DEDUCTION_AMOUNT:N',
                    'OD.DEDUCTION_HOUR_ROUNDED_TOTAL:NZ:DEDUCTION_HOUR_ROUNDED_TOTAL:N',
                    'OD.WORKED_AMOUNT_TOTAL:NZ:WORKED_AMOUNT_TOTAL:N',
                    'OD.ADJUST_AMOUNT:NZ:ADJUST_AMOUNT:N', 
                    'OD.OT_ADJUST_AMOUNT:NZ:OT_ADJUST_AMOUNT:N', 

                    'EM.EMPLOYEE_CODE:S:EMPLOYEE_CODE:Y',
                    'EM.EMPLOYEE_NAME:S:EMPLOYEE_NAME:Y',
                    'EM.EMPLOYEE_LASTNAME:S:EMPLOYEE_LASTNAME:N',
                    'EM.EMPLOYEE_NAME_ENG:S:EMPLOYEE_NAME_ENG:N', 

                    'OD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'OD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',                    
                  ],

                  [ # 2 For update document type and status
                    'OT_DOC_ID:SPK:OT_DOC_ID:Y',
                    'DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:N',        
                  ], 
                  
                  [ # 3 For Get EMPLOYEE_DEDUCTION
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'OD.DEDUCTION_AMOUNT:NZ:AMOUNT:N',  
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',   
                    
                    'OD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'OD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',                      
                  ],                  
    );

    private $froms = array(

                'FROM OT_DOCUMENT ',

                'FROM OT_DOCUMENT OD ' .
                  'LEFT OUTER JOIN EMPLOYEE EM ON (OD.EMPLOYEE_ID = EM.EMPLOYEE_ID) ' ,

                'FROM OT_DOCUMENT ' ,

                'FROM OT_DOCUMENT OD ' ,
    );

    private $orderby = array(

                'ORDER BY OT_DOC_ID DESC ',
                
                'ORDER BY OD.DOCUMENT_DATE DESC, EM.EMPLOYEE_CODE ASC ',   
                
                'ORDER BY OT_DOC_ID DESC ', 
                
                'ORDER BY OD.OT_DOC_ID DESC ', 
    );

    function __construct($db) 
    {
        parent::__construct($db, 'OT_DOCUMENT', 'OT_DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>