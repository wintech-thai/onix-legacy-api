<?php
/* 
    Purpose : Base class for Account Document
    Created By : Seubpong Monsar
    Created Date : 10/03/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AccountDocumentBase extends CBaseController
{
    private $db = null;
    private $area = 0;
    private $document = null;

    function __construct($db, $data, $invoiceArea) 
    {
        $this->area = $invoiceArea;
        $this->db = $db;
        $this->document = $data;
    } 
    
    protected function getDBConnection()
    {
        return($this->db);
    } 

    protected function getDocument()
    {
        return($this->document);
    }

    protected function getArea()
    {
        return($this->area);
    }    

    protected function deriveArApDocument()
    {
        $area = $this->getArea();
        $doc = $this->getDocument();

        //No actual document for AR and AP
        return($doc);
    }
/*
    protected function deriveCrDocumentFromChange($changeAmt, $setDocNo)
    {
        $isNegative = ($changeAmt < 0);

        $area = $this->getArea();
        $doc = $this->getDocument();
        
        $docID = $doc->GetFieldValue('ACCOUNT_DOC_ID');
        $docDate = $doc->GetFieldValue('DOCUMENT_DATE');

        $docNo = $setDocNo;
        if ($setDocNo == '')
        {
            $prefix = '(chg) ';
            if ($isNegative)
            {
                $prefix = '(rem) ';
            }

            $docNo = $prefix . $doc->GetFieldValue('DOCUMENT_NO');
        }

        $note = $doc->GetFieldValue('DOCUMENT_DESC');
        $ecode = $doc->GetFieldValue('ENTITY_CODE');
        $eid = $doc->GetFieldValue('ENTITY_ID');
        $amt = abs($changeAmt);
        $flag = 'Y';
        $brId = $doc->GetFieldValue('BRANCH_ID');
        $dueDate = $doc->GetFieldValue('DUE_DATE');
        $refDocNo = $doc->GetFieldValue('DOCUMENT_NO');
        $vatType = $doc->GetFieldValue('VAT_TYPE');
        $vatPct = "0.00";
        $vatAmt = "0.00";
        $whPct = "0.00";
        $whAmt = "0.00";
        $priceAmt = abs($changeAmt);
        $cashAmt = abs($changeAmt);
        $cashActualAmt = abs($changeAmt);
        $exp = abs($changeAmt);
        
        $drcrDoc = new CTable('');

        $drcrDoc->SetFieldValue('DRCR_FOR_EXPENSE_REVENUE', 'N'); //ใช้ลดหนี้/เพิ่มหนี้เท่านั้น ไม่นับเป็นรายรับ รายจ่าย
        $drcrDoc->SetFieldValue('INTERNAL_DRCR_FLAG', 'N'); //ถูกดึงมาอ้างในใบเสร็จ ครั้งหน้าได้
        $drcrDoc->SetFieldValue('DOCUMENT_DATE', $docDate);
        $drcrDoc->SetFieldValue('DOCUMENT_NO', $docNo);
        $drcrDoc->SetFieldValue('DOCUMENT_DESC', $note);
        $drcrDoc->SetFieldValue('ENTITY_CODE', $ecode);
        $drcrDoc->SetFieldValue('AR_AP_AMT', $amt);
        $drcrDoc->SetFieldValue('ALLOW_AR_AP_NEGATIVE', $flag);
        $drcrDoc->SetFieldValue('ENTITY_ID', $eid);
        $drcrDoc->SetFieldValue('BRANCH_ID', $brId);
        $drcrDoc->SetFieldValue('DUE_DATE', $dueDate);
        $drcrDoc->SetFieldValue('REF_DOCUMENT_NO', $refDocNo);
        $drcrDoc->SetFieldValue('VAT_TYPE', $vatType);
        $drcrDoc->SetFieldValue('VAT_PCT', $vatPct);
        $drcrDoc->SetFieldValue('VAT_AMT', $vatAmt);
        $drcrDoc->SetFieldValue('WH_TAX_PCT', $whPct);
        $drcrDoc->SetFieldValue('WH_TAX_AMT', $whAmt);
        $drcrDoc->SetFieldValue('PRICING_AMT', $priceAmt);
        $drcrDoc->SetFieldValue('CASH_RECEIPT_AMT', $cashAmt);
        $drcrDoc->SetFieldValue('CASH_ACTUAL_RECEIPT_AMT', $cashActualAmt);
        $drcrDoc->SetFieldValue('REVENUE_EXPENSE_AMT', $exp);
        $drcrDoc->SetFieldValue('ACCOUNT_DOC_ID', ''); //Set to empty string for adding
        $drcrDoc->SetFieldValue('DOCUMENT_STATUS', "1");
        $drcrDoc->SetFieldValue('AUTO_NUMBER', "N");

        if ($area == AccountDocument::ACCOUNT_DOC_AREA_AR)
        {
            $drcrDoc->SetFieldValue('DOCUMENT_TYPE', AccountDocument::ACCOUNT_DOC_CREDIT_NOTE);
            if ($isNegative)
            {
                $drcrDoc->SetFieldValue('DOCUMENT_TYPE', AccountDocument::ACCOUNT_DOC_DEBIT_NOTE);
            }
        }
        else
        {        
            $drcrDoc->SetFieldValue('DOCUMENT_TYPE', AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY);
            if ($isNegative)
            {
                $drcrDoc->SetFieldValue('DOCUMENT_TYPE', AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY);
            }            
        }

        return($drcrDoc);
    }    
*/
    protected function deriveCashDocument()
    {
        $area = $this->getArea();
        $doc = $this->getDocument();

        $docDate = $doc->GetFieldValue('DOCUMENT_DATE');
        $docNo = $doc->GetFieldValue('DOCUMENT_NO');
        $note = $doc->GetFieldValue('DOCUMENT_DESC');
        $acctCode = $doc->GetFieldValue('ACCOUNT_NO');
        $acctID = $doc->GetFieldValue('CASH_ACCOUNT_ID');

        $cashDoc = new CTable('CASH_DOC');

        if ($area == AccountDocument::ACCOUNT_DOC_AREA_AR)
        {
            //Sale Invoice
            $dt = CashDocument::CASH_DOC_IN;

            $cashDoc->SetFieldValue('TO_ACCOUNT_NO', $acctCode);
            $cashDoc->SetFieldValue('CASH_ACCOUNT_ID2', $acctID);
        }     
        else if ($area == AccountDocument::ACCOUNT_DOC_AREA_AP)
        {
            //Purchase Invoice
            $dt = CashDocument::CASH_DOC_OUT;

            $cashDoc->SetFieldValue('FROM_ACCOUNT_NO', $acctCode);
            $cashDoc->SetFieldValue('CASH_ACCOUNT_ID1', $acctID);
        } 

        $cashDoc->SetFieldValue('DOCUMENT_DATE', $docDate);
        $cashDoc->SetFieldValue('DOCUMENT_NO', $docNo);
        $cashDoc->SetFieldValue('NOTE', $note);
        $cashDoc->SetFieldValue('DOCUMENT_TYPE', $dt);
        $cashDoc->SetFieldValue('ALLOW_NEGATIVE', $doc->GetFieldValue('ALLOW_CASH_NEGATIVE')); 

        $cashAmt = $doc->GetFieldValue('CASH_ACTUAL_RECEIPT_AMT'); //Paid amount for AP is also in this field
        if ($cashAmt == '')
        {
            $cashAmt = "0.00";
        }
        $cashDoc->SetFieldValue('TOTAL_AMOUNT', $cashAmt); 
        
        return($cashDoc);
    }

    private function getDefaultCashAccount()
    {
        $db = self::getDBConnection();

        $g = new CTable('');
        $g->SetFieldValue('VARIABLE_NAME', 'SALE_PETTY_CASH_ACCT_NO');
        list($p, $gv) = GlobalVariable::GetSingleGlobalVariableInfo($db, new CTable(''), $g);
        $acctNo = $gv->GetFieldValue('VARIABLE_VALUE');

        $model = new MCashAccount($db);

        $ca = new CTable('');
        $ca->SetFieldValue("!EXT_EQUAL_STRING_COMPARE_FIELDS", "ACCOUNT_NO");  
        $ca->SetFieldValue('ACCOUNT_NO', $acctNo);

        list($cnt, $rows) = $model->Query(0, $ca);
        if ($cnt == 1)
        {
            $ca = $rows[0];
        }
        else
        {
            throw new Exception("Get zero or more than one account for [$acctNo]");
        }

        return($ca);
    }

    private function rearrangePayments($payments)
    {
        $changes = [];
        $regulars = [];

        foreach ($payments as $pmt)
        {
            $direction = $pmt->GetFieldValue('DIRECTION');
            if ($direction == '2')
            {
                array_push($changes, $pmt);
            }
            else
            {
                array_push($regulars, $pmt);
            }
        }

        foreach ($changes as $pmt)
        {
            array_push($regulars, $pmt);
        }

        return($regulars);
    }

    /* 1 Account Document can be more than one payment */
    protected function deriveCashDocumentSet()
    {
        $cashDocSet = [];
        //ACCOUNT_DOC_PAYMENTS
        $area = $this->getArea();
        $doc = $this->getDocument();

        $docDate = $doc->GetFieldValue('DOCUMENT_DATE');
        $docNo = $doc->GetFieldValue('DOCUMENT_NO');
        $note = $doc->GetFieldValue('DOCUMENT_DESC');
        $accountDocID = $doc->GetFieldValue('ACCOUNT_DOC_ID');

        $payments = self::rearrangePayments($doc->GetChildArray('ACCOUNT_DOC_PAYMENTS'));
        $cnt = count($payments)-1; //Minus 1 for Change
        $idx = 0;

        $changeCreditCnt = 0;

        foreach ($payments as $pmt)
        {
            $pmtType = $pmt->GetFieldValue('PAYMENT_TYPE');
            $acctCode = $pmt->GetFieldValue('ACCOUNT_NO');
            $acctID = $pmt->GetFieldValue('CASH_ACCOUNT_ID');
            $direction = $pmt->GetFieldValue('DIRECTION');
            $changeType = $pmt->GetFieldValue('CHANGE_TYPE');            
            $extFlag = $pmt->GetFieldValue('EXT_FLAG');

            if ($extFlag == 'D')
            {
                continue;
            }

            if ($pmtType == '4')
            {
                //Do nothing for Cheque
                continue;
            }

            $changeCreditAmt = 0;
            if (($direction == '2') && ($changeType == '2'))
            {
                //Change by Credit, no need to create cash doc

                $changeCreditAmt = $pmt->GetFieldValue('PAID_AMOUNT');
                $changeCreditCnt++; //Should be only 1

                continue;
            }

            if ($direction != '2')
            {
                //Not change
                $idx++;
            }
            
            $cashDoc = new CTable('CASH_DOC');

            if (($pmtType == '1') && ($acctID == ''))
            {
                //Cash & From Center, AR & AP use the same petty cash account
                $ca = self::getDefaultCashAccount();

                $acctCode = $ca->GetFieldValue('ACCOUNT_NO');
                $acctID = $ca->GetFieldValue('CASH_ACCOUNT_ID');                              
            }

            if ($area == AccountDocument::ACCOUNT_DOC_AREA_AR)
            {
                //Sale Invoice

                //Increase payment
                $dt = CashDocument::CASH_DOC_IN;
    
                $cashDoc->SetFieldValue('TO_ACCOUNT_NO', $acctCode);
                $cashDoc->SetFieldValue('CASH_ACCOUNT_ID2', $acctID);
                $cashDoc->SetFieldValue('CHANGE_FLAG', 'N');

                if ($direction == '2')
                {
                    //This is for the change by cash
                    $dt = CashDocument::CASH_DOC_OUT;
                    
                    $cashDoc->SetFieldValue('FROM_ACCOUNT_NO', $acctCode);
                    $cashDoc->SetFieldValue('CASH_ACCOUNT_ID1', $acctID);
                    $cashDoc->SetFieldValue('CHANGE_FLAG', 'Y');
                }
            }
            elseif ($area == AccountDocument::ACCOUNT_DOC_AREA_AP)
            {
                //Purchase Invoice

                //Decreased payment
                $dt = CashDocument::CASH_DOC_OUT;
    
                $cashDoc->SetFieldValue('FROM_ACCOUNT_NO', $acctCode);
                $cashDoc->SetFieldValue('CASH_ACCOUNT_ID1', $acctID);
                $cashDoc->SetFieldValue('CHANGE_FLAG', 'N');

                if ($direction == '2')
                {
                    //This is for the change by cash
                    $dt = CashDocument::CASH_DOC_IN;
                    
                    $cashDoc->SetFieldValue('TO_ACCOUNT_NO', $acctCode);
                    $cashDoc->SetFieldValue('CASH_ACCOUNT_ID2', $acctID);
                    $cashDoc->SetFieldValue('CHANGE_FLAG', 'Y');
                }
            }

            $docSubNo = "";
            if ($direction == '2')
            {
                $docSubNo = "(chg) $docNo";
            }
            else if ($cnt >= 1)
            {
                $docSubNo = "$docNo-$idx";
            }
            
            $cashDoc->SetFieldValue('ACCOUNT_DOC_ID', $accountDocID);
            $cashDoc->SetFieldValue('DOCUMENT_DATE', $docDate);
            $cashDoc->SetFieldValue('DOCUMENT_NO', $docSubNo);
            $cashDoc->SetFieldValue('NOTE', $note);
            $cashDoc->SetFieldValue('DOCUMENT_TYPE', $dt);
            $cashDoc->SetFieldValue('ALLOW_NEGATIVE', $doc->GetFieldValue('ALLOW_CASH_NEGATIVE')); 
    
            $cashAmt = $pmt->GetFieldValue('PAID_AMOUNT');
            if ($cashAmt == '')
            {
                $cashAmt = "0.00";
            }
            $cashDoc->SetFieldValue('TOTAL_AMOUNT', $cashAmt);

            if ($cashAmt > 0)
            {
                array_push($cashDocSet, $cashDoc);
            }
        }

        return([$cashDocSet, $changeCreditCnt, $changeCreditAmt]);
    }

    /* 1 Account Document can be more than one Cr document */
    protected function deriveCrDocumentSet()
    {
        $crDocSet = [];
        $doc = $this->getDocument();

        $docNo = $doc->GetFieldValue('DOCUMENT_NO');
        $deposits = $doc->GetChildArray('ACCOUNT_DOC_DEPOSITS');
        $idx = 0;

        foreach ($deposits as $dep)
        {          
            $extFlag = $dep->GetFieldValue('EXT_FLAG');
            $debtAmt = $dep->GetFieldValue('DEPOSIT_AMOUNT');

            if ($extFlag == 'D')
            {
                continue;
            }

            $idx++;

            $docSubNo = "$docNo-$idx";            
            
            if ($debtAmt > 0)
            {
                $crDoc = $this->deriveCrDocumentFromChange($debtAmt, $docSubNo);
                array_push($crDocSet, $crDoc);
            }
        }

        return($crDocSet);
    }
    
    protected function deriveInventoryDocument()
    {
        $area = $this->getArea();

        $doc = $this->getDocument();
        $docDate = $doc->GetFieldValue('DOCUMENT_DATE');
        $docNo = $doc->GetFieldValue('DOCUMENT_NO');
        $note = $doc->GetFieldValue('DOCUMENT_DESC');
        $locationCode = $doc->GetFieldValue('LOCATION_CODE');
        $locationID = $doc->GetFieldValue('LOCATION_ID');

        $invDoc = new CTable('INVENTORY_DOC');
        $dt = '';

        if ($area == AccountDocument::ACCOUNT_DOC_AREA_AR)
        {
            //Sale Invoice
            $dt = InventoryDocument::INV_DOCTYPE_EXPORT;

            $invDoc->SetFieldValue('FROM_LOCATION_CODE', $locationCode);
            $invDoc->SetFieldValue('LOCATION_ID1', $locationID);
        }
        elseif ($area == AccountDocument::ACCOUNT_DOC_AREA_AP)
        {
            //Purchasing Invoice
            $dt = InventoryDocument::INV_DOCTYPE_IMPORT;

            $invDoc->SetFieldValue('TO_LOCATION_CODE', $locationCode);
            $invDoc->SetFieldValue('LOCATION_ID2', $locationID);
        }

        $invDoc->SetFieldValue('DOCUMENT_DATE', $docDate);
        $invDoc->SetFieldValue('DOCUMENT_NO', $docNo);
        $invDoc->SetFieldValue('NOTE', $note);
        $invDoc->SetFieldValue('DOCUMENT_TYPE', $dt);
        $invDoc->SetFieldValue('ALLOW_NEGATIVE', $doc->GetFieldValue('ALLOW_INVENTORY_NEGATIVE'));

        $arr = [];
        $items = $doc->GetChildArray('ACCOUNT_DOC_ITEM');
        $cnt = 0;
        $linkID = 0;

        foreach ($items as $item)
        {
            $selType = $item->GetFieldValue('SELECTION_TYPE');
            if ($selType != '2')
            {          
                //Only for inventory item      
                continue;
            }

            $flag = $item->GetFieldValue('EXT_FLAG');
            if ($flag == 'D')
            {
                continue;
            }

            $inventoryItem = new CTable('INVENTORY_TX');
            $inventoryItem->SetFieldValue('EXT_FLAG', 'A');

            $qty = $item->GetFieldValue('QUANTITY');
            $amt = $item->GetFieldValue('REVENUE_EXPENSE_AMT');

            $inventoryItem->SetFieldValue('ITEM_ID', $item->GetFieldValue('ITEM_ID'));
            $inventoryItem->SetFieldValue('ITEM_CODE', $item->GetFieldValue('ITEM_CODE'));
            $inventoryItem->SetFieldValue('ITEM_QUANTITY', $qty);

            //Will actually need in case of import
            $inventoryItem->SetFieldValue('ITEM_AMOUNT', $amt); 
            if ($dt == InventoryDocument::INV_DOCTYPE_IMPORT)
            {
                $inventoryItem->SetFieldValue('UI_ITEM_AMOUNT', $amt);
                $price = 0.00;
                if ($qty > 0.00)
                {
                    $price = $amt/$qty;
                }
                $inventoryItem->SetFieldValue('UI_ITEM_UNIT_PRICE', $price);
            }
/*          
$itemCode = $item->GetFieldValue('ITEM_CODE');

CLog::WriteLn("In AccountDocumentBase Code=[$itemCode], Quantity=[$qty], Amount=[$amt]");              
*/
            $linkID++;

            //Will be used further
            $item->SetFieldValue('LINK_ID', $linkID);
            $inventoryItem->SetFieldValue('LINK_ID', $linkID);

            array_push($arr, $inventoryItem);
            $cnt++;
        }

        $invDoc->AddChildArray('TX_ITEM', $arr);

        if ($cnt <= 0)
        {
            return(null);
        }

        return($invDoc);
    }    
}

?>