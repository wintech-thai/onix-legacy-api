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
    );

    private $froms = array(

                   'FROM EMPLOYEE_LEAVE_RECORD ',
    );

    private $orderby = array(

                   'ORDER BY LEAVE_DATE ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'EMPLOYEE_LEAVE_RECORD', 'EMP_LEAVE_REC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>