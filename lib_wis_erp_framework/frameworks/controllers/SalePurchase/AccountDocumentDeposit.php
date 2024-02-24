<?php
/* 
    Purpose : Base class for Account Document Deposit
    Created By : Seubpong Monsar
    Created Date : 05/01/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AccountDocumentDeposit extends AccountDocumentBase
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

        //== Start ar ap Doc
        $crDocSet = $this->deriveCrDocumentSet();

        foreach ($crDocSet as $crDoc)
        {        
            if ($mode == 1)
            {
                list($p, $d) = ArApDocument::VerifyArApDoc($db, $param, $crDoc);
            }
            else
            {
                list($p, $d) = ArApDocument::ApproveArApDoc($db, $param, $crDoc);
            }

            $doc = $d;
            $cd = $d;

            $errors = $doc->GetChildArray('ERROR_ITEM');
            $errCnt = count($errors);
        
            if ($errCnt > 0)
            {
                return([$errCnt, $doc, null]);
            }
        } 
        //== End Ar/Ap Doc

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

        //No CR from Changes here

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
        if ($errCnt <= 0)
        {
            self::updateAssociateValues($cashDoc, $invDoc);
        }   

        return([$errCnt, $cashDoc, $invDoc]);
    }    
}

?>