<?php
/* 
Purpose : Model for EMPLOYEE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MEmployeeLeaveDoc extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update
                    'EMP_LEAVE_DOC_ID:SPK:EMP_LEAVE_DOC_ID:Y',
                    'EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'LEAVE_YEAR:N:LEAVE_YEAR:Y',
                    'LEAVE_MONTH:N:LEAVE_MONTH:Y',
                    'LATE:N:LATE:N',
                    'SICK_LEAVE:N:SICK_LEAVE:N',
                    'PERSONAL_LEAVE:N:PERSONAL_LEAVE:N',
                    'EXTRA_LEAVE:N:EXTRA_LEAVE:N',
                    'ANNUAL_LEAVE:N:ANNUAL_LEAVE:N',
                    'ABNORMAL_LEAVE:N:ABNORMAL_LEAVE:N',
                    'DEDUCTION_LEAVE:N:DEDUCTION_LEAVE:N',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                  
                  [ # 1 For query
                  'ELD.EMP_LEAVE_DOC_ID:SPK:EMP_LEAVE_DOC_ID:Y',
                  'ELD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                  'ELD.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                  'ELD.LEAVE_YEAR:N:LEAVE_YEAR:Y',
                  'ELD.LEAVE_MONTH:N:LEAVE_MONTH:Y',
                  'ELD.LATE:N:LATE:N',
                  'ELD.SICK_LEAVE:N:SICK_LEAVE:N',
                  'ELD.PERSONAL_LEAVE:N:PERSONAL_LEAVE:N',
                  'ELD.EXTRA_LEAVE:N:EXTRA_LEAVE:N',
                  'ELD.ANNUAL_LEAVE:N:ANNUAL_LEAVE:N',
                  'ELD.ABNORMAL_LEAVE:N:ABNORMAL_LEAVE:N',
                  'ELD.DEDUCTION_LEAVE:N:DEDUCTION_LEAVE:N',

                  'ELD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                  'ELD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',                  
                  ],  
    );

    private $froms = array(

                   'FROM EMPLOYEE_LEAVE_DOC ',
                   
                   'FROM EMPLOYEE_LEAVE_DOC ELD ',            
    );

    private $orderby = array(

                   'ORDER BY EMP_LEAVE_DOC_ID DESC ',  
                   
                   'ORDER BY ELD.EMP_LEAVE_DOC_ID DESC ',                                                                                                                           
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'EMPLOYEE_LEAVE_DOC', 'EMP_LEAVE_DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>