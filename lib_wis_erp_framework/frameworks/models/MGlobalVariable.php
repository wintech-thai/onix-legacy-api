<?php
/* 
    Purpose : Model for GLOBAL_VARIABLE
    Created By : Seubpong Monsar
    Created Date : 11/14/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MGlobalVariable extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update, delete
            'GLOBAL_VARIABLE_ID:SPK:GLOBAL_VARIABLE_ID:Y',
            'VARIABLE_NAME:S:VARIABLE_NAME:Y',
            'VARIABLE_TYPE:N:VARIABLE_TYPE:N',
            'VARIABLE_CATEGORY:N:VARIABLE_CATEGORY:N',
            'VARIABLE_VALUE:S:VARIABLE_VALUE:N',
            'VARIABLE_DESC:S:VARIABLE_DESC:N',

            'CREATE_DATE:CD:CREATE_DATE:N',
            'MODIFY_DATE:MD:MODIFY_DATE:N'
        ],
    );

    private $froms = array(
        'FROM GLOBAL_VARIABLE ',
    );

    private $orderby = array(
        'ORDER BY GLOBAL_VARIABLE_ID ASC ',
        '',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'GLOBAL_VARIABLE', 'GLOBAL_VARIABLE_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>