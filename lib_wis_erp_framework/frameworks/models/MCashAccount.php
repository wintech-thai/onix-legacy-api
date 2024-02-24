<?php
/* 
Purpose : Model for CASH_ACCOUNT
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCashAccount extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'CASH_ACCOUNT_ID:SPK:CASH_ACCOUNT_ID:Y',
                    'ACCOUNT_NO:S:ACCOUNT_NO:Y',
                    'ACCOUNT_NNAME:S:ACCOUNT_NNAME:Y',
                    'BANK_ID:N:BANK_ID:N',
                    'BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:Y',
                    'NOTE:S:NOTE:N',
                    'IS_FOR_CHEQUE:S:IS_FOR_CHEQUE:N',
                    'OWNER_FLAG:S:OWNER_FLAG:Y',
                    'PAYROLL_FLAG:S:PAYROLL_FLAG:Y',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                ],

                [ # 1 For Query
                    'CA.CASH_ACCOUNT_ID:SPK:CASH_ACCOUNT_ID:Y',
                    'CA.ACCOUNT_NO:S:ACCOUNT_NO:Y',
                    'CA.ACCOUNT_NNAME:S:ACCOUNT_NNAME:Y',
                    'CA.BANK_ID:N:BANK_ID:Y',
                    'MR.DESCRIPTION:S:BANK_NAME:Y',
                    'CA.BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:Y',
                    'CA.NOTE:S:NOTE:N',
                    'CA.TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N',
                    'CA.IS_FOR_CHEQUE:S:IS_FOR_CHEQUE:N',
                    'CA.OWNER_FLAG:S:OWNER_FLAG:Y',
                    'CA.PAYROLL_FLAG:S:PAYROLL_FLAG:Y',

                    'CA.CREATE_DATE:CD:CREATE_DATE:N',
                    'CA.MODIFY_DATE:MD:MODIFY_DATE:N'
                ],

                [ # 2 For Update Total Amount
                    'CASH_ACCOUNT_ID:SPK:CASH_ACCOUNT_ID:Y',
                    'TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N'
                ],

                [ # 3 Generic purpose, usually used by Patch functions. Do not modify!!!
                ],               
              
    );

    private $froms = array(

                'FROM CASH_ACCOUNT ',
                'FROM CASH_ACCOUNT CA '.
                    'LEFT OUTER JOIN MASTER_REF MR ON (CA.BANK_ID = MR.MASTER_ID) ',
                'FROM CASH_ACCOUNT ',
                'FROM CASH_ACCOUNT ',            
    );

    private $orderby = array(

                'ORDER BY CASH_ACCOUNT_ID DESC ',
                'ORDER BY CASH_ACCOUNT_ID DESC ',
                'ORDER BY CASH_ACCOUNT_ID DESC ',
                'ORDER BY CASH_ACCOUNT_ID DESC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'CASH_ACCOUNT', 'CASH_ACCOUNT_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>