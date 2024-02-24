<?php
/* 
Purpose : Model for ACCOUNT_DOC_DEPOSIT
Created By : Seubpong Monsar
Created Date : 05/01/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MAccountDocDeposit extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'ACT_DOC_DEPOSIT_ID:SPK:ACT_DOC_DEPOSIT_ID:Y',
                    'ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                    'RATIO_TYPE:REFID:RATIO_TYPE:Y',
                    'NOTE:S:NOTE:N',
                    'AMOUNT_PCT:NZ:AMOUNT_PCT:N',
                    'DEPOSIT_AMOUNT:NZ:DEPOSIT_AMOUNT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get Account Doc Payment List
                     'AD.ACT_DOC_DEPOSIT_ID:SPK:ACT_DOC_DEPOSIT_ID:Y',
                     'AD.ACCOUNT_DOC_ID:REFID:ACCOUNT_DOC_ID:Y',
                     'AD.RATIO_TYPE:REFID:RATIO_TYPE:Y',
                     'AD.NOTE:S:NOTE:N',
                     'AD.AMOUNT_PCT:NZ:AMOUNT_PCT:N',
                     'DEPOSIT_AMOUNT:NZ:DEPOSIT_AMOUNT:N',
                  ],

                  [ # 2 For Delete by parent
                    'ACCOUNT_DOC_ID:SPK:ACCOUNT_DOC_ID:Y',
                  ], 
    );

    private $froms = array(

                'FROM ACCOUNT_DOC_DEPOSIT ',

                'FROM ACCOUNT_DOC_DEPOSIT AD ',

                'FROM ACCOUNT_DOC_DEPOSIT ',                               
    );

    private $orderby = array(

                'ORDER BY ACT_DOC_DEPOSIT_ID ASC ',
                
                'ORDER BY AD.ACT_DOC_DEPOSIT_ID ASC ',
                
                'ORDER BY ACT_DOC_DEPOSIT_ID ASC ',         
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ACCOUNT_DOC_DEPOSIT', 'ACT_DOC_DEPOSIT_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>