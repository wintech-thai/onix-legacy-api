<?php
/* 
Purpose : Model for USER_GROUP
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MUserGroup extends MBaseModel
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

                'FROM USER_GROUP ',
                'FROM USER_GROUP ',
            
    );

    private $orderby = array(

                'ORDER BY GROUP_NAME ASC ',
                'ORDER BY GROUP_NAME ASC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'USER_GROUP', 'GROUP_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>