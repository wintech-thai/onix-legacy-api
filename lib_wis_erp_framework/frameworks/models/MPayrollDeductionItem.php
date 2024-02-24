<?php
/* 
Purpose : Model for PAYROLL_DEDUCTION_ITEM
Created By : Seubpong Monsar
Created Date : 05/17/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPayrollDeductionItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'PAYROLL_DEDUCTION_ITEM_ID:SPK:PAYROLL_DEDUCTION_ITEM_ID:Y',
                    'OT_DOC_ID:REFID:OT_DOC_ID:Y',
                    'DEDUCTION_NOTE:S:DEDUCTION_NOTE:N',
                    'DEDUCTION_AMOUNT:NZ:DEDUCTION_AMOUNT:N',
                    'DEDUCTION_TYPE:REFID:DEDUCTION_TYPE:Y',                  
                    'DEDUCTION_DATE:S:DEDUCTION_DATE:N',
                    'DEDUCTION_QUANTITY:NZ:DEDUCTION_QUANTITY:N',
                    'DEDUCTION_PRICE:NZ:DEDUCTION_PRICE:N',
                    'DURATION:NZ:DURATION:N',
                    'DURATION_MINUTE:NZ:DURATION_MINUTE:N',
                    'DURATION_UNIT:S:DURATION_UNIT:N',
                    'DURATION_HINT:S:DURATION_HINT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For delete by parent
                    'OT_DOC_ID:SPK:OT_DOC_ID:Y'
                  ],
                  
                  [ # 2
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'PEI.DEDUCTION_TYPE:REFID:DEDUCTION_TYPE:Y',

                    'SUM(PEI.DEDUCTION_AMOUNT):NZ:DEDUCTION_AMOUNT:N',
                    'SUM(PEI.DEDUCTION_AMOUNT):NZ:AMOUNT:N',

                    'OD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'OD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ],
                  
                  [ # 3
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',

                    'SUM(PEI.DEDUCTION_AMOUNT):NZ:DEDUCTION_AMOUNT:N',
                    'SUM(PEI.DEDUCTION_AMOUNT):NZ:AMOUNT:N',

                    'OD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'OD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ],                    
    );

    private $froms = array(

                'FROM PAYROLL_DEDUCTION_ITEM ',

                'FROM PAYROLL_DEDUCTION_ITEM ',

                'FROM PAYROLL_DEDUCTION_ITEM PEI ' .
                  'LEFT OUTER JOIN OT_DOCUMENT OD ON (PEI.OT_DOC_ID = OD.OT_DOC_ID) ',

                'FROM PAYROLL_DEDUCTION_ITEM PEI ' .
                  'LEFT OUTER JOIN OT_DOCUMENT OD ON (PEI.OT_DOC_ID = OD.OT_DOC_ID) ',                  
    );

    private $orderby = array(

                'ORDER BY PAYROLL_DEDUCTION_ITEM_ID ASC ',

                'ORDER BY PAYROLL_DEDUCTION_ITEM_ID ASC ',

                'GROUP BY OD.EMPLOYEE_ID, PEI.DEDUCTION_TYPE ORDER BY OD.EMPLOYEE_ID ASC ',

                'GROUP BY OD.EMPLOYEE_ID ORDER BY OD.EMPLOYEE_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PAYROLL_DEDUCTION_ITEM', 'PAYROLL_DEDUCTION_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>