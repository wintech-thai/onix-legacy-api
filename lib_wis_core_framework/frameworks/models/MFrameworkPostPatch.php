<?php
/* 
    Purpose : Model for FRAMEWORK_PATCH_HISTORY
    Created By : Seubpong Monsar
    Created Date : 09/04/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrameworkPostPatch extends MBaseModel
{
    private $cols = array(
        [ // 0 For insert, update, delete
            "PATCH_POST_ID:SPK:PATCH_POST_ID:Y",
            "PATCH_DATE:CD:PATCH_DATE:Y",
            "FUNCTION_NAME:S:FUNCTION_NAME:Y",
            "FILE_NAME:S:FILE_NAME:Y",

            "CREATE_DATE:CD:CREATE_DATE:N",
            "MODIFY_DATE:MD:MODIFY_DATE:N"
        ]
    );

    private $froms = array(
        'FROM FRW_PATCH_POST '
    );

    private $orderby = array(
        'ORDER BY PATCH_POST_ID ASC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_PATCH_POST', 'PATCH_POST_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>