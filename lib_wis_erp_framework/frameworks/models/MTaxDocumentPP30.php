<?php
/* 
Purpose : Model for PROJECT
Created By : Seubpong Monsar
Created Date : 01/12/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MTaxDocumentPP30 extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'TAX_PP30_ID:SPK:TAX_PP30_ID:Y',
                    'TAX_DOC_ID:REFID:TAX_DOC_ID:Y',
                    
                    'SALE_AMOUNT:NZ:SALE_AMOUNT:N',
                    'SALE_ZERO_PCT_AMOUNT:NZ:SALE_ZERO_PCT_AMOUNT:N',
                    'SALE_EXEMPT_AMOUNT:NZ:SALE_EXEMPT_AMOUNT:N',
                    'SALE_ELIGIBLE_AMOUNT:NZ:SALE_ELIGIBLE_AMOUNT:N',
                    'SALE_VAT_AMOUNT:NZ:SALE_VAT_AMOUNT:N',
                    'PURCHASE_ELIGIBLE_AMOUNT:NZ:PURCHASE_ELIGIBLE_AMOUNT:N',
                    'PURCHASE_VAT_AMOUNT:NZ:PURCHASE_VAT_AMOUNT:N',
                    'VAT_EXTRA_AMOUNT:NZ:VAT_EXTRA_AMOUNT:N',
                    'VAT_CLAIM_AMOUNT:NZ:VAT_CLAIM_AMOUNT:N',
                    'VAT_PREV_FWD_AMOUNT:NZ:VAT_PREV_FWD_AMOUNT:N',
                    'VAT_EXTRA_TOTAL_AMOUNT:NZ:VAT_EXTRA_TOTAL_AMOUNT:N',
                    'VAT_CLAIM_TOTAL_AMOUNT:NZ:VAT_CLAIM_TOTAL_AMOUNT:N',
                    'VAT_EXTRA_GRAND_AMOUNT:NZ:VAT_EXTRA_GRAND_AMOUNT:N',
                    'VAT_CLAIM_GRAND_AMOUNT:NZ:VAT_CLAIM_GRAND_AMOUNT:N',
                    'ADDITIONAL_AMOUNT:NZ:ADDITIONAL_AMOUNT:N',
                    'PENALTY_AMOUNT:NZ:PENALTY_AMOUNT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For delete by parent
                   'TAX_DOC_ID:SPK:TAX_DOC_ID:Y'
                  ],                           
    );

    private $froms = array(

                'FROM TAX_DOCUMENT_PP30 ',
                'FROM TAX_DOCUMENT_PP30 ',
    );

    private $orderby = array(

                'ORDER BY TAX_PP30_ID ASC ',
                'ORDER BY TAX_PP30_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'TAX_DOCUMENT_PP30', 'TAX_PP30_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>