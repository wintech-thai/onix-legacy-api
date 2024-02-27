<?php
/*
    Purpose : Controller for Payroll Document
    Created By : Seubpong Monsar
    Created Date : 01/06/2019 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class EmployeeLeave extends CBaseController
{
    private static $cfg = NULL;
    private static $leaveFields = ['EMP_LEAVE_DOC_ID', 'LATE', 'SICK_LEAVE', 'PERSONAL_LEAVE', 'EXTRA_LEAVE', 'ANNUAL_LEAVE', 'ABNORMAL_LEAVE', 'DEDUCTION_LEAVE'];
    private static $empLeaveFields = ['SICK_LEAVE', 'PERSONAL_LEAVE', 'EXTRA_LEAVE', 'ANNUAL_LEAVE', 'LATE', 'ABNORMAL_LEAVE', 'DEDUCTION_LEAVE'];
    private static $empDeductionLeaves = ['ABNORMAL_LEAVE' => 1, 'DEDUCTION_LEAVE' => 2, 'LATE' => 3];

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['EMPLOYEE_LEAVE_RECORDS', new MEmployeeLeaveRecord($db), 0, 0, 0], 
        );

        self::$cfg = $config;

        return($config);
    }

    private static function LoadEmployeeLeaveDocs($db, $data)
    {
        //Required Leave Month, LeaveYear
        $u = new MEmployeeLeaveDoc($db);
        list($cnt, $rows) = $u->Query(1, $data); 

        $hash = CHelper::RowToHash($rows, array("EMPLOYEE_ID"), "");  
        return ($hash);
    }

    private static function PopulateLeaveDetail($leaves, $emp, $data)
    {
        $fields = self::$leaveFields;

        $leave = new CTable("");

        $id = $emp->getFieldValue("EMPLOYEE_ID");
        if (array_key_exists($id, $leaves))
        {
            $leave = $leaves[$id];
        }

        foreach ($fields as $f)
        {
            $value = $leave->getFieldValue($f);
            $emp->setFieldValue($f, $value);
        }

        $emp->setFieldValue('LEAVE_MONTH', $data->getFieldValue('LEAVE_MONTH'));
        $emp->setFieldValue('LEAVE_YEAR', $data->getFieldValue('LEAVE_YEAR'));
    }

    public static function GetEmployeeLeaveDocList($db, $param, $data)
    {
        $leaves = self::LoadEmployeeLeaveDocs($db, $data);

//CSql::SetDumpSQL(true);
        $u = new MEmployee($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $items = array();
        foreach ($rows as $emp)
        {
            self::PopulateLeaveDetail($leaves, $emp, $data);
            array_push($items, $emp);
        }

//CSql::SetDumpSQL(false);
        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'EMPLOYEE_LEAVE_LIST', $items);
        
        return(array($param, $pkg));
    }

    public static function GetEmployeeLeaveDocInfo($db, $param, $data)
    {
        //Should get zero or only one record
        $leaves = self::LoadEmployeeLeaveDocs($db, $data);
        self::PopulateLeaveDetail($leaves, $data, $data);
        
        $cnt = count($leaves);
        if ($cnt > 0)
        {
            $cfg = self::initSqlConfig($db);
            $u = new MEmployeeLeaveDoc($db);     
            self::PopulateChildItems($data, $u, $cfg);
        }
        
        return(array($param, $data));  
    }

    private static function LoadEmployeeLeaveByMonth($db, $data)
    {
        $u = new MEmployeeLeaveDoc($db);     
        list($cnt, $rows) = $u->Query(1, $data);

        $fields = ['EMPLOYEE_ID', 'LEAVE_YEAR', 'LEAVE_MONTH'];
        $hash = CHelper::RowToHash($rows, $fields, ':');

        return $hash;
    }

    private static function LoadEmployeeDeductionByMonth($db, $data)
    {
        $u = new MPayrollDeductionItem($db);     
        list($cnt, $rows) = $u->Query(4, $data);

        $fields = ['EMPLOYEE_ID', 'DEDUCTION_TYPE', 'YYYYMM'];
        $hash = CHelper::RowToHash($rows, $fields, ':');

        return $hash;
    }

    private static function PopulateLeaveRecords($leaveByMonth, $duductions, $data)
    {
        $fields = self::$empLeaveFields;
        $deductionLeaves = self::$empDeductionLeaves;

        $empId = $data->getFieldValue('EMPLOYEE_ID');
        $year = $data->getFieldValue('LEAVE_YEAR');

        $arr = array();

        for ($i=1; $i<=12; $i++)
        {
            $key1 = "$empId:$year:$i";            

            $o = new CTable('');
            if (array_key_exists($key1, $leaveByMonth))
            {
                $o = $leaveByMonth[$key1];
            }

            foreach ($deductionLeaves as $f => $deductionType)
            {
                $mm = str_pad("$i", 2, "0", STR_PAD_LEFT);
                $key2 = "$empId:$deductionType:$year/$mm";

                $deduction = 0;
                if (array_key_exists($key2, $duductions))
                {
                    $deductionObj = $duductions[$key2];
                    $deduction = $deductionObj->GetFieldValue('DURATION');
                }

                $o->SetFieldValue($f, $deduction);
            }  

            $o->SetFieldValue('LEAVE_MONTH', $i);
            foreach ($fields as $f)
            {
                $curr = $data->GetFieldValue($f);
                $amt = $o->GetFieldValue($f);
                $total = $curr + $amt;
                
                $data->SetFieldValue($f, $total);
            }
        
            array_push($arr, $o);
        }

        $data->AddChildArray('EMPLOYEE_LEAVE_RECORDS', $arr);
    }

    private static function LoadEmployeeLeaveByYear($db, $data)
    {
        $u = new MEmployeeLeaveRecord($db);     
        list($cnt, $rows) = $u->Query(2, $data);

        $fields = ['EMPLOYEE_ID', 'LEAVE_YEAR'];
        $hash = CHelper::RowToHash($rows, $fields, ':');

        return $hash;
    }

    private static function LoadEmployeeDeductionByYear($db, $data)
    {
        $u = new MPayrollDeductionItem($db);     
        list($cnt, $rows) = $u->Query(5, $data);

        $fields = ['EMPLOYEE_ID', 'DEDUCTION_TYPE', 'YYYY'];
        $hash = CHelper::RowToHash($rows, $fields, ':');

        return $hash;
    }

    private static function PopulateEmployeeLeaveSummary($db, $param, $data, $leaveByYear, $deductions)
    {
        $year = $data->getFieldValue('LEAVE_YEAR');

        $fields = self::$empLeaveFields;
        $deductionLeaves = self::$empDeductionLeaves;
        
        $orders = array();
        $od = new CTable('');
        $od->setFieldValue('COLUMN_KEY', 'employee_code');
        $od->setFieldValue('ORDER_BY', 'ASC');
        array_push($orders, $od);

        $data->AddChildArray('@ORDER_BY_COLUMNS', $orders);

        list($p, $d) = Employee::GetEmployeeList($db, $param, $data);
        $emps = $d->GetChildArray("EMPLOYEE_LIST");

        $arr = array();
        foreach ($emps as $emp)
        {
            $empId = $emp->getFieldValue('EMPLOYEE_ID');
            $key1 = "$empId:$year";     

            $o = new CTable('');
            if (array_key_exists($key1, $leaveByYear))
            {
                $o = $leaveByYear[$key1];
            }

            foreach ($deductionLeaves as $f => $deductionType)
            {
                $key2 = "$empId:$deductionType:$year";

                $deduction = 0;
                if (array_key_exists($key2, $deductions))
                {
                    $deductionObj = $deductions[$key2];
                    $deduction = $deductionObj->GetFieldValue('DURATION');
                }

                $o->SetFieldValue($f, $deduction);
            }  

            foreach ($fields as $f)
            {
                $amt = $o->GetFieldValue($f);                
                $emp->SetFieldValue($f, $amt);
            }

            array_push($arr, $emp);
        }

        $data->AddChildArray('EMPLOYEE_LEAVE_RECORDS', $arr);
    }

    public static function GetEmployeeLeaveSummary($db, $param, $data)
    {
        $year = $data->getFieldValue('LEAVE_YEAR');
        if ($year == '')
        {
            $currDtm = CUtils::GetCurrentDateTimeInternal();
            $year = substr($currDtm, 0, 4);
        }

        $beginDtm = "$year/01/01 00:00:00";
        $endDtm = "$year/12/31 23:59:59";

        $data->setFieldValue('LEAVE_YEAR', $year);
        $data->setFieldValue('FROM_DEDUCTION_DATE', $beginDtm);
        $data->setFieldValue('TO_DEDUCTION_DATE', $endDtm);

        $deductions = self::LoadEmployeeDeductionByYear($db, $data);
        $leaveByYear = self::LoadEmployeeLeaveByYear($db, $data);

        self::PopulateEmployeeLeaveSummary($db, $param, $data, $leaveByYear, $deductions);
        
        return(array($param, $data));  
    }    
    
    public static function GetEmployeeLeaveInfo($db, $param, $data)
    {
        $currDtm = CUtils::GetCurrentDateTimeInternal();

        $year = substr($currDtm, 0, 4);
        $beginDtm = "$year/01/01 00:00:00";
        $endDtm = "$year/12/31 23:59:59";

        $data->setFieldValue('LEAVE_YEAR', $year);
        $data->setFieldValue('FROM_DEDUCTION_DATE', $beginDtm);
        $data->setFieldValue('TO_DEDUCTION_DATE', $endDtm);

        $deductions = self::LoadEmployeeDeductionByMonth($db, $data);
        $leaveByMonth = self::LoadEmployeeLeaveByMonth($db, $data);
        self::PopulateLeaveRecords($leaveByMonth, $deductions, $data);
        
        return(array($param, $data));  
    }    

    private static function PopulateLeaveFields($leave)
    {
        $fields = self::$leaveFields;

        foreach ($fields as $f)
        {
            $value = $leave->getFieldValue($f);
            if ($value == '')
            {
                $leave->setFieldValue($f, "0.00");
            }
        }
    }

    public static function CreateEmployeeLeaveDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = new MEmployeeLeaveDoc($db);

        $childs = self::initSqlConfig($db);
        self::PopulateLeaveFields($data);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    } 

    public static function UpdateEmployeeLeaveDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);          
        $u = new MEmployeeLeaveDoc($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }

    public static function SaveEmployeeLeaveDoc($db, $param, $data)
    {
        $id = $data->GetFieldValue("EMP_LEAVE_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateEmployeeLeaveDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateEmployeeLeaveDoc($db, $param, $data);
        }
        
        return(array($p, $d));
    }
}

?>