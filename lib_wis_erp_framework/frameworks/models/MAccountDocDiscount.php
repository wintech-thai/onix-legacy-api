<?php
/* 
Purpose : Model for ACCOUNT_DOC_DISCOUNT
Created By : Seubpong Monsar
Created Date : 04/18/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MAccountDocDiscount extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'ACT_DOC_DISCOUNT_ID:SPK:ACT_DOC_DISCOUNT_ID:Y',
                    'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'DISCOUNT_TYPE:REFID:DISCOUNT_TYPE:Y',
                    'NOTE:S:NOTE:N',
                    'DISCOUNT_AMOUNT:NZ:DISCOUNT_AMOUNT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get Account Doc Payment List
                     'AD.ACT_DOC_DISCOUNT_ID:SPK:ACT_DOC_DISCOUNT_ID:Y',
                     'AD.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                     'AD.DISCOUNT_TYPE:REFID:DISCOUNT_TYPE:Y',
                     'AD.NOTE:S:NOTE:N',
                     'AD.DISCOUNT_AMOUNT:NZ:DISCOUNT_AMOUNT:N',
                  ],

                  [ # 2 For Delete by parent
                    'ACCOUNT_DOC_ID:SPK:ACCOUNT_DOC_ID:Y',
                  ], 
    );

    private $froms = array(

                'FROM ACCOUNT_DOC_DISCOUNT ',

                'FROM ACCOUNT_DOC_DISCOUNT AD ',

                'FROM ACCOUNT_DOC_DISCOUNT ',                               
    );

    private $orderby = array(

                'ORDER BY ACT_DOC_DISCOUNT_ID ASC ',
                
                'ORDER BY AD.ACT_DOC_DISCOUNT_ID ASC ',
                
                'ORDER BY ACT_DOC_DISCOUNT_ID ASC ',         
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ACCOUNT_DOC_DISCOUNT', 'ACT_DOC_DISCOUNT_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>