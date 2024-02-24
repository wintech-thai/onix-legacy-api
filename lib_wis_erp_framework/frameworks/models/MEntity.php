<?php
/* 
Purpose : Model for ENTITY
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MEntity extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update
                   'ENTITY_ID:SPK:ENTITY_ID:Y', 
                   'ENTITY_CODE:S:ENTITY_CODE:Y', 
                   'ENTITY_NAME:S:ENTITY_NAME:Y', 
                   'ADDRESS:S:ADDRESS:Y', 
                   'EMAIL:S:EMAIL:Y', 
                   'WEBSITE:S:WEBSITE:Y', 
                   'PHONE:S:PHONE:Y', 
                   'FAX:S:FAX:Y', 
                   'NOTE:S:NOTE:Y',                  
                   'CREDIT_TERM:NZ:CREDIT_TERM:N', 
                   'CREDIT_LIMIT:NZ:CREDIT_LIMIT:N',
                   'CATEGORY:N:CATEGORY:Y',
                   'ENTITY_TYPE:REFID:ENTITY_TYPE:Y', 
                   'ENTITY_GROUP:REFID:ENTITY_GROUP:Y',
                   'ID_NUMBER:S:ID_NUMBER:Y',
                   'NAME_PREFIX:REFID:NAME_PREFIX:Y',
                   'RV_TAX_TYPE:S:RV_TAX_TYPE:Y',
                   'CONTACT_PERSON:S:CONTACT_PERSON:Y',
                   'CREDIT_TERM_ID:REFID:CREDIT_TERM_ID:Y',
                   'PROMPT_PAY_ID:S:PROMPT_PAY_ID:Y',
                   
                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                  
                  [ # 1 For query
                   'EN.ENTITY_ID:SPK:ENTITY_ID:Y', 
                   'EN.ENTITY_CODE:S:ENTITY_CODE:Y', 
                   'EN.ENTITY_NAME:S:ENTITY_NAME:Y', 
                   'EN.ADDRESS:S:ADDRESS:Y', 
                   'EN.EMAIL:S:EMAIL:Y', 
                   'EN.WEBSITE:S:WEBSITE:Y', 
                   'EN.PHONE:S:PHONE:Y', 
                   'EN.FAX:S:FAX:Y', 
                   'EN.NOTE:S:NOTE:Y',                  
                   'EN.CREDIT_TERM:NZ:CREDIT_TERM:N', 
                   'EN.CREDIT_LIMIT:NZ:CREDIT_LIMIT:N',
                   'EN.CATEGORY:N:CATEGORY:Y',
                   'EN.ENTITY_TYPE:REFID:ENTITY_TYPE:Y', 
                   'EN.ENTITY_GROUP:REFID:ENTITY_GROUP:Y',
                   'EN.AR_AP_BALANCE:NZ:AR_AP_BALANCE:N',
                   'EN.ID_NUMBER:S:ID_NUMBER:Y',
                   'EN.NAME_PREFIX:REFID:NAME_PREFIX:Y',
                   'EN.RV_TAX_TYPE:S:RV_TAX_TYPE:Y',
                   'EN.CONTACT_PERSON:S:CONTACT_PERSON:Y',
                   'EN.CREDIT_TERM_ID:REFID:CREDIT_TERM_ID:Y',
                   'EN.PROMPT_PAY_ID:S:PROMPT_PAY_ID:Y',

                   'MR1.DESCRIPTION:S:ENTITY_TYPE_NAME:N', 
                   'MR2.DESCRIPTION:S:ENTITY_GROUP_NAME:N',
                   'MR3.DESCRIPTION:S:NAME_PREFIX_DESC:N',
                   'MR4.DESCRIPTION:S:CREDIT_TERM_DESC:N',
                   'MR4.OPTIONAL:S:CREDIT_TERM_DAY:N',

                   'EN.CREATE_DATE:CD:CREATE_DATE:N',
                   'EN.MODIFY_DATE:MD:MODIFY_DATE:N'                   
                  ],  

                  [ # 2 For Update AR/AP Balance
                  'ENTITY_ID:SPK:ENTITY_ID:Y',
                  'AR_AP_BALANCE:NZ:AR_AP_BALANCE:N'
                  ],
                  
                  [ # 3 Generic purpose, usually used by Patch functions. Do not modify!!!
                  ],                  
    );

    private $froms = array(

                   'FROM ENTITY ',
                   
                   'FROM ENTITY EN ' .
                       'LEFT OUTER JOIN MASTER_REF MR1 ON (EN.ENTITY_TYPE = MR1.MASTER_ID) ' .                       
                       'LEFT OUTER JOIN MASTER_REF MR2 ON (EN.ENTITY_GROUP = MR2.MASTER_ID) '.
                       'LEFT OUTER JOIN MASTER_REF MR3 ON (EN.NAME_PREFIX = MR3.MASTER_ID) '.
                       'LEFT OUTER JOIN MASTER_REF MR4 ON (EN.CREDIT_TERM_ID = MR4.MASTER_ID) ', 

                    'FROM ENTITY ',

                    'FROM ENTITY ', 
            
    );

    private $orderby = array(

                   'ORDER BY ENTITY_ID DESC ',  
                   
                   'ORDER BY EN.ENTITY_ID DESC ',   
                   
                   'ORDER BY ENTITY_ID DESC ', 
                   
                   'ORDER BY ENTITY_ID DESC ', 
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ENTITY', 'ENTITY_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>