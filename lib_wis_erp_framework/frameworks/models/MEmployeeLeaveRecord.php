<?php
/* 
Purpose : Model for EMPLOYEE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MEmployeeLeaveRecord extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update
                    'EMP_LEAVE_REC_ID:SPK:EMP_LEAVE_REC_ID:Y',
                    'EMP_LEAVE_DOC_ID:REFID:EMP_LEAVE_DOC_ID:Y',
                    'LEAVE_DATE:S:LEAVE_DATE:N',
                    'LATE:NZ:LATE:N',
                    'SICK_LEAVE:NZ:SICK_LEAVE:N',
                    'PERSONAL_LEAVE:NZ:PERSONAL_LEAVE:N',
                    'EXTRA_LEAVE:NZ:EXTRA_LEAVE:N',
                    'ANNUAL_LEAVE:NZ:ANNUAL_LEAVE:N',
                    'ABNORMAL_LEAVE:NZ:ABNORMAL_LEAVE:N',
                    'DEDUCTION_LEAVE:NZ:DEDUCTION_LEAVE:N',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ], 
                  
                  [ # 1 For sum group by year and month
                    'ELD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'ELD.LEAVE_YEAR:N:LEAVE_YEAR:Y',
                    'ELD.LEAVE_MONTH:N:LEAVE_MONTH:Y',                    
                    'SUM(ELR.LATE):NZ:LATE:N',
                    'SUM(ELR.SICK_LEAVE):NZ:SICK_LEAVE:N',
                    'SUM(ELR.PERSONAL_LEAVE):NZ:PERSONAL_LEAVE:N',
                    'SUM(ELR.EXTRA_LEAVE):NZ:EXTRA_LEAVE:N',
                    'SUM(ELR.ANNUAL_LEAVE):NZ:ANNUAL_LEAVE:N',
                    'SUM(ELR.ABNORMAL_LEAVE):NZ:ABNORMAL_LEAVE:N',
                    'SUM(ELR.DEDUCTION_LEAVE):NZ:DEDUCTION_LEAVE:N',
                  ],
                  
                  [ # 2 For sum group by year
                    'ELD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'ELD.LEAVE_YEAR:N:LEAVE_YEAR:Y',
                    'SUM(ELR.LATE):NZ:LATE:N',
                    'SUM(ELR.SICK_LEAVE):NZ:SICK_LEAVE:N',
                    'SUM(ELR.PERSONAL_LEAVE):NZ:PERSONAL_LEAVE:N',
                    'SUM(ELR.EXTRA_LEAVE):NZ:EXTRA_LEAVE:N',
                    'SUM(ELR.ANNUAL_LEAVE):NZ:ANNUAL_LEAVE:N',
                    'SUM(ELR.ABNORMAL_LEAVE):NZ:ABNORMAL_LEAVE:N',
                    'SUM(ELR.DEDUCTION_LEAVE):NZ:DEDUCTION_LEAVE:N',
                  ],                  
    );

    private $froms = array(

                  'FROM EMPLOYEE_LEAVE_RECORD ',

                  'FROM EMPLOYEE_LEAVE_RECORD ELR '.
                      'LEFT OUTER JOIN EMPLOYEE_LEAVE_DOC ELD ON (ELR.EMP_LEAVE_DOC_ID = ELD.EMP_LEAVE_DOC_ID) ', 

                  'FROM EMPLOYEE_LEAVE_RECORD ELR '.
                      'LEFT OUTER JOIN EMPLOYEE_LEAVE_DOC ELD ON (ELR.EMP_LEAVE_DOC_ID = ELD.EMP_LEAVE_DOC_ID) ',                 

    );

    private $orderby = array(

                   'ORDER BY LEAVE_DATE ASC ',

                   'GROUP BY ELD.EMPLOYEE_ID, ELD.LEAVE_YEAR, ELD.LEAVE_MONTH ORDER BY ELD.EMPLOYEE_ID DESC ',

                   'GROUP BY ELD.EMPLOYEE_ID, ELD.LEAVE_YEAR ORDER BY ELD.EMPLOYEE_ID DESC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'EMPLOYEE_LEAVE_RECORD', 'EMP_LEAVE_REC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>