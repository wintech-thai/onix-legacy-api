<?php
/* 
    Purpose : Base class for Account Document Invoice
    Created By : Seubpong Monsar
    Created Date : 10/03/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AccountDocumentDummy extends AccountDocumentBase
{
    function __construct($db, $data, $invoiceArea) 
    {
        parent::__construct($db, $data, $invoiceArea);
    }

    public function VerifyDocument()
    {
        $doc = $this->getDocument();
        return([0, $doc, $doc]);
    }
    
    public function ApproveDocument()
    {
        $doc = $this->getDocument();
        return([0, $doc, $doc]);
    }    
}

?>