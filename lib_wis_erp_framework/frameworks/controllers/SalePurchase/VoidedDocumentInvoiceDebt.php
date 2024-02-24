<?php
/* 
    Purpose : Base class for Account Document Invoice
    Created By : Seubpong Monsar
    Created Date : 10/03/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class VoidedDocumentInvoiceDebt extends VoidedDocumentBase
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

        //== Start ar ap Doc
        $crDoc = $this->deriveCrDocument();

        if ($mode == 1)
        {
            list($p, $d) = AccountDocument::VerifyAccountDoc($db, $param, $crDoc);
        }
        else
        {
            list($p, $d) = AccountDocument::ApproveAccountDoc($db, $param, $crDoc);
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