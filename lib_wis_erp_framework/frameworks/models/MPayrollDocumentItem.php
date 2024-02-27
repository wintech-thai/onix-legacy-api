<?php
/* 
Purpose : Model for PAYROLL_DOC_ITEM
Created By : Seubpong Monsar
Created Date : 02/09/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPayrollDocumentItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'PAYROLL_DOC_ITEM_ID:SPK:PAYROLL_DOC_ITEM_ID:Y',
                    'PAYROLL_DOC_ID:REFID:PAYROLL_DOC_ID:Y',
                    'EMPLOYEE_ID:REFID:EMPLOYEE_ID:N',
                    'NOTE:S:NOTE:N',

                    'RECEIVE_AMOUNT:NZ:RECEIVE_AMOUNT:N',
                    'DEDUCT_AMOUNT:NZ:DEDUCT_AMOUNT:N',
                    'REMAIN_AMOUNT:NZ:REMAIN_AMOUNT:N',
                    'RECEIVE_INCOME:NZ:RECEIVE_INCOME:N',
                    'RECEIVE_OT:NZ:RECEIVE_OT:N',
                    'SLIP_RECEIVE_OT:NZ:SLIP_RECEIVE_OT:N',
                    'RECEIVE_POSITION:NZ:RECEIVE_POSITION:N',
                    'RECEIVE_TRANSPORTATION:NZ:RECEIVE_TRANSPORTATION:N',
                    'RECEIVE_TELEPHONE:NZ:RECEIVE_TELEPHONE:N',
                    'RECEIVE_COMMISSION:NZ:RECEIVE_COMMISSION:N',
                    'RECEIVE_ALLOWANCE:NZ:RECEIVE_ALLOWANCE:N',
                    'RECEIVE_BONUS:NZ:RECEIVE_BONUS:N',
                    'RECEIVE_REFUND:NZ:RECEIVE_REFUND:N',
                    'DEDUCT_TAX:NZ:DEDUCT_TAX:N',
                    'DEDUCT_PENALTY:NZ:DEDUCT_PENALTY:N',
                    'DEDUCT_BORROW:NZ:DEDUCT_BORROW:N',
                    'DEDUCT_ADVANCE:NZ:DEDUCT_ADVANCE:N',
                    'DEDUCT_SOCIAL_SECURITY:NZ:DEDUCT_SOCIAL_SECURITY:N',
                    'DEDUCT_OTHER:NZ:DEDUCT_OTHER:N',
                    'SOCIAL_SECURITY_COMPANY:NZ:SOCIAL_SECURITY_COMPANY:N',
                    'DEDUCT_COVERAGE:NZ:DEDUCT_COVERAGE:N',
                    'RECEIVE_OTHERS_TOTAL:NZ:RECEIVE_OTHERS_TOTAL:N',
                    'GRAND_TOTAL_AMOUNT:NZ:GRAND_TOTAL_AMOUNT:N',
                    'BAL_TOTAL_DEFINITION:S:BAL_TOTAL_DEFINITION:N',
                    'BAL_YEAR_DEFINITION:S:BAL_YEAR_DEFINITION:N',
                    'ENDING_TOTAL_DEFINITION:S:ENDING_TOTAL_DEFINITION:N',
                    'ENDING_YEAR_DEFINITION:S:ENDING_YEAR_DEFINITION:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For delete by parent
                    'PAYROLL_DOC_ID:SPK:PAYROLL_DOC_ID:Y'
                  ],   
                  
                  [ # 2
                    'PDI.PAYROLL_DOC_ITEM_ID:SPK:PAYROLL_DOC_ITEM_ID:Y',
                    'PDI.PAYROLL_DOC_ID:REFID:PAYROLL_DOC_ID:Y',
                    'PDI.EMPLOYEE_ID:REFID:EMPLOYEE_ID:N',
                    'PDI.NOTE:S:NOTE:N',

                    'PDI.RECEIVE_AMOUNT:NZ:RECEIVE_AMOUNT:N',
                    'PDI.DEDUCT_AMOUNT:NZ:DEDUCT_AMOUNT:N',
                    'PDI.REMAIN_AMOUNT:NZ:REMAIN_AMOUNT:N',
                    'PDI.RECEIVE_INCOME:NZ:RECEIVE_INCOME:N',
                    'PDI.RECEIVE_OT:NZ:RECEIVE_OT:N',
                    'PDI.SLIP_RECEIVE_OT:NZ:SLIP_RECEIVE_OT:N',
                    'PDI.RECEIVE_POSITION:NZ:RECEIVE_POSITION:N',
                    'PDI.RECEIVE_TRANSPORTATION:NZ:RECEIVE_TRANSPORTATION:N',
                    'PDI.RECEIVE_TELEPHONE:NZ:RECEIVE_TELEPHONE:N',
                    'PDI.RECEIVE_COMMISSION:NZ:RECEIVE_COMMISSION:N',
                    'PDI.RECEIVE_ALLOWANCE:NZ:RECEIVE_ALLOWANCE:N',
                    'PDI.RECEIVE_BONUS:NZ:RECEIVE_BONUS:N',
                    'PDI.RECEIVE_REFUND:NZ:RECEIVE_REFUND:N',
                    'PDI.DEDUCT_TAX:NZ:DEDUCT_TAX:N',
                    'PDI.DEDUCT_PENALTY:NZ:DEDUCT_PENALTY:N',
                    'PDI.DEDUCT_BORROW:NZ:DEDUCT_BORROW:N',
                    'PDI.DEDUCT_ADVANCE:NZ:DEDUCT_ADVANCE:N',
                    'PDI.DEDUCT_SOCIAL_SECURITY:NZ:DEDUCT_SOCIAL_SECURITY:N',
                    'PDI.DEDUCT_OTHER:NZ:DEDUCT_OTHER:N',
                    'PDI.SOCIAL_SECURITY_COMPANY:NZ:SOCIAL_SECURITY_COMPANY:N',
                    'PDI.DEDUCT_COVERAGE:NZ:DEDUCT_COVERAGE:N',
                    'PDI.RECEIVE_OTHERS_TOTAL:NZ:RECEIVE_OTHERS_TOTAL:N',
                    'PDI.GRAND_TOTAL_AMOUNT:NZ:GRAND_TOTAL_AMOUNT:N',
                    'PDI.BAL_TOTAL_DEFINITION:S:BAL_TOTAL_DEFINITION:N',
                    'PDI.BAL_YEAR_DEFINITION:S:BAL_YEAR_DEFINITION:N',
                    'PDI.ENDING_TOTAL_DEFINITION:S:ENDING_TOTAL_DEFINITION:N',
                    'PDI.ENDING_YEAR_DEFINITION:S:ENDING_YEAR_DEFINITION:N',

                    'EM.EMPLOYEE_CODE:S:EMPLOYEE_CODE:N',
                    'EM.EMPLOYEE_NAME:S:EMPLOYEE_NAME:N',
                    'EM.EMPLOYEE_LASTNAME:S:EMPLOYEE_LASTNAME:N',
                    'EM.EMPLOYEE_NAME_ENG:S:EMPLOYEE_NAME_ENG:N', 
                    'EM.ACCOUNT_NO:S:ACCOUNT_NO:N', 
                    'EM.BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:N', 
                    'EM.ID_NUMBER:S:ID_NUMBER:N', 

                    'MR1.DESCRIPTION:S:NAME_PREFIX_DESC:N', 
                    'MR2.DESCRIPTION:S:BANK_NAME:N',
                    
                    'VD1.DIRECTORY_NAME:S:POSITION_NAME:N',
                    'VD2.DIRECTORY_NAME:S:DEPARTMENT_NAME:N',
                  ],

                  [ # 3
                    'PDI.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',

                    'SUM(PDI.RECEIVE_AMOUNT):NZ:RECEIVE_AMOUNT:N',
                    'SUM(PDI.DEDUCT_AMOUNT):NZ:DEDUCT_AMOUNT:N',
                    'SUM(PDI.REMAIN_AMOUNT):NZ:REMAIN_AMOUNT:N',
                    'SUM(PDI.RECEIVE_INCOME):NZ:RECEIVE_INCOME:N',
                    'SUM(PDI.RECEIVE_OT):NZ:RECEIVE_OT:N',
                    'SUM(PDI.SLIP_RECEIVE_OT):NZ:SLIP_RECEIVE_OT:N',
                    'SUM(PDI.RECEIVE_POSITION):NZ:RECEIVE_POSITION:N',
                    'SUM(PDI.RECEIVE_TRANSPORTATION):NZ:RECEIVE_TRANSPORTATION:N',
                    'SUM(PDI.RECEIVE_TELEPHONE):NZ:RECEIVE_TELEPHONE:N',
                    'SUM(PDI.RECEIVE_COMMISSION):NZ:RECEIVE_COMMISSION:N',
                    'SUM(PDI.RECEIVE_ALLOWANCE):NZ:RECEIVE_ALLOWANCE:N',
                    'SUM(PDI.RECEIVE_BONUS):NZ:RECEIVE_BONUS:N',
                    'SUM(PDI.RECEIVE_REFUND):NZ:RECEIVE_REFUND:N',
                    'SUM(PDI.DEDUCT_TAX):NZ:DEDUCT_TAX:N',
                    'SUM(PDI.DEDUCT_PENALTY):NZ:DEDUCT_PENALTY:N',
                    'SUM(PDI.DEDUCT_BORROW):NZ:DEDUCT_BORROW:N',
                    'SUM(PDI.DEDUCT_ADVANCE):NZ:DEDUCT_ADVANCE:N',
                    'SUM(PDI.DEDUCT_SOCIAL_SECURITY):NZ:DEDUCT_SOCIAL_SECURITY:N',
                    'SUM(PDI.DEDUCT_OTHER):NZ:DEDUCT_OTHER:N',
                    'SUM(PDI.SOCIAL_SECURITY_COMPANY):NZ:SOCIAL_SECURITY_COMPANY:N',
                    'SUM(PDI.DEDUCT_COVERAGE):NZ:DEDUCT_COVERAGE:N',
                    'SUM(PDI.RECEIVE_OTHERS_TOTAL):NZ:RECEIVE_OTHERS_TOTAL:N',
                    'SUM(PDI.GRAND_TOTAL_AMOUNT):NZ:GRAND_TOTAL_AMOUNT:N',

                    'PRD.FROM_SALARY_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'PRD.FROM_SALARY_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ],

                  [ # 4
                    'PDI.PAYROLL_DOC_ITEM_ID:SPK:PAYROLL_DOC_ITEM_ID:Y',
                    'PDI.PAYROLL_DOC_ID:REFID:PAYROLL_DOC_ID:Y',
                    'PDI.EMPLOYEE_ID:REFID:EMPLOYEE_ID:N',
                    'PDI.NOTE:S:NOTE:N',

                    'PDI.RECEIVE_AMOUNT:NZ:RECEIVE_AMOUNT:N',
                    'PDI.DEDUCT_AMOUNT:NZ:DEDUCT_AMOUNT:N',
                    'PDI.REMAIN_AMOUNT:NZ:REMAIN_AMOUNT:N',
                    'PDI.RECEIVE_INCOME:NZ:RECEIVE_INCOME:N',
                    'PDI.RECEIVE_OT:NZ:RECEIVE_OT:N',
                    'PDI.SLIP_RECEIVE_OT:NZ:SLIP_RECEIVE_OT:N',
                    'PDI.RECEIVE_POSITION:NZ:RECEIVE_POSITION:N',
                    'PDI.RECEIVE_TRANSPORTATION:NZ:RECEIVE_TRANSPORTATION:N',
                    'PDI.RECEIVE_TELEPHONE:NZ:RECEIVE_TELEPHONE:N',
                    'PDI.RECEIVE_COMMISSION:NZ:RECEIVE_COMMISSION:N',
                    'PDI.RECEIVE_ALLOWANCE:NZ:RECEIVE_ALLOWANCE:N',
                    'PDI.RECEIVE_BONUS:NZ:RECEIVE_BONUS:N',
                    'PDI.RECEIVE_REFUND:NZ:RECEIVE_REFUND:N',
                    'PDI.DEDUCT_TAX:NZ:DEDUCT_TAX:N',
                    'PDI.DEDUCT_PENALTY:NZ:DEDUCT_PENALTY:N',
                    'PDI.DEDUCT_BORROW:NZ:DEDUCT_BORROW:N',
                    'PDI.DEDUCT_ADVANCE:NZ:DEDUCT_ADVANCE:N',
                    'PDI.DEDUCT_SOCIAL_SECURITY:NZ:DEDUCT_SOCIAL_SECURITY:N',
                    'PDI.DEDUCT_OTHER:NZ:DEDUCT_OTHER:N',
                    'PDI.SOCIAL_SECURITY_COMPANY:NZ:SOCIAL_SECURITY_COMPANY:N',
                    'PDI.DEDUCT_COVERAGE:NZ:DEDUCT_COVERAGE:N',
                    'PDI.RECEIVE_OTHERS_TOTAL:NZ:RECEIVE_OTHERS_TOTAL:N',
                    'PDI.GRAND_TOTAL_AMOUNT:NZ:GRAND_TOTAL_AMOUNT:N',

                    'PD.EMPLOYEE_TYPE:REFID:EMPLOYEE_TYPE:Y',
                    'PD.FROM_SALARY_DATE:S:FROM_SALARY_DATE:N',
                    'PD.TO_SALARY_DATE:S:TO_SALARY_DATE:N',
                    'PD.DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'PD.NOTE:S:NOTE:N',

                    'EM.EMPLOYEE_ID:REFID:EMPLOYEE_ID:N',
                    'EM.EMPLOYEE_CODE:S:EMPLOYEE_CODE:Y',
                    'EM.EMPLOYEE_NAME:S:EMPLOYEE_NAME:N',
                    'EM.EMPLOYEE_LASTNAME:S:EMPLOYEE_LASTNAME:N',
                    'EM.EMPLOYEE_NAME_ENG:S:EMPLOYEE_NAME_ENG:N', 
                    'EM.ACCOUNT_NO:S:ACCOUNT_NO:N', 
                    'EM.BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:N', 
                    'EM.ID_NUMBER:S:ID_NUMBER:N', 

                    'MR1.DESCRIPTION:S:NAME_PREFIX_DESC:N', 
                    'MR2.DESCRIPTION:S:BANK_NAME:N', 

                    'PD.FROM_SALARY_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'PD.FROM_SALARY_DATE:TD:TO_DOCUMENT_DATE:Y',                     
                  ],       
                  

                  [ # 5
                    'PDI.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'SUBSTRING(PRD.FROM_SALARY_DATE from 1 for 7):S:YYYYMM:N',
                    'SUM(PDI.DEDUCT_TAX):NZ:DEDUCT_TAX:N',
                    'SUM(PDI.DEDUCT_SOCIAL_SECURITY):NZ:DEDUCT_SOCIAL_SECURITY:N',
                    'SUM(PDI.RECEIVE_AMOUNT):NZ:RECEIVE_AMOUNT:N',

                    'PRD.FROM_SALARY_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'PRD.FROM_SALARY_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ], 
                  
                  [ # 6
                    'PDI.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                    'SUBSTRING(PRD.FROM_SALARY_DATE from 1 for 5):S:YYYY:N',
                    'SUM(PDI.DEDUCT_TAX):NZ:DEDUCT_TAX:N',
                    'SUM(PDI.DEDUCT_SOCIAL_SECURITY):NZ:DEDUCT_SOCIAL_SECURITY:N',
                    'SUM(PDI.RECEIVE_AMOUNT):NZ:RECEIVE_AMOUNT:N',

                    'PRD.FROM_SALARY_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'PRD.FROM_SALARY_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ],                   
    );

    private $froms = array(

                'FROM PAYROLL_DOC_ITEM ',

                'FROM PAYROLL_DOC_ITEM ',

                'FROM PAYROLL_DOC_ITEM PDI ' .
                  'LEFT OUTER JOIN EMPLOYEE EM ON (PDI.EMPLOYEE_ID = EM.EMPLOYEE_ID) ' .
                  'LEFT OUTER JOIN MASTER_REF MR1 ON (EM.NAME_PREFIX = MR1.MASTER_ID) '.
                  'LEFT OUTER JOIN MASTER_REF MR2 ON (EM.BANK_ID = MR2.MASTER_ID) '.
                  'LEFT OUTER JOIN VIRTUAL_DIRECTORY VD1 ON (EM.EMPLOYEE_POSITION = VD1.DIRECTORY_ID) '.
                  'LEFT OUTER JOIN VIRTUAL_DIRECTORY VD2 ON (EM.EMPLOYEE_DEPARTMENT = VD2.DIRECTORY_ID) ',
                  
                'FROM PAYROLL_DOC_ITEM PDI ' .
                  'LEFT OUTER JOIN PAYROLL_DOCUMENT PRD ON (PDI.PAYROLL_DOC_ID = PRD.PAYROLL_DOC_ID) ',       

                'FROM PAYROLL_DOC_ITEM PDI ' .
                  'LEFT OUTER JOIN PAYROLL_DOCUMENT PD ON (PDI.PAYROLL_DOC_ID = PD.PAYROLL_DOC_ID) ' .
                  'LEFT OUTER JOIN EMPLOYEE EM ON (PDI.EMPLOYEE_ID = EM.EMPLOYEE_ID) ' .
                  'LEFT OUTER JOIN MASTER_REF MR1 ON (EM.NAME_PREFIX = MR1.MASTER_ID) '.
                  'LEFT OUTER JOIN MASTER_REF MR2 ON (EM.BANK_ID = MR2.MASTER_ID) ', 
                  
                'FROM PAYROLL_DOC_ITEM PDI ' .
                  'LEFT OUTER JOIN PAYROLL_DOCUMENT PRD ON (PDI.PAYROLL_DOC_ID = PRD.PAYROLL_DOC_ID) ',                     
                  
                'FROM PAYROLL_DOC_ITEM PDI ' .
                  'LEFT OUTER JOIN PAYROLL_DOCUMENT PRD ON (PDI.PAYROLL_DOC_ID = PRD.PAYROLL_DOC_ID) ',                                     

    );

    private $orderby = array(

                'ORDER BY PAYROLL_DOC_ITEM_ID ASC ',

                'ORDER BY PAYROLL_DOC_ITEM_ID ASC ',

                'ORDER BY EM.EMPLOYEE_CODE, PAYROLL_DOC_ITEM_ID ASC ',

                'GROUP BY PDI.EMPLOYEE_ID ORDER BY PDI.EMPLOYEE_ID ASC ',

                'ORDER BY PD.FROM_SALARY_DATE ASC ,EM.EMPLOYEE_CODE ASC ',

                'GROUP BY PDI.EMPLOYEE_ID, YYYYMM ORDER BY PDI.EMPLOYEE_ID ASC ',

                'GROUP BY PDI.EMPLOYEE_ID, YYYY ORDER BY PDI.EMPLOYEE_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PAYROLL_DOC_ITEM', 'PAYROLL_DOC_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>