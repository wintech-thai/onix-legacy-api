<?php
/* 
Purpose : Model for LOG_IMPORT_ERROR
Created By : Seubpong Monsar
Created Date : 11/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MLogImportError extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'LOG_IMPORT_ERROR_ID:SPK:LOG_IMPORT_ERROR_ID:Y',
                    'LOG_IMPORT_ISSUE_ID:REFID:LOG_IMPORT_ISSUE_ID:Y',
                    'ERROR_DESC:S:ERROR_DESC:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',    
                ],

                [ # 1 Delete by Parent
                    'LOG_IMPORT_ISSUE_ID:SPK:LOG_IMPORT_ISSUE_ID:Y',  
                ],                 
    );

    private $froms = array(

                'FROM LOG_IMPORT_ERROR ',
                'FROM LOG_IMPORT_ERROR ',
    );

    private $orderby = array(

                'ORDER BY LOG_IMPORT_ERROR_ID DESC ',
                'ORDER BY LOG_IMPORT_ERROR_ID DESC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'LOG_IMPORT_ERROR', 'LOG_IMPORT_ERROR_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>