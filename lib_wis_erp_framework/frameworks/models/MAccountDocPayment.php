<?php
/* 
Purpose : Model for ACCOUNT_DOC_PAYMENT
Created By : Seubpong Monsar
Created Date : 11/26/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MAccountDocPayment extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'ACT_DOC_PAYMENT_ID:SPK:ACT_DOC_PAYMENT_ID:Y',
                    'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'PAYMENT_TYPE:NZ:PAYMENT_TYPE:Y',
                    'BANK_ID:REFID:BANK_ID:Y',
                    'CASH_ACCOUNT_ID:REFID:CASH_ACCOUNT_ID:Y',
                    'PAID_AMOUNT:NZ:PAID_AMOUNT:N',
                    'NOTE:S:NOTE:N',
                    'DIRECTION:N:DIRECTION:Y',
                    'CHANGE_TYPE:NZ:CHANGE_TYPE:Y',
                    'CHEQUE_ID:REFID:CHEQUE_ID:Y',
                    'FEE_AMOUNT:NZ:FEE_AMOUNT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get Account Doc Payment List
                    'AP.ACT_DOC_PAYMENT_ID:SPK:ACT_DOC_PAYMENT_ID:Y',
                    'AP.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'AP.PAYMENT_TYPE:NZ:PAYMENT_TYPE:Y',
                    'AP.BANK_ID:REFID:BANK_ID:Y',
                    'AP.CASH_ACCOUNT_ID:REFID:CASH_ACCOUNT_ID:Y',
                    'AP.PAID_AMOUNT:NZ:PAID_AMOUNT:N',
                    'AP.NOTE:S:NOTE:N',
                    'AP.DIRECTION:N:DIRECTION:Y',
                    'AP.CHANGE_TYPE:NZ:CHANGE_TYPE:Y',
                    'AP.CHEQUE_ID:REFID:CHEQUE_ID:Y',
                    'AP.FEE_AMOUNT:NZ:FEE_AMOUNT:N',

                    'CQ.CHEQUE_NO:S:CHEQUE_NO:N',
                    'CQ.CHEQUE_DATE:S:CHEQUE_DATE:N',
                    'CA.ACCOUNT_NO:S:ACCOUNT_NO:N',
                  ],

                  [ # 2 For Delete by parent
                    'ACCOUNT_DOC_ID:SPK:ACCOUNT_DOC_ID:Y',
                  ], 

                  [ # 3 For Payment Report
                    'AP.ACT_DOC_PAYMENT_ID:SPK:ACT_DOC_PAYMENT_ID:Y',
                    'AP.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'AP.PAYMENT_TYPE:NZ:PAYMENT_TYPE:Y',
                    'AP.BANK_ID:REFID:BANK_ID:Y',
                    'AP.CASH_ACCOUNT_ID:REFID:CASH_ACCOUNT_ID:Y',
                    'AP.PAID_AMOUNT:NZ:PAID_AMOUNT:N',
                    'AP.NOTE:S:NOTE:N',
                    'AP.DIRECTION:N:DIRECTION:Y',
                    'AP.CHANGE_TYPE:NZ:CHANGE_TYPE:Y',
                    'AP.CHEQUE_ID:REFID:CHEQUE_ID:Y',
                    'AP.FEE_AMOUNT:NZ:FEE_AMOUNT:N',
                    //'AP.REFUND_STATUS:REFID:REFUND_STATUS:Y', #Not use

                    'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'AD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'AD.ENTITY_ID:REFID:ENTITY_ID:Y',
                    'AD.REF_DOCUMENT_NO:S:REF_DOCUMENT_NO:Y',
                    'AD.REF_PO_NO:S:REF_PO_NO:Y',
                    'AD.DOCUMENT_TYPE:NZ:DOCUMENT_TYPE:Y',
                    'AD.DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:Y',

                    'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
                    'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
                    'EN.ENTITY_TYPE:REFID:ENTITY_TYPE:Y',
                    'EN.ENTITY_GROUP:REFID:ENTITY_GROUP:Y',
        
                    'BN.CODE:S:BANK_CODE:Y',
                    'BN.DESCRIPTION:S:BANK_NAME:Y',                    

                    'CBN.CODE:S:CHEQUE_TO_BANK_CODE:Y',
                    'CBN.DESCRIPTION:S:CHEQUE_TO_BANK_NAME:Y',   

                    'CX.CASH_XFER_DTL_ID:REFID:CASH_XFER_DTL_ID:Y',
                    'CD.DOCUMENT_STATUS:N:REFUND_STATUS:Y',
                    'CD.DOCUMENT_NO:S:PAYMENT_DOCUMENT_NO:N',

                    'CQ.CHEQUE_NO:S:CHEQUE_NO:N',
                    'CQ.CHEQUE_DATE:S:CHEQUE_DATE:N',
                    'CA.ACCOUNT_NO:S:ACCOUNT_NO:N',

                    # Always put these at the end
                    'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                    'AD.DOCUMENT_TYPE:INC_SET:DOCUMENT_TYPE_SET:Y',
                    'AP.PAYMENT_TYPE:INC_SET:PAYMENT_TYPE_SET:Y',
                    'AD.DOCUMENT_STATUS:INC_SET:DOCUMENT_STATUS_SET:Y', 
                    'CD.DOCUMENT_STATUS:IS_NULL:IS_NULL_REFUND_STATUS:Y',
                  ],                  
    );

    private $froms = array(

                'FROM ACCOUNT_DOC_PAYMENT ',

                'FROM ACCOUNT_DOC_PAYMENT AP ' .
                    'LEFT OUTER JOIN CHEQUE CQ ON (AP.CHEQUE_ID = CQ.CHEQUE_ID) '.                
                    'LEFT OUTER JOIN CASH_ACCOUNT CA ON (AP.CASH_ACCOUNT_ID = CA.CASH_ACCOUNT_ID) ',

                'FROM ACCOUNT_DOC_PAYMENT ',
                
                'FROM ACCOUNT_DOC_PAYMENT AP ' .
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (AP.ACCOUNT_DOC_ID = AD.ACCOUNT_DOC_ID) '.  
                    'LEFT OUTER JOIN ENTITY EN ON (AD.ENTITY_ID = EN.ENTITY_ID) '.              
                    'LEFT OUTER JOIN CHEQUE CQ ON (AP.CHEQUE_ID = CQ.CHEQUE_ID) '. 
                    'LEFT OUTER JOIN MASTER_REF CBN ON (CQ.BANK_ID = CBN.MASTER_ID) '.  
                    'LEFT OUTER JOIN MASTER_REF BN ON (AP.BANK_ID = BN.MASTER_ID) '.                
                    'LEFT OUTER JOIN CASH_XFER_DETAIL CX ON (AP.ACT_DOC_PAYMENT_ID = CX.ACT_DOC_PAYMENT_ID) '.
                    'LEFT OUTER JOIN CASH_DOC CD ON (CX.CASH_DOC_ID = CD.CASH_DOC_ID) '.
                    'LEFT OUTER JOIN CASH_ACCOUNT CA ON (AP.CASH_ACCOUNT_ID = CA.CASH_ACCOUNT_ID) ',
                
    );

    private $orderby = array(

                'ORDER BY ACT_DOC_PAYMENT_ID ASC ',
                
                'ORDER BY AP.ACT_DOC_PAYMENT_ID ASC ',
                
                'ORDER BY ACT_DOC_PAYMENT_ID ASC ',  
                
                'ORDER BY ACT_DOC_PAYMENT_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ACCOUNT_DOC_PAYMENT', 'ACT_DOC_PAYMENT_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>