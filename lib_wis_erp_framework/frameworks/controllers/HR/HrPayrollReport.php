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
    

    private static function PopulateEmployeePayrollItemMonth($db, $param, $data, $taxByMonth, $field)
    {
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $year = $data->getFieldValue('TAX_YEAR');
        
        $orders = array();
        $od = new CTable('');
        $od->setFieldValue('COLUMN_KEY', 'employee_name');
        $od->setFieldValue('ORDER_BY', 'ASC');
        array_push($orders, $od);
        $data->AddChildArray('@ORDER_BY_COLUMNS', $orders);

        list($p, $d) = Employee::GetEmployeeList($db, $param, $data);
        $emps = $d->GetChildArray("EMPLOYEE_LIST");

        $arr = array();
        foreach ($emps as $emp)
        {
            $empId = $emp->getFieldValue('EMPLOYEE_ID');
            $found = false;
            $sum = 0.00;

            foreach ($months as $mm)
            {
                $yyyymm = "$year/$mm";
                $key = "$empId:$yyyymm";

                $o = new CTable('');
                if (array_key_exists($key, $taxByMonth))
                {
                    $o = $taxByMonth[$key];
                    $found = true;
                }

                $amt = $o->GetFieldValue($field);                
                $emp->SetFieldValue($mm, $amt);  
                
                $sum = $sum + $amt;
            }

            if ($found)
            {
                $emp->SetFieldValue('TOTAL', $sum);  
                array_push($arr, $emp);
            }
        }

        $data->AddChildArray('EMPLOYEE_TAX_RECORDS', $arr);
    }

    private static function LoadEmployeePayrollItemByMonth($db, $data)
    {
        $u = new MPayrollDocumentItem($db);     
        list($cnt, $rows) = $u->Query(5, $data);

        $fields = ['EMPLOYEE_ID', 'YYYYMM'];
        $hash = CHelper::RowToHash($rows, $fields, ':');

        return $hash;
    }

    private static function LoadEmployeePayrollItemByYear($db, $data)
    {
        $u = new MPayrollDocumentItem($db);     
        list($cnt, $rows) = $u->Query(3, $data);

        $fields = ['EMPLOYEE_ID'];
        $hash = CHelper::RowToHash($rows, $fields, ':');

        return $hash;
    }    

    public static function GetEmployeeTaxMonthSummary($db, $param, $data)
    {
        $year = $data->getFieldValue('TAX_YEAR');
        if ($year == '')
        {
            $currDtm = CUtils::GetCurrentDateTimeInternal();
            $year = substr($currDtm, 0, 4);
        }

        $beginDtm = "$year/01/01 00:00:00";
        $endDtm = "$year/12/31 23:59:59";

        $data->setFieldValue('TAX_YEAR', $year);
        $data->setFieldValue('FROM_DOCUMENT_DATE', $beginDtm);
        $data->setFieldValue('TO_DOCUMENT_DATE', $endDtm);

        $deductByMonth = self::LoadEmployeePayrollItemByMonth($db, $data);
        self::PopulateEmployeePayrollItemMonth($db, $param, $data, $deductByMonth, 'DEDUCT_TAX');
        
        return(array($param, $data));  
    }  
    
    public static function GetEmployeeTaxYearSummary($db, $param, $data)
    {
        $year = $data->getFieldValue('TAX_YEAR');
        if ($year == '')
        {
            $currDtm = CUtils::GetCurrentDateTimeInternal();
            $year = substr($currDtm, 0, 4);
        }

        $beginDtm = "$year/01/01 00:00:00";
        $endDtm = "$year/12/31 23:59:59";

        $data->setFieldValue('TAX_YEAR', $year);
        $data->setFieldValue('FROM_DOCUMENT_DATE', $beginDtm);
        $data->setFieldValue('TO_DOCUMENT_DATE', $endDtm);

        $u = new MPayrollDocumentItem($db);     
        list($cnt, $rows) = $u->Query(6, $data);

        $data->AddChildArray('EMPLOYEE_YEARLY_SUMMARY', $rows);
        
        return(array($param, $data));  
    }  
    
    
    public static function GetEmployeeSocialInsuranceMonthSummary($db, $param, $data)
    {
        $year = $data->getFieldValue('TAX_YEAR');
        if ($year == '')
        {
            $currDtm = CUtils::GetCurrentDateTimeInternal();
            $year = substr($currDtm, 0, 4);
        }

        $beginDtm = "$year/01/01 00:00:00";
        $endDtm = "$year/12/31 23:59:59";

        $data->setFieldValue('TAX_YEAR', $year);
        $data->setFieldValue('FROM_DOCUMENT_DATE', $beginDtm);
        $data->setFieldValue('TO_DOCUMENT_DATE', $endDtm);

        $deductByMonth = self::LoadEmployeePayrollItemByMonth($db, $data);
        self::PopulateEmployeePayrollItemMonth($db, $param, $data, $deductByMonth, 'DEDUCT_SOCIAL_SECURITY');
        
        return(array($param, $data));  
    }
    
    public static function GetEmployeeRevenueMonthSummary($db, $param, $data)
    {
        $year = $data->getFieldValue('TAX_YEAR');
        if ($year == '')
        {
            $currDtm = CUtils::GetCurrentDateTimeInternal();
            $year = substr($currDtm, 0, 4);
        }

        $beginDtm = "$year/01/01 00:00:00";
        $endDtm = "$year/12/31 23:59:59";

        $data->setFieldValue('TAX_YEAR', $year);
        $data->setFieldValue('FROM_DOCUMENT_DATE', $beginDtm);
        $data->setFieldValue('TO_DOCUMENT_DATE', $endDtm);

        $deductByMonth = self::LoadEmployeePayrollItemByMonth($db, $data);
        self::PopulateEmployeePayrollItemMonth($db, $param, $data, $deductByMonth, 'RECEIVE_AMOUNT');
        
        return(array($param, $data));  
    } 
    

    private static function PopulateEmployeePayrollItemYearly($db, $param, $data, $taxByYear)
    {
        $fields = ['DEDUCT_TAX', 'DEDUCT_SOCIAL_SECURITY', 'RECEIVE_AMOUNT'];
        $year = $data->getFieldValue('TAX_YEAR');
        
        $orders = array();
        $od = new CTable('');
        $od->setFieldValue('COLUMN_KEY', 'employee_name');
        $od->setFieldValue('ORDER_BY', 'ASC');
        array_push($orders, $od);
        $data->AddChildArray('@ORDER_BY_COLUMNS', $orders);

        list($p, $d) = Employee::GetEmployeeList($db, $param, $data);
        $emps = $d->GetChildArray("EMPLOYEE_LIST");

        $arr = array();
        foreach ($emps as $emp)
        {
            $empId = $emp->getFieldValue('EMPLOYEE_ID');
            $found = false;
            $sum = 0.00;

            foreach ($fields as $fld)
            {
                $key = "$empId";

                $o = new CTable('');
                if (array_key_exists($key, $taxByYear))
                {
                    $o = $taxByYear[$key];
                    $found = true;
                }

                $amt = $o->GetFieldValue($fld);                
                $emp->SetFieldValue($fld, $amt);  
                
                $sum = $sum + $amt;
            }

            if ($found)
            {
                $emp->SetFieldValue('TOTAL', $sum);  
                array_push($arr, $emp);
            }
        }

        $data->AddChildArray('EMPLOYEE_YEARLY_SUMMARY', $arr);
    }

    public static function GetEmployeeYearlySummary($db, $param, $data)
    {
        $year = $data->getFieldValue('TAX_YEAR');
        if ($year == '')
        {
            $currDtm = CUtils::GetCurrentDateTimeInternal();
            $year = substr($currDtm, 0, 4);
        }

        $beginDtm = "$year/01/01 00:00:00";
        $endDtm = "$year/12/31 23:59:59";

        $data->setFieldValue('TAX_YEAR', $year);
        $data->setFieldValue('FROM_DOCUMENT_DATE', $beginDtm);
        $data->setFieldValue('TO_DOCUMENT_DATE', $endDtm);

        $yearlyTotal = self::LoadEmployeePayrollItemByYear($db, $data);
        self::PopulateEmployeePayrollItemYearly($db, $param, $data, $yearlyTotal);
        
        return(array($param, $data));  
    }      
}

?>