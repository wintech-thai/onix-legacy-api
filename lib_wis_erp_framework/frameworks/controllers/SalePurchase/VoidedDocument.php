<?php
/*
    Purpose : Controller for Cancelation Document
    Created By : Seubpong Monsar
    Created Date : 01/16/2018 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class VoidedDocument extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MAccountDoc($db);
        return($u);
    }

    public static function GetVoidedDocList($db, $param, $data)
    {
        $u = new MVoidedDoc($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'VOIDED_DOC_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetVoidedDocInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MVoidedDoc($db);
        $obj = self::GetRowByID($data, $u, 0);
        
        if (!isset($obj))
        {
            throw new Exception("No voided document in database!!!");
        }

        return(array($param, $obj));  
    }

    public static function CreateVoidedDoc($db, $param, $data)
    {
        $u = new MVoidedDoc($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    }    

    public static function UpdateVoidedDoc($db, $param, $data)
    {
        $u = new MVoidedDoc($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }


    public static function SaveVoidedDoc($db, $param, $data)
    {
        $id = $data->GetFieldValue("VOIDED_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateVoidedDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateVoidedDoc($db, $param, $data);
        }
        
        return(array($p, $d));
    }
    
    private static function createAccountDocObject($db, $data)
    {
        //Class factory function

        $doc = null;

        $dt = $data->getFieldValue("DOCUMENT_TYPE");
        if ($dt == AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH)
        {
            $doc = new VoidedDocumentInvoiceCash($db, $data, AccountDocument::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT)
        {
            $doc = new VoidedDocumentInvoiceDebt($db, $data, AccountDocument::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY)
        {
            $doc = new VoidedDocumentInvoiceCash($db, $data, AccountDocument::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY)
        {
            $doc = new VoidedDocumentInvoiceDebt($db, $data, AccountDocument::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == AccountDocument::ACCOUNT_DOC_RECEIPT)
        {
            $doc = new VoidedDocumentReceipt($db, $data, AccountDocument::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == AccountDocument::ACCOUNT_DOC_RECEIPT_BUY)
        {
            $doc = new VoidedDocumentReceipt($db, $data, AccountDocument::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == AccountDocument::ACCOUNT_DOC_MISC_REVENUE)
        {
            //Reuse VoidedDocumentInvoiceCash but no any inventory item
            $doc = new VoidedDocumentInvoiceCash($db, $data, AccountDocument::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == AccountDocument::ACCOUNT_DOC_MISC_EXPENSE)
        {
            //Reuse VoidedDocumentInvoiceCash but no any inventory item
            $doc = new VoidedDocumentInvoiceCash($db, $data, AccountDocument::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == AccountDocument::ACCOUNT_DOC_SALE_ORDER)
        {
            $doc = new VoidedDocumentSaleOrder($db, $data, AccountDocument::ACCOUNT_DOC_SALE_ORDER);
        }             
        elseif ($dt == AccountDocument::ACCOUNT_DOC_BILL_SUMMARY)
        {
            $doc = new VoidedDocumentBillSummary($db, $data, AccountDocument::ACCOUNT_DOC_BILL_SUMMARY);
        }
        
        return($doc);
    }    

    private static function updateOriginalDocStatus($db, $param, $d)
    {
        //Document Type and ID are already in the passed objet
        $obj = new CTable('');

        $obj->SetFieldValue('ACCOUNT_DOC_ID', $d->GetFieldValue('ACCOUNT_DOC_ID'));
        $obj->SetFieldValue('DOCUMENT_TYPE', $d->GetFieldValue('DOCUMENT_TYPE'));        
        $obj->SetFieldValue("DOCUMENT_STATUS", AccountDocument::ACCOUNT_DOC_VOIDED);

        $u = self::createObject($db);

        $rc = $u->Update(3, $obj);

        //Update back to caller
        $d->SetFieldValue('DOCUMENT_STATUS', AccountDocument::ACCOUNT_DOC_VOIDED);
    }

    public static function VerifyVoidedDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $docDate = $data->GetFieldValue('DOCUMENT_DATE');

        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        list($p, $obj) = AccountDocument::GetAccountDocInfo($db, $param, $data);
        $obj->SetFieldValue('DOCUMENT_DATE', $docDate);
        $obj->SetFieldValue('ALLOW_INVENTORY_NEGATIVE', $data->GetFieldValue('ALLOW_INVENTORY_NEGATIVE'));
        $obj->SetFieldValue('ALLOW_CASH_NEGATIVE', $data->GetFieldValue('ALLOW_CASH_NEGATIVE'));
        $obj->SetFieldValue('ALLOW_AR_AP_NEGATIVE', $data->GetFieldValue('ALLOW_AR_AP_NEGATIVE'));
        
        $doc = self::createAccountDocObject($db, $obj);
        list($errCnt, $d) = $doc->VerifyDocument();

        return(array($param, $d));
    }

    public static function ApproveVoidedDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $u = self::createObject($db);
        
        list($p, $obj) = AccountDocument::GetAccountDocInfo($db, $param, $data);
        $obj->SetFieldValue('DOCUMENT_DATE', $data->GetFieldValue('DOCUMENT_DATE'));
        $obj->SetFieldValue('ALLOW_INVENTORY_NEGATIVE', $data->GetFieldValue('ALLOW_INVENTORY_NEGATIVE'));
        $obj->SetFieldValue('ALLOW_CASH_NEGATIVE', $data->GetFieldValue('ALLOW_CASH_NEGATIVE'));
        $obj->SetFieldValue('ALLOW_AR_AP_NEGATIVE', $data->GetFieldValue('ALLOW_AR_AP_NEGATIVE'));

        //Check status if it is void able
        if ($obj->GetFieldValue("DOCUMENT_STATUS") != AccountDocument::ACCOUNT_DOC_APPROVED)
        {
            throw new Exception("This document has not been approved so not allowed to cancel!!!");
        }

        if ($obj->GetFieldValue("RECEIPT_ID") != '')
        {
            throw new Exception("This document has been assigned to a receipt!!!");
        }

        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::SaveVoidedDoc($db, $param, $data);        

        $doc = self::createAccountDocObject($db, $obj);
        list($errCnt, $cashDoc, $invDoc) = $doc->ApproveDocument();
        if ($errCnt > 0)
        {
            if ($tx)
            {
                $db->rollBack();
            }

            return([$param, $cashDoc]);
        }

        self::updateOriginalDocStatus($db, $param, $obj);        

        if ($tx)
        {
            $db->commit();
        }

        list($p, $d) = AccountDocument::GetAccountDocInfo($db, $param, $obj);

        return(array($param, $d));
    }

}

?>