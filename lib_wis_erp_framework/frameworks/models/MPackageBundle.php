<?php
/* 
    Purpose : Model for Package Period
    Created By : Seubpong Monsar
    Created Date : 09/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPackageBundle extends MBaseModel
{
    private $cols = array(
        [ # 0 For Insert, Update, Delete
        'PACKAGE_BUNDLE_ID:SPK:PACKAGE_BUNDLE_ID:Y',
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',
        'SERVICE_ID:REFID:SERVICE_ID:Y',
        'ITEM_ID:REFID:ITEM_ID:Y',
        'ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',
        'ENABLE_FLAG:S:ENABLE_FLAG:N',
        'QUANTITY:N:QUANTITY:N',
        'SELECTION_TYPE:N:SELECTION_TYPE:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',
    ],
    [ # 1 For delete by parent
       'PACKAGE_ID:SPK:PACKAGE_ID:Y'
    ],
    [ # 2 For select items by parent
        'PB.PACKAGE_BUNDLE_ID:SPK:PACKAGE_BUNDLE_ID:Y',
        'PB.PACKAGE_ID:REFID:PACKAGE_ID:Y',
        'PB.SERVICE_ID:REFID:SERVICE_ID:Y',
        'PB.ITEM_ID:REFID:ITEM_ID:Y',
        'PB.ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',
        'PB.ENABLE_FLAG:S:ENABLE_FLAG:N',
        'PB.QUANTITY:N:QUANTITY:N',
        'PB.SELECTION_TYPE:N:SELECTION_TYPE:N',
        
        'SV.SERVICE_CODE:S:SERVICE_CODE:N',
        'SV.SERVICE_NAME:S:SERVICE_NAME:N',
        
        'IT.ITEM_CODE:S:ITEM_CODE:N',
        'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',
        
        'IC.CATEGORY_NAME:S:CATEGORY_NAME:N',                                                            
    ],
    [ # 3 For For Get Company Package All
        'PACKAGE_BUNDLE_ID:SPK:PACKAGE_BUNDLE_ID:Y',
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',
        'SERVICE_ID:REFID:SERVICE_ID:Y',
        'ITEM_ID:REFID:ITEM_ID:Y',
        'ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',
        'ENABLE_FLAG:S:ENABLE_FLAG:N',
        'QUANTITY:N:QUANTITY:N',
        'SELECTION_TYPE:N:SELECTION_TYPE:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',

        'PACKAGE_ID:INC_SET:PACKAGE_ID_SET:Y',
    ]
);

    private $froms = array(
        'FROM PACKAGE_BUNDLE ',
        'FROM PACKAGE_BUNDLE ',
        'FROM PACKAGE_BUNDLE PB '.
            'LEFT OUTER JOIN SERVICE SV ON (PB.SERVICE_ID = SV.SERVICE_ID) '.
            'LEFT OUTER JOIN ITEM IT ON (PB.ITEM_ID = IT.ITEM_ID) '.
            'LEFT OUTER JOIN ITEM_CATEGORY IC ON (PB.ITEM_CATEGORY = IC.ITEM_CATEGORY_ID) ',
        'FROM PACKAGE_BUNDLE ',
    );

    private $orderby = array(
        'ORDER BY PACKAGE_BUNDLE_ID ASC ',
        'ORDER BY PACKAGE_BUNDLE_ID ASC ',
        'ORDER BY PB.PACKAGE_BUNDLE_ID ASC',
        'ORDER BY PACKAGE_BUNDLE_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PACKAGE_BUNDLE', 'PACKAGE_BUNDLE_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>