<?php
/* 
    Purpose : Base class for Account Document Invoice
    Created By : Seubpong Monsar
    Created Date : 10/03/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AccountDocumentReceipt extends AccountDocumentBase
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
        $arapDoc = $this->deriveArApDocument();
        if ($mode == 1)
        {
            list($p, $d) = ArApDocument::VerifyArApDoc($db, $param, $arapDoc);
        }
        else
        {
            list($p, $d) = ArApDocument::ApproveArApDoc($db, $param, $arapDoc);
        }

        $doc = $d;
        $cd = $d;

        $errors = $doc->GetChildArray('ERROR_ITEM');
        $errCnt = count($errors);
        
        if ($errCnt > 0)
        {
            return([$errCnt, $doc, null]);
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

        //== Start CR note from Change
        //ยกเลิก ระบบการทอนเงินด้วยเครดิต เนื่องจากทำให้ระบบซับซ้อนจนเกินไป ตอนที่ยกเลิกเอกสารแล้วต้องดูว่าดึงมาแสดงในใบเสร็จได้หรือไม่
        //จะใช้ระบบ wallet แทน
/*        
        if (($changeCreditCnt > 0) && ($changeCreditAmt != 0))
        {
            $crDoc = $this->deriveCrDocumentFromChange($changeCreditAmt, '');
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
        //== End CR note from Change

        return([$errCnt, $cd, $ivd]);  
    }

    private function updateAssociateValues($cashDoc, $invDoc)
    {
        if (!isset($invDoc))
        {
            return;
        }

        $d = $this->getDocument();

        $invDocID = $invDoc->GetFieldValue('DOC_ID');
        //$cashDocID = $cashDoc->GetFieldValue('CASH_DOC_ID');

        $d->SetFieldValue('INVENTORY_DOC_ID', $invDocID);
        //$d->SetFieldValue('CASH_DOC_ID', $cashDocID);
        
        $arr = $d->GetChildArray('ACCOUNT_DOC_ITEM');
        foreach ($arr as $accDocItem)
        {
            $linkID1 = $accDocItem->GetFieldValue('LINK_ID');

            $invArr = $invDoc->GetChildArray('TX_ITEM');
            foreach ($invArr as $tx)
            {
                $linkID2 = $tx->GetFieldValue('LINK_ID');
//CLog::WriteLn("LinkID1 = [$linkID1], LinkID2 = [$linkID2]");                
                if ($linkID1 != $linkID2)
                {
                    continue;
                }

                $txID = $tx->GetFieldValue('TX_ID');
                $accDocItem->SetFieldValue('INVENTORY_TX_ID', $txID);

                $flag = $accDocItem->GetFieldValue('EXT_FLAG');
               
                if ($flag != 'A')
                {
//CLog::WriteLn("Update : Flag = [$flag], ID=[$txID]");                     
                    $accDocItem->SetFieldValue('EXT_FLAG', 'E');
                }
            }
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
        if ($errCnt <= 0)
        {
            self::updateAssociateValues($cashDoc, $invDoc);
        }   

        return([$errCnt, $cashDoc, $invDoc]);
    }    
}

?>