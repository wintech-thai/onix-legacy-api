<?php
/* 
Purpose : Model for MTaxDocumentRv3_53
Created By : Seubpong Monsar
Created Date : 01/12/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MTaxDocumentRv3_53 extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'TAX_RV3_53_ID:SPK:TAX_RV3_53_ID:Y',
                    'TAX_DOC_ID:REFID:TAX_DOC_ID:Y',
                    
                    'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:N',
                    'ACCOUNT_DOC_ITEM_ID:REFID:ACCOUNT_DOC_ITEM_ID:N',
                    'DOCUMENT_TYPE:REFID:DOCUMENT_TYPE:N',
                    'RECEIPT_DATE:S:RECEIPT_DATE:N',
                    'WH_NO:S:WH_NO:N',
                    'INVOICE_NO:S:INVOICE_NO:N',
                    'INVOICE_DATE:S:INVOICE_DATE:N',
                    'DOCUMENT_NO:S:DOCUMENT_NO:N',
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'SUPPLIER_NAME:S:SUPPLIER_NAME:N',
                    'SUPPLIER_ADDRESS:S:SUPPLIER_ADDRESS:N',
                    'SUPPLIER_ID:REFID:SUPPLIER_ID:N',
                    'SUPPLIER_TAX_ID:S:SUPPLIER_TAX_ID:N',
                    'EXPENSE_REVENUE_AMT:NZ:EXPENSE_REVENUE_AMT:N',
                    'WH_GROUP:REFID:WH_GROUP:N',
                    'WH_PAY_TYPE:REFID:WH_PAY_TYPE:N',
                    'SUPPLIER_REV_TYPE:REFID:SUPPLIER_REV_TYPE:N',
                    'WH_PCT:NZ:WH_PCT:N',
                    'WH_AMOUNT:NZ:WH_AMOUNT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For delete by parent
                    'TAX_DOC_ID:SPK:TAX_DOC_ID:Y'
                  ],                           
    );

    private $froms = array(

                'FROM TAX_DOCUMENT_REV3_53 ',
                'FROM TAX_DOCUMENT_REV3_53 ',
    );

    private $orderby = array(

                'ORDER BY TAX_RV3_53_ID ASC ',
                'ORDER BY TAX_RV3_53_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'TAX_DOCUMENT_REV3_53', 'TAX_RV3_53_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>