<?php
/* 
Purpose : Model for GROUP_PERMISSION
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MGroupPermission extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, delete
                    'GROUP_PERMISSION_ID:SPK:GROUP_PERMISSION_ID:Y',
                    'PERM_ID:REFID:PERM_ID:Y',
                    'GROUP_ID:REFID:GROUP_ID:Y',
                    'IS_ALLOWED:S:IS_ALLOWED:Y',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For delete by parent
                    'GROUP_ID:SPK:GROUP_ID:Y' 
                  ],   
                  
                  [ # 2 For query
                  'GP.GROUP_PERMISSION_ID:SPK:GROUP_PERMISSION_ID:Y',                  
                  'GP.PERM_ID:REFID:PERM_ID:Y',
                  'GP.GROUP_ID:REFID:GROUP_ID:Y',
                  'GP.IS_ALLOWED:S:IS_ALLOWED:Y',
                  'PM.PERM_NAME:S:PERM_NAME:Y',

                  'GP.CREATE_DATE:CD:CREATE_DATE:N',
                  'GP.MODIFY_DATE:MD:MODIFY_DATE:N',
                ],                  
              
    );

    private $froms = array(
                'FROM GROUP_PERMISSION ',
                'FROM GROUP_PERMISSION ',
                'FROM GROUP_PERMISSION GP ' .  
                    'LEFT OUTER JOIN PERMISSION PM ON (GP.PERM_ID = PM.PERM_ID) ',
    );

    private $orderby = array(

                'ORDER BY PERM_ID ASC ',
                'ORDER BY PERM_ID ASC ',  
                'ORDER BY GP.PERM_ID ASC ',                             
    );

    function __construct($db) 
    {
        parent::__construct($db, 'GROUP_PERMISSION', 'PERM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>