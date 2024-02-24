<?php
/* 
Purpose : Model for BRANCH_CONFIG
Created By : Seubpong Monsar
Created Date : 11/03/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MBranchConfig extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'BRANCH_CONFIG_ID:SPK:BRANCH_CONFIG_ID:Y',
                    'BRANCH_ID:REFID:BRANCH_ID:Y',
                    'KEY:S:KEY:Y',
                    'DEF_CASH_ACCOUNT_ID:REFID:DEF_CASH_ACCOUNT_ID:N',
                    'DEF_LOCATION_ID:REFID:DEF_LOCATION_ID:N',
                    'DEF_CASH_ACCOUNT_ID_NV:REFID:DEF_CASH_ACCOUNT_ID_NV:N',
                    'DEF_LOCATION_ID_NV:REFID:DEF_LOCATION_ID_NV:N',

                    'DEF_EMP_ID:REFID:DEF_EMP_ID:N',

                    'DOC_NO_CASH_PREFIX:S:DOC_NO_CASH_PREFIX:N',
                    'DOC_NO_CASH_PATTERN:S:DOC_NO_CASH_PATTERN:N',
                    'DOC_NO_CASH_YEAR_OFFSET:NZ:DOC_NO_CASH_YEAR_OFFSET:N',
                    'DOC_NO_CASH_RESET_CRITERIA:NZ:DOC_NO_CASH_RESET_CRITERIA:N',
                    'DOC_NO_CASH_SEQ_LENGTH:NZ:DOC_NO_CASH_SEQ_LENGTH:N',

                    'DOC_NO_DEBT_PREFIX:S:DOC_NO_DEBT_PREFIX:N',
                    'DOC_NO_DEBT_PATTERN:S:DOC_NO_DEBT_PATTERN:N',
                    'DOC_NO_DEBT_YEAR_OFFSET:NZ:DOC_NO_DEBT_YEAR_OFFSET:N',
                    'DOC_NO_DEBT_RESET_CRITERIA:NZ:DOC_NO_DEBT_RESET_CRITERIA:N',
                    'DOC_NO_DEBT_SEQ_LENGTH:NZ:DOC_NO_DEBT_SEQ_LENGTH:N',

                    'DOC_NO_CASH_PREFIX_NV:S:DOC_NO_CASH_PREFIX_NV:N',
                    'DOC_NO_CASH_PATTERN_NV:S:DOC_NO_CASH_PATTERN_NV:N',
                    'DOC_NO_CASH_NV_YEAR_OFFSET:NZ:DOC_NO_CASH_NV_YEAR_OFFSET:N',
                    'DOC_NO_CASH_NV_RESET_CRITERIA:NZ:DOC_NO_CASH_NV_RESET_CRITERIA:N',
                    'DOC_NO_CASH_NV_SEQ_LENGTH:NZ:DOC_NO_CASH_NV_SEQ_LENGTH:N',

                    'DOC_NO_DEBT_PREFIX_NV:S:DOC_NO_DEBT_PREFIX_NV:N',
                    'DOC_NO_DEBT_PATTERN_NV:S:DOC_NO_DEBT_PATTERN_NV:N',
                    'DOC_NO_DEBT_NV_YEAR_OFFSET:NZ:DOC_NO_DEBT_NV_YEAR_OFFSET:N',
                    'DOC_NO_DEBT_NV_RESET_CRITERIA:NZ:DOC_NO_DEBT_NV_RESET_CRITERIA:N',
                    'DOC_NO_DEBT_NV_SEQ_LENGTH:NZ:DOC_NO_DEBT_NV_SEQ_LENGTH:N',

                    'ALLOW_INVENTORY_NEGATIVE:S:ALLOW_INVENTORY_NEGATIVE:N',
                    'ALLOW_INVENTORY_NEGATIVE_NV:S:ALLOW_INVENTORY_NEGATIVE_NV:N',
                    'ALLOW_CASH_NEGATIVE:S:ALLOW_CASH_NEGATIVE:N',
                    'ALLOW_CASH_NEGATIVE_NV:S:ALLOW_CASH_NEGATIVE_NV:N',

                    'VOID_BILL_PASSWORD:S:VOID_BILL_PASSWORD:N',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                ],

                [ # 1 For Query
                    'BC.BRANCH_CONFIG_ID:SPK:BRANCH_CONFIG_ID:Y',
                    'BC.BRANCH_ID:REFID:BRANCH_ID:Y',
                    'BC.KEY:S:KEY:N',
                    'BC.DEF_CASH_ACCOUNT_ID:REFID:DEF_CASH_ACCOUNT_ID:N',
                    'BC.DEF_LOCATION_ID:REFID:DEF_LOCATION_ID:N',
                    'BC.DEF_CASH_ACCOUNT_ID_NV:REFID:DEF_CASH_ACCOUNT_ID_NV:N',
                    'BC.DEF_LOCATION_ID_NV:REFID:DEF_LOCATION_ID_NV:N',                    
                    'BC.DEF_EMP_ID:REFID:DEF_EMP_ID:N',
                    'BC.DOC_NO_CASH_PREFIX:S:DOC_NO_CASH_PREFIX:N',
                    'BC.DOC_NO_CASH_PATTERN:S:DOC_NO_CASH_PATTERN:N',
                    'BC.DOC_NO_DEBT_PREFIX:S:DOC_NO_DEBT_PREFIX:N',
                    'BC.DOC_NO_DEBT_PATTERN:S:DOC_NO_DEBT_PATTERN:N',
                    'BC.DOC_NO_CASH_PREFIX_NV:S:DOC_NO_CASH_PREFIX_NV:N',
                    'BC.DOC_NO_CASH_PATTERN_NV:S:DOC_NO_CASH_PATTERN_NV:N',
                    'BC.DOC_NO_DEBT_PREFIX_NV:S:DOC_NO_DEBT_PREFIX_NV:N',
                    'BC.DOC_NO_DEBT_PATTERN_NV:S:DOC_NO_DEBT_PATTERN_NV:N',
                    
                    'BC.DOC_NO_CASH_YEAR_OFFSET:NZ:DOC_NO_CASH_YEAR_OFFSET:N',
                    'BC.DOC_NO_CASH_RESET_CRITERIA:NZ:DOC_NO_CASH_RESET_CRITERIA:N',
                    'BC.DOC_NO_CASH_SEQ_LENGTH:NZ:DOC_NO_CASH_SEQ_LENGTH:N',

                    'BC.DOC_NO_DEBT_YEAR_OFFSET:NZ:DOC_NO_DEBT_YEAR_OFFSET:N',
                    'BC.DOC_NO_DEBT_RESET_CRITERIA:NZ:DOC_NO_DEBT_RESET_CRITERIA:N',
                    'BC.DOC_NO_DEBT_SEQ_LENGTH:NZ:DOC_NO_DEBT_SEQ_LENGTH:N',

                    'BC.DOC_NO_CASH_NV_YEAR_OFFSET:NZ:DOC_NO_CASH_NV_YEAR_OFFSET:N',
                    'BC.DOC_NO_CASH_NV_RESET_CRITERIA:NZ:DOC_NO_CASH_NV_RESET_CRITERIA:N',
                    'BC.DOC_NO_CASH_NV_SEQ_LENGTH:NZ:DOC_NO_CASH_NV_SEQ_LENGTH:N',

                    'BC.DOC_NO_DEBT_NV_YEAR_OFFSET:NZ:DOC_NO_DEBT_NV_YEAR_OFFSET:N',
                    'BC.DOC_NO_DEBT_NV_RESET_CRITERIA:NZ:DOC_NO_DEBT_NV_RESET_CRITERIA:N',
                    'BC.DOC_NO_DEBT_NV_SEQ_LENGTH:NZ:DOC_NO_DEBT_NV_SEQ_LENGTH:N',

                    'BC.ALLOW_INVENTORY_NEGATIVE:S:ALLOW_INVENTORY_NEGATIVE:N',
                    'BC.ALLOW_INVENTORY_NEGATIVE_NV:S:ALLOW_INVENTORY_NEGATIVE_NV:N',
                    'BC.ALLOW_CASH_NEGATIVE:S:ALLOW_CASH_NEGATIVE:N',
                    'BC.ALLOW_CASH_NEGATIVE_NV:S:ALLOW_CASH_NEGATIVE_NV:N',

                    'BC.VOID_BILL_PASSWORD:S:VOID_BILL_PASSWORD:N',

                    'BR.CODE:S:BRANCH_CODE:Y',
                    'BR.DESCRIPTION:S:BRANCH_NAME:Y',

                    'LC.LOCATION_CODE:S:LOCATION_CODE:N',
                    'LC.DESCRIPTION:S:LOCATION_NAME:N',

                    'CA.ACCOUNT_NO:S:CASH_ACCOUNT_NO:N',
                    'CA.ACCOUNT_NNAME:S:CASH_ACCOUNT_NAME:N',
                    'CA.BANK_ID:S:BANK_ID:N',

                    'LC2.LOCATION_CODE:S:LOCATION_CODE_NV:N',
                    'LC2.DESCRIPTION:S:LOCATION_NAME_NV:N',

                    'CA2.ACCOUNT_NO:S:CASH_ACCOUNT_NO_NV:N',
                    'CA2.ACCOUNT_NNAME:S:CASH_ACCOUNT_NAME_NV:N',
                    'CA2.BANK_ID:S:BANK_ID_NV:N',

                    'BC.CREATE_DATE:CD:CREATE_DATE:N',
                    'BC.MODIFY_DATE:MD:MODIFY_DATE:N'                
                ],                        
    );

    private $froms = array(

                'FROM BRANCH_CONFIG ',

                'FROM BRANCH_CONFIG BC ' .
                    'LEFT OUTER JOIN MASTER_REF BR ON (BC.BRANCH_ID = BR.MASTER_ID) ' . 
                    'LEFT OUTER JOIN CASH_ACCOUNT CA ON (BC.DEF_CASH_ACCOUNT_ID = CA.CASH_ACCOUNT_ID) ' .
                    'LEFT OUTER JOIN LOCATION LC ON (BC.DEF_LOCATION_ID = LC.LOCATION_ID) '.
                    'LEFT OUTER JOIN CASH_ACCOUNT CA2 ON (BC.DEF_CASH_ACCOUNT_ID_NV = CA2.CASH_ACCOUNT_ID) ' .
                    'LEFT OUTER JOIN LOCATION LC2 ON (BC.DEF_LOCATION_ID_NV = LC2.LOCATION_ID) ',
                    
    );

    private $orderby = array(

                'ORDER BY BRANCH_CONFIG_ID DESC ',
                'ORDER BY BRANCH_CONFIG_ID DESC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'BRANCH_CONFIG', 'BRANCH_CONFIG_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>