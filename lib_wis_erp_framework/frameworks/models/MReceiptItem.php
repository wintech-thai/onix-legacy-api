<?php
/* 
    Purpose : Model for RECEIPT_ITEM
    Created By : Seubpong Monsar
    Created Date : 12/27/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MReceiptItem extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update, delete
            'RECEIPT_ITEM_ID:SPK:RECEIPT_ITEM_ID:Y',
            'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
            'DEBT_BEGIN_AMOUNT:NZ:DEBT_BEGIN_AMOUNT:N',
            'DEBT_END_AMOUNT:NZ:DEBT_END_AMOUNT:N',
            'PAID_AMOUNT:NZ:PAID_AMOUNT:N',
            'DOCUMENT_ID:REFID:DOCUMENT_ID:N',

            'CREATE_DATE:CD:CREATE_DATE:N',
            'MODIFY_DATE:MD:MODIFY_DATE:N'
        ],
    );

    private $froms = array(
        'FROM RECEIPT_ITEM ',
    );

    private $orderby = array(
        'ORDER BY RECEIPT_ITEM_ID ASC ',
        '',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'RECEIPT_ITEM', 'RECEIPT_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>