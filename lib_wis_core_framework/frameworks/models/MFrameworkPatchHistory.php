<?php
/* 
    Purpose : Model for FRAMEWORK_PATCH_HISTORY
    Created By : Seubpong Monsar
    Created Date : 09/04/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrameworkPatchHistory extends MBaseModel
{
    private $cols = array(
        [ // 0 For insert, update, delete
            "PATCH_ID:SPK:PATCH_ID:Y",
            "PATCH_DATE:CD:PATCH_DATE:Y",
            "VERSION:S:VERSION:Y",
            "LAST_PATCH_POINT:N:LAST_PATCH_POINT:N",
            "PATCH_FILE:S:PATCH_FILE:N",

            "CREATE_DATE:CD:CREATE_DATE:N",
            "MODIFY_DATE:MD:MODIFY_DATE:N"
        ]
    );

    private $froms = array(
        'FROM FRW_PATCH_HISTORY '
    );

    private $orderby = array(
        'ORDER BY PATCH_ID DESC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_PATCH_HISTORY', 'PATCH_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>