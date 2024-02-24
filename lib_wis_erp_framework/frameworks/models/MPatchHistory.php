<?php
/* 
Purpose : Model for PATCH_HISTORY
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPatchHistory extends MBaseModel
{
    private $cols = array(
                  [ # 0 For query, insert, delete, update
                   'PATCH_ID:SPK:PATCH_ID:Y', 
                   'PATCH_DATE:SD:PATCH_DATE:N',                    
                   'VERSION:S:VERSION:N', 
                   'LAST_PATCH_POINT:N:LAST_PATCH_POINT:N', 
                   'PATCH_FILE:S:PATCH_FILE:N', 

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],
    );

    private $froms = array(
                   'FROM PATCH_HISTORY ',
    );

    private $orderby = array(
                   'ORDER BY PATCH_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PATCH_HISTORY', 'PATCH_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>