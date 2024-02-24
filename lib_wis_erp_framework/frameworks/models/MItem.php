<?php
/* 
Purpose : Model for ITEM
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For query
                   'IT.ITEM_ID:SPK:ITEM_ID:Y', 
                   'IT.ITEM_CODE:S:ITEM_CODE:Y', 
                   'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
                   'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y', 
                   'IT.ITEM_TYPE:REFID:ITEM_TYPE:Y',
                   'IT.ITEM_CATEGORY:REFID:ITEM_CATEGORY:Y', 
                   'IT.ITEM_UOM:REFID:ITEM_UOM:Y', 
                   'IT.FINISH_GOOD_FLAG:S:FINISH_GOOD_FLAG:Y', 
                   'IT.PART_FLAG:S:PART_FLAG:Y', 
                   'IT.RM_FLAG:S:RM_FLAG:Y', 
                   'IT.PURCHASE_FLAG:S:PURCHASE_FLAG:Y', 
                   'IT.SALE_FLAG:S:SALE_FLAG:Y', 
                   'IT.PRODUCTION_FLAG:S:PRODUCTION_FLAG:Y',
                   'IT.REFERENCE_CODE:S:REFERENCE_CODE:N', 
                   'IT.BRAND:REFID:BRAND:Y', 
                   'IT.NOTE:S:NOTE:Y',
                   'IT.DEFAULT_SELL_PRICE:NZ:DEFAULT_SELL_PRICE:N', 
                   'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
                   'IT.SALE_UOM:REFID:SALE_UOM:Y', 
                   'IT.MINIMUM_ALLOW:N:MINIMUM_ALLOW:N',
                   'IT.PRICE_CATEGORY:REFID:PRICE_CATEGORY:N',
                   'IT.ITEM_LINEAR:REFID:ITEM_LINEAR:N',
                   'IT.VAT_FLAG:S:VAT_FLAG:Y', 
                   'IT.BORROW_FLAG:S:BORROW_FLAG:Y',  

                   'MR1.DESCRIPTION:S:TYPE_NAME:N', 
                   'MR2.DESCRIPTION:S:UOM_NAME:N', 
                   'MR3.DESCRIPTION:S:BRAND_NAME:N', 
                   'MR4.DESCRIPTION:S:SALE_UOM_NAME:N',
                   'IC.CATEGORY_NAME:S:CATEGORY_NAME:N', 
                   
                   'IT.CREATE_DATE:CD:CREATE_DATE:N',
                   'IT.MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                  
                  [ # 1 For delete
                   'ITEM_ID:SPK:ITEM_ID:N'
                  ],   
                  
                  [ # 2 For insert, update
                   'ITEM_ID:SPK:ITEM_ID:Y', 
                   'ITEM_CODE:S:ITEM_CODE:Y', 
                   'ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
                   'ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y', 
                   'ITEM_TYPE:REFID:ITEM_TYPE:Y',
                   'ITEM_CATEGORY:REFID:ITEM_CATEGORY:Y', 
                   'ITEM_UOM:REFID:ITEM_UOM:Y', 
                   'FINISH_GOOD_FLAG:S:FINISH_GOOD_FLAG:Y', 
                   'PART_FLAG:S:PART_FLAG:Y', 
                   'RM_FLAG:S:RM_FLAG:Y', 
                   'PURCHASE_FLAG:S:PURCHASE_FLAG:Y', 
                   'SALE_FLAG:S:SALE_FLAG:Y', 
                   'PRODUCTION_FLAG:S:PRODUCTION_FLAG:Y',
                   'REFERENCE_CODE:S:REFERENCE_CODE:N', 
                   'BRAND:REFID:BRAND:Y', 
                   'NOTE:S:NOTE:Y', 
                   'SALE_UOM:REFID:SALE_UOM:Y', 
                   'MINIMUM_ALLOW:N:MINIMUM_ALLOW:N',
                   'PRICE_CATEGORY:REFID:PRICE_CATEGORY:N',
                   'ITEM_LINEAR:REFID:ITEM_LINEAR:N',
                   'DEFAULT_SELL_PRICE:NZ:DEFAULT_SELL_PRICE:N', 
                   'PRICING_DEFINITION:S:PRICING_DEFINITION:N',                   
                   'VAT_FLAG:S:VAT_FLAG:Y', 
                   'BORROW_FLAG:S:BORROW_FLAG:Y',  

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],       
                                    
                  [ # 3 For count(*)
                   'ITEM_CATEGORY:SPK:ITEM_CATEGORY:N',                  
                   'COUNT(*):N:ITEM_COUNT:N'
                  ],
                  
                  [ # 4 Generic purpose, usually used by Patch functions. Do not modify!!!
                  ],                  
              
    );

    private $froms = array(

                   'FROM ITEM IT ' .
                       'LEFT OUTER JOIN MASTER_REF MR1 ON (IT.ITEM_TYPE = MR1.MASTER_ID) ' .
                       'LEFT OUTER JOIN MASTER_REF MR2 ON (IT.ITEM_UOM = MR2.MASTER_ID) ' .    
                       'LEFT OUTER JOIN MASTER_REF MR3 ON (IT.BRAND = MR3.MASTER_ID) ' .
                       'LEFT OUTER JOIN MASTER_REF MR4 ON (IT.SALE_UOM = MR4.MASTER_ID) ' .
                       'LEFT OUTER JOIN ITEM_CATEGORY IC ON (IT.ITEM_CATEGORY = IC.ITEM_CATEGORY_ID) ',          
                       
                   'FROM ITEM ',
                   
                   'FROM ITEM ',
                   
                   'FROM ITEM GROUP BY (ITEM_CATEGORY) ',

                   'FROM ITEM ',
    );

    private $orderby = array(

                   'ORDER BY IT.ITEM_ID DESC ',     
                   
                   'ORDER BY ITEM_ID DESC ',     
                                      
                   'ORDER BY ITEM_ID DESC ',     
                   
                   'ORDER BY ITEM_CATEGORY DESC ',

                   'ORDER BY ITEM_ID DESC ',                   
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ITEM', 'ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>