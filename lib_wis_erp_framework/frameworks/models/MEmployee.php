<?php
/* 
Purpose : Model for EMPLOYEE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MEmployee extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update
                    'EMPLOYEE_ID:SPK:EMPLOYEE_ID:Y',
                    'EMPLOYEE_CODE:S:EMPLOYEE_CODE:Y',
                    'EMPLOYEE_NAME:S:EMPLOYEE_NAME:Y',
                    'EMPLOYEE_NAME_ENG:S:EMPLOYEE_NAME_ENG:Y',
                    'ADDRESS:S:ADDRESS:N',
                    'EMAIL:S:EMAIL:N',
                    'WEBSITE:S:WEBSITE:N',
                    'PHONE:S:PHONE:N',
                    'FAX:S:FAX:N',
                    'EMPLOYEE_TYPE:REFID:EMPLOYEE_TYPE:Y',
                    'EMPLOYEE_GROUP:REFID:EMPLOYEE_GROUP:N',
                    'BRANCH_ID:REFID:BRANCH_ID:N',                    
                    'CATEGORY:N:CATEGORY:N',
                    'NOTE:S:NOTE:N',
                    'COMMISSION_CYCLE_ID:REFID:COMMISSION_CYCLE_ID:N',
                    'COMMISSION_CYCLE_TYPE:NZ:COMMISSION_CYCLE_TYPE:N',
                    'SALESMAN_SPECIFIC_FLAG:S:SALESMAN_SPECIFIC_FLAG:N',
                    'BANK_ID:REFID:BANK_ID:N',     
                    'ACCOUNT_NO:S:ACCOUNT_NO:N',
                    'BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:N',
                    'ID_NUMBER:S:ID_NUMBER:N',
                    'HOUR_RATE:NZ:HOUR_RATE:N',
                    'SALARY:NZ:SALARY:N',
                    'RESIGNED_FLAG:S:RESIGNED_FLAG:Y',
                    'EMPLOYEE_ADDRESS:S:EMPLOYEE_ADDRESS:N',
                                        
                    'NAME_PREFIX:REFID:NAME_PREFIX:N',
                    'GENDER:REFID:GENDER:N',
                    'EMPLOYEE_POSITION:REFID:EMPLOYEE_POSITION:N',
                    'EMPLOYEE_DEPARTMENT:REFID:EMPLOYEE_DEPARTMENT:N',
                    'EMPLOYEE_LASTNAME:S:EMPLOYEE_LASTNAME:N',
                    'FINGERPRINT_CODE:S:FINGERPRINT_CODE:N',
                    'EMPLOYEE_LASTNAME_ENG:S:EMPLOYEE_LASTNAME_ENG:N',
                    'LINE_ID:S:LINE_ID:N',
                    'EMPLOYEE_PROFILE_IMAGE:S:EMPLOYEE_PROFILE_IMAGE:N',
                    'HIRING_DATE:S:HIRING_DATE:N',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                  
                  [ # 1 For query
                    'EP.EMPLOYEE_ID:SPK:EMPLOYEE_ID:Y',
                    'EP.EMPLOYEE_CODE:S:EMPLOYEE_CODE:Y',
                    'EP.EMPLOYEE_NAME:S:EMPLOYEE_NAME:Y',
                    'EP.EMPLOYEE_NAME_ENG:S:EMPLOYEE_NAME_ENG:Y',
                    'EP.ADDRESS:S:ADDRESS:N',
                    'EP.EMAIL:S:EMAIL:N',
                    'EP.WEBSITE:S:WEBSITE:N',
                    'EP.PHONE:S:PHONE:N',
                    'EP.FAX:S:FAX:N',
                    'EP.EMPLOYEE_TYPE:REFID:EMPLOYEE_TYPE:Y',
                    'EP.EMPLOYEE_GROUP:REFID:EMPLOYEE_GROUP:Y',
                    'EP.BRANCH_ID:REFID:BRANCH_ID:Y',
                    'EP.CATEGORY:N:CATEGORY:Y',
                    'EP.NOTE:S:NOTE:N',
                    'EP.COMMISSION_CYCLE_ID:REFID:COMMISSION_CYCLE_ID:Y',
                    'EP.COMMISSION_CYCLE_TYPE:NZ:COMMISSION_CYCLE_TYPE:Y',
                    'EP.SALESMAN_SPECIFIC_FLAG:S:SALESMAN_SPECIFIC_FLAG:Y',
                    'EP.BANK_ID:REFID:BANK_ID:N',     
                    'EP.ACCOUNT_NO:S:ACCOUNT_NO:N',
                    'EP.BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:N',
                    'EP.ID_NUMBER:S:ID_NUMBER:N',

                    'EP.NAME_PREFIX:REFID:NAME_PREFIX:N',
                    'EP.GENDER:REFID:GENDER:N',
                    'EP.EMPLOYEE_POSITION:REFID:EMPLOYEE_POSITION:N',
                    'EP.EMPLOYEE_DEPARTMENT:REFID:EMPLOYEE_DEPARTMENT:N',
                    'EP.EMPLOYEE_LASTNAME:S:EMPLOYEE_LASTNAME:Y',
                    'EP.FINGERPRINT_CODE:S:FINGERPRINT_CODE:Y',
                    'EP.EMPLOYEE_LASTNAME_ENG:S:EMPLOYEE_LASTNAME_ENG:Y',
                    'EP.LINE_ID:S:LINE_ID:Y',
                    'EP.EMPLOYEE_PROFILE_IMAGE:S:EMPLOYEE_PROFILE_IMAGE:N',
                    'EP.EMPLOYEE_PROFILE_IMAGE:S:EMPLOYEE_PROFILE_IMAGE_WIP:N', //Temp field for keeping the old value
                    "CONCAT(EP.EMPLOYEE_NAME, ' ', EP.EMPLOYEE_LASTNAME):S:EMPLOYEE_NAME_LASTNAME:N:Y",
                    'EP.HOUR_RATE:NZ:HOUR_RATE:N',
                    'EP.SALARY:NZ:SALARY:N',
                    'EP.RESIGNED_FLAG:S:RESIGNED_FLAG:Y',
                    'EP.HIRING_DATE:S:HIRING_DATE:N',
                    'EP.EMPLOYEE_ADDRESS:S:EMPLOYEE_ADDRESS:N',

                    'MR1.DESCRIPTION:S:EMPLOYEE_TYPE_NAME:N', 
                    'MR2.DESCRIPTION:S:EMPLOYEE_GROUP_NAME:N',
                    'MR3.DESCRIPTION:S:BRANCH_NAME:N',
                    'MR4.DESCRIPTION:S:NAME_PREFIX_DESC:N',

                    'EP.CREATE_DATE:CD:CREATE_DATE:N',
                    'EP.MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                                                                                                                
              
    );

    private $froms = array(

                   'FROM EMPLOYEE ',
                   
                   'FROM EMPLOYEE EP ' .
                       'LEFT OUTER JOIN MASTER_REF MR1 ON (EP.EMPLOYEE_TYPE = MR1.MASTER_ID) ' .                       
                       'LEFT OUTER JOIN MASTER_REF MR2 ON (EP.EMPLOYEE_GROUP = MR2.MASTER_ID) '.
                       'LEFT OUTER JOIN MASTER_REF MR3 ON (EP.BRANCH_ID = MR3.MASTER_ID) '.
                       'LEFT OUTER JOIN MASTER_REF MR4 ON (EP.NAME_PREFIX = MR4.MASTER_ID) ',
            
    );

    private $orderby = array(

                   'ORDER BY EMPLOYEE_ID DESC ',  
                   
                   'ORDER BY EP.EMPLOYEE_ID DESC ',                                                                                                                           
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'EMPLOYEE', 'EMPLOYEE_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>