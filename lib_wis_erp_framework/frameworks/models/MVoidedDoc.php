<?php
/*
Purpose : Model for VOIDED_DOCUMENT
Created By : Seubpong Monsar
Created Date : 01/16/2018 (MM/DD/YYYY)
IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MVoidedDoc extends MBaseModel
{
    private $cols = array(

        [ # 0 For insert, update, delete
            'VOIDED_DOC_ID:SPK:VOIDED_DOC_ID:Y',
            'DOCUMENT_NO:S:DOCUMENT_NO:N',
            'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
            'NOTE:S:NOTE:Y',
            'CANCEL_REASON:REFID:CANCEL_REASON:Y',
            'ALLOW_AR_AP_NEGATIVE:S:ALLOW_AR_AP_NEGATIVE:N',
            'ALLOW_INVENTORY_NEGATIVE:S:ALLOW_INVENTORY_NEGATIVE:N',
            'ALLOW_CASH_NEGATIVE:S:ALLOW_CASH_NEGATIVE:N',              

            'CREATE_DATE:CD:CREATE_DATE:N',
            'MODIFY_DATE:MD:MODIFY_DATE:N',
        ],

        [ # For Get voided doc List
            'VD.VOIDED_DOC_ID:SPK:VOIDED_DOC_ID:Y',
            'VD.DOCUMENT_NO:S:DOCUMENT_NO:N',
            'VD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
            'VD.NOTE:S:NOTE:Y',
            'VD.CANCEL_REASON:REFID:CANCEL_REASON:Y',
            'VD.ALLOW_AR_AP_NEGATIVE:S:ALLOW_AR_AP_NEGATIVE:N',
            'VD.ALLOW_INVENTORY_NEGATIVE:S:ALLOW_INVENTORY_NEGATIVE:N',
            'VD.ALLOW_CASH_NEGATIVE:S:ALLOW_CASH_NEGATIVE:N',     

            # Always put these at the end
            'VD..DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
            'VD..DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
            'VD..DELIVERY_DATE:FD:FROM_DELIVERY_DATE:Y',
            'VD..DELIVERY_DATE:TD:TO_DELIVERY_DATE:Y',
        ],
    );

    private $froms = array(
        
        'FROM VOIDED_DOCUMENT ',

        'FROM VOIDED_DOCUMENT VD ',
    );

    private $orderby = array(

        'ORDER BY VOIDED_DOC_ID ASC ',
        'ORDER BY VD.VOIDED_DOC_ID DESC ',
    );

    function __construct($db)
    {
        parent::__construct($db, 'VOIDED_DOCUMENT', 'VOIDED_DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>