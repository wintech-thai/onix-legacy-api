<?php
/* 
Purpose : Model for PAYROLL_DOCUMENT
Created By : Seubpong Monsar
Created Date : 02/08/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPayrollDocument extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'PAYROLL_DOC_ID:SPK:PAYROLL_DOC_ID:Y',
                    'PAYROLL_YEAR:S:PAYROLL_YEAR:Y',
                    'PAYROLL_MONTH:S:PAYROLL_MONTH:Y',
                    'NOTE:S:NOTE:N',
                    'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                    'EMPLOYEE_TYPE:REFID:EMPLOYEE_TYPE:Y',
                    'DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'ITEM_COUNT:NZ:ITEM_COUNT:Y',
                    'RECEIVE_AMOUNT:NZ:RECEIVE_AMOUNT:Y',
                    'DEDUCT_AMOUNT:NZ:DEDUCT_AMOUNT:Y',
                    'REMAIN_AMOUNT:NZ:REMAIN_AMOUNT:Y',
                    'FROM_SALARY_DATE:S:FROM_SALARY_DATE:N',
                    'TO_SALARY_DATE:S:TO_SALARY_DATE:N',
                    'PAY_IN_DATE:S:PAY_IN_DATE:N',
                    'PAYROLL_CASH_ACCOUNT_ID:REFID:PAYROLL_CASH_ACCOUNT_ID:N',
                    'COMPANY_SOCIAL_SECURITY_AMOUNT:NZ:COMPANY_SOCIAL_SECURITY_AMOUNT:N',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get List
                    'PRD.PAYROLL_DOC_ID:SPK:PAYROLL_DOC_ID:Y',
                    'PRD.PAYROLL_YEAR:S:PAYROLL_YEAR:Y',
                    'PRD.PAYROLL_MONTH:S:PAYROLL_MONTH:Y',
                    'PRD.NOTE:S:NOTE:N',
                    'PRD.DOCUMENT_DATE:S:DOCUMENT_DATE:Y',
                    'PRD.EMPLOYEE_TYPE:REFID:EMPLOYEE_TYPE:Y',
                    'PRD.DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'PRD.ITEM_COUNT:NZ:ITEM_COUNT:Y',
                    'PRD.RECEIVE_AMOUNT:NZ:RECEIVE_AMOUNT:Y',
                    'PRD.DEDUCT_AMOUNT:NZ:DEDUCT_AMOUNT:Y',
                    'PRD.REMAIN_AMOUNT:NZ:REMAIN_AMOUNT:Y',
                    'PRD.FROM_SALARY_DATE:S:FROM_SALARY_DATE:Y',
                    'PRD.TO_SALARY_DATE:S:TO_SALARY_DATE:N',
                    'PRD.PAY_IN_DATE:S:PAY_IN_DATE:N',
                    'PRD.PAYROLL_CASH_ACCOUNT_ID:REFID:PAYROLL_CASH_ACCOUNT_ID:N',
                    'PRD.COMPANY_SOCIAL_SECURITY_AMOUNT:NZ:COMPANY_SOCIAL_SECURITY_AMOUNT:N',

                    'CA.ACCOUNT_NO:S:PAYROLL_ACCOUNT_NO:Y',
                    'CA.ACCOUNT_NNAME:S:PAYROLL_ACCOUNT_NAME:Y',

                    'PRD.FROM_SALARY_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'PRD.FROM_SALARY_DATE:TD:TO_DOCUMENT_DATE:Y',                
                  ],

                  [ # 2 For update document type and status
                    'PAYROLL_DOC_ID:SPK:PAYROLL_DOC_ID:Y',
                    'DOCUMENT_STATUS:NZ:DOCUMENT_STATUS:N',
                    'EMPLOYEE_TYPE:NZ:EMPLOYEE_TYPE:N',            
                  ],                    
    );

    private $froms = array(

                'FROM PAYROLL_DOCUMENT ',

                'FROM PAYROLL_DOCUMENT PRD ' .
                    'LEFT OUTER JOIN CASH_ACCOUNT CA ON (CA.CASH_ACCOUNT_ID = PRD.PAYROLL_CASH_ACCOUNT_ID) ',                

                'FROM PAYROLL_DOCUMENT ' ,
    );

    private $orderby = array(

                'ORDER BY PAYROLL_DOC_ID DESC ',
                
                'ORDER BY PRD.PAYROLL_DOC_ID DESC ',   
                
                'ORDER BY PAYROLL_DOC_ID DESC ',   
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PAYROLL_DOCUMENT', 'PAYROLL_DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>