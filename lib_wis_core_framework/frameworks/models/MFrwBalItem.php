<?php
/* 
    Purpose : Model for FRW_BAL_ITEM
    Created By : Seubpong Monsar
    Created Date : 09/04/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwBalItem extends MBaseModel
{
    private $cols = array(
        [ // 0 For insert, update, delete
            "BAL_ITEM_ID:SPK:BAL_ITEM_ID:Y",
            "BAL_ITEM_CODE:S:BAL_ITEM_CODE:Y",
            "BAL_ITEM_NAME:S:BAL_ITEM_NAME:Y",
            "BAL_ITEM_TYPE:N:BAL_ITEM_TYPE:Y",
            "REF1:REFID:REF1:Y",
            "REF2:REFID:REF2:Y",
            "REF3:REFID:REF3:Y",
            "ACTUAL_ID:N:ACTUAL_ID:Y",

            "CREATE_DATE:CD:CREATE_DATE:N",
            "MODIFY_DATE:MD:MODIFY_DATE:N"
        ]
    );

    private $froms = array(
        'FROM FRW_BAL_ITEM '
    );

    private $orderby = array(
        'ORDER BY BAL_ITEM_ID DESC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_BAL_ITEM', 'BAL_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>