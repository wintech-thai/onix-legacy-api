<?php
/* 
Purpose : Model for DOCUMENT_NUMBER
Created By : Seubpong Monsar
Created Date : 10/29/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MFrwDocumentNumber extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                   'DOCUMENT_NUMBER_ID:SPK:DOCUMENT_NUMBER_ID:Y', 
                   'DOC_TYPE:S:DOC_TYPE:Y', 
                   'LAST_RUN_YEAR:N:LAST_RUN_YEAR:N', 
                   'LAST_RUN_MONTH:N:LAST_RUN_MONTH:N', 
                   'PREFIX:S:PREFIX:N',                   
                   'FORMULA:S:FORMULA:N',
                   'RESET_CRITERIA:N:RESET_CRITERIA:N', 
                   'CURRENT_SEQ:N:CURRENT_SEQ:N', 
                   'START_SEQ:N:START_SEQ:N', 
                   'SEQ_LENGTH:N:SEQ_LENGTH:N', 
                   'YEAR_OFFSET:N:YEAR_OFFSET:N', 
                   'PARENT_ID:N:PARENT_ID:N', 
                   'GROUP_ID:N:GROUP_ID:N', 
                   'CUSTOM_SEQ_VAR:S:CUSTOM_SEQ_VAR:N', 
                   'USE_CUSTOM_SEQ:S:USE_CUSTOM_SEQ:N', 
                   'SEQUENCE_DEFINITION:S:SEQUENCE_DEFINITION:N', 
                   
                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],

                  [ # 1 For query by parent
                  'DOCUMENT_NUMBER_ID:SPK:DOCUMENT_NUMBER_ID:Y', 
                  'DOC_TYPE:S:DOC_TYPE:Y', 
                  'LAST_RUN_YEAR:N:LAST_RUN_YEAR:N', 
                  'LAST_RUN_MONTH:N:LAST_RUN_MONTH:N', 
                  'PREFIX:S:PREFIX:N',
                  'FORMULA:S:FORMULA:N',
                  'RESET_CRITERIA:N:RESET_CRITERIA:N', 
                  'CURRENT_SEQ:N:CURRENT_SEQ:N', 
                  'START_SEQ:N:START_SEQ:N', 
                  'SEQ_LENGTH:N:SEQ_LENGTH:N', 
                  'YEAR_OFFSET:N:YEAR_OFFSET:N', 
                  'PARENT_ID:N:PARENT_ID:Y', 
                  'GROUP_ID:N:GROUP_ID:N', 
                  'CUSTOM_SEQ_VAR:S:CUSTOM_SEQ_VAR:N', 
                  'USE_CUSTOM_SEQ:S:USE_CUSTOM_SEQ:N', 
                  'SEQUENCE_DEFINITION:S:SEQUENCE_DEFINITION:N', 

                  'CREATE_DATE:CD:CREATE_DATE:N',
                  'MODIFY_DATE:MD:MODIFY_DATE:N'
                 ],
                  
                  [ # 2 For partial update
                   'DOCUMENT_NUMBER_ID:SPK:DOCUMENT_NUMBER_ID:Y', 
                   'LAST_RUN_YEAR:N:LAST_RUN_YEAR:N', 
                   'LAST_RUN_MONTH:N:LAST_RUN_MONTH:N', 
                   'CURRENT_SEQ:N:CURRENT_SEQ:N', 
                   'SEQUENCE_DEFINITION:S:SEQUENCE_DEFINITION:N', 

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],                                                                                                                  
              
    );

    private $froms = array(
                               
                   'FROM FRW_DOCUMENT_NUMBER ',
                   
                   'FROM FRW_DOCUMENT_NUMBER ',                                                                                                        
    );

    private $orderby = array(

                   'ORDER BY DOCUMENT_NUMBER_ID ASC ',     
                   
                   'ORDER BY DOCUMENT_NUMBER_ID ASC ',                                                                                                                         
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_DOCUMENT_NUMBER', 'DOCUMENT_NUMBER_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>