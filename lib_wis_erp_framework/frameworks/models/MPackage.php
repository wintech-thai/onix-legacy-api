<?php
/* 
    Purpose : Model for Package
    Created By : Seubpong Monsar
    Created Date : 09/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPackage extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update
        'PACKAGE_ID:SPK:PACKAGE_ID:Y',
        'PACKAGE_CODE:S:PACKAGE_CODE:Y',
        'PACKAGE_NAME:S:PACKAGE_NAME:Y',
        'EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
        'EXPIRE_DATE:S:EXPIRE_DATE:N',
        'ENABLE_FLAG:S:ENABLE_FLAG:Y',
        'TIME_SPECIFIC_FLAG:S:TIME_SPECIFIC_FLAG:Y',
        'CUSTOMER_SPECIFIC_FLAG:S:CUSTOMER_SPECIFIC_FLAG:Y',
        'PACKAGE_TYPE:NZ:PACKAGE_TYPE:Y',
        'DISCOUNT_MAP_TYPE:NZ:DISCOUNT_MAP_TYPE:N',
        'BUNDLE_AMOUNT:NZ:BUNDLE_AMOUNT:N',                   
        'DISCOUNT_BASKET_TYPE:NZ:DISCOUNT_BASKET_TYPE:N',
        'PRODUCT_SPECIFIC_FLAG:S:PRODUCT_SPECIFIC_FLAG:N', 
        'DISCOUNT_DEFINITION:S:DISCOUNT_DEFINITION:N',
        'BRANCH_SPECIFIC_FLAG:S:BRANCH_SPECIFIC_FLAG:N', 
        'DISCOUNT_BASKET_TYPE_CONFIG:S:DISCOUNT_BASKET_TYPE_CONFIG:N', 
        'TRAY_NAME:S:TRAY_NAME:N', 
                                             
        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',                                      
       ],

       [ # 1 For query
        'PK.PACKAGE_ID:SPK:PACKAGE_ID:Y',
        'PK.PACKAGE_CODE:S:PACKAGE_CODE:Y',
        'PK.PACKAGE_NAME:S:PACKAGE_NAME:Y',
        'PK.EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
        'PK.EXPIRE_DATE:S:EXPIRE_DATE:N',
        'PK.ENABLE_FLAG:S:ENABLE_FLAG:Y',
        'PK.TIME_SPECIFIC_FLAG:S:TIME_SPECIFIC_FLAG:Y',
        'PK.CUSTOMER_SPECIFIC_FLAG:S:CUSTOMER_SPECIFIC_FLAG:Y',
        'PK.EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
        'PK.EXPIRE_DATE:S:EXPIRE_DATE:N',
        'PK.BUNDLE_AMOUNT:NZ:BUNDLE_AMOUNT:N',                   
        'PK.PACKAGE_TYPE:NZ:PACKAGE_TYPE:Y',
        'PK.DISCOUNT_MAP_TYPE:NZ:DISCOUNT_MAP_TYPE:N',
        'PK.DISCOUNT_BASKET_TYPE:NZ:DISCOUNT_BASKET_TYPE:N',
        'PK.PRODUCT_SPECIFIC_FLAG:S:PRODUCT_SPECIFIC_FLAG:N', 
        'PK.DISCOUNT_DEFINITION:S:DISCOUNT_DEFINITION:N',  
        'PK.BRANCH_SPECIFIC_FLAG:S:BRANCH_SPECIFIC_FLAG:N', 
        'PK.TRAY_NAME:S:TRAY_NAME:N',
        'PK.DISCOUNT_BASKET_TYPE_CONFIG:S:DISCOUNT_BASKET_TYPE_CONFIG:N',                  
        'PTM.PACKAGE_GROUP:N:PACKAGE_GROUP:Y',
        
        'PK.CREATE_DATE:CD:CREATE_DATE:N',
        'PK.MODIFY_DATE:MD:MODIFY_DATE:N',

        #Always put these at the end
        'PK.EFFECTIVE_DATE:FD:FROM_EFFECTIVE_DATE:Y',
        'PK.EFFECTIVE_DATE:TD:TO_EFFECTIVE_DATE:Y',

        'PK.EXPIRE_DATE:FD:FROM_EXPIRE_DATE:Y',
        'PK.EXPIRE_DATE:TD:TO_EXPIRE_DATE:Y'
       ],
       [ # 2 For Get Company Package All
        'PACKAGE_ID:SPK:PACKAGE_ID:N',
        'PACKAGE_CODE:S:PACKAGE_CODE:Y',
        'PACKAGE_NAME:S:PACKAGE_NAME:Y',
        'EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
        'EXPIRE_DATE:S:EXPIRE_DATE:N',
        'ENABLE_FLAG:S:ENABLE_FLAG:Y',
        'TIME_SPECIFIC_FLAG:S:TIME_SPECIFIC_FLAG:Y',
        'CUSTOMER_SPECIFIC_FLAG:S:CUSTOMER_SPECIFIC_FLAG:Y',
        'PACKAGE_TYPE:NZ:PACKAGE_TYPE:Y',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',
        
        #Always put these at the end
        'PACKAGE_ID:INC_SET:PACKAGE_ID_SET:Y',                     
       ],
    );

    private $froms = array(
        'FROM PACKAGE ',
        
        'FROM PACKAGE PK ' .
            'LEFT OUTER JOIN PACKAGE_TYPE_MAP PTM ON (PK.PACKAGE_TYPE = PTM.PACKAGE_TYPE) ',                  

        'FROM PACKAGE '
    );

    private $orderby = array(
        'ORDER BY PACKAGE_ID DESC ',
        
        'ORDER BY PK.PACKAGE_ID DESC ',
        
        'ORDER BY PACKAGE_ID DESC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PACKAGE', 'PACKAGE_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>