<?php
/*
Purpose : Model for CommissionBatchDetail
Created By : Supakit Tanyung
Created Date : 10/05/2017 (MM/DD/YYYY)
IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCommissionBatchDetail extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update
                    'COMMISSION_BATCH_DTL_ID:SPK:COMMISSION_BATCH_DTL_ID:Y',
                    'COMMISSION_BATCH_ID:REFID:COMMISSION_BATCH_ID:Y',
                    'EMPLOYEE_ID:REFID:EMPLOYEE_ID:N',
                    'TOTAL_BILL_COUNT:NZ:TOTAL_BILL_COUNT:N',
                    'TOTAL_BILL_AMT:NZ:TOTAL_BILL_AMT:N',
                    'TOTAL_COMMISSION_AMT:NZ:TOTAL_COMMISSION_AMT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For query
                    'CBD.COMMISSION_BATCH_DTL_ID:SPK:COMMISSION_BATCH_DTL_ID:Y',
                    'CBD.COMMISSION_BATCH_ID:REFID:COMMISSION_BATCH_ID:Y',
                    'CBD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'EP.EMPLOYEE_CODE:S:EMPLOYEE_CODE:Y',
                    'EP.EMPLOYEE_NAME:S:EMPLOYEE_NAME:Y',
                    'CBD.TOTAL_BILL_COUNT:NZ:TOTAL_BILL_COUNT:N',
                    'CBD.TOTAL_BILL_AMT:NZ:TOTAL_BILL_AMT:N',
                    'CBD.TOTAL_COMMISSION_AMT:NZ:TOTAL_COMMISSION_AMT:N',

                    'CBD.CREATE_DATE:CD:CREATE_DATE:N',
                    'CBD.MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  [
                    'COMMISSION_BATCH_ID:SPK:COMMISSION_BATCH_ID:N',
                  ]


    );

    private $froms = array(

                   'FROM COMMISSION_BATCH_DETAIL ',

                   'FROM COMMISSION_BATCH_DETAIL CBD ' .
                   'LEFT OUTER JOIN COMMISSION_BATCH CB ON (CBD.COMMISSION_BATCH_ID = CB.COMMISSION_BATCH_ID) '.
                   'LEFT OUTER JOIN EMPLOYEE EP ON (CBD.EMPLOYEE_ID = EP.EMPLOYEE_ID) ',

                   'FROM COMMISSION_BATCH_DETAIL ',
    );

    private $orderby = array(

                   'ORDER BY COMMISSION_BATCH_DTL_ID ASC ',

                   'ORDER BY CBD.COMMISSION_BATCH_DTL_ID ASC ',

                   'ORDER BY COMMISSION_BATCH_DTL_ID ASC ',
    );

    function __construct($db)
    {
        parent::__construct($db, 'COMMISSION_BATCH_DETAIL', 'COMMISSION_BATCH_DTL_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>