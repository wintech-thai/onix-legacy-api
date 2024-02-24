<?php
/* 
    Purpose : Model for FRW_BAL_DOCUMENT
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
            "BAL_DOC_ID:SPK:BAL_DOC_ID:Y",
            "BAL_DOC_NO:S:BAL_DOC_NO:Y",
            "BAL_DOC_DATE:S:BAL_DOC_DATE:Y",
            "BAL_DOC_NOTE:S:BAL_DOC_NOTE:Y",
            "BAL_ITEM_TYPE:N:BAL_ITEM_TYPE:Y",
            "BAL_OWNER_TYPE:N:BAL_OWNER_TYPE:Y",
            "BAL_DOC_TYPE:N:BAL_DOC_TYPE:Y",
            "ACTUAL_ID:N:ACTUAL_ID:Y",
            "ACTUAL_DOC_DATE:S:ACTUAL_DOC_DATE:N",

            "CREATE_DATE:CD:CREATE_DATE:N",
            "MODIFY_DATE:MD:MODIFY_DATE:N"
        ]
    );

    private $froms = array(
        'FROM FRW_BAL_DOCUMENT '
    );

    private $orderby = array(
        'ORDER BY BAL_DOC_ID DESC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_BAL_DOCUMENT', 'BAL_DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>