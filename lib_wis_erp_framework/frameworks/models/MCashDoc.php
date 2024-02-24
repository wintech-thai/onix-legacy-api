<?php
/* 
Purpose : Model for CASH_DOC
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCashDoc extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'CASH_DOC_ID:SPK:CASH_DOC_ID:Y',
                    'DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'DOCUMENT_TYPE:N:DOCUMENT_TYPE:N',
                    'DOCUMENT_STATUS:N:DOCUMENT_STATUS:N',
                    'APPROVED_DATE:S:APPROVED_DATE:N',
                    'APPROVED_SEQ:NZ:APPROVED_SEQ:N',
                    'CASH_ACCOUNT_ID1:REFID:CASH_ACCOUNT_ID1:N',
                    'CASH_ACCOUNT_ID2:REFID:CASH_ACCOUNT_ID2:N',
                    'NOTE:S:NOTE:N',
                    'ALLOW_NEGATIVE:S:ALLOW_NEGATIVE:N',
                    'TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N',
                    'INTERNAL_FLAG:S:INTERNAL_FLAG:Y',
                    'CHEQUE_ID:REFID:CHEQUE_ID:N',
                    'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:N',
                    'CASH_XFER_TYPE:REFID:CASH_XFER_TYPE:Y',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                ],
                
                [ # 1 For Query
                    'CD.CASH_DOC_ID:SPK:CASH_DOC_ID:Y',
                    'CD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'CD.DOCUMENT_DATE:S:DOCUMENT_DATE:Y',
                    'CD.DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                    'CD.DOCUMENT_STATUS:N:DOCUMENT_STATUS:Y',
                    'CD.APPROVED_DATE:S:APPROVED_DATE:Y',
                    'CD.APPROVED_SEQ:NZ:APPROVED_SEQ:N',
                    'CD.CASH_ACCOUNT_ID1:REFID:CASH_ACCOUNT_ID1:Y',
                    'CD.CASH_ACCOUNT_ID2:REFID:CASH_ACCOUNT_ID2:Y',
                    'CD.NOTE:S:NOTE:N',
                    'CD.TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N',
                    'CD.ALLOW_NEGATIVE:S:ALLOW_NEGATIVE:N',
                    'CD.INTERNAL_FLAG:S:INTERNAL_FLAG:Y',
                    'CD.CHEQUE_ID:REFID:CHEQUE_ID:N',
                    'CD.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:N',
                    'CD.CASH_XFER_TYPE:REFID:CASH_XFER_TYPE:Y',

                    'CA1.ACCOUNT_NO:S:FROM_ACCOUNT_NO:Y',
                    'CA1.ACCOUNT_NNAME:S:FROM_ACCOUNT_NNAME:Y',
                    'CA1.BANK_ID:N:FROM_BANK_ID:Y',
                    'CA1.BANK_BRANCH_NAME:S:FROM_BANK_BRANCH_NAME:N',
                    'CA1.NOTE:S:FROM_NOTE:N',
                    'CA1.TOTAL_AMOUNT:NZ:FROM_TOTAL_AMOUNT:N',

                    'MR1.DESCRIPTION:S:FROM_BANK_NAME:Y',

                    'CA2.ACCOUNT_NO:S:TO_ACCOUNT_NO:Y',
                    'CA2.ACCOUNT_NNAME:S:TO_ACCOUNT_NNAME:Y',
                    'CA2.BANK_ID:N:TO_BANK_ID:Y',
                    'CA2.BANK_BRANCH_NAME:S:TO_BANK_BRANCH_NAME:N',
                    'CA2.NOTE:S:TO_NOTE:N',
                    'CA2.TOTAL_AMOUNT:NZ:TO_TOTAL_AMOUNT:N',

                    'MR2.DESCRIPTION:S:TO_BANK_NAME:Y',

                    'CD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'CD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                ],
                
                [ # 2 Cash Movement Report
                    'CD.CASH_DOC_ID:SPK:CASH_DOC_ID:Y',
                    'CD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'CD.DOCUMENT_DATE:S:DOCUMENT_DATE:Y',
                    'CD.DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                    'CD.DOCUMENT_STATUS:N:DOCUMENT_STATUS:Y',
                    'CD.APPROVED_DATE:S:APPROVED_DATE:Y',
                    'CD.APPROVED_SEQ:NZ:APPROVED_SEQ:N',
                    'CD.CASH_ACCOUNT_ID1:REFID:CASH_ACCOUNT_ID1:Y',
                    'CD.CASH_ACCOUNT_ID2:REFID:CASH_ACCOUNT_ID2:Y',
                    'CD.NOTE:S:NOTE:N',
                    'CD.TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N',
                    'CD.ALLOW_NEGATIVE:S:ALLOW_NEGATIVE:N',
                    'CD.INTERNAL_FLAG:S:INTERNAL_FLAG:Y',
                    'CD.CHEQUE_ID:REFID:CHEQUE_ID:N',
                    'CD.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:N',
                    'CD.CASH_XFER_TYPE:REFID:CASH_XFER_TYPE:N',

                    'CA1.ACCOUNT_NO:S:FROM_ACCOUNT_NO:Y',
                    'CA1.ACCOUNT_NNAME:S:FROM_ACCOUNT_NNAME:Y',
                    'CA1.BANK_ID:N:FROM_BANK_ID:Y',
                    'CA1.BANK_BRANCH_NAME:S:FROM_BANK_BRANCH_NAME:N',
                    'CA1.NOTE:S:FROM_NOTE:N',
                    'CA1.TOTAL_AMOUNT:NZ:FROM_TOTAL_AMOUNT:N',

                    'MR1.DESCRIPTION:S:FROM_BANK_NAME:Y',

                    'CA2.ACCOUNT_NO:S:TO_ACCOUNT_NO:Y',
                    'CA2.ACCOUNT_NNAME:S:TO_ACCOUNT_NNAME:Y',
                    'CA2.BANK_ID:N:TO_BANK_ID:Y',
                    'CA2.BANK_BRANCH_NAME:S:TO_BANK_BRANCH_NAME:N',
                    'CA2.NOTE:S:TO_NOTE:N',
                    'CA2.TOTAL_AMOUNT:NZ:TO_TOTAL_AMOUNT:N',

                    'MR2.DESCRIPTION:S:TO_BANK_NAME:Y',

                    'AD.DOCUMENT_NO:S:ACCOUNT_DOCUMENT_NO:Y',
                    'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
                    'CEN.ENTITY_NAME:S:CHEQUE_ENTITY_NAME:Y',
                    'CQ.PAYEE_NAME:S:PAYEE_NAME:Y',

                    'CD.CUSTOM_WHERE_FIELD:CST:CUSTOM_WHERE_FIELD:Y:N',                    
                    'CD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'CD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                ], 

                [ # 3 For begin balance
                    'CD.CASH_ACCOUNT_ID1:REFID:CASH_ACCOUNT_ID1:Y',
                    'CD.CASH_ACCOUNT_ID2:REFID:CASH_ACCOUNT_ID2:Y',
                    'CD.DOCUMENT_STATUS:N:DOCUMENT_STATUS:Y:N',
                    'SUM(CD.TOTAL_AMOUNT):NZ:TOTAL_AMOUNT:N',

                    # Always put these at the end
                    'CD.CUSTOM_WHERE_FIELD:CST:CUSTOM_WHERE_FIELD:Y:N',
                    'CD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'CD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
            ],                
    );

    private $froms = array(

                'FROM CASH_DOC ',

                'FROM CASH_DOC CD '.
                    'LEFT OUTER JOIN CASH_ACCOUNT CA1 ON (CD.CASH_ACCOUNT_ID1 = CA1.CASH_ACCOUNT_ID) '.
                    'LEFT OUTER JOIN CASH_ACCOUNT CA2 ON (CD.CASH_ACCOUNT_ID2 = CA2.CASH_ACCOUNT_ID) '.
                    'LEFT OUTER JOIN MASTER_REF MR1 ON (MR1.MASTER_ID = CA1.BANK_ID) '.
                    'LEFT OUTER JOIN MASTER_REF MR2 ON (MR2.MASTER_ID = CA2.BANK_ID) ',  
                    
                'FROM CASH_DOC CD '.
                    'LEFT OUTER JOIN CASH_ACCOUNT CA1 ON (CD.CASH_ACCOUNT_ID1 = CA1.CASH_ACCOUNT_ID) '.
                    'LEFT OUTER JOIN CASH_ACCOUNT CA2 ON (CD.CASH_ACCOUNT_ID2 = CA2.CASH_ACCOUNT_ID) '.
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (CD.ACCOUNT_DOC_ID = AD.ACCOUNT_DOC_ID) '.
                    'LEFT OUTER JOIN ENTITY EN ON (AD.ENTITY_ID = EN.ENTITY_ID) '.
                    'LEFT OUTER JOIN CHEQUE CQ ON (CD.CHEQUE_ID = CQ.CHEQUE_ID) '.
                    'LEFT OUTER JOIN ENTITY CEN ON (CQ.ENTITY_ID = CEN.ENTITY_ID) '.
                    'LEFT OUTER JOIN MASTER_REF MR1 ON (MR1.MASTER_ID = CA1.BANK_ID) '.
                    'LEFT OUTER JOIN MASTER_REF MR2 ON (MR2.MASTER_ID = CA2.BANK_ID) ',      
                    
                'FROM CASH_DOC CD ',                    

    );

    private $orderby = array(

                'ORDER BY CASH_DOC_ID DESC ',

                'ORDER BY CASH_DOC_ID DESC ',

                'ORDER BY CD.DOCUMENT_DATE ASC, CD.CASH_DOC_ID ASC ',     
                
                'GROUP BY CD.CASH_ACCOUNT_ID1, CD.CASH_ACCOUNT_ID2 ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'CASH_DOC', 'CASH_DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>