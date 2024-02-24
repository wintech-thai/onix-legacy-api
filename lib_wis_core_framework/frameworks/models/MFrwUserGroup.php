<?php
/* 
Purpose : Model for FRW_USER_GROUP
Created By : Seubpong Monsar
Created Date : 01/22/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwUserGroup extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'GROUP_ID:SPK:GROUP_ID:Y',
                    'GROUP_NAME:S:GROUP_NAME:Y',
                    'DESCRIPTION:S:DESCRIPTION:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],
                  [ # 1 For insert, update, delete
                    'GROUP_ID:SPK:GROUP_ID:Y',
                    'GROUP_NAME:S:GROUP_NAME:Y',
                    'DESCRIPTION:S:DESCRIPTION:N',
                  ]
              
    );

    private $froms = array(

                'FROM FRW_USER_GROUP ',
                'FROM FRW_USER_GROUP ',
            
    );

    private $orderby = array(

                'ORDER BY GROUP_NAME ASC ',
                'ORDER BY GROUP_NAME ASC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_USER_GROUP', 'GROUP_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>