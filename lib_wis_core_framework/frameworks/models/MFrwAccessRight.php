<?php
/* 
Purpose : Model for FRW_ACCESS_RIGHT
Created By : Seubpong Monsar
Created Date : 10/30/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwAccessRight extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'ACCESS_RIGHT_ID:SPK:ACCESS_RIGHT_ID:Y',
                    'ACCESS_RIGHT_CODE:S:ACCESS_RIGHT_CODE:Y',
                    'RIGHT_DESCRIPTION:S:RIGHT_DESCRIPTION:N',
                    'ACCESS_CATEGORY:S:ACCESS_CATEGORY:Y',
                    'ACCESS_NS:S:ACCESS_NS:Y',
                    'DEFAULT_VALUE:S:DEFAULT_VALUE:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],           
    );

    private $froms = array(

            'FROM FRW_ACCESS_RIGHT ',
    );

    private $orderby = array(

            'ORDER BY ACCESS_RIGHT_CODE ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_ACCESS_RIGHT', 'ACCESS_RIGHT_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>