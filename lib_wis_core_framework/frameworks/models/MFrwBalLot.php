<?php
/* 
    Purpose : Model for FRW_BAL_LOT
    Created By : Seubpong Monsar
    Created Date : 09/20/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwBalDocument extends MBaseModel
{
    private $cols = array(
        [ // 0 For insert, update, delete
            "BAL_LOT_ID:SPK:BAL_LOT_ID:Y",
            "BAL_LOT_DATE:S:BAL_LOT_DATE:Y",
            "BAL_LEVEL:N:BAL_LEVEL:Y",
            "BAL_ITEM_ID:N:BAL_ITEM_ID:Y",
            "BAL_OWNER_ID:N:BAL_OWNER_ID:Y",
            "LOT_NO:S:LOT_NO:N",
            "LOT_DESC:S:LOT_DESC:N",
            "LOT_EXPIRE_DATE:S:LOT_EXPIRE_DATE:N",
            "QUANTITY:N:QUANTITY:N",
            "AMOUNT:N:AMOUNT:N",
            "PRICE:N:PRICE:N",
            "ANNONYMOUS_FLAG:S:ANNONYMOUS_FLAG:Y",

            "CREATE_DATE:CD:CREATE_DATE:N",
            "MODIFY_DATE:MD:MODIFY_DATE:N"
        ]
    );

    private $froms = array(
        'FROM FRW_BAL_LOT '
    );

    private $orderby = array(
        'ORDER BY BAL_LOT_ID DESC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_BAL_LOT', 'BAL_LOT_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>