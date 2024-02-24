<?php
/*
    Purpose : Controller for Payroll Document
    Created By : Seubpong Monsar
    Created Date : 01/06/2019 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class OtDocument extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['OT_DOC_LIST', new MOtDocumentItem($db), 0, 0, 1], 
            ['OT_EXPENSE_LIST', new MEmployeeExpenseItem($db), 0, 0, 1], 
            ['DEDUCTION_LIST', new MPayrollDeductionItem($db), 0, 0, 1], 
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MOtDocument($db);
        return($u);
    }

    public static function GetOtDocList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $u = new MOtDocument($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);
//CSql::SetDumpSQL(false);
        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'OT_DOC_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetOtDocInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MOtDocument($db);
        $obj = self::GetRowByID($data, $u, 1);
        
        if (!isset($obj))
        {
            throw new Exception("No OT document in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);
        
        return(array($param, $obj));  
    }

    public static function CreateOtDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = new MOtDocument($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    } 

    public static function UpdateOtDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);          
        $u = new MOtDocument($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }

    public static function SaveOtDoc($db, $param, $data)
    {
        $id = $data->GetFieldValue("OT_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateOtDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateOtDoc($db, $param, $data);
        }
        
        return(array($p, $d));
    }
    
    public static function DeleteOtDoc($db, $param, $data)
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
            throw new Exception("No OT document in database!!!");
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

        $obj->SetFieldValue('OT_DOC_ID', $d->GetFieldValue('OT_DOC_ID'));   
        $obj->SetFieldValue("DOCUMENT_STATUS", AccountDocument::ACCOUNT_DOC_APPROVED);

        $u = self::createObject($db);

        $rc = $u->Update(2, $obj);

        //Update back to caller
        $d->SetFieldValue('DOCUMENT_STATUS', AccountDocument::ACCOUNT_DOC_APPROVED);
    }

    public static function ApproveOtDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $u = self::createObject($db);
        
        list($p, $obj) = self::GetOtDocInfo($db, $param, $data);

        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::SaveOtDoc($db, $param, $data);        

        self::updateOriginalDocStatus($db, $param, $obj);        

        if ($tx)
        {
            $db->commit();
        }

        list($p, $d) = self::GetOtDocInfo($db, $param, $obj);

        return(array($param, $d));
    }
}

?>