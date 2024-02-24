<?php
/* 
    Purpose : Model for Package Period
    Created By : Seubpong Monsar
    Created Date : 09/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPackageCustomer extends MBaseModel
{
    private $cols = array(
        [ # 0 For Insert, Update, Delete
        'PACKAGE_CUSTOMER_ID:SPK:PACKAGE_CUSTOMER_ID:Y',
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',
        'ENTITY_ID:REFID:ENTITY_ID:N',
        'CUSTOMER_TYPE:REFID:CUSTOMER_TYPE:N',
        'CUSTOMER_GROUP:REFID:CUSTOMER_GROUP:N',
        'ENABLE_FLAG:S:ENABLE_FLAG:N',
        'SELECTION_TYPE:N:SELECTION_TYPE:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N'
    ],
    [ # 1 For delete by parent
       'PACKAGE_ID:SPK:PACKAGE_ID:Y' 
    ],
    [ # 2 For select items by parent
        'PC.PACKAGE_CUSTOMER_ID:SPK:PACKAGE_CUSTOMER_ID:N',
        'PC.PACKAGE_ID:REFID:PACKAGE_ID:Y',
        'PC.ENTITY_ID:REFID:ENTITY_ID:N',
        'PC.CUSTOMER_TYPE:N:CUSTOMER_TYPE:N',
        'PC.CUSTOMER_GROUP:N:CUSTOMER_GROUP:N',
        'PC.ENABLE_FLAG:S:ENABLE_FLAG:N',
        'PC.SELECTION_TYPE:N:SELECTION_TYPE:N',
        'EN.ENTITY_NAME:S:CUSTOMER_NAME:N',
        'EN.ENTITY_CODE:S:CUSTOMER_CODE:N',
        'MR1.CODE:S:CUSTOMER_GROUP_CODE:N',
        'MR1.DESCRIPTION:S:CUSTOMER_GROUP_NAME:N',
        'MR2.CODE:S:CUSTOMER_TYPE_CODE:N',
        'MR2.DESCRIPTION:S:CUSTOMER_TYPE_NAME:N'
    ],
    [ # 3 For For Get Company Package All
        'PACKAGE_CUSTOMER_ID:SPK:PACKAGE_CUSTOMER_ID:Y',
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',
        'ENTITY_ID:REFID:ENTITY_ID:N',
        'CUSTOMER_TYPE:REFID:CUSTOMER_TYPE:N',
        'CUSTOMER_GROUP:REFID:CUSTOMER_GROUP:N',
        'ENABLE_FLAG:S:ENABLE_FLAG:N',
        'SELECTION_TYPE:N:SELECTION_TYPE:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',

        'PACKAGE_ID:INC_SET:PACKAGE_ID_SET:Y'
    ]
);

    private $froms = array(
        'FROM PACKAGE_CUSTOMER ',            
        
        'FROM PACKAGE_CUSTOMER ',       

        'FROM PACKAGE_CUSTOMER PC ' . 
            'LEFT OUTER JOIN MASTER_REF MR1 ON (PC.CUSTOMER_GROUP = MR1.MASTER_ID) '.
            'LEFT OUTER JOIN MASTER_REF MR2 ON (PC.CUSTOMER_TYPE = MR2.MASTER_ID) '.
            'LEFT OUTER JOIN ENTITY EN ON (PC.ENTITY_ID = EN.ENTITY_ID) ',

        'FROM PACKAGE_CUSTOMER '
    );

    private $orderby = array(
        'ORDER BY PACKAGE_CUSTOMER_ID ASC ',   
        
        'ORDER BY PACKAGE_CUSTOMER_ID ASC ',  
        
        'ORDER BY PC.PACKAGE_CUSTOMER_ID ASC ',

        'ORDER BY PACKAGE_CUSTOMER_ID ASC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PACKAGE_CUSTOMER', 'PACKAGE_CUSTOMER_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>