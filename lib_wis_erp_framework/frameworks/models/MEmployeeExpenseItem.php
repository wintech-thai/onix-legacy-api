<?php
/* 
Purpose : Model for PAYROLL_EXPENSE_ITEM
Created By : Seubpong Monsar
Created Date : 04/14/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MEmployeeExpenseItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'PAYROLL_EXP_ITEM_ID:SPK:PAYROLL_EXP_ITEM_ID:Y',
                    'OT_DOC_ID:REFID:OT_DOC_ID:Y',
                    'EXPENSE_NOTE:S:EXPENSE_NOTE:N',
                    'EXPENSE_AMOUNT:NZ:EXPENSE_AMOUNT:N',
                    'EXPENSE_TYPE:REFID:EXPENSE_TYPE:Y',                  
                    'EXPENSE_DATE:S:EXPENSE_DATE:N',
                    'EXPENSE_QUANTITY:NZ:EXPENSE_QUANTITY:N',
                    'EXPENSE_PRICE:NZ:EXPENSE_PRICE:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For delete by parent
                    'OT_DOC_ID:SPK:OT_DOC_ID:Y'
                  ],
                  
                  [ # 2
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'PEI.EXPENSE_TYPE:REFID:EXPENSE_TYPE:Y',

                    'SUM(PEI.EXPENSE_AMOUNT):NZ:EXPENSE_AMOUNT:N',
                    'SUM(PEI.EXPENSE_AMOUNT):NZ:AMOUNT:N',

                    'OD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'OD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ],
                  
                  [ # 3
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',

                    'SUM(PEI.EXPENSE_AMOUNT):NZ:EXPENSE_AMOUNT:N',
                    'SUM(PEI.EXPENSE_AMOUNT):NZ:AMOUNT:N',

                    'OD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'OD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ],                    
    );

    private $froms = array(

                'FROM PAYROLL_EXPENSE_ITEM ',

                'FROM PAYROLL_EXPENSE_ITEM ',

                'FROM PAYROLL_EXPENSE_ITEM PEI ' .
                  'LEFT OUTER JOIN OT_DOCUMENT OD ON (PEI.OT_DOC_ID = OD.OT_DOC_ID) ',

                'FROM PAYROLL_EXPENSE_ITEM PEI ' .
                  'LEFT OUTER JOIN OT_DOCUMENT OD ON (PEI.OT_DOC_ID = OD.OT_DOC_ID) ',                  
    );

    private $orderby = array(

                'ORDER BY PAYROLL_EXP_ITEM_ID ASC ',

                'ORDER BY PAYROLL_EXP_ITEM_ID ASC ',

                'GROUP BY OD.EMPLOYEE_ID, PEI.EXPENSE_TYPE ORDER BY OD.EMPLOYEE_ID ASC ',

                'GROUP BY OD.EMPLOYEE_ID ORDER BY OD.EMPLOYEE_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PAYROLL_EXPENSE_ITEM', 'PAYROLL_EXP_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>