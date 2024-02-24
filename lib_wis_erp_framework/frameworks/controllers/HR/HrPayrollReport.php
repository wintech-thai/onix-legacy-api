<?php
/* 
    Purpose : Controller for HR payroll Report
    Created By : Seubpong Monsar
    Created Date : 03/03/2019 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class HrPayrollReport extends CBaseController
{
    private static $cfg = NULL;

    public static function GetEmployeePayrollByDateList($db, $param, $data)
    {
        $u = new MPayrollDocumentItem($db);
        $u->OverideOrderBy(4, 'ORDER BY PD.FROM_SALARY_DATE ASC, EM.EMPLOYEE_CODE ASC ');

        list($cnt, $rows) = $u->Query(4, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'PAYROLL_EMPLOYEE_LIST', $rows);
        
        return([$param, $p]);
    } 

    public static function GetEmployeePayrollByEmployeeList($db, $param, $data)
    {
        $u = new MPayrollDocumentItem($db);
        $u->OverideOrderBy(4, 'ORDER BY EM.EMPLOYEE_CODE ASC, PD.FROM_SALARY_DATE ASC ');

        list($cnt, $rows) = $u->Query(4, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'PAYROLL_EMPLOYEE_LIST', $rows);
        
        return([$param, $p]);
    }   
    
    public static function GetEmployeePayrollAccumulate($db, $param, $data)
    {
        $arr = [
                ['EMPLOYEE_OT', new MOtDocumentItem($db), 2, ''],
                ['EMPLOYEE_WORK', new MOtDocumentItem($db), 3, ''],
                ['EMPLOYEE_EXPENSE', new MEmployeeExpenseItem($db), 2, 'EXPENSE_TYPE'],
                ['EMPLOYEE_EXPENSE', new MEmployeeExpenseItem($db), 3, ''],
                ['EMPLOYEE_DEDUCTION', new MOtDocument($db), 3, ''],
            ];

        $dat = new CTable('');
        $dat->setFIeldValue('FROM_DOCUMENT_DATE', $data->getFieldValue('FROM_DOCUMENT_DATE'));
        $dat->setFIeldValue('TO_DOCUMENT_DATE', $data->getFieldValue('TO_DOCUMENT_DATE'));
        $dat->setFIeldValue('EMPLOYEE_ID', $data->getFieldValue('EMPLOYEE_ID'));

        $accums = [];
        foreach ($arr as $tupple)
        {
            list($key, $u, $idx, $extKey) = $tupple;
            list($cnt, $rows) = $u->Query($idx, $dat);

            if ($cnt == 1)
            {
                $o = $rows[0];
                $o->setFieldValue('ACCUM_TYPE', $key);
                array_push($accums, $o);
            }
            elseif ($cnt > 1)
            {
                foreach ($rows as $rw)
                {
                    $o = $rw;
                    $k = $rw->getFieldValue($extKey);
                    $o->setFieldValue('ACCUM_TYPE', "$key-$k");
                    array_push($accums, $o);                    
                }
            }
        }

        $p = new CTable('');
        self::PopulateRow($p, count($accums), 1, 'PAYROLL_EMPLOYEE_ACCUM_LIST', $accums);
        
        return([$param, $p]);
    }     
}

?>