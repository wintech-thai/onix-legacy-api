<?php
/* 
Purpose : Model for CASH_XFER_DETAIL
Created By : Seubpong Monsar
Created Date : 10/27/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCashXferDetail extends MBaseModel
{
    private $cols = array(

                [ # 0 For insert, update, delete
                   'CASH_XFER_DTL_ID:SPK:CASH_XFER_DTL_ID:Y', 
                   'CASH_DOC_ID:REFID:CASH_DOC_ID:Y', 
                   'ACT_DOC_PAYMENT_ID:REFID:ACT_DOC_PAYMENT_ID:Y', 

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                ] ,
                  
                [ # 1 For query
                    'CX.CASH_XFER_DTL_ID:SPK:CASH_XFER_DTL_ID:Y', 
                    'CX.CASH_DOC_ID:REFID:CASH_DOC_ID:Y', 
                    'CX.ACT_DOC_PAYMENT_ID:REFID:ACT_DOC_PAYMENT_ID:Y', 

                    'AD.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:N',
                    'AD.DOCUMENT_DATE:S:ACCOUNT_DOCUMENT_DATE:N',
                    'AD.DOCUMENT_NO:S:DOCUMENT_NO:N',
                    'AD.DOCUMENT_STATUS:S:ACCOUNT_DOCUMENT_STATUS:N',
                   
                    'AP.PAID_AMOUNT:NZ:PAID_AMOUNT:N',
                    'AP.DIRECTION:N:DIRECTION:N',
                    'AP.PAYMENT_TYPE:NZ:PAYMENT_TYPE:N',

                    'CA.ACCOUNT_NO:S:ACCOUNT_NO:N',

                    'BN.CODE:S:BANK_CODE:N',
                    'BN.DESCRIPTION:S:BANK_NAME:N',    

                    'CX.CREATE_DATE:CD:CREATE_DATE:N',
                    'CX.MODIFY_DATE:MD:MODIFY_DATE:N',

                    # Always put these at the end
                    'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',                   
                ], 

                [ # 2 For Delete by parent
                    'CASH_DOC_ID:SPK:CASH_DOC_ID:Y',
                ],                  
              
    );

    private $froms = array(
                               
                'FROM CASH_XFER_DETAIL ',

                'FROM CASH_XFER_DETAIL CX ' .
                    'LEFT OUTER JOIN ACCOUNT_DOC_PAYMENT AP ON (CX.ACT_DOC_PAYMENT_ID = AP.ACT_DOC_PAYMENT_ID) ' .
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AP.ACCOUNT_DOC_ID = AD.ACCOUNT_DOC_ID) ' .  
                    'LEFT OUTER JOIN MASTER_REF BN ON (AP.BANK_ID = BN.MASTER_ID) ' .   
                    'LEFT OUTER JOIN CASH_ACCOUNT CA ON (AP.CASH_ACCOUNT_ID = CA.CASH_ACCOUNT_ID) ',

                'FROM CASH_XFER_DETAIL ',
            
    );

    private $orderby = array(

                'ORDER BY CASH_XFER_DTL_ID DESC ',

                'ORDER BY AD.DOCUMENT_DATE ASC, AD.DOCUMENT_NO ASC ',

                'ORDER BY CASH_XFER_DTL_ID DESC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'CASH_XFER_DETAIL', 'CASH_XFER_DTL_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>