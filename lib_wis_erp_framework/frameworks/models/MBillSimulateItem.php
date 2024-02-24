<?php
/* 
Purpose : Model for BILL_SIM_ITEM
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MBillSimulateItem extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'BILL_SIM_ITEM_ID:SPK:BILL_SIM_ITEM_ID:Y',
                    'BILL_SIMULATE_ID:REFID:BILL_SIMULATE_ID:Y',
                    'SERVICE_ID:REFID:SERVICE_ID:N',
                    'ITEM_ID:REFID:ITEM_ID:N',
                    'QUANTITY:NZ:QUANTITY:N',
                    'SELECTION_TYPE:N:SELECTION_TYPE:N',
                    'ENABLE_FLAG:S:ENABLE_FLAG:N',
                    'TRAY_FLAG:S:TRAY_FLAG:N',
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                ],
                
                [ # 1 For delete by parent
                   'BILL_SIMULATE_ID:SPK:BILL_SIMULATE_ID:Y' 
                ],
                
                [ # 2 For select items by parent
                    'BI.BILL_SIM_ITEM_ID:SPK:BILL_SIM_ITEM_ID:N',
                    'BI.BILL_SIMULATE_ID:REFID:BILL_SIMULATE_ID:Y',

                    'BI.SERVICE_ID:REFID:SERVICE_ID:N',
                    'SV.SERVICE_CODE:S:SERVICE_CODE:N',
                    'SV.SERVICE_NAME:S:SERVICE_NAME:N',
                    'SV.PRICING_DEFINITION:S:SERVICE_PRICING_DEFINITION:N',
                    
                    'BI.ITEM_ID:REFID:ITEM_ID:N',
                    'IT.ITEM_CODE:S:ITEM_CODE:N',
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',
                    'IT.ITEM_CATEGORY:N:ITEM_CATEGORY:N',
                    'IT.PRICING_DEFINITION:S:PRICING_DEFINITION:N',
                    'IT.DEFAULT_SELL_PRICE:S:DEFAULT_SELL_PRICE_ITEM:N',
                    
                    'BI.ENABLE_FLAG:S:ENABLE_FLAG:N',
                    'BI.TRAY_FLAG:S:TRAY_FLAG:N',
                    'BI.QUANTITY:NZ:QUANTITY:N',
                    'BI.SELECTION_TYPE:N:SELECTION_TYPE:N'
                ],                
              
    );

    private $froms = array(

                'FROM BILL_SIM_ITEM ',            
                
                'FROM BILL_SIM_ITEM ',   
                
                'FROM BILL_SIM_ITEM BI ' . 
                    'LEFT OUTER JOIN SERVICE SV ON (BI.SERVICE_ID = SV.SERVICE_ID) '.
                    'LEFT OUTER JOIN ITEM IT ON (BI.ITEM_ID = IT.ITEM_ID) '                  
            
    );

    private $orderby = array(

                   'ORDER BY BILL_SIM_ITEM_ID ASC ',   
                   
                   'ORDER BY BILL_SIM_ITEM_ID ASC ',  
                   
                   'ORDER BY BI.BILL_SIM_ITEM_ID ASC ',                     
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'BILL_SIM_ITEM', 'BILL_SIM_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>