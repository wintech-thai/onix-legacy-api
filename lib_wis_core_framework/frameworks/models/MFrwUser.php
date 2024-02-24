<?php
/*
    Purpose : Model for FRAMEWORK_USER
    Created By : Seubpong Monsar
    Created Date : 01/22/2018 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwUser extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, delete
        'USER_ID:SPK:USER_ID:Y',
        'USER_NAME:S:USER_NAME:N',
        'DESCRIPTION:S:DESCRIPTION:N',
        'PASSWORD:S:PASSWORD:N',
        'IS_ENABLE:S:IS_ENABLE:N',
        'IS_ADMIN:S:IS_ADMIN:N',
        'GROUP_ID:REFID:GROUP_ID:N',
        'IS_INTIAL:S:IS_INTIAL:N',
        'EMAIL:S:EMAIL:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',
      ],

      [ # 1 For Get User List
        'US.USER_ID:SPK:USER_ID:Y',
        'US.USER_NAME:S:USER_NAME:Y',
        'US.DESCRIPTION:S:DESCRIPTION:Y',
        'US.IS_ENABLE:S:IS_ENABLE:Y',
        'US.IS_ADMIN:S:IS_ADMIN:Y',
        'US.GROUP_ID:REFID:GROUP_ID:Y',
        'GR.GROUP_NAME:S:GROUP_NAME:Y',
        'US.EMAIL:S:EMAIL:N',
      ],

      [ # 2 For update
        'USER_ID:SPK:USER_ID:Y',
        'USER_NAME:S:USER_NAME:Y',
        'DESCRIPTION:S:DESCRIPTION:N',
        'IS_ENABLE:S:IS_ENABLE:N',
        'IS_ADMIN:S:IS_ADMIN:N',
        'GROUP_ID:REFID:GROUP_ID:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',
      ],

      [ # 3 For changing password
        'USER_ID:SPK:USER_ID:Y',
        'PASSWORD:S:PASSWORD:N',

        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',
    ],

    [ # 4 For login
        'USER_ID:SPK:USER_ID:Y',
        'USER_NAME:S:USER_NAME:Y',
        'DESCRIPTION:S:DESCRIPTION:N',
        'PASSWORD:S:PASSWORD:Y',
        'IS_ENABLE:S:IS_ENABLE:Y',
        'IS_ADMIN:S:IS_ADMIN:Y',
        'IS_INTIAL:S:IS_INTIAL:Y',
        'GROUP_ID:REFID:GROUP_ID:N',
    ],
    );

    private $froms = array(
        'FROM FRW_USER ',
        'FROM FRW_USER US '.
            'LEFT OUTER JOIN FRW_USER_GROUP GR ON (US.GROUP_ID = GR.GROUP_ID) ',
        'FROM FRW_USER ',
        'FROM FRW_USER ',
        'FROM FRW_USER ',
    );

    private $orderby = array(
        'ORDER BY USER_ID ASC ',
        'ORDER BY US.MODIFY_DATE ASC ',
        'ORDER BY USER_ID ASC ',
        'ORDER BY USER_ID ASC ',
        'ORDER BY USER_ID ASC ',
    );

    function __construct($db)
    {
        parent::__construct($db, 'FRW_USER', 'USER_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>