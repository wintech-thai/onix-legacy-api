<?php
/* 
    Purpose : Model for Package Period
    Created By : Seubpong Monsar
    Created Date : 09/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPackageFinalDiscount extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update, delete
        'PACKAGE_FNLDISC_ID:SPK:PACKAGE_FNLDISC_ID:Y',
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
          'PACKAGE_ID:SPK:PACKAGE_ID:Y',
      ],
      [
        'PF.PACKAGE_FNLDISC_ID:SPK:PACKAGE_FNLDISC_ID:Y',
        'PF.PACKAGE_ID:REFID:PACKAGE_ID:Y',
        'PF.SERVICE_ID:REFID:SERVICE_ID:Y',
        'PF.ITEM_ID:REFID:ITEM_ID:Y',
        'PF.ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',
        'PF.ENABLE_FLAG:S:ENABLE_FLAG:N',
        'PF.QUANTITY:N:QUANTITY:N',
        'PF.SELECTION_TYPE:N:SELECTION_TYPE:N',

        'SV.SERVICE_CODE:S:SERVICE_CODE:N',
        'SV.SERVICE_NAME:S:SERVICE_NAME:N',

        'IT.ITEM_CODE:S:ITEM_CODE:N',
        'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',

        'IC.CATEGORY_NAME:S:CATEGORY_NAME:N',
      ]
);

    private $froms = array(
        'FROM PACKAGE_FINAL_DISCOUNT ',
        'FROM PACKAGE_FINAL_DISCOUNT ',
        'FROM PACKAGE_FINAL_DISCOUNT PF '.
            'LEFT OUTER JOIN SERVICE SV ON (SV.SERVICE_ID = PF.SERVICE_ID) '.
            'LEFT OUTER JOIN ITEM IT ON (IT.ITEM_ID = PF.ITEM_ID) '.
            'LEFT OUTER JOIN ITEM_CATEGORY IC ON (IC.ITEM_CATEGORY_ID = PF.ITEM_CATEGORY) '
    );

    private $orderby = array(
        'ORDER BY PACKAGE_FNLDISC_ID ASC ',
        'ORDER BY PACKAGE_FNLDISC_ID ASC ',
        'ORDER BY PF.PACKAGE_FNLDISC_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PACKAGE_FINAL_DISCOUNT', 'PACKAGE_FNLDISC_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>