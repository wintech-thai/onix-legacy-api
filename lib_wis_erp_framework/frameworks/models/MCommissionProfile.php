<?php
/* 
Purpose : Model for COMMISSION_PROFILE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCommissionProfile extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'COMMISSION_PROF_ID:SPK:COMMISSION_PROF_ID:Y',
                    'PROFILE_CODE:S:PROFILE_CODE:Y',
                    'PROFILE_DESC:S:PROFILE_DESC:N',
                    'ENABLE_FLAG:S:ENABLE_FLAG:N',
                    'EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
                    'EXPIRE_DATE:S:EXPIRE_DATE:N',
                    'PROFILE_TYPE:N:PROFILE_TYPE:N',
                    'EMPLOYEE_SPECIFIC_FLAG:S:EMPLOYEE_SPECIFIC_FLAG:N',
                    'COMMISSION_DEFINITION:S:COMMISSION_DEFINITION:N',
                    'PRODUCT_SPECIFIC_FLAG:S:PRODUCT_SPECIFIC_FLAG:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],
                  [ # 1 For insert, update, delete
                    'COMMISSION_PROF_ID:SPK:COMMISSION_PROF_ID:Y',
                    'PROFILE_CODE:S:PROFILE_CODE:Y',
                    'PROFILE_DESC:S:PROFILE_DESC:Y',
                    'ENABLE_FLAG:S:ENABLE_FLAG:Y',
                    'EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
                    'EXPIRE_DATE:S:EXPIRE_DATE:N',
                    'PROFILE_TYPE:N:PROFILE_TYPE:Y',
                    'EMPLOYEE_SPECIFIC_FLAG:S:EMPLOYEE_SPECIFIC_FLAG:Y',
                    'PRODUCT_SPECIFIC_FLAG:S:PRODUCT_SPECIFIC_FLAG:N',                    
                    'COMMISSION_DEFINITION:S:COMMISSION_DEFINITION:N',

                    'EFFECTIVE_DATE:FD:FROM_EFFECTIVE_DATE:Y',
                    'EFFECTIVE_DATE:TD:TO_EFFECTIVE_DATE:Y',

                    'EXPIRE_DATE:FD:FROM_EXPIRE_DATE:Y',
                    'EXPIRE_DATE:TD:TO_EXPIRE_DATE:Y',

                  ]                                                                                                            
              
    );

    private $froms = array(
                               
                'FROM COMMISSION_PROFILE ',
                'FROM COMMISSION_PROFILE ',
            
    );

    private $orderby = array(

                'ORDER BY COMMISSION_PROF_ID DESC ',
                'ORDER BY COMMISSION_PROF_ID DESC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'COMMISSION_PROFILE', 'COMMISSION_PROF_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>