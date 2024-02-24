<?php
/* 
    Purpose : Base class for Void Document Invoice
    Created By : Seubpong Monsar
    Created Date : 03/03/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class VoidedDocumentReceipt extends VoidedDocumentBase
{
    function __construct($db, $data, $invoiceArea) 
    {
        parent::__construct($db, $data, $invoiceArea);
    }

    private function processDocument($mode)
    {
        $ivd = null;
        $cd = null;

        $errCnt = 0;
        $doc = $this->getDocument();
        $origDoc = $this->getDocument();
        $db = $this->getDBConnection();

        $param = new CTable("PARAM");
        $param->SetFieldValue('AUTO_NUMBER', 'N');  
        $receiptAmt = $doc->GetFieldValue('AR_AP_AMT'); 

        //== Start DR Doc
        $drDocParent = $this->deriveDrDocument($receiptAmt, 'Y', 'N');
        if ($mode == 1)
        {
            list($p, $d) = AccountDocument::VerifyAccountDoc($db, $param, $drDocParent);
        }
        else
        {
            list($p, $d) = AccountDocument::ApproveAccountDoc($db, $param, $drDocParent);
        }

        $doc = $d;

        $errors = $doc->GetChildArray('ERROR_ITEM');
        $errCnt = count($errors);
        
        if ($errCnt > 0)
        {
            return([$errCnt, $doc, null]);
        }
        //== End DR Doc

        //== Start Cash Doc
        list($cashDocSet, $changeCreditCnt, $changeCreditAmt) = $this->deriveCashDocumentSet();

        if ($mode == 1)
        {
            list($p, $d) = CashDocument::VerifyCashDocSet($db, $param, $cashDocSet);
        }
        else
        {
            list($p, $d) = CashDocument::ApproveCashDocSet($db, $param, $cashDocSet);
        }

        $doc = $d;
        $cd = $d;

        $errors = $doc->GetChildArray('ERROR_ITEM');
        $errCnt = count($errors);
        
        if ($errCnt > 0)
        {
            return([$errCnt, $doc, null]);
        }        
        //== End Cash Doc

        //== Start DR note from Change
        //ซับซ้อนเกินไป ถอดฟีเจอร์นี้ออก ใช้วิธีการ wallet แทน
/*        
        if (($changeCreditCnt > 0) && ($changeCreditAmt != 0))
        {
            $drDoc = $this->deriveDrDocument($changeCreditAmt, 'N', 'Y');
            if ($mode == 1)
            {
                list($p, $d) = AccountDocument::VerifyAccountDoc($db, $param, $drDoc);
            }
            else
            {
                list($p, $d) = AccountDocument::ApproveAccountDoc($db, $param, $drDoc);
            }    
            
            $doc = $d;
    
            $errors = $doc->GetChildArray('ERROR_ITEM');
            $errCnt = count($errors);
            
            if ($errCnt > 0)
            {
                return([$errCnt, $doc, null]);
            }
        }
*/        
        //== End DR note from Change

        //Release receipt item to allow invoices to be included in the future
        $this::releaseReceiptItem($db, $origDoc);
        
        return([$errCnt, $cd, $ivd]);  
    }

    private static function releaseReceiptItem($db, $data)
    {
        //TODO : We should prevent the concurrent issue here
        //TODO : We should re read the current RECEIPT_ID again here

        $m = new MAccountDoc($db);
        $d = new CTable('');

        $refByID = '';

        $arr = $data->GetChildArray('ACCOUNT_DOC_RECEIPTS');
        foreach ($arr as $row)
        {
            $docID = $row->GetFieldValue('DOCUMENT_ID');

            $d->SetFieldValue('ACCOUNT_DOC_ID', $docID);
            $d->SetFieldValue('RECEIPT_ID', $refByID);
            $m->Update(5, $d);
        }
    }

    public function VerifyDocument()
    {
        list($errCnt, $cashDoc, $invDoc) = self::processDocument(1);
        return([$errCnt, $cashDoc, $invDoc]);
    }
    
    public function ApproveDocument()
    {
        list($errCnt, $cashDoc, $invDoc) = self::processDocument(2); 
        return([$errCnt, $cashDoc, $invDoc]);
    }    
}

?>