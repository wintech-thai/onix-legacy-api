<?php
/* 
Purpose : Model for PAYROLL_ALLOWANCE_ITEM
Created By : Seubpong Monsar
Created Date : 04/14/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MEmployeeAllowanceItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'PAYROLL_ALLOWANCE_ITEM_ID:SPK:PAYROLL_ALLOWANCE_ITEM_ID:Y',
                    'OT_DOC_ID:REFID:OT_DOC_ID:Y',
                    'ALLOWANCE_NOTE:S:ALLOWANCE_NOTE:N',
                    'ALLOWANCE_AMOUNT:NZ:ALLOWANCE_AMOUNT:N',
                    'ALLOWANCE_TYPE:REFID:ALLOWANCE_TYPE:Y',
                    'ALLOWANCE_DATE:S:ALLOWANCE_DATE:N',
                    'ALLOWANCE_QUANTITY:NZ:ALLOWANCE_QUANTITY:N',
                    'ALLOWANCE_PRICE:NZ:ALLOWANCE_PRICE:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For delete by parent
                    'OT_DOC_ID:SPK:OT_DOC_ID:Y'
                  ],           
    );

    private $froms = array(

                'FROM PAYROLL_ALLOWANCE_ITEM ',

                'FROM PAYROLL_ALLOWANCE_ITEM ',               
    );

    private $orderby = array(

                'ORDER BY PAYROLL_ALLOWANCE_ITEM_ID ASC ',

                'ORDER BY PAYROLL_ALLOWANCE_ITEM_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PAYROLL_ALLOWANCE_ITEM', 'PAYROLL_ALLOWANCE_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>