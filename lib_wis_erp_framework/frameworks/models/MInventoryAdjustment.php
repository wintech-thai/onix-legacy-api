<?php
/* 
Purpose : Model for ACCOUNT_DOC_PAYMENT
Created By : Seubpong Monsar
Created Date : 11/26/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MInventoryAdjustment extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'INVENTORY_ADJ_ID:SPK:INVENTORY_ADJ_ID:Y',
                    'DOC_ID:REFID:DOC_ID:Y',
                    'ITEM_ID:REFID:ITEM_ID:Y',
                    'QUANTITY:NZ:QUANTITY:N',
                    'AMOUNT:NZ:AMOUNT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get Item List
                    'IA.INVENTORY_ADJ_ID:SPK:INVENTORY_ADJ_ID:Y',
                    'IA.DOC_ID:REFID:DOC_ID:Y',
                    'IA.ITEM_ID:REFID:ITEM_ID:Y',
                    'IA.QUANTITY:NZ:QUANTITY:N',
                    'IA.AMOUNT:NZ:AMOUNT:N',
                    
                    'IT.ITEM_ID:SPK:ITEM_ID:Y', 
                    'IT.ITEM_CODE:S:ITEM_CODE:Y', 
                    'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y', 
                    'IT.ITEM_TYPE:REFID:ITEM_TYPE:Y',                    
                  ],

                  [ # 2 For Delete by parent
                    'DOC_ID:SPK:DOC_ID:Y',
                  ], 
    );

    private $froms = array(

                'FROM INVENTORY_ADJUSTMENT ',

                'FROM INVENTORY_ADJUSTMENT IA ' .
                    'LEFT OUTER JOIN ITEM IT ON (IA.ITEM_ID = IT.ITEM_ID) ',

                'FROM INVENTORY_ADJUSTMENT ',                        
    );

    private $orderby = array(

                'ORDER BY INVENTORY_ADJ_ID ASC ',
                
                'ORDER BY IA.INVENTORY_ADJ_ID ASC ',
                
                'ORDER BY INVENTORY_ADJ_ID ASC ',         
    );

    function __construct($db) 
    {
        parent::__construct($db, 'INVENTORY_ADJUSTMENT', 'INVENTORY_ADJ_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>