<?php
/* 
    Purpose : Model for 
    Created By : Seubpong Monsar
    Created Date : 09/04/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MUserVariable extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update, delete
        'VARIABLE_ID:SPK:VARIABLE_ID:Y',
        'VARIABLE_NAME:S:VARIABLE_NAME:N',
        'VARIABLE_VALUE:S:VARIABLE_VALUE:N',
        'USER_ID:REFID:USER_ID:Y',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N'
      ],

      [ # For Get User List
        'UV.VARIABLE_ID:SPK:VARIABLE_ID:Y',
        'UV.VARIABLE_NAME:S:VARIABLE_NAME:Y',
        'UV.VARIABLE_VALUE:S:VARIABLE_VALUE:N',
        'UV.USER_ID:REFID:USER_ID:Y',
      ],

      [ # 2 For delete from parent 
        'USER_ID:SPK:USER_ID:Y',
      ],
    );

    private $froms = array(
        'FROM USER_VARIABLE ',
        'FROM USER_VARIABLE UV ',
        'FROM USER_VARIABLE ',
    );

    private $orderby = array(
        'ORDER BY VARIABLE_ID ASC ',
        '',
        '',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'USER_VARIABLE', 'VARIABLE_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>