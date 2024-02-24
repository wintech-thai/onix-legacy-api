<?php
/* 
    Purpose : Model for FRAMEWORK_PATCH_HISTORY
    Created By : Seubpong Monsar
    Created Date : 09/04/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MLoginHistory extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update, delete
        'LOGIN_ID:SPK:LOGIN_ID:Y',
        'LOGIN_DATE:SD:LOGIN_DATE:N',
        'IP_ADDRESS:S:IP_ADDRESS:N',
        'USER_NAME:S:USER_NAME:N',
        'FAILED_PASSWORD:S:FAILED_PASSWORD:N',
        'LOGIN_SUCCESS:S:LOGIN_SUCCESS:N',
        'ERROR_DESC:S:ERROR_DESC:N',
        'LOGOUT_DATE:S:LOGOUT_DATE:N',
        'SESSION:S:SESSION:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N'
      ],

      [
        'LH.LOGIN_ID:SPK:LOGIN_ID:Y',
        'LH.LOGIN_DATE:S:LOGIN_DATE:Y',
        'LH.IP_ADDRESS:S:IP_ADDRESS:Y',
        'LH.USER_NAME:S:USER_NAME:Y',
        'LH.ERROR_DESC:S:ERROR_DESC:Y',
        'LH.LOGIN_SUCCESS:S:LOGIN_SUCCESS:Y',
        'LH.LOGOUT_DATE:S:LOGOUT_DATE:N',
        'LH.SESSION:S:SESSION:N',

        #Always put these at the end
        'LH.LOGIN_DATE:FD:FROM_LOGIN_DATE:Y',
        'LH.LOGIN_DATE:TD:TO_LOGIN_DATE:Y'
      ],

      [ # 2 Logout
        'LOGIN_ID:SPK:LOGIN_ID:Y',
        'LOGOUT_DATE:S:LOGOUT_DATE:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N'
      ],      
    );

    private $froms = array(
        'FROM LOGIN_HISTORY ',
        'FROM LOGIN_HISTORY LH',
        'FROM LOGIN_HISTORY ',
    );

    private $orderby = array(
        'ORDER BY LOGIN_ID ASC ',
        'ORDER BY LH.LOGIN_ID DESC ',
        'ORDER BY LOGIN_ID DESC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'LOGIN_HISTORY', 'LOGIN_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>