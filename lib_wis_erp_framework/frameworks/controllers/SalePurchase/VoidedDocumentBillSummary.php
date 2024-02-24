<?php
/* 
    Purpose : Base class for Void Document Sale Order
    Created By : Seubpong Monsar
    Created Date : 01/16/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class VoidedDocumentBillSummary extends VoidedDocumentBase
{
    function __construct($db, $data, $invoiceArea) 
    {
        parent::__construct($db, $data, $invoiceArea);
    }

    private function processDocument($mode)
    {        
        $origDoc = $this->getDocument();
        $db = $this->getDBConnection();
                
        //Release bill summary item to allow invoices to be included in the future
        $this::releaseBillSummaryItem($db, $origDoc);

        return([0, null, null]);  
    }

    private static function releaseBillSummaryItem($db, $data)
    {
        //TODO : We should prevent the concurrent issue here
        //TODO : We should re read the current BILL_SUMMARY_ID again here

        $m = new MAccountDoc($db);
        $d = new CTable('');

        $refByID = '';

        $arr = $data->GetChildArray('ACCOUNT_DOC_RECEIPTS');
        foreach ($arr as $row)
        {
            $docID = $row->GetFieldValue('DOCUMENT_ID');

            $d->SetFieldValue('ACCOUNT_DOC_ID', $docID);
            $d->SetFieldValue('BILL_SUMMARY_ID', $refByID);
            $m->Update(10, $d);
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