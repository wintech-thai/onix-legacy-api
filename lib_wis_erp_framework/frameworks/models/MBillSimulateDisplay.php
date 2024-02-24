<?php
/* 
Purpose : Model for BILL_SIMULATE_DISPLAY
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MBillSimulateDisplay extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'BILL_SIM_DISPLAY_ID:SPK:BILL_SIM_DISPLAY_ID:Y',
                    'BILL_SIMULATE_ID:REFID:BILL_SIMULATE_ID:Y',
                    'SELECTION_TYPE:N:SELECTION_TYPE:N',
                    'SERVICE_ID:REFID:SERVICE_ID:N',
                    'ITEM_ID:REFID:ITEM_ID:N',
                    'CODE:S:CODE:N',
                    'NAME:S:NAME:N',
                    'IS_TRAY:S:IS_TRAY:N',
                    'IS_PRICED:S:IS_PRICED:N',
                    'TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N',
                    'DISCOUNT:NZ:DISCOUNT:N',
                    'AMOUNT:NZ:AMOUNT:N',
                    'QUANTITY:NZ:QUANTITY:N',
                    'BASKET_TYPE:N:BASKET_TYPE:N',
                    'BUNDLE_AMOUNT:NZ:BUNDLE_AMOUNT:N',
                    'GROUP_NO:N:GROUP_NO:N',
                    'PROMOTION_CODE:S:PROMOTION_CODE:N',
                    'PROMOTION_NAME:S:PROMOTION_NAME:N',
                    'DISPLAY_CATEGORY:N:DISPLAY_CATEGORY:Y',                    
                    'VOUCHER_ID:REFID:VOUCHER_ID:N',
                    'FREE_TEXT:S:FREE_TEXT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                ],
                
                [ # 1 For delete by parent
                   'BILL_SIMULATE_ID:SPK:BILL_SIMULATE_ID:Y' 
                ],
                
                [ # 2 For select items by parent
                    'BD.BILL_SIM_DISPLAY_ID:SPK:BILL_SIM_DISPLAY_ID:Y',
                    'BD.BILL_SIMULATE_ID:REFID:BILL_SIMULATE_ID:Y',
                    'BD.SELECTION_TYPE:N:SELECTION_TYPE:N',
                    'BD.SERVICE_ID:REFID:SERVICE_ID:N',
                    'BD.ITEM_ID:REFID:ITEM_ID:N',
                    'BD.CODE:S:CODE:N',
                    'BD.NAME:S:NAME:N',
                    'BD.IS_TRAY:S:IS_TRAY:N',
                    'BD.IS_PRICED:S:IS_PRICED:N',
                    'BD.TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N',
                    'BD.DISCOUNT:NZ:DISCOUNT:N',
                    'BD.AMOUNT:NZ:AMOUNT:N',
                    'BD.QUANTITY:NZ:QUANTITY:N',
                    'BD.BASKET_TYPE:N:BASKET_TYPE:N',
                    'BD.BUNDLE_AMOUNT:NZ:BUNDLE_AMOUNT:N',
                    'BD.GROUP_NO:N:GROUP_NO:N',
                    'BD.PROMOTION_CODE:S:PROMOTION_CODE:N',
                    'BD.PROMOTION_NAME:S:PROMOTION_NAME:N',
                    'BD.DISPLAY_CATEGORY:N:DISPLAY_CATEGORY:Y',                     
                    'BD.VOUCHER_ID:REFID:VOUCHER_ID:N',
                    'BD.FREE_TEXT:S:FREE_TEXT:N',

                    'SV.SERVICE_CODE:S:SERVICE_CODE:N',
                    'SV.SERVICE_NAME:S:SERVICE_NAME:N',
                    'SV.PRICING_DEFINITION:S:SERVICE_PRICING_DEFINITION:N',
                    
                    'IT.ITEM_CODE:S:ITEM_CODE:N',
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',
                    'IT.ITEM_CATEGORY:N:ITEM_CATEGORY:N',
                    'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
                    'IT.DEFAULT_SELL_PRICE:S:DEFAULT_SELL_PRICE_ITEM:N',

                    'VT.VC_TEMPLATE_NO:S:VOUCHER_CODE:N',
                    'VT.VC_TEMPLATE_NNAME:S:VOUCHER_NAME:N',                  
                ],                
              
    );

    private $froms = array(

                'FROM BILL_SIMULATE_DISPLAY ',            
                
                'FROM BILL_SIMULATE_DISPLAY ',   
                
                'FROM BILL_SIMULATE_DISPLAY BD ' . 
                    'LEFT OUTER JOIN SERVICE SV ON (BD.SERVICE_ID = SV.SERVICE_ID) '.
                    'LEFT OUTER JOIN ITEM IT ON (BD.ITEM_ID = IT.ITEM_ID) ' .
                    'LEFT OUTER JOIN VOUCHER_TEMPLATE VT ON (BD.VOUCHER_ID = VT.VOUCHER_TEMPLATE_ID) '                  
            
    );

    private $orderby = array(

                   'ORDER BY BILL_SIM_DISPLAY_ID ASC ',   
                   
                   'ORDER BY BILL_SIM_DISPLAY_ID ASC ',  
                   
                   'ORDER BY BILL_SIM_DISPLAY_ID ASC ',                     
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'BILL_SIMULATE_DISPLAY', 'BILL_SIM_DISPLAY_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>