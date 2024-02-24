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