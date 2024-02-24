<?php
/*
    Purpose : Controller for Inventory Document
    Created By : Seubpong Monsar
    Created Date : 09/26/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AccountDocument extends CBaseController
{
    const AR_ITEM_CODE = '###AR###';
    const AP_ITEM_CODE = '###AP###';

    const ACCOUNT_DOC_AREA_AR = 1;
    const ACCOUNT_DOC_AREA_AP = 2;

    const ACCOUNT_DOC_PENDING = '1';
    const ACCOUNT_DOC_APPROVED = '2';
    const ACCOUNT_DOC_VOIDED = '3';

    const ACCOUNT_DOC_INVOICE_BY_CASH = 1;
    const ACCOUNT_DOC_INVOICE_BY_DEBT = 2;
    const ACCOUNT_DOC_CREDIT_NOTE = 3; //reduce debt
    const ACCOUNT_DOC_DEBIT_NOTE = 4; //increase debt
    const ACCOUNT_DOC_RECEIPT = 9; 

    const ACCOUNT_DOC_INVOICE_BY_CASH_BUY = 5;
    const ACCOUNT_DOC_INVOICE_BY_DEBT_BUY = 6;
    const ACCOUNT_DOC_CREDIT_NOTE_BUY = 7; //reduce debt
    const ACCOUNT_DOC_DEBIT_NOTE_BUY = 8; //increase debt
    const ACCOUNT_DOC_RECEIPT_BUY = 10; 

    const ACCOUNT_DOC_MISC_REVENUE = 11; 
    const ACCOUNT_DOC_MISC_EXPENSE = 12; 

    const ACCOUNT_DOC_DEPOSIT_SALE = 13; 
    const ACCOUNT_DOC_DEPOSIT_PURCHASE = 14; 

    const ACCOUNT_DOC_SALE_ORDER = 15; 
    const ACCOUNT_DOC_BILL_SUMMARY = 16; 

    private static $cfg = NULL;
    private static $cfgAdjust = NULL;

    private static $orderByConfig = [
        'inventory_doc_no' => 'DOCUMENT_NO',
        'inventory_doc_date' => 'DOCUMENT_DATE',
        'inventory_doc_desc' => 'NOTE',
        'customer_name' => 'ENTITY_NAME',
        'supplier_name' => 'ENTITY_NAME',
        'inventory_doc_status' => 'DOCUMENT_STATUS',
    ];

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['ACCOUNT_DOC_ITEM', new MAccountDocItem($db), 1, 0, 2],
            ['ACCOUNT_DOC_PAYMENTS', new MAccountDocPayment($db), 1, 0, 2],
            ['ACCOUNT_DOC_RECEIPTS', new MAccountDocReceipt($db), 1, 0, 2],
            ['ACCOUNT_DOC_DISCOUNTS', new MAccountDocDiscount($db), 1, 0, 2],
            ['ACCOUNT_DOC_DEPOSITS', new MAccountDocDeposit($db), 1, 0, 2],
        );

        self::$cfg = $config;

        return($config);
    }

    private static function initSqlAdjustConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['ACCOUNT_DOC_ITEM', new MAccountDocItem($db), 1, 8, -1],
        );

        self::$cfgAdjust = $config;

        return($config);
    }

    private static function initSqlConfigForReceiptCopy($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['ACCOUNT_DOC_ITEM', new MAccountDocItem($db), 1, 0, 2],
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MAccountDoc($db);
        return($u);
    }

    private static function populateBillSumaryDocList($data)
    {
        $cat = $data->GetFieldValue('CATEGORY');

        $docTypeSet = sprintf("(%s, %s, %s)", 
            self::ACCOUNT_DOC_INVOICE_BY_DEBT,
            self::ACCOUNT_DOC_CREDIT_NOTE,
            self::ACCOUNT_DOC_DEBIT_NOTE);

        //Support only for AR

        $data->SetFieldValue('DOCUMENT_TYPE_SET', $docTypeSet);
        $data->SetFieldValue('ARAP_PAID_OFF_FLAG', 'N');

        //Even Pending status can be included
        $data->SetFieldValue('DOCUMENT_STATUS', '');
        
        //Not yet included in any Receipts
        $data->SetFieldValue('RECEIPT_ID', '');
        $data->SetFieldValue('RECEIPT_ABLE_FLAG', 'Y');     
        //And not yet included in any Bill Summaries
        $data->SetFieldValue('BILL_SUMMARY_ID', '');   
        $data->SetFieldValue('BILLSUM_ABLE_FLAG', 'Y');

        $data->SetFieldValue('BY_VOID_FLAG', 'N');
    }    

    private static function populateReceiveableDocList($data)
    {
        $cat = $data->GetFieldValue('CATEGORY');

        $docTypeSet = sprintf("(%s, %s, %s)", 
            self::ACCOUNT_DOC_INVOICE_BY_DEBT,
            self::ACCOUNT_DOC_CREDIT_NOTE,
            self::ACCOUNT_DOC_DEBIT_NOTE);

        if ($cat == self::ACCOUNT_DOC_AREA_AP)
        {
            $docTypeSet = sprintf("(%s, %s, %s)", 
                self::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY,
                self::ACCOUNT_DOC_CREDIT_NOTE_BUY,
                self::ACCOUNT_DOC_DEBIT_NOTE_BUY);
        }

        $data->SetFieldValue('DOCUMENT_TYPE_SET', $docTypeSet);
        $data->SetFieldValue('ARAP_PAID_OFF_FLAG', 'N');        
        $data->SetFieldValue('DOCUMENT_STATUS', self::ACCOUNT_DOC_APPROVED);

        $data->SetFieldValue('RECEIPT_ID', '');
        $data->SetFieldValue('RECEIPT_ABLE_FLAG', 'Y');
        $data->SetFieldValue('BY_VOID_FLAG', 'N');
    }

    public static function GetArApInvoiceList($db, $param, $data)
    {
        self::populateReceiveableDocList($data);
    
        $u = self::createObject($db);
        $u->OverideOrderBy(1, "ORDER BY EN.ENTITY_CODE ASC, AD.APPROVED_SEQ ASC ");
        list($cnt, $rows) = $u->Query(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $cnt, 1, 'ACCOUNT_DOC_LIST', $rows);

        return(array($param, $pkg));
    }

    public static function GetBillSummaryAbleDocList($db, $param, $data)
    {        
        self::populateBillSumaryDocList($data);

        $u = self::createObject($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'ACCOUNT_DOC_LIST', $rows);

        return(array($param, $pkg));
    }    


    public static function GetReceivableDocList($db, $param, $data)
    {        
        self::populateReceiveableDocList($data);

        $u = self::createObject($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'ACCOUNT_DOC_LIST', $rows);

        return(array($param, $pkg));
    }    

    public static function GetAccountDocList($db, $param, $data)
    {
        $u = self::createObject($db);

        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'ACCOUNT_DOC_LIST', $rows);

        return(array($param, $pkg));
    }

    public static function GetAccountDocInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No account document in database!!!");
        }

        $obj->SetFieldValue('ENTITY_ADDRESS_FLAG', $data->GetFieldValue('ENTITY_ADDRESS_FLAG'));

        self::PopulateChildItems($obj, $u, $cfg);
        self::populateBillSimulation($db, $param, $obj);
        self::populateEntityAddresses($db, $param, $obj);

        return(array($param, $obj));
    }

    private static function populateEntityAddresses($db, $param, $data)
    {
        $flag = $data->GetFieldValue('ENTITY_ADDRESS_FLAG');

        if ($flag != 'Y')
        {
            return;
        }

        $dat = new CTable("");
        $dat->SetFieldValue('ENTITY_ID', $data->GetFieldValue('ENTITY_ID'));

        $en = new MEntityAddress($db);
        list($cnt, $rows) = $en->Query(0, $dat);

        $arr = [];
        foreach ($rows as $row)
        {
            array_push($arr, $row);
        }

        $data->AddChildArray('ENTITY_ADDRESS_ITEMS', $arr);
    }

    public static function IsAccountDocExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "DOCUMENT_NO", "DOCUMENT_NO", 0);

        return(array($param, $o));
    }

    private static function getDocumentConfig($data, $ds)
    {
        $dt = $data->getFieldValue("DOCUMENT_TYPE");
        $vt = $data->getFieldValue("VAT_TYPE");

        $cfgs = [
            self::ACCOUNT_DOC_INVOICE_BY_CASH => ['ACCOUNT_DOC_CASH_TEMP', 'ACCOUNT_DOC_CASH_APPROVED'],
            self::ACCOUNT_DOC_INVOICE_BY_DEBT => ['ACCOUNT_DOC_DEPT_TEMP', 'ACCOUNT_DOC_DEPT_APPROVED'],
            self::ACCOUNT_DOC_CREDIT_NOTE => ['ACCOUNT_DOC_CR_TEMP', 'ACCOUNT_DOC_CR_APPROVED'],
            self::ACCOUNT_DOC_DEBIT_NOTE => ['ACCOUNT_DOC_DR_TEMP', 'ACCOUNT_DOC_DR_APPROVED'],
            self::ACCOUNT_DOC_RECEIPT => ['SALE_DOC_RECEIPT_TEMP', 'SALE_DOC_RECEIPT_APPROVED'],
            self::ACCOUNT_DOC_MISC_REVENUE => ['SALE_DOC_REVENUE_TEMP', 'SALE_DOC_REVENUE_APPROVED'],
            self::ACCOUNT_DOC_DEPOSIT_SALE => ['DEPOSIT_DOC_SALE_TEMP', 'DEPOSIT_DOC_SALE_APPROVED'],
            
            self::ACCOUNT_DOC_INVOICE_BY_CASH_BUY => ['ACCOUNT_DOC_CASH_BUY_TEMP', 'ACCOUNT_DOC_CASH_BUY_APPROVED'],
            self::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY => ['ACCOUNT_DOC_DEPT_BUY_TEMP', 'ACCOUNT_DOC_DEPT_BUY_APPROVED'],
            self::ACCOUNT_DOC_CREDIT_NOTE_BUY => ['ACCOUNT_DOC_CR_BUY_TEMP', 'ACCOUNT_DOC_CR_BUY_APPROVED'],
            self::ACCOUNT_DOC_DEBIT_NOTE_BUY => ['ACCOUNT_DOC_DR_BUY_TEMP', 'ACCOUNT_DOC_DR_BUY_APPROVED'],            
            self::ACCOUNT_DOC_RECEIPT_BUY => ['PURCHASE_DOC_RECEIPT_TEMP', 'PURCHASE_DOC_RECEIPT_APPROVED'],
            self::ACCOUNT_DOC_MISC_EXPENSE => ['PURCHASE_DOC_EXPENSE_TEMP', 'PURCHASE_DOC_EXPENSE_APPROVED'],
            self::ACCOUNT_DOC_DEPOSIT_PURCHASE => ['DEPOSIT_DOC_PURCHASE_TEMP', 'DEPOSIT_DOC_PURCHASE_APPROVED'],

            self::ACCOUNT_DOC_SALE_ORDER => ['SO_DOC_NUMBER', ''], #No need number for approved
            self::ACCOUNT_DOC_BILL_SUMMARY => ['SALE_BILL_SUMMARY', ''], #No need number for approved
        ];

        $cfgs_nv = [
            self::ACCOUNT_DOC_INVOICE_BY_CASH => ['ACCOUNT_DOC_CASH_TEMP_NV', 'ACCOUNT_DOC_CASH_APPROVED_NV'],
            self::ACCOUNT_DOC_INVOICE_BY_DEBT => ['ACCOUNT_DOC_DEPT_TEMP_NV', 'ACCOUNT_DOC_DEPT_APPROVED_NV'],
            self::ACCOUNT_DOC_CREDIT_NOTE => ['ACCOUNT_DOC_CR_TEMP_NV', 'ACCOUNT_DOC_CR_APPROVED_NV'],
            self::ACCOUNT_DOC_DEBIT_NOTE => ['ACCOUNT_DOC_DR_TEMP_NV', 'ACCOUNT_DOC_DR_APPROVED_NV'],

            self::ACCOUNT_DOC_INVOICE_BY_CASH_BUY => ['ACCOUNT_DOC_CASH_BUY_TEMP_NV', 'ACCOUNT_DOC_CASH_BUY_APPROVED_NV'],
            self::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY => ['ACCOUNT_DOC_DEPT_BUY_TEMP_NV', 'ACCOUNT_DOC_DEPT_BUY_APPROVED_NV'],
            self::ACCOUNT_DOC_CREDIT_NOTE_BUY => ['ACCOUNT_DOC_CR_BUY_TEMP_NV', 'ACCOUNT_DOC_CR_BUY_APPROVED_NV'],
            self::ACCOUNT_DOC_DEBIT_NOTE_BUY => ['ACCOUNT_DOC_DR_BUY_TEMP_NV', 'ACCOUNT_DOC_DR_BUY_APPROVED_NV'],         

            self::ACCOUNT_DOC_SALE_ORDER => ['SO_DOC_NUMBER', ''], #No need number for approved    
        ];

        if ($vt == '1')
        {
            //No VAT
            list($temp, $approved) = $cfgs_nv[$dt];
        }
        else
        {
            list($temp, $approved) = $cfgs[$dt];
        }

        if ($ds == self::ACCOUNT_DOC_PENDING)
        {
            return($temp);
        }

        return($approved);
    }

    private static function populateBillSimulation($db, $param, $data)
    {
        $id = $data->GetFieldValue('BILL_SIMULATE_ID');
        if ($id != '')
        {
            $arr = [];

            list($p, $d) = BillSimulate::GetBillSimulateInfo($db, $param, $data);

            array_push($arr, $d);
            $data->AddChildArray('ACCOUNT_BILLSIM_ITEM', $arr);
        }
    }

    public static function UpdatePaymentIndex($db, $data)
    {
        self::populateIndexField($data);
        $u = new MAccountDoc($db);
        $u->Update(9, $data);
    }

    private static function populateIndexField($data)
    {
        $arr = $data->GetChildArray('ACCOUNT_DOC_RECEIPTS');
        $temp = [];
        foreach ($arr as $row)
        {
            $flag = $row->GetFieldValue('EXT_FLAG');
            if ($flag == 'D')
            {
                continue;
            }

            $docNo = $row->GetFieldValue('DOCUMENT_NO');
            array_push($temp, $docNo);            
        }
        
        $index = join(';', $temp);
        $data->SetFieldValue('INDEX_DOC_INCLUDE', $index);

        #=== Begin Project Index
        $arr = $data->GetChildArray('ACCOUNT_DOC_ITEM');
        $temp = [];
        $dupHash = [];
        foreach ($arr as $row)
        {
            $flag = $row->GetFieldValue('EXT_FLAG');
            if ($flag == 'D')
            {
                continue;
            }

            $projCode = $row->GetFieldValue('PROJECT_CODE');
            if ($projCode == '')
            {
                continue;
            }

            if (!array_key_exists($projCode, $dupHash))
            {
                $dupHash[$projCode] = $projCode;   
                array_push($temp, $projCode);
            }
        }

        $index = join(';', $temp);
        $data->SetFieldValue('INDEX_PROJECT', $index);  
        #=== End Project Index

        //Payment Index
        $arr = $data->GetChildArray('ACCOUNT_DOC_PAYMENTS');
        $temp = [];
        foreach ($arr as $row)
        {
            $flag = $row->GetFieldValue('EXT_FLAG');
            if ($flag == 'D')
            {
                continue;
            }

            $direction = $row->GetFieldValue('DIRECTION');
            if ($direction == '2')
            {
                //Changes
                continue;
            }

            $pt = $row->GetFieldValue('PAYMENT_TYPE');
            $cheqNo = $row->GetFieldValue('CHEQUE_NO');
            $accNo = $row->GetFieldValue('ACCOUNT_NO');
            $amt = $row->GetFieldValue('PAID_AMOUNT');

            $pmtDef = "$pt|$cheqNo|$accNo|$amt";
            array_push($temp, $pmtDef);            
        }
        
        $index = join(';', $temp);
        $data->SetFieldValue('INDEX_PAYMENT', $index);
    }    

    private static function updateOrCreateBillSimulate($db, $param, $data)
    {
        //This function is already called in transaction

        $arr = $data->GetChildArray('ACCOUNT_BILLSIM_ITEM');
        if (!isset($arr))
        {
            return;
        }

        if (count($arr) <= 0)
        {
            return;
        }

        $billSim = $arr[0];
        $id = $data->GetFieldValue('BILL_SIMULATE_ID');
        $oldID = $data->GetFieldValue('ORG_BILL_SIMULATE_ID');

        $billSim->SetFieldValue('DOCUMENT_NO', $data->GetFieldValue('DOCUMENT_NO'));
        $billSim->SetFieldValue('DOCUMENT_DATE', $data->GetFieldValue('DOCUMENT_DATE'));
        $billSim->SetFieldValue('DOCUMENT_TYPE', $data->GetFieldValue('DOCUMENT_TYPE'));
        $billSim->SetFieldValue('DOCUMENT_STATUS', $data->GetFieldValue('DOCUMENT_STATUS'));
        $billSim->SetFieldValue('SIMULATION_FLAG', 'N');
        $billSim->SetFieldValue('BRANCH_ID', $data->GetFieldValue('BRANCH_ID'));
        $billSim->SetFieldValue('CUSTOMER_ID', $data->GetFieldValue('ENTITY_ID'));
        $billSim->SetFieldValue('SIMULATE_TIME', $data->GetFieldValue('DOCUMENT_DATE'));
        $billSim->SetFieldValue('NOTE', $data->GetFieldValue('DOCUMENT_DESC'));

        if ($id == '')
        {
            if ($oldID != '')
            {
                //Create from copy
                $billSim->SetFieldValue('BILL_SIMULATE_ID', $oldID);
                
                //No need to create bill simulate if copy
                //list($p, $d) = BillSimulate::CopyBillSimulate($db, $param, $billSim);
                $data->SetFieldValue('BILL_SIMULATE_ID', '');
            }
            else
            {
                list($p, $d) = BillSimulate::CreateBillSimulate($db, $param, $billSim);
                $data->SetFieldValue('BILL_SIMULATE_ID', $d->GetFieldValue('BILL_SIMULATE_ID'));
            }            
        }
        else
        {
            //Update
            list($p, $d) = BillSimulate::UpdateBillSimulate($db, $param, $billSim);
        }
    }

    private static function updateDefaultFields($data)
    {
        $receiptFlag = $data->GetFieldValue('RECEIPT_FLAG');
        $byVoidFlag = $data->GetFieldValue('BY_VOID_FLAG');
        $internalDrCrFlag = $data->GetFieldValue('INTERNAL_DRCR_FLAG');
        $soInUsedFlag = $data->GetFieldValue('SO_IN_USED_BY_INVOICE');
        $isApprovedDocNo = $data->GetFieldValue('IS_APPROVED_DOC_NO');
        $drcrForExpenseRevenue = $data->GetFieldValue('DRCR_FOR_EXPENSE_REVENUE');

        if ($drcrForExpenseRevenue == '')
        {
            $data->SetFieldValue('DRCR_FOR_EXPENSE_REVENUE', 'Y');
        }

        if ($receiptFlag == '')
        {
            $data->SetFieldValue('RECEIPT_FLAG', 'N');
        }

        if ($byVoidFlag == '')
        {
            $data->SetFieldValue('BY_VOID_FLAG', 'N');
        }

        if ($internalDrCrFlag == '')
        {
            $data->SetFieldValue('INTERNAL_DRCR_FLAG', 'N');
        }

        if ($soInUsedFlag == '')
        {
            $data->SetFieldValue('SO_IN_USED_BY_INVOICE', 'N');
        }

        if ($isApprovedDocNo == '')
        {
            $data->SetFieldValue('IS_APPROVED_DOC_NO', 'N');
        }        
        
        $docDate = $data->GetFieldValue('DOCUMENT_DATE');
        $dueDate = $data->GetFieldValue('DUE_DATE');
        if ($dueDate == '')
        {
            $data->SetFieldValue('DUE_DATE', $docDate);
        }

        CHelper::PopulateDocumentDateKey($data, "DOCUMENT_DATE");

        $data->SetFieldValue('RECEIPT_ID', '');
    }

    public static function createUpdateDummyItems($data)
    {
        $drcrExpRev = $data->getFieldValue('DRCR_FOR_EXPENSE_REVENUE');
        $dt = $data->getFieldValue('DOCUMENT_TYPE');
        $projID = $data->getFieldValue('PROJECT_ID');
        $projCd = $data->getFieldValue('PROJECT_CODE');

        if (($dt != self::ACCOUNT_DOC_DEBIT_NOTE_BUY) &&
            ($dt != self::ACCOUNT_DOC_CREDIT_NOTE_BUY) &&
            ($dt != self::ACCOUNT_DOC_DEBIT_NOTE) && 
            ($dt != self::ACCOUNT_DOC_CREDIT_NOTE))
        {
            return;
        }

        if ($drcrExpRev != 'Y')
        {
            //ควรจะสร้าง item เมื่อเป็น ลดหนี้เพิ่มหนี้ ที่อีกฝั่งบัญชีเป็นรายรับรายจ่ายเท่านั้น
            return;
        }

        $factor = 1;
        if (($dt == self::ACCOUNT_DOC_CREDIT_NOTE_BUY) || ($dt == self::ACCOUNT_DOC_CREDIT_NOTE))
        {
            $factor = -1;;
        }

        $arr = $data->getChildArray('ACCOUNT_DOC_ITEM');
        $cnt = count($arr);

        $itm = new CTable('');
        if ($cnt > 0)
        {
            //Already created
            $itm = $arr[0];
            $itm->setFieldValue('EXT_FLAG', 'E');            
        }
        else
        {
            $itm->setFieldValue('EXT_FLAG', 'A'); 
            array_push($arr, $itm);
            
            $data->addChildArray('ACCOUNT_DOC_ITEM', $arr);
        }

        $itm->setFieldValue('PROJECT_ID', $projID);
        $itm->setFieldValue('PROJECT_CODE', $projCd);
        $itm->setFieldValue('QUANTITY', '0.00');
        $itm->setFieldValue('UNIT_PRICE', '0.00');
        $itm->setFieldValue('AMOUNT', '0.00');
        $itm->setFieldValue('DISCOUNT_AMT', '0.00');
        $itm->setFieldValue('FACTOR', $factor);

        
        $whAmt = $data->getFieldValue('WH_TAX_AMT');
        $whFlag = 'N';
        if ($whAmt > 0.00)
        {
            $whFlag = 'Y';
        }
        $itm->setFieldValue('WH_TAX_FLAG', $whFlag);
        $itm->setFieldValue('WH_TAX_PCT', $data->getFieldValue('WH_TAX_PCT'));        
        $itm->setFieldValue('WH_TAX_AMT', $whAmt);


        $vatAmt = $data->getFieldValue('VAT_AMT');
        $vatFlag = 'N';
        if ($vatAmt > 0.00)
        {
            $vatFlag = 'Y';
        } 
        $itm->setFieldValue('VAT_TAX_FLAG', $vatFlag);
        $itm->setFieldValue('VAT_TAX_PCT', $data->getFieldValue('VAT_PCT'));
        $itm->setFieldValue('VAT_TAX_AMT', $vatAmt);


        $itm->setFieldValue('REVENUE_EXPENSE_AMT', $data->getFieldValue('REVENUE_EXPENSE_AMT'));
        $itm->setFieldValue('AR_AP_AMT', $data->getFieldValue('AR_AP_AMT'));
        $itm->setFieldValue('TOTAL_AMT', '0.00');
        $itm->setFieldValue('FREE_TEXT', $data->getFieldValue('DOCUMENT_DESC'));
        $itm->setFieldValue('SELECTION_TYPE', '3');
    }    

    private static function updateChildItems($data)
    {
        $projID = $data->getFieldValue('PROJECT_ID');

        $arr = $data->getChildArray('ACCOUNT_DOC_ITEM');
        foreach ($arr as $itm)
        {
            $flag = $itm->getFieldValue('EXT_FLAG');
            if ($flag == 'D')
            {
                continue;
            }

            $factor = $itm->getFieldValue('FACTOR');
            if (($factor != 1) && ($factor != -1))
            {
                $itm->setFieldValue('FACTOR', '1');
            }

            $poID = $itm->getFieldValue('PO_ID');
            if ($poID == '')
            {
                //Not neccessary now

                //Use project id from parent if not import from PO
                //$itm->setFieldValue('PROJECT_ID', $projID);
                //if ($flag != 'A')
                //{
                //    $itm->setFieldValue('EXT_FLAG', 'E');
                //}
            }
        }
    }

    public static function CreateAccountDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $auto_no = $param->GetFieldValue('AUTO_NUMBER');

        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::updateDefaultFields($data);
                
        self::createUpdateDummyItems($data);
        self::updateChildItems($data);
        
        self::populateIndexField($data);
        //Start Create document number
        
        if ($auto_no != 'N')
        {
            if (CHelper::IsApprovedDocNumberRequired($db, $data))
            {
                //Use approved document number immediately
                $cfg = self::getDocumentConfig($data, self::ACCOUNT_DOC_APPROVED);
                $data->setFieldValue("IS_APPROVED_DOC_NO", 'Y');
            }
            else
            {
                $cfg = self::getDocumentConfig($data, self::ACCOUNT_DOC_PENDING);
            }

            $t = new CTable("");
            $t->setFieldValue("DOC_TYPE", $cfg);
            CHelper::PopulateCustomVariables($data, $t, 1);
            
            list($p, $d) = DocumentNumber::GenerateDocumentNumber($db, $param, $t);
            $docNo = $d->getFieldValue("LAST_DOCUMENT_NO");
            $data->setFieldValue("DOCUMENT_NO", $docNo);
            //End Create document number
        }

        $u = self::createObject($db);
        
        $data->SetFieldValue('ARAP_PAID_OFF_FLAG', 'N');
        $data->SetFieldValue('DOCUMENT_STATUS', self::ACCOUNT_DOC_PENDING);
        self::PopulateStartEndDate($data, 'DOCUMENT_DATE', true);

        //BILL_SIMULATE_ID will be populated here
        self::updateOrCreateBillSimulate($db, $param, $data);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);
        self::updateAssociateDocuments($db, $data, false);
        self::createDocItemsForReceipt($db, $data);
        self::updateQuotationInuseFlag($db, $data, 'Y');
        self::updateSaleOrderInuseFlag($db, $data, 'Y');

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    private static function isDocumentEditable($obj)
    {
        if (($obj->GetFieldValue("DOCUMENT_STATUS") == self::ACCOUNT_DOC_APPROVED) ||
            ($obj->GetFieldValue("DOCUMENT_STATUS") == self::ACCOUNT_DOC_VOIDED))
        {
            throw new Exception("This document has been approved/voided and not allowed to update");
        }
    }

    private static function isDocumentAdjustable($obj)
    {
        if (($obj->GetFieldValue("DOCUMENT_STATUS") == self::ACCOUNT_DOC_APPROVED) ||
            ($obj->GetFieldValue("DOCUMENT_STATUS") == self::ACCOUNT_DOC_VOIDED))
        {
            return(true);
        }

        throw new Exception("This document has been approved/voided and not allowed to update");
    }

    private static function isRequireUpdateAssociate($data)
    {
        $dt = $data->GetFieldValue('DOCUMENT_TYPE');

        if (
            ($dt != self::ACCOUNT_DOC_RECEIPT) && 
            ($dt != self::ACCOUNT_DOC_RECEIPT_BUY) &&
            ($dt != self::ACCOUNT_DOC_BILL_SUMMARY) && 
            ($dt != self::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY) && 
            ($dt != self::ACCOUNT_DOC_INVOICE_BY_CASH_BUY)
           )
        {
            return(false);
        }

        return(true);
    }

    //WH and some others Report need this function
    public static function CreateDocItemsForReceipt($db, $data)    
    {
        $dt = $data->getFieldValue('DOCUMENT_TYPE');
        if (($dt != self::ACCOUNT_DOC_RECEIPT_BUY) && 
            ($dt != self::ACCOUNT_DOC_RECEIPT))
        {
            return;
        }

        $receiptID = $data->getFieldValue('ACCOUNT_DOC_ID');
        $mi = new MAccountDocItem($db);

        //Delete doc item from receipt first

        $rcp = new CTable('');
        $rcp->setFieldValue('ACCOUNT_DOC_ID', $receiptID);
        $mi->Delete(2, $rcp);

        $docSet = CHelper::CreateSetFromArray($data, 'ACCOUNT_DOC_RECEIPTS', 'DOCUMENT_ID');

        if ($docSet == '')
        {
            //Do nothing
            return;
        }

        $adi = new CTable('');
        $adi->setFieldValue('ACCOUNT_DOC_ID_SET', $docSet);
        list($cnt, $rows) = $mi->Query(1, $adi);

        //Get doc item for all document in the receipt
        foreach ($rows as $r)
        {
            $r->setFieldValue('REF_DOC_ID', $r->getFieldValue('ACCOUNT_DOC_ID'));
            $r->setFieldValue('ACCOUNT_DOC_ID', $receiptID);
            $mi->Insert(0, $r, true);
        }
    }

    private static function updateQuotationInuseFlag($db, $data, $flag)
    {
        $auxID = $data->GetFieldValue('REF_QUOTATION_ID');
        if ($auxID == '')
        {
            return;
        }

        $ad = new MAuxilaryDoc($db);
        $d = new CTable('');
        
        $d->SetFieldValue('AUXILARY_DOC_ID', $auxID);
        $d->SetFieldValue('IN_USED_BY_SO', $flag);
        $ad->Update(2, $d);
    }

    private static function updateSaleOrderInuseFlag($db, $data, $flag)
    {
        $docID = $data->GetFieldValue('REF_SALE_ORDER_ID');
        if ($docID == '')
        {
            return;
        }

        $ad = new MAccountDoc($db);
        $d = new CTable('');
        
        $d->SetFieldValue('ACCOUNT_DOC_ID', $docID);
        $d->SetFieldValue('SO_IN_USED_BY_INVOICE', $flag);
        $ad->Update(7, $d);
    }

    private static function updateAssociateDocuments($db, $data, $removeAllFlag)
    {
        //TODO : We should prevent the concurrent issue here
        //TODO : We should re read the current RECEIPT_ID again here

        $receiptID = $data->GetFieldValue('ACCOUNT_DOC_ID');
        $invoiceID = $data->GetFieldValue('ACCOUNT_DOC_ID');
        $dt = $data->GetFieldValue('DOCUMENT_TYPE');

        if (!self::isRequireUpdateAssociate($data))
        {
            return;
        }

        $m = self::createObject($db); //AccountDoc
        $pc = new MPaymentCriteria($db);
        $ai = new MAuxilaryDocItem($db);

        $d = new CTable('');

        $arr = $data->GetChildArray('ACCOUNT_DOC_RECEIPTS');
        foreach ($arr as $row)
        {
            $flag = $row->GetFieldValue('EXT_FLAG');
            $docID = $row->GetFieldValue('DOCUMENT_ID');

            $refByID = $receiptID;
            if (($flag == 'D') || $removeAllFlag)
            {
                $refByID = '';
            }
            
            $d->SetFieldValue('ACCOUNT_DOC_ID', $docID);

            if ($dt == self::ACCOUNT_DOC_BILL_SUMMARY)
            {
                $d->SetFieldValue('BILL_SUMMARY_ID', $refByID);
                $m->Update(10, $d);
            }
            else
            {
                //Receipt both Sale and Purchase
                $d->SetFieldValue('RECEIPT_ID', $refByID);
                $m->Update(5, $d);
            }
        }

        if (($dt != self::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY) && 
            ($dt != self::ACCOUNT_DOC_INVOICE_BY_CASH_BUY))
        {
            //Receipt and Bill Summary can also have ACCOUNT_DOC_ITEM(s)
            return;
        }

        //Account doc item update back to AUX DOC ITEM and Payment Criteria
        $arr = $data->GetChildArray('ACCOUNT_DOC_ITEM');
        foreach ($arr as $row)
        {
            $flag = $row->GetFieldValue('EXT_FLAG');
            $auxItemID = $row->GetFieldValue('PO_ITEM_ID');
            $auxCriteriaID = $row->GetFieldValue('PO_CRITERIA_ID');

            $refByID = $invoiceID;
            if (($flag == 'D') || $removeAllFlag)
            {
                $refByID = '';
            }
            
            if ($auxItemID != '')
            {
                $d->SetFieldValue('AUXILARY_DOC_ITEM_ID', $auxItemID);
                $d->SetFieldValue('REF_BY_ID', $refByID);
                $ai->Update(4, $d);
            }
            else if ($auxCriteriaID != '')
            {
                $d->SetFieldValue('PAYMENT_CRITERIA_ID', $auxCriteriaID);
                $d->SetFieldValue('REF_BY_ID', $refByID);
                $pc->Update(4, $d);
            }
        }        
    }

    private static function removeReceiptItem($data)
    {
        //We don't wont receipt item to be refered one more time
        if (!self::isRequireUpdateAssociate($data))
        {
            return;
        }

        $dt = $data->GetFieldValue('DOCUMENT_TYPE');
        $data->AddChildArray('ACCOUNT_DOC_RECEIPTS', []);

        if (($dt == self::ACCOUNT_DOC_RECEIPT) || ($dt == self::ACCOUNT_DOC_RECEIPT_BUY)) 
        {     
            $data->AddChildArray('ACCOUNT_DOC_ITEM', []);
        }
    }
    
    private static function unLinkPoItem($data)
    {
        $arr = $data->GetChildArray('ACCOUNT_DOC_ITEM');

        foreach ($arr as $row)
        {
            $row->SetFieldValue('PO_ITEM_ID', '');
            $row->SetFieldValue('PO_CRITERIA_ID', '');
        }          
    }

    public static function AdjustApproveAccountDoc($db, $param, $data)
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
            throw new Exception("No account document in database!!!");
        }

        self::isDocumentAdjustable($obj);
        
        CHelper::PopulateDocumentDateKey($data, "DOCUMENT_DATE");
        self::createUpdateDummyItems($data);

        //$childs = []; 
        $childs = self::initSqlAdjustConfig($db);
        self::UpdateData($db, $data, $u, 6, $childs);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }
    
    public static function UpdateAccountDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::updateDefaultFields($data);
        
        self::createUpdateDummyItems($data);
        self::updateChildItems($data);
        
        self::populateIndexField($data);        

        $u = self::createObject($db);

        $obj = self::GetRowByID($data, $u, 1);
        if (!isset($obj))
        {
            throw new Exception("No account document in database!!!");
        }

        self::isDocumentEditable($obj);

        //BILL_SIMULATE_ID will be populated here
        self::updateOrCreateBillSimulate($db, $param, $data);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);
        self::updateAssociateDocuments($db, $data, false);
        self::createDocItemsForReceipt($db, $data);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    public static function DeleteAccountDoc($db, $param, $data)
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
            throw new Exception("No account document in database!!!");
        }

        self::isDocumentEditable($obj);

        $isApprovedDocNo = $obj->getFieldValue('IS_APPROVED_DOC_NO');
        if ($isApprovedDocNo == 'Y')
        {
            throw new Exception("Field IS_APPROVED_DOC_NO is set to 'Y', unable to delete");
        }

        if (self::isRequireUpdateAssociate($data))
        {
            list($p, $d) = self::GetAccountDocInfo($db, $param, $data);            
            self::updateAssociateDocuments($db, $d, true);
        }
        self::updateQuotationInuseFlag($db, $obj, 'N');
        self::updateSaleOrderInuseFlag($db, $obj, 'N');

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        $id = $data->GetFieldValue('BILL_SIMULATE_ID');
        if ($id != '')
        {
            $billSim = new CTable('');
            $billSim->SetFieldValue('BILL_SIMULATE_ID', $id);
            BillSimulate::DeleteBillSimulate($db, $param, $billSim);
        }

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    public static function CopyAccountDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $onlyHead = $data->getFieldValue('IS_ONLY_HEAD');

        list($p, $d) = self::GetAccountDocInfo($db, $param, $data);
        self::PopulateNewCode($d, 'DOCUMENT_NO');

        if ($onlyHead == 'Y')
        {
            //Can refactor to use self::$cfg loop instead
            $d->addChildArray('ACCOUNT_DOC_ITEM', []);
            $d->addChildArray('ACCOUNT_DOC_PAYMENTS', []);
            $d->addChildArray('ACCOUNT_DOC_RECEIPTS', []);
            $d->addChildArray('ACCOUNT_DOC_DISCOUNTS', []);
            $d->addChildArray('ACCOUNT_DOC_DEPOSITS', []);         
        }
        else
        {
            //Obsoleted
            //self::InitCopyItems($d, self::$cfg);
            //self::removeReceiptItem($d);
            //self::unlinkPoItem($d);
        }

        $d->SetFieldValue("DOCUMENT_STATUS", self::ACCOUNT_DOC_PENDING);
        $d->SetFieldValue("APPROVED_DATE","");
        $d->SetFieldValue("APPROVED_SEQ","");
        $d->SetFieldValue("REF_QUOTATION_ID","");
        $d->SetFieldValue("BY_VOID_FLAG","N");
        $d->SetFieldValue("INTERNAL_DRCR_FLAG","N");
        $d->SetFieldValue("SO_IN_USED_BY_INVOICE","N");
        $d->SetFieldValue("REF_SALE_ORDER_ID","");
        $d->SetFieldValue("CHEQUE_ID","");
        $d->SetFieldValue("BILL_SUMMARY_ID","");
        $d->SetFieldValue("RECEIPT_ID","");
        $d->SetFieldValue("IS_APPROVED_DOC_NO","");
        $d->SetFieldValue("ARAP_PAID_OFF_FLAG","N");
        $d->SetFieldValue("REVENUE_EXPENSE_AMT","0.00");
        $d->SetFieldValue("AR_AP_AMT","0.00");
        $d->SetFieldValue("VAT_AMT","0.00");
        $d->SetFieldValue("WH_TAX_AMT","0.00");
        $d->SetFieldValue("CASH_RECEIPT_AMT","0.00");
        $d->SetFieldValue("PRICING_AMT","0.00");
        $d->SetFieldValue("CASH_ACTUAL_RECEIPT_AMT","0.00");
        $d->SetFieldValue("CASH_RECEIPT_AMT","0.00");
        $d->SetFieldValue("CASH_CHANGE_AMT","0.00");
        $d->SetFieldValue("CASH_RECEIVE_AMT","0.00");
        $d->SetFieldValue("FINAL_DISCOUNT","0.00");
        
        //Create new BillSimulate
        $oldID = $d->GetFieldValue('BILL_SIMULATE_ID');
        $d->SetFieldValue('BILL_SIMULATE_ID', '');
        $d->SetFieldValue('ORG_BILL_SIMULATE_ID', $oldID);

        list($p, $d) = self::CreateAccountDoc($db, $param, $d);
        list($p, $d) = self::GetAccountDocInfo($db, $param, $d);

        return(array($param, $d));
    }

    private static function createAccountDocObject($db, $data)
    {
        //Class factory function

        $doc = null;

        $dt = $data->getFieldValue("DOCUMENT_TYPE");
        if ($dt == self::ACCOUNT_DOC_INVOICE_BY_CASH)
        {
            $doc = new AccountDocumentInvoiceCash($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == self::ACCOUNT_DOC_INVOICE_BY_DEBT)
        {
            $doc = new AccountDocumentInvoiceDebt($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == self::ACCOUNT_DOC_CREDIT_NOTE)
        {
            $doc = new AccountDocumentDrCrNote($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == self::ACCOUNT_DOC_DEBIT_NOTE)
        {
            $doc = new AccountDocumentDrCrNote($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == self::ACCOUNT_DOC_INVOICE_BY_CASH_BUY)
        {
            $doc = new AccountDocumentInvoiceCash($db, $data, self::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == self::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY)
        {
            $doc = new AccountDocumentInvoiceDebt($db, $data, self::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == self::ACCOUNT_DOC_CREDIT_NOTE_BUY)
        {
            $doc = new AccountDocumentDrCrNote($db, $data, self::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == self::ACCOUNT_DOC_DEBIT_NOTE_BUY)
        {
            $doc = new AccountDocumentDrCrNote($db, $data, self::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == self::ACCOUNT_DOC_RECEIPT)
        {
            $doc = new AccountDocumentReceipt($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == self::ACCOUNT_DOC_RECEIPT_BUY)
        {
            $doc = new AccountDocumentReceipt($db, $data, self::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == self::ACCOUNT_DOC_MISC_REVENUE)
        {
            //Reuse AccountDocumentInvoiceCash but no any inventory item
            $doc = new AccountDocumentInvoiceCash($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == self::ACCOUNT_DOC_MISC_EXPENSE)
        {
            //Reuse AccountDocumentInvoiceCash but no any inventory item
            $doc = new AccountDocumentInvoiceCash($db, $data, self::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == self::ACCOUNT_DOC_DEPOSIT_SALE)
        {
            $doc = new AccountDocumentDeposit($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == self::ACCOUNT_DOC_DEPOSIT_PURCHASE)
        {
            $doc = new AccountDocumentDeposit($db, $data, self::ACCOUNT_DOC_AREA_AP);
        }
        elseif ($dt == self::ACCOUNT_DOC_SALE_ORDER)
        {
            $doc = new AccountDocumentDummy($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }
        elseif ($dt == self::ACCOUNT_DOC_BILL_SUMMARY)
        {
            $doc = new AccountDocumentDummy($db, $data, self::ACCOUNT_DOC_AREA_AR);
        }

        return($doc);
    }

    public static function UnlinkSaleOrderFromInvoice($db, $param, $data)
    {
        $docID = $data->GetFieldValue('ACCOUNT_DOC_ID');
        $soID = $data->GetFieldValue('REF_SALE_ORDER_ID');
        
        if (($soID == '') || ($docID == ''))
        {
            return;
        }

        $ad = new MAccountDoc($db);
        $d = new CTable('');
        
        $d->SetFieldValue('ACCOUNT_DOC_ID', $soID);
        $d->SetFieldValue('SO_IN_USED_BY_INVOICE', 'N');
        $ad->Update(12, $d);

        $d->SetFieldValue('ACCOUNT_DOC_ID', $docID);
        $d->SetFieldValue('REF_SALE_ORDER_ID', '');
        $ad->Update(13, $d);

        return(array($param, $data));
    }

    public static function VerifyAccountDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $doc = self::createAccountDocObject($db, $data);
        list($errCnt, $d) = $doc->VerifyDocument();

        return(array($param, $d));
    }

    public static function ApproveAccountDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $auto_no = $param->GetFieldValue('AUTO_NUMBER');
        
        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        $id = $data->GetFieldValue("ACCOUNT_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateAccountDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateAccountDoc($db, $param, $data);
        }

        $isApprovedDocNo = $d->getFieldValue('IS_APPROVED_DOC_NO');

        if ($auto_no != 'N')
        {
            if ($isApprovedDocNo == 'N')
            {
                $cfg = self::getDocumentConfig($data, self::ACCOUNT_DOC_APPROVED);
            
                //SaleOrder will be blank for approval            
                if ($cfg != '')
                {
                    $t = new CTable("");
                    $t->setFieldValue("DOC_TYPE", $cfg);
                    CHelper::PopulateCustomVariables($data, $t, 1);

                    list($p1, $d1) = DocumentNumber::GenerateDocumentNumber($db, $param, $t);
                    $docNo = $d1->getFieldValue("LAST_DOCUMENT_NO");
                    $d->SetFieldValue('DOCUMENT_NO', $docNo);
                }
            }
        }

        $doc = self::createAccountDocObject($db, $d);
        list($errCnt, $cashDoc, $invDoc) = $doc->ApproveDocument();
        if ($errCnt > 0)
        {
            if ($tx)
            {
                $db->rollBack();
            }

            return([$param, $cashDoc]);
        }

        //Update value back such as begin and end balance, approve_date etc.
        $d->setFieldValue('DOCUMENT_STATUS', self::ACCOUNT_DOC_APPROVED);
        $d->setFieldValue('APPROVED_DATE', CUtils::GetCurrentDateTimeInternal());
        $d->setFieldValue('APPROVED_SEQ', CSql::GetSeq($db, 'ACCOUNT_DOC_APPROVED_SEQ', 1));
        list($p, $d) = self::UpdateAccountDoc($db, $param, $d);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $d));
    }

}

?>