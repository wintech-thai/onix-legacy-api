<?php
/*
    Purpose : Controller for Payroll Document
    Created By : Seubpong Monsar
    Created Date : 01/06/2019 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class PayrollDocument extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['PAYROLL_DOC_LIST', new MPayrollDocumentItem($db), 2, 0, 1], 
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MPayrollDocument($db);
        return($u);
    }

    public static function GetPayrollDocList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $u = new MPayrollDocument($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);
//CSql::SetDumpSQL(false);
        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'PAYROLL_DOC_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetPayrollDocInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MPayrollDocument($db);
        $obj = self::GetRowByID($data, $u, 1);
        
        if (!isset($obj))
        {
            throw new Exception("No payroll document in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);
        
        return(array($param, $obj));  
    }

    public static function CreatePayrollDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = new MPayrollDocument($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    } 

    public static function UpdatePayrollDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);          
        $u = new MPayrollDocument($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }

    public static function SavePayrollDoc($db, $param, $data)
    {
        $id = $data->GetFieldValue("PAYROLL_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreatePayrollDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdatePayrollDoc($db, $param, $data);
        }
        
        return(array($p, $d));
    }
    
    public static function DeletePayrollDoc($db, $param, $data)
    {
        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        $u = self::createObject($db);

        $obj = self::GetRowByID($data, $u, 1);
        if (!isset($obj))
        {
            throw new Exception("No payroll document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") != AccountDocument::ACCOUNT_DOC_PENDING)
        {
            throw new Exception("This document has been approved/canceled and not allowed to delete");
        }

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    private static function updateOriginalDocStatus($db, $param, $d)
    {
        //Document Type and ID are already in the passed objet
        $obj = new CTable('');

        $obj->SetFieldValue('PAYROLL_DOC_ID', $d->GetFieldValue('PAYROLL_DOC_ID'));
        $obj->SetFieldValue('EMPLOYEE_TYPE', $d->GetFieldValue('EMPLOYEE_TYPE'));        
        $obj->SetFieldValue("DOCUMENT_STATUS", AccountDocument::ACCOUNT_DOC_APPROVED);

        $u = self::createObject($db);

        $rc = $u->Update(2, $obj);

        //Update back to caller
        $d->SetFieldValue('DOCUMENT_STATUS', AccountDocument::ACCOUNT_DOC_APPROVED);
    }

    public static function ApprovePayrollDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $u = self::createObject($db);
        
        list($p, $obj) = self::GetPayrollDocInfo($db, $param, $data);

        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::SavePayrollDoc($db, $param, $data);        

        self::updateOriginalDocStatus($db, $param, $obj);        

        if ($tx)
        {
            $db->commit();
        }

        list($p, $d) = self::GetPayrollDocInfo($db, $param, $obj);

        return(array($param, $d));
    }

    public static function GetEmployeeAccumulate($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $startDate = $data->getFieldValue('START_DATE');
        $accumType = $data->getFieldValue('ACCUM_TYPE');
        
        $fromDate = '';
        $toDate = CUtils::DateAdd($startDate, -1);
        if ($accumType == "Y")
        {
            //Yearly
            $year = substr($startDate, 0, 4);
            $fromDate = sprintf("%s/01/01 00:00:00", $year); //1st date of year
        }

        $data->setFieldValue('FROM_DOCUMENT_DATE', $fromDate);
        $data->setFieldValue('TO_DOCUMENT_DATE', $toDate);

        $u = new MPayrollDocumentItem($db);

        list($cnt, $rows) = $u->Query(3, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'EMPLOYEE_PAYROLL_ACCUM_LIST', $rows);

        return([$param, $p]);
    }    
}

?>