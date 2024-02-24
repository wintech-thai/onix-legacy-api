<?php
/* 
    Purpose : Model for Package Period
    Created By : Seubpong Monsar
    Created Date : 09/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPackageTrayPrice extends MBaseModel
{
    private $cols = array(
        [ # 0 For Insert, Update, Delete
        'PACKAGE_TRAY_PRICE_ID:SPK:PACKAGE_TRAY_PRICE_ID:Y', 
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',                    
        'SERVICE_ID:REFID:SERVICE_ID:Y', 
        'ITEM_ID:REFID:ITEM_ID:Y',                    
        'CATEGORY_ID:REFID:CATEGORY_ID:N',                    
        'PRODUCT_TYPE:REFID:PRODUCT_TYPE:N',                    
        'ITEM_TYPE:NZ:ITEM_TYPE:N',
        'PRICING_DEFINITION:S:PRICING_DEFINITION:N',         
        'DISCOUNT_DEFINITION:S:DISCOUNT_DEFINITION:N',                                
        'PRICING_TYPE:REFID:PRICING_TYPE:N',                    
        'SEQUENCE_NO:N:SEQUENCE_NO:N', 
        'LINEAR_UNIT_PRICE:NZ:LINEAR_UNIT_PRICE:N',                                        
        'ENABLE_FLAG:S:ENABLE_FLAG:N',     
        'STD_PRICE_FLAG:S:STD_PRICE_FLAG:N',
                                              
        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N'
       ],    
       
       [ # 1 For delete by parent
        'PACKAGE_ID:SPK:PACKAGE_ID:Y' 
       ],  
       
       [ # 2 For select items by parent
        'PP.PACKAGE_TRAY_PRICE_ID:SPK:PACKAGE_TRAY_PRICE_ID:Y', 
        'PP.PACKAGE_ID:REFID:PACKAGE_ID:Y',                    
        'PP.SERVICE_ID:REFID:SERVICE_ID:N', 
        'PP.ITEM_ID:REFID:ITEM_ID:N',                    
        'PP.CATEGORY_ID:REFID:CATEGORY_ID:N',                    
        'PP.PRODUCT_TYPE:REFID:PRODUCT_TYPE:Y',                    
        'PP.ITEM_TYPE:REFID:ITEM_TYPE:N',
        'PP.PRICING_DEFINITION:S:PRICING_DEFINITION:N',     
        'PP.DISCOUNT_DEFINITION:S:DISCOUNT_DEFINITION:N',                                  
        'PP.PRICING_TYPE:REFID:PRICING_TYPE:N',                    
        'PP.SEQUENCE_NO:N:SEQUENCE_NO:N', 
        'PP.LINEAR_UNIT_PRICE:NZ:LINEAR_UNIT_PRICE:N', 
        'PP.ENABLE_FLAG:S:ENABLE_FLAG:N',      
        'PP.STD_PRICE_FLAG:S:STD_PRICE_FLAG:N',                                                   
        'SV.SERVICE_CODE:S:SERVICE_CODE:N', 
        'SV.SERVICE_NAME:S:SERVICE_NAME:N',                     
        'IT.ITEM_CODE:S:ITEM_CODE:N',
        'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',                   
        'IC.CATEGORY_NAME:S:CATEGORY_NAME:N'                                                  
       ],
       [ # 3 For For Get Company Package All
        'PACKAGE_TRAY_PRICE_ID:SPK:PACKAGE_TRAY_PRICE_ID:Y', 
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',                    
        'SERVICE_ID:REFID:SERVICE_ID:Y', 
        'ITEM_ID:REFID:ITEM_ID:Y',                    
        'CATEGORY_ID:REFID:CATEGORY_ID:N',                    
        'PRODUCT_TYPE:REFID:PRODUCT_TYPE:N',                    
        'ITEM_TYPE:NZ:ITEM_TYPE:N',
        'PRICING_DEFINITION:S:PRICING_DEFINITION:N',                   
        'PRICING_TYPE:REFID:PRICING_TYPE:N',                    
        'SEQUENCE_NO:N:SEQUENCE_NO:N', 
        'LINEAR_UNIT_PRICE:NZ:LINEAR_UNIT_PRICE:N',                                        
        
        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',

        'PACKAGE_ID:INC_SET:PACKAGE_ID_SET:Y'
       ]
);

    private $froms = array(
        'FROM PACKAGE_TRAY_PRICE ',            
        
        'FROM PACKAGE_TRAY_PRICE ',       

        'FROM PACKAGE_TRAY_PRICE PP  ' . 
            'LEFT OUTER JOIN ITEM IT ON (PP.ITEM_ID = IT.ITEM_ID) ' .    
            'LEFT OUTER JOIN SERVICE SV ON (PP.SERVICE_ID = SV.SERVICE_ID) ' . 
            'LEFT OUTER JOIN ITEM_CATEGORY IC ON (PP.CATEGORY_ID = IC.ITEM_CATEGORY_ID) ',
            
         'FROM PACKAGE_TRAY_PRICE '
    );

    private $orderby = array(
        'ORDER BY SEQUENCE_NO ASC ',   
        
        'ORDER BY SEQUENCE_NO ASC ',  
        
        'ORDER BY PACKAGE_TRAY_PRICE_ID ASC ',

        'ORDER BY SEQUENCE_NO ASC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PACKAGE_TRAY_PRICE', 'PACKAGE_TRAY_PRICE_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>