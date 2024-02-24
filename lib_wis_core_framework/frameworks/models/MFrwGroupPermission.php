<?php
/* 
Purpose : Model for FRW_GROUP_PERMISSION
Created By : Seubpong Monsar
Created Date : 10/30/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwGroupPermission extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'GROUP_PERMISSION_ID:SPK:GROUP_PERMISSION_ID:Y',
                    'GROUP_ID:REFID:GROUP_ID:Y',
                    'ACCESS_RIGHT_ID:REFID:ACCESS_RIGHT_ID:Y',
                    'IS_ENABLE:S:IS_ENABLE:Y',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],           
    );

    private $froms = array(

            'FROM FRW_GROUP_PERMISSION ',
    );

    private $orderby = array(

            'ORDER BY GROUP_PERMISSION_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_GROUP_PERMISSION', 'GROUP_PERMISSION_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>