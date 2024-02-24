<?php
/* 
Purpose : Model for CHEQUE
Created By : Seubpong Monsar
Created Date : 03/06/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCheque extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'CHEQUE_ID:SPK:CHEQUE_ID:Y',
                    'CHEQUE_NO:S:CHEQUE_NO:Y',
                    'CHEQUE_DATE:S:CHEQUE_DATE:Y',
                    'ENTITY_ID:REFID:ENTITY_ID:N',
                    'BANK_ID:REFID:BANK_ID:N',
                    'CASH_ACCT_ID:REFID:CASH_ACCT_ID:N',
                    'BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:Y',
                    'ISSUE_DATE:S:ISSUE_DATE:Y',
                    'CHEQUE_AMOUNT:N:CHEQUE_AMOUNT:N',
                    'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:N',
                    'DIRECTION:N:DIRECTION:Y',
                    'PAYEE_NAME:S:PAYEE_NAME:Y',
                    'CHEQUE_STATUS:N:CHEQUE_STATUS:Y',
                    'ALLOW_NEGATIVE:S:ALLOW_NEGATIVE:Y',
                    'APPROVED_DATE:S:APPROVED_DATE:Y',
                    'APPROVED_SEQ:NZ:APPROVED_SEQ:Y',
                    'NOTE:S:NOTE:Y',
                    'AC_PAYEE_ONLY:S:AC_PAYEE_ONLY:Y',
                    'CHEQUE_BANK_ID:REFID:CHEQUE_BANK_ID:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                ],

                [ # 1 For Query
                    'CQ.CHEQUE_ID:SPK:CHEQUE_ID:Y',
                    'CQ.CHEQUE_NO:S:CHEQUE_NO:Y',
                    'CQ.CHEQUE_DATE:S:CHEQUE_DATE:Y',
                    'CQ.ENTITY_ID:REFID:ENTITY_ID:Y',
                    'CQ.BANK_ID:REFID:BANK_ID:N',
                    'CQ.CASH_ACCT_ID:REFID:CASH_ACCT_ID:N',
                    'CQ.BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:Y',
                    'CQ.ISSUE_DATE:S:ISSUE_DATE:Y',
                    'CQ.CHEQUE_AMOUNT:N:CHEQUE_AMOUNT:N',
                    'CQ.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:N',
                    'CQ.DIRECTION:N:DIRECTION:Y',
                    'CQ.PAYEE_NAME:S:PAYEE_NAME:Y',
                    'CQ.CHEQUE_STATUS:N:CHEQUE_STATUS:Y',
                    'CQ.ALLOW_NEGATIVE:S:ALLOW_NEGATIVE:Y',
                    'CQ.APPROVED_DATE:S:APPROVED_DATE:Y',
                    'CQ.APPROVED_SEQ:NZ:APPROVED_SEQ:Y',
                    'CQ.NOTE:S:NOTE:Y',
                    'CQ.AC_PAYEE_ONLY:S:AC_PAYEE_ONLY:Y',
                    'CQ.CHEQUE_BANK_ID:REFID:CHEQUE_BANK_ID:N',

                    'CA.ACCOUNT_NO:S:ACCOUNT_NO:Y',
                    'CA.BANK_ID:REFID:ACCOUNT_BANK_ID:Y',

                    'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
                    'EN.ENTITY_NAME:S:ENTITY_NAME:Y',

                    'MR1.DESCRIPTION:S:BANK_NAME:N',
                    'MR2.DESCRIPTION:S:CHEQUE_BANK_NAME:N',
                    'MR3.DESCRIPTION:S:ACCOUNT_BANK_NAME:N',

                    'CQ.CHEQUE_DATE:FD:FROM_CHEQUE_DATE:Y',
                    'CQ.CHEQUE_DATE:TD:TO_CHEQUE_DATE:Y'                   
                ],               
    );

    private $froms = array(

                'FROM CASH_ACCOUNT ',
                'FROM CHEQUE CQ '.
                    'LEFT OUTER JOIN ENTITY EN ON (CQ.ENTITY_ID = EN.ENTITY_ID) '.
                    'LEFT OUTER JOIN MASTER_REF MR2 ON (CQ.CHEQUE_BANK_ID = MR2.MASTER_ID) '.
                    'LEFT OUTER JOIN CASH_ACCOUNT CA ON (CQ.CASH_ACCT_ID = CA.CASH_ACCOUNT_ID) '.
                    'LEFT OUTER JOIN MASTER_REF MR3 ON (CA.BANK_ID = MR3.MASTER_ID) '.
                    'LEFT OUTER JOIN MASTER_REF MR1 ON (CQ.BANK_ID = MR1.MASTER_ID) ',

    );

    private $orderby = array(

                'ORDER BY CHEQUE_ID DESC ',
                'ORDER BY CHEQUE_ID DESC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'CHEQUE', 'CHEQUE_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>