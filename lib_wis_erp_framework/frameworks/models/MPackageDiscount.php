<?php
/* 
    Purpose : Model for Package discount
    Created By : Seubpong Monsar
    Created Date : 09/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPackageDiscount extends MBaseModel
{
    private $cols = array(
        [ # 0 For Insert, Update, Delete
        'PACKAGE_DISCOUNT_ID:SPK:PACKAGE_DISCOUNT_ID:Y',
        'PACKAGE_ID:REFID:PACKAGE_ID:N',
        'SERVICE_ID:REFID:SERVICE_ID:N',
        'ITEM_ID:REFID:ITEM_ID:N',
        'ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',
        'ENABLE_FLAG:S:ENABLE_FLAG:N',
        'DISCOUNT_VALUE:NZ:DISCOUNT_VALUE:N',
        'DISCOUNT_TYPE:N:DISCOUNT_TYPE:N',
        'SELECTION_TYPE:N:SELECTION_TYPE:N',   
        'PRICING_DEFINITION:S:PRICING_DEFINITION:N',                 
        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N'
    ],
    [ # 1 For delete by parent
       'PACKAGE_ID:SPK:PACKAGE_ID:Y' 
    ],
    [ # 2 For select items by parent
        'PD.PACKAGE_DISCOUNT_ID:SPK:PACKAGE_DISCOUNT_ID:N',
        'PD.PACKAGE_ID:REFID:PACKAGE_ID:Y',
        'PD.SERVICE_ID:REFID:SERVICE_ID:N',
        
        'SV.SERVICE_CODE:S:SERVICE_CODE:N',
        'SV.SERVICE_NAME:S:SERVICE_NAME:N',

        'PD.ITEM_ID:REFID:ITEM_ID:N',
        'IT.ITEM_CODE:S:ITEM_CODE:N',
        'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',

        'PD.ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',
        'CT.CATEGORY_NAME:S:CATEGORY_NAME:N',

        'PD.ENABLE_FLAG:S:ENABLE_FLAG:N',
        'PD.DISCOUNT_VALUE:NZ:DISCOUNT_VALUE:N',
        'PD.DISCOUNT_TYPE:N:DISCOUNT_TYPE:N',
        'PD.SELECTION_TYPE:N:SELECTION_TYPE:N',
        'PD.PRICING_DEFINITION:S:PRICING_DEFINITION:N',                    
    ],
    [ # 3 For For Get Company Package All
        'PACKAGE_DISCOUNT_ID:SPK:PACKAGE_DISCOUNT_ID:Y',
        'PACKAGE_ID:REFID:PACKAGE_ID:N',
        'SERVICE_ID:REFID:SERVICE_ID:N',
        'ITEM_ID:REFID:ITEM_ID:N',
        'ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',
        'ENABLE_FLAG:S:ENABLE_FLAG:N',
        'DISCOUNT_VALUE:NZ:DISCOUNT_VALUE:N',
        'DISCOUNT_TYPE:N:DISCOUNT_TYPE:N',
        'SELECTION_TYPE:N:SELECTION_TYPE:N',                    
        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',

        'PACKAGE_ID:INC_SET:PACKAGE_ID_SET:Y'
    ]
);

    private $froms = array(
        'FROM PACKAGE_DISCOUNT ',            
        
        'FROM PACKAGE_DISCOUNT ',       

        'FROM PACKAGE_DISCOUNT PD ' . 
            'LEFT OUTER JOIN SERVICE SV ON (PD.SERVICE_ID = SV.SERVICE_ID) '.
            'LEFT OUTER JOIN ITEM IT ON (PD.ITEM_ID = IT.ITEM_ID) '.
            'LEFT OUTER JOIN ITEM_CATEGORY CT ON (PD.ITEM_CATEGORY = CT.ITEM_CATEGORY_ID) ',
        'FROM PACKAGE_DISCOUNT '
    );

    private $orderby = array(
        'ORDER BY PACKAGE_DISCOUNT_ID ASC ',   
        
        'ORDER BY PACKAGE_DISCOUNT_ID ASC ',  
        
        'ORDER BY PD.PACKAGE_DISCOUNT_ID ASC ',

        'ORDER BY PACKAGE_DISCOUNT_ID ASC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PACKAGE_DISCOUNT', 'PACKAGE_DISCOUNT_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>