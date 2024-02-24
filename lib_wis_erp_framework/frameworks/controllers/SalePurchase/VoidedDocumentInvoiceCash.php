<?php
/* 
    Purpose : Base class for Void Document Invoice
    Created By : Seubpong Monsar
    Created Date : 01/16/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class VoidedDocumentInvoiceCash extends VoidedDocumentBase
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
        $db = $this->getDBConnection();

        $param = new CTable("PARAM");
        $param->SetFieldValue('AUTO_NUMBER', 'N');  

        //== Start Inventory Doc
        $invDoc = $this->deriveInventoryDocument();
        if (isset($invDoc))
        {
            if ($mode == 1)
            {
                list($p, $d) = InventoryDocument::VerifyInventoryDoc($db, $param, $invDoc);
            }
            else
            {
                list($p, $d) = InventoryDocument::ApproveInventoryDoc($db, $param, $invDoc);
            }

            $doc = $d;
            $ivd = $d;
        }

        $errors = $doc->GetChildArray('ERROR_ITEM');
        $errCnt = count($errors);
        
        if ($errCnt > 0)
        {
            return([$errCnt, $doc, null]);
        }
        //== End Inventory Doc

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
            $crDoc = $this->deriveDrDocument($changeCreditAmt, 'N', 'Y');
            if ($mode == 1)
            {
                list($p, $d) = AccountDocument::VerifyAccountDoc($db, $param, $crDoc);
            }
            else
            {
                list($p, $d) = AccountDocument::ApproveAccountDoc($db, $param, $crDoc);
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

        
        return([$errCnt, $cd, $ivd]);  
    }

    public function VerifyDocument()
    {
        list($errCnt, $cashDoc, $invDoc) = self::processDocument(1);
        return([$errCnt, $cashDoc, $invDoc]);
    }
    
    public function ApproveDocument()
    {
        list($errCnt, $cashDoc, $invDoc) = self::processDocument(2); 
        self::unlinkPO();
        self::unlinkSaleOrder();
        
        return([$errCnt, $cashDoc, $invDoc]);
    }    
}

?>