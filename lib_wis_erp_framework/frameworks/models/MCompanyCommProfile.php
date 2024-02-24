<?php
/* 
Purpose : Model for COMPANY_COMM_PROFILE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCompanyCommProfile extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'COMPANY_COMMPROF_ID:SPK:COMPANY_COMMPROF_ID:Y',
                    'COMPANY_ID:N:COMPANY_ID:N',
                    'COMMISSION_PROF_ID:REFID:COMMISSION_PROF_ID:N',
                    'SEQUENCE_NO:N:SEQUENCE_NO:N',
                    'ENABLE_FLAG:S:ENABLE_FLAG:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],
                  [ # 1 For Query
                    'CCP.COMPANY_COMMPROF_ID:SPK:COMPANY_COMMPROF_ID:Y',
                    'CCP.COMPANY_ID:N:COMPANY_ID:Y',
                    'CCP.COMMISSION_PROF_ID:REFID:COMMISSION_PROF_ID:Y',
                    'CCP.SEQUENCE_NO:N:SEQUENCE_NO:N',
                    'CCP.ENABLE_FLAG:S:ENABLE_FLAG:Y',

                    'CP.PROFILE_TYPE:NZ:PROFILE_TYPE:N',
                    'CP.PROFILE_DESC:S:PROFILE_DESC:N',
                    'CP.PROFILE_CODE:S:PROFILE_CODE:N',
                    'CP.EMPLOYEE_SPECIFIC_FLAG:S:EMPLOYEE_SPECIFIC_FLAG:N',
                    'CP.PRODUCT_SPECIFIC_FLAG:S:PRODUCT_SPECIFIC_FLAG:N',
                    'CP.ENABLE_FLAG:S:ENABLE_FLAG:N',
                    'CP.EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
                    'CP.EXPIRE_DATE:S:EXPIRE_DATE:N',                 
                  ],
                  [ # 2 For delete from parent
                    'COMMISSION_PROF_ID:SPK:COMMISSION_PROF_ID:Y',
                  ]                                                                                                          
              
    );

    private $froms = array(
                               
                'FROM COMPANY_COMM_PROFILE ',

                'FROM COMPANY_COMM_PROFILE CCP '.
                        'LEFT OUTER JOIN COMMISSION_PROFILE CP ON (CCP.COMMISSION_PROF_ID = CP.COMMISSION_PROF_ID) ',
                        
                'FROM COMPANY_COMM_PROFILE ',
            
    );

    private $orderby = array(

                ' ',
                'ORDER BY CCP.SEQUENCE_NO ASC ',
                ' ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'COMPANY_COMM_PROFILE', 'COMPANY_COMMPROF_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>