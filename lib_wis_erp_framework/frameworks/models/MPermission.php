<?php
/* 
Purpose : Model for PERMISSION
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPermission extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, delete
                    'PERM_ID:SPK:PERM_ID:Y',
                    'PERM_NAME:S:PERM_NAME:Y',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  [
                    'PM.PERM_ID:PK:PERM_ID:N',
                    'PM.PERM_NAME:S:PERM_NAME:Y',
                  ]
              
    );

    private $froms = array(

                'FROM PERMISSION ',
                'FROM PERMISSION PM ',
            
    );

    private $orderby = array(

                'ORDER BY PERM_ID ASC ',
                'ORDER BY PM.PERM_NAME ASC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PERMISSION', 'PERM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>