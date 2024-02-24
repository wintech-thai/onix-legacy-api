<?php
/* 
    Purpose : Model for FRW_BAL_ACCUMULATE
    Created By : Seubpong Monsar
    Created Date : 09/20/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwBalAccumulate extends MBaseModel
{
    private $cols = array(
        [ // 0 For Query
            "BA.BAL_ID:SPK:BAL_ID:Y",
            "BA.BAL_DATE:S:BAL_DATE:Y",
            "BA.BAL_LEVEL:N:BAL_LEVEL:Y",
            "BA.BAL_ITEM_ID:N:BAL_ITEM_ID:Y",
            "BA.BAL_OWNER_ID:N:BAL_OWNER_ID:Y",

            "BA.BEGIN_QTY_AVG:N:BEGIN_QTY_AVG:N",
            "BA.BEGIN_AMOUNT_AVG:N:BEGIN_AMOUNT_AVG:N",
            "BA.BEGIN_PRICE_AVG:N:BEGIN_PRICE_AVG:N",

            "BA.BEGIN_QTY_FIFO:N:BEGIN_QTY_FIFO:N",
            "BA.BEGIN_AMOUNT_FIFO:N:BEGIN_AMOUNT_FIFO:N",
            "BA.BEGIN_PRICE_FIFO:N:BEGIN_PRICE_FIFO:N",

            "BA.IN_QTY_AVG:N:IN_QTY_AVG:N",
            "BA.IN_AMOUNT_AVG:N:IN_AMOUNT_AVG:N",
            "BA.IN_PRICE_AVG:N:IN_PRICE_AVG:N",

            "BA.IN_QTY_FIFO:N:IN_QTY_FIFO:N",
            "BA.IN_AMOUNT_FIFO:N:IN_AMOUNT_FIFO:N",
            "BA.IN_PRICE_FIFO:N:IN_PRICE_FIFO:N",

            "BA.OUT_QTY_AVG:N:OUT_QTY_AVG:N",
            "BA.OUT_AMOUNT_AVG:N:OUT_AMOUNT_AVG:N",
            "BA.OUT_PRICE_AVG:N:OUT_PRICE_AVG:N",

            "BA.OUT_QTY_FIFO:N:OUT_QTY_FIFO:N",
            "BA.OUT_AMOUNT_FIFO:N:OUT_AMOUNT_FIFO:N",
            "BA.OUT_PRICE_FIFO:N:OUT_PRICE_FIFO:N",

            "BA.END_QTY_AVG:N:END_QTY_AVG:N",
            "BA.END_AMOUNT_AVG:N:END_AMOUNT_AVG:N",
            "BA.END_PRICE_AVG:N:END_PRICE_AVG:N",

            "BA.END_QTY_FIFO:N:END_QTY_FIFO:N",
            "BA.END_AMOUNT_FIFO:N:END_AMOUNT_FIFO:N",
            "BA.END_PRICE_FIFO:N:END_PRICE_FIFO:N",

            "BA.CREATE_DATE:CD:CREATE_DATE:N",
            "BA.MODIFY_DATE:MD:MODIFY_DATE:N",

            "BI.BAL_ITEM_CODE:S:BAL_ITEM_CODE:N",
            "BI.BAL_ITEM_NAME:S:BAL_ITEM_NAME:N",
            "BI.ACTUAL_ID:N:BAL_ITEM_ACTUAL_ID:N",
            "BO.BAL_OWNER_CODE:S:BAL_OWNER_CODE:N",
            "BO.BAL_OWNER_NAME:S:BAL_OWNER_NAME:N",
            "BO.ACTUAL_ID:N:BAL_OWNER_ACTUAL_ID:N",

            //Always put these at the end
            'BA.BAL_DATE:FD:FROM_BAL_DATE:Y',
            'BA.BAL_DATE:TD:TO_BAL_DATE:Y',            
        ],

        [ // 1 For insert, update, delete
            "BAL_ID:SPK:BAL_ID:Y",
            "BAL_DATE:S:BAL_DATE:Y",
            "BAL_LEVEL:N:BAL_LEVEL:Y",
            "BAL_ITEM_ID:N:BAL_ITEM_ID:Y",
            "BAL_OWNER_ID:N:BAL_OWNER_ID:Y",

            "BEGIN_QTY_AVG:N:BEGIN_QTY_AVG:N",
            "BEGIN_AMOUNT_AVG:N:BEGIN_AMOUNT_AVG:N",
            "BEGIN_PRICE_AVG:N:BEGIN_PRICE_AVG:N",

            "BEGIN_QTY_FIFO:N:BEGIN_QTY_FIFO:N",
            "BEGIN_AMOUNT_FIFO:N:BEGIN_AMOUNT_FIFO:N",
            "BEGIN_PRICE_FIFO:N:BEGIN_PRICE_FIFO:N",

            "IN_QTY_AVG:N:IN_QTY_AVG:N",
            "IN_AMOUNT_AVG:N:IN_AMOUNT_AVG:N",
            "IN_PRICE_AVG:N:IN_PRICE_AVG:N",

            "IN_QTY_FIFO:N:IN_QTY_FIFO:N",
            "IN_AMOUNT_FIFO:N:IN_AMOUNT_FIFO:N",
            "IN_PRICE_FIFO:N:IN_PRICE_FIFO:N",

            "OUT_QTY_AVG:N:OUT_QTY_AVG:N",
            "OUT_AMOUNT_AVG:N:OUT_AMOUNT_AVG:N",
            "OUT_PRICE_AVG:N:OUT_PRICE_AVG:N",

            "OUT_QTY_FIFO:N:OUT_QTY_FIFO:N",
            "OUT_AMOUNT_FIFO:N:OUT_AMOUNT_FIFO:N",
            "OUT_PRICE_FIFO:N:OUT_PRICE_FIFO:N",

            "END_QTY_AVG:N:END_QTY_AVG:N",
            "END_AMOUNT_AVG:N:END_AMOUNT_AVG:N",
            "END_PRICE_AVG:N:END_PRICE_AVG:N",

            "END_QTY_FIFO:N:END_QTY_FIFO:N",
            "END_AMOUNT_FIFO:N:END_AMOUNT_FIFO:N",
            "END_PRICE_FIFO:N:END_PRICE_FIFO:N",

            "CREATE_DATE:CD:CREATE_DATE:N",
            "MODIFY_DATE:MD:MODIFY_DATE:N",           
        ]        
    );

    private $froms = array(
        'FROM FRW_BAL_ACCUMULATE BA ' . 
            'LEFT OUTER JOIN FRW_BAL_ITEM BI ON (BA.BAL_ITEM_ID = BI.BAL_ITEM_ID) ' . 
            'LEFT OUTER JOIN FRW_BAL_OWNER BO ON (BA.BAL_OWNER_ID = BO.BAL_OWNER_ID) ' ,

        'FROM FRW_BAL_ACCUMULATE '        
    );

    private $orderby = array(
        'ORDER BY BA.BAL_ID DESC ',
        'ORDER BY BAL_ID DESC '        
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_BAL_ACCUMULATE', 'BAL_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>