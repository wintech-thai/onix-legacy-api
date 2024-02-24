<?php
/* 
Purpose : Model for LOG_IMPORT_ISSUE
Created By : Seubpong Monsar
Created Date : 11/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MLogImportIssue extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'LOG_IMPORT_ISSUE_ID:SPK:LOG_IMPORT_ISSUE_ID:Y',
                    'IMPORT_DATE:S:IMPORT_DATE:N',
                    'BRANCH_NAME:S:BRANCH_NAME:Y',
                    'IMPORT_BY:S:IMPORT_BY:N',
                    'ORIGINAL_DOC:S:ORIGINAL_DOC:N',
                    'MODIFIED_DOC:S:MODIFIED_DOC:N',
                    'REF_ID:S:REF_ID:Y',
                    'STATUS:N:STATUS:Y',                    
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'BRANCH_ID:REFID:BRANCH_ID:Y',
                    'DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'DOCUMENT_DESC:S:DOCUMENT_DESC:Y',
                    'DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',                  
                ],         
                
                [ # 1 For Query
                    'LOG_IMPORT_ISSUE_ID:SPK:LOG_IMPORT_ISSUE_ID:Y',
                    'IMPORT_DATE:S:IMPORT_DATE:N',
                    'BRANCH_NAME:S:BRANCH_NAME:Y',
                    'IMPORT_BY:S:IMPORT_BY:N',
                    //'ORIGINAL_DOC:S:ORIGINAL_DOC:N',
                    //'MODIFIED_DOC:S:MODIFIED_DOC:N',
                    'REF_ID:S:REF_ID:Y',                    
                    'STATUS:N:STATUS:Y',
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'BRANCH_ID:REFID:BRANCH_ID:Y',
                    'DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'DOCUMENT_DESC:S:DOCUMENT_DESC:Y',
                    'DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',

                    'DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',                    
                ],  
                
                [ # 2 Update MODIFIED_DOC
                    'LOG_IMPORT_ISSUE_ID:SPK:LOG_IMPORT_ISSUE_ID:Y',
                    'MODIFIED_DOC:S:MODIFIED_DOC:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',               
                ],    
                
                [ # 3 Update STATUS
                    'LOG_IMPORT_ISSUE_ID:SPK:LOG_IMPORT_ISSUE_ID:Y',
                    'STATUS:N:STATUS:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',               
                ],                  
    );

    private $froms = array(

                'FROM LOG_IMPORT_ISSUE ',
                'FROM LOG_IMPORT_ISSUE ',
                'FROM LOG_IMPORT_ISSUE ', 
                'FROM LOG_IMPORT_ISSUE ',                
    );

    private $orderby = array(

                'ORDER BY LOG_IMPORT_ISSUE_ID DESC ',
                'ORDER BY LOG_IMPORT_ISSUE_ID DESC ',
                'ORDER BY LOG_IMPORT_ISSUE_ID DESC ',
                'ORDER BY LOG_IMPORT_ISSUE_ID DESC ',                
    );

    function __construct($db) 
    {
        parent::__construct($db, 'LOG_IMPORT_ISSUE', 'LOG_IMPORT_ISSUE_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>