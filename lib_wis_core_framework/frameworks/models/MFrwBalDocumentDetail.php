<?php
/* 
    Purpose : Model for FRW_BAL_DOCUMENT_DETAIL
    Created By : Seubpong Monsar
    Created Date : 09/20/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwBalDocumentDetail extends MBaseModel
{
    private $cols = array(
        [ // 0 For insert, update, delete
            "BAL_DOC_DTL_ID:SPK:BAL_DOC_DTL_ID:Y",
            "BAL_DOC_ID:N:BAL_DOC_ID:Y",
            "BAL_ITEM_ID:N:BAL_ITEM_ID:Y",
            "BAL_OWNER_ID:N:BAL_OWNER_ID:Y",
            "DIRECTION:S:DIRECTION:Y",
            "ACTUAL_ID:N:ACTUAL_ID:Y",

            "BEGIN_QTY_AVG:NZ:BEGIN_QTY_AVG:N",
            "BEGIN_AMOUNT_AVG:NZ:BEGIN_AMOUNT_AVG:N",
            "BEGIN_QTY_FIFO:NZ:BEGIN_QTY_FIFO:N",
            "BEGIN_AMOUNT_FIFO:NZ:BEGIN_AMOUNT_FIFO:N",

            "TX_QTY_AVG:NZ:TX_QTY_AVG:N",
            "TX_AMT_AVG:NZ:TX_AMT_AVG:N",
            "TX_QTY_FIFO:NZ:TX_QTY_FIFO:N",
            "TX_AMT_FIFO:NZ:TX_AMT_FIFO:N",            

            "END_QTY_AVG:NZ:END_QTY_AVG:N",
            "END_AMOUNT_AVG:NZ:END_AMOUNT_AVG:N",
            "END_QTY_FIFO:NZ:END_QTY_FIFO:N",
            "END_AMOUNT_FIFO:NZ:END_AMOUNT_FIFO:N",

            "LOT_NO:S:LOT_NO:N",
            "LOT_DESC:S:LOT_DESC:N",
            "LOT_EXPIRE_DATE:S:LOT_EXPIRE_DATE:N",

            "GROUP_ID:NZ:GROUP_ID:Y",

            "CREATE_DATE:CD:CREATE_DATE:N",
            "MODIFY_DATE:MD:MODIFY_DATE:N"
        ]
    );

    private $froms = array(
        'FROM FRW_BAL_DOCUMENT_DETAIL '
    );

    private $orderby = array(
        'ORDER BY BAL_DOC_DTL_ID DESC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_BAL_DOCUMENT_DETAIL', 'BAL_DOC_DTL_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>