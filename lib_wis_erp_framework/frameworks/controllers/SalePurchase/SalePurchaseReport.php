<?php
/* 
    Purpose : Controller for Cash Report
    Created By : Seubpong Monsar
    Created Date : 10/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class SalePurchaseReport extends CBaseController
{
    private static $cfg = NULL;

    private static $orderByConfig3 = [
        'inventory_doc_no' => 'DOCUMENT_NO',
        'inventory_doc_date' => 'DOCUMENT_DATE',
        'item_code' => 'ITEM_CODE',
        'item_name_thai' => 'ITEM_NAME_THAI',
    ];

    protected static function populateDefaultDocStatusSet($data)
    {
        $dt = $data->getFieldValue('DOCUMENT_STATUS');
        if (($dt == '') || ($dt == '0'))
        {
            $docSet = sprintf('(%s, %s)', 
                AccountDocument::ACCOUNT_DOC_PENDING,
                AccountDocument::ACCOUNT_DOC_APPROVED);

            $data->SetFieldValue('DOCUMENT_STATUS_SET', $docSet);      
        }
    }

    protected static function populateExpRevDocTypeSet($data)
    {
        $docSet = sprintf('(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)', 
            AccountDocument::ACCOUNT_DOC_MISC_REVENUE,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT,

            AccountDocument::ACCOUNT_DOC_CREDIT_NOTE,
            AccountDocument::ACCOUNT_DOC_DEBIT_NOTE,

            AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY,
            AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY,  

            AccountDocument::ACCOUNT_DOC_MISC_EXPENSE,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY);

        $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);
    }

    protected static function populateDocTypeSet($data)
    {
        $category = $data->GetFieldValue('CATEGORY');

        //User $category to create DOCUMENT_TYPE set
        if ($category == '1')
        {
            //AR
            //Set document type here
        
            $docSet = sprintf('(%s, %s, %s)', 
                AccountDocument::ACCOUNT_DOC_MISC_REVENUE,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT);

            $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);
        }
        else
        {
            //AP
            $docSet = sprintf('(%s, %s, %s)', 
                AccountDocument::ACCOUNT_DOC_MISC_EXPENSE,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY);

            $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);            
        }        
    }

    protected static function populateDocTypeDrCrSet($data)
    {
        $category = $data->GetFieldValue('CATEGORY');

        //User $category to create DOCUMENT_TYPE set
        if ($category == '1')
        {
            //AR
            //Set document type here
        
            $docSet = sprintf('(%s, %s, %s, %s, %s)', 
                AccountDocument::ACCOUNT_DOC_MISC_REVENUE,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH,                
                AccountDocument::ACCOUNT_DOC_CREDIT_NOTE,
                AccountDocument::ACCOUNT_DOC_DEBIT_NOTE,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT);

            $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);
        }
        else
        {
            //AP
            $docSet = sprintf('(%s, %s, %s, %s, %s)', 
                AccountDocument::ACCOUNT_DOC_MISC_EXPENSE,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY,
                AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY,
                AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY,                
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY);

            $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);            
        }        
    }    

    protected static function populateWhDocTypeSetDrCr($data)
    {
        $category = $data->GetFieldValue('CATEGORY');

        //User $category to create DOCUMENT_TYPE set
        if ($category == '1')
        {
            //AR
            //Set document type here
        
            $docSet = sprintf('(%s, %s, %s, %s, %s)', 
                AccountDocument::ACCOUNT_DOC_MISC_REVENUE,
                AccountDocument::ACCOUNT_DOC_RECEIPT,
                AccountDocument::ACCOUNT_DOC_CREDIT_NOTE,
                AccountDocument::ACCOUNT_DOC_DEBIT_NOTE,                
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH);

            $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);
        }
        else
        {
            //AP
            $docSet = sprintf('(%s, %s, %s, %s, %s)', 
                AccountDocument::ACCOUNT_DOC_MISC_EXPENSE,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY,
                AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY,
                AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY,                 
                AccountDocument::ACCOUNT_DOC_RECEIPT_BUY);

            $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);            
        }        
    }

    protected static function populateWhDocTypeSet($data)
    {
        $category = $data->GetFieldValue('CATEGORY');

        //User $category to create DOCUMENT_TYPE set
        if ($category == '1')
        {
            //AR
            //Set document type here
        
            $docSet = sprintf('(%s, %s, %s)', 
                AccountDocument::ACCOUNT_DOC_MISC_REVENUE,
                AccountDocument::ACCOUNT_DOC_RECEIPT,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH);

            $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);
        }
        else
        {
            //AP
            $docSet = sprintf('(%s, %s, %s)', 
                AccountDocument::ACCOUNT_DOC_MISC_EXPENSE,
                AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY,
                AccountDocument::ACCOUNT_DOC_RECEIPT_BUY);

            $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);            
        }        
    }

    public static function GetSalePurchaseTransactionList($db, $param, $data)
    {
        self::populateDocTypeSet($data);
        
        $u = new MAccountDocItem($db);
        $u->OverideOrderBy(3, 'ORDER BY AD.DOCUMENT_DATE ASC, AD.DOCUMENT_NO ASC, AI.ACCOUNT_DOC_ITEM_ID ASC ');

        list($cnt, $rows) = $u->Query(3, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SALE_PURCHASE_TRANSACION_LIST', $rows);
        
        return([$param, $p]);
    } 

    public static function GetSalePurchaseDocItemList($db, $param, $data)
    {
        self::populateDocTypeSet($data);
        
        $u = new MAccountDocItem($db);
        $u->OverideOrderBy(3, 'ORDER BY AD.APPROVED_SEQ ASC, AD.DOCUMENT_NO ASC, AI.ACCOUNT_DOC_ITEM_ID ASC ');

        list($cnt, $rows) = $u->Query(3, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SALE_PURCHASE_TRANSACION_LIST', $rows);
        
        return([$param, $p]);
    }     
    
    public static function GetSalePurchaseHistoryList($db, $param, $data)
    {
        self::populateDocTypeSet($data);

        $u = new MAccountDocItem($db);
        $u->OverideOrderBy(3, 'ORDER BY AD.DOCUMENT_DATE DESC, AD.DOCUMENT_NO DESC, AI.ACCOUNT_DOC_ITEM_ID DESC ');

        CHelper::OverrideOrderBy($u, 3, $data, self::$orderByConfig3); 
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(3, $data);
           
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $item_cnt, $chunk_cnt, 'SALE_PURCHASE_HISTORY_LIST', $rows);
        
        return([$param, $p]);
    } 

    public static function GetSalePurchaseByDateProdct($db, $param, $data)
    {
        self::populateDocTypeSet($data);

        $u = new MAccountDocItem($db);

        list($cnt, $rows) = $u->Query(4, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 4, 'SALE_PURCHASE_DATE_PRODUCT_LIST', $rows);

        return([$param, $p]);
    }  
    
    public static function GetSalePurchaseDocumentList($db, $param, $data)
    {
        $reportType = $data->getFieldValue('REPORT_TYPE');

        self::populateDocTypeDrCrSet($data);
        self::populateDefaultDocStatusSet($data);
        //CHelper::PopulateFromToDate($data, 'VAT_MONTH_YEAR', 'FROM_MONTH_YEAR', 'TO_MONTH_YEAR');

        $u = new MAccountDoc($db);
        if ($reportType == '1')
        {
            $u->OverideOrderBy(1, 'ORDER BY AD.REF_DOCUMENT_DATE ASC, AD.ACCOUNT_DOC_ID ASC ');
        }
        else
        {
            $u->OverideOrderBy(1, 'ORDER BY AD.DOCUMENT_DATE ASC, AD.ACCOUNT_DOC_ID ASC ');
        }

        $data->setFieldValue('BY_VOID_FLAG', 'N');
        $data->setFieldValue('VAT_CLAIMABLE', 'Y');
        
        list($cnt, $rows) = $u->Query(1, $data);
        
        $filters = [];
        $cnt = 0;
        foreach ($rows as $r)
        {
            $vat = $r->getFieldValue('VAT_AMT');
            if ($vat > 0.00)
            {
                array_push($filters, $r);
                $cnt++;
            }
        }

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SALE_PURCHASE_DOC_LIST', $filters);

        return([$param, $p]);
    } 
        
    public static function GetSalePurchaseWhDocList($db, $param, $data)
    {
        //จริง ๆ แล้ว ควรจะต้องดึง ตามรายการทึ่จ่าย ๆ จริง ๆ มาแล้วเท่านั้น ดังนั้นที่ถูกต้องจะต้องดึงมาเฉพาะ
        //รายการที่มาจาก ซื้อสด และ จ่ายชำเจ้าหนี้, ต้องใช้ AI.REF_DOC_ID ไป link เพื่อหา เอกสารที่เอามาตัดชำระจริง
        $actualPay = $data->getFieldValue('ACTUAL_PAY');
        if ($actualPay == 'Y')
        {
            //เอาเฉพาะที่จ่ายจริง ๆ แล้ว
            self::populateWhDocTypeSet($data);
        }
        else
        {
            //เอาเพิ่มหนี้ลดหนี้มาด้วย ทั้ง ๆ ที่ยังไม่ได้จ่ายจริง ๆ รายการจะถูกดึงมา double
            //เช่น ใบลดหนี้ ที่ถูกดึงมาชำระหนี้แล้ว จะออกมาซ้อนกันกับรายการของใบลดหนี้เอง
            //อันนี้ผิด แต่คงไว้เพราะ backward compattible
            self::populateWhDocTypeSetDrCr($data);
        }

        self::populateDefaultDocStatusSet($data);

        $rvTaxType = $data->getFieldValue('RV_TAX_TYPE');
        if ($rvTaxType == '0')
        {
            $data->setFieldValue('RV_TAX_TYPE', '');
        }

        $fromTaxDoc = $data->getFieldValue('FROM_TAX_DOC');

        $u = new MAccountDocItem($db);
        if ($fromTaxDoc == 'Y')
        {
            $u->OverideOrderBy(5, 'ORDER BY EN.ENTITY_NAME ASC, AD.DOCUMENT_DATE ASC, AD.ACCOUNT_DOC_ID ASC, AI.ACCOUNT_DOC_ITEM_ID ASC ');
        }
        else
        {
            $u->OverideOrderBy(5, 'ORDER BY AD.REF_WH_DOC_NO ASC, AD.ACCOUNT_DOC_ID ASC ');
        }

        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(5, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SALE_PURCHASE_DOC_LIST', $rows);

        return([$param, $p]);
    } 

    public static function GetPaymentTransactionList($db, $param, $data)
    {
        $chunkFlag = $data->GetFieldValue('CHUNK_FLAG');

        $ownerFlag = $data->getFieldValue("OWNER_FLAG");        
        if ($ownerFlag == 'Y')
        {
            $g = new CTable('');
            $g->SetFieldValue('VARIABLE_NAME', 'OWNER_PAYMENT_TYPE_SET');
            list($p, $gv) = GlobalVariable::GetSingleGlobalVariableInfo($db, new CTable(''), $g);
            $pmtSet = $gv->GetFieldValue('VARIABLE_VALUE'); 
            if ($pmtSet != '')
            {
                $data->setFieldValue("PAYMENT_TYPE_SET", "($pmtSet)");
            }
        }

        self::populateDefaultDocStatusSet($data);

        $u = new MAccountDocPayment($db);
        $u->OverideOrderBy(3, 'ORDER BY AD.DOCUMENT_DATE ASC, AD.DOCUMENT_NO ASC ');

        //Can be used the same one as WH doc
        self::populateWhDocTypeSet($data);
        $data->setFieldValue('DIRECTION', '1');
        $p = new CTable($u->GetTableName());

        if ($chunkFlag != 'Y')
        {
            //Query all , by default
            list($cnt, $rows) = $u->Query(3, $data);
            self::PopulateRow($p, $cnt, 1, 'PAYMENT_TRANSACTION_LIST', $rows);    
        }
        else
        {
            list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(3, $data);        
            self::PopulateRow($p, $item_cnt, $chunk_cnt, 'PAYMENT_TRANSACTION_LIST', $rows);            
        }
        
        return(array($param, $p));
    }   
    
    public static function GetInvoiceListByProject($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        self::populateDocTypeDrCrSet($data);
        self::populateDefaultDocStatusSet($data);

        $reportType = $data->GetFieldValue('REPORT_TYPE');

        $u = new MAccountDoc($db);
        if ($reportType == '1')
        {
            $u->OverideOrderBy(8, 'ORDER BY AD.DOCUMENT_DATE ASC, AD.DOCUMENT_NO ASC ');
        }
        else if ($reportType == '2')
        {
            $u->OverideOrderBy(8, 'ORDER BY PJ.PROJECT_CODE ASC, AD.DOCUMENT_DATE, AD.DOCUMENT_NO ASC ');
        }        
        else
        {
            $u->OverideOrderBy(8, 'ORDER BY PG.CODE ASC, AD.DOCUMENT_DATE, AD.DOCUMENT_NO ASC ');
        }

        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(8, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'INVOICE_BY_PROJECT_LIST', $rows);

        return([$param, $p]);
    }    
        
    public static function GetSalePurchaseTxList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);           
        self::populateDocTypeDrCrSet($data);
        self::populateDefaultDocStatusSet($data);

        $u = new MAccountDocItem($db);
        $u->OverideOrderBy(6, 'ORDER BY AD.DOCUMENT_DATE ASC, AD.DOCUMENT_NO ASC ');

        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(6, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SALE_PURCHASE_DOC_TX_LIST', $rows);

        return([$param, $p]);
    }

    public static function GetSalePurchaseTxByPoProjectGroup($db, $param, $data)
    {        
//CSql::SetDumpSQL(true); 
        $rt = $data->getFieldValue('REPORT_TYPE');

        self::populateDocTypeDrCrSet($data);
        self::populateDefaultDocStatusSet($data);

        $u = new MAccountDocItem($db);
        if ($rt == '4')
        {
            $u->OverideOrderBy(6, 'ORDER BY PJ.PROJECT_CODE ASC, AD.DOCUMENT_DATE ASC, AD.DOCUMENT_NO ASC ');
        }
        else
        {
            $u->OverideOrderBy(6, 'ORDER BY PG.CODE ASC, AD.DOCUMENT_DATE ASC, AD.DOCUMENT_NO ASC ');
        }
            
        $data->setFieldValue('INTERNAL_DRCR_FLAG', 'N');
        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(6, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SALE_PURCHASE_DOC_TX_LIST', $rows);

        return([$param, $p]);
    }     

    public static function GetSaleInvoicePurchaseTxByPoProjectGroup($db, $param, $data)
    {
//CSql::SetDumpSQL(true); 
        $rt = $data->getFieldValue('REPORT_TYPE');

        self::populateDocTypeDrCrSet($data);
        self::populateDefaultDocStatusSet($data);

        $u = new MAccountDocItem($db);

        $data->setFieldValue('INTERNAL_DRCR_FLAG', 'N');
        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(11, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SALE_PURCHASE_DOC_TX_LIST', $rows);

        return([$param, $p]);
    }

    public static function GetProfitByDocTypeMonth($db, $param, $data)
    {        
//CSql::SetDumpSQL(true);
        self::populateDefaultDocStatusSet($data);
        $yyyy = $data->getFieldValue('DOCUMENT_YYYY');

        $u = new MAccountDoc($db);

        $data->setFieldValue('INTERNAL_DRCR_FLAG', 'N');
        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(11, $data);

        $hash = CHelper::RowToHash($rows, ['DOCUMENT_TYPE', 'DOCUMENT_YYYYMM'], '|');
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

        $docTypes = [
            [1, AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH],
            [1, AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT],
            [1, AccountDocument::ACCOUNT_DOC_CREDIT_NOTE],
            [1, AccountDocument::ACCOUNT_DOC_DEBIT_NOTE],
            [1, AccountDocument::ACCOUNT_DOC_MISC_REVENUE],

            [2, AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY],
            [2, AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY],
            [2, AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY],
            [2, AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY],            
            [2, AccountDocument::ACCOUNT_DOC_MISC_EXPENSE],             
        ];

        $rows = array();
        $cnt = 0;
        
        foreach ($docTypes as $tupple)
        {
            list($type, $dt) = $tupple;

            $t = new CTable('');

            $t->setFieldValue('DOCUMENT_TYPE', $dt);
            $t->setFieldValue('GROUP', $type);
            $total = 0.00;

            foreach ($months as $m)
            {
                $key = "$dt|$yyyy$m";

                $r = null;
                if (array_key_exists($key, $hash))
                {
                    $r = $hash[$key];
                }

                $amt = 0.00;               
                if ($r != null)
                {
                    $amt = $r->getFieldValue('REVENUE_EXPENSE_AMT');
                }

                $t->setFieldValue($m, $amt);
                $total = $total + $amt;                
            }

            $t->setFieldValue('TOTAL', $total);

            array_push($rows, $t);
            $cnt++;
        }

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'DOCTYPE_SUMMARY_LIST', $rows);

        return([$param, $p]);
    } 
    
    public static function GetVatAmountByDocTypeInMonth($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        self::populateDefaultDocStatusSet($data);
        $yyyy = $data->getFieldValue('DOCUMENT_YYYY');

        $u = new MAccountDoc($db);

        $data->setFieldValue('INTERNAL_DRCR_FLAG', 'N');
        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(11, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'DOCTYPE_SUMMARY_LIST', $rows);

        return([$param, $p]);
    } 

    public static function GetProfitByDocTypeProject($db, $param, $data)
    {
//CSql::SetDumpSQL(true);              
        self::populateDefaultDocStatusSet($data);
        $yyyy = $data->getFieldValue('DOCUMENT_YYYY');
        $mm = sprintf('%02d', $data->getFieldValue('DOCUMENT_MM'));
        $yyyymm = "$yyyy$mm";
        $data->setFieldValue('DOCUMENT_YYYYMM', $yyyymm);

        $u = new MAccountDocItem($db);

        $data->setFieldValue('INTERNAL_DRCR_FLAG', 'N');
        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(9, $data);

        $hash = CHelper::RowToHash($rows, ['PROJECT_ID', 'DOCUMENT_TYPE', 'DOCUMENT_YYYYMM'], '|');
        $projects = CHelper::CreateDistinctRow($rows, 'PROJECT_ID', ['PROJECT_CODE', 'PROJECT_NAME']);

        $docTypes = [
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT,
            AccountDocument::ACCOUNT_DOC_CREDIT_NOTE,
            AccountDocument::ACCOUNT_DOC_DEBIT_NOTE,
            AccountDocument::ACCOUNT_DOC_MISC_REVENUE,

            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY,
            AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY,
            AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY,            
            AccountDocument::ACCOUNT_DOC_MISC_EXPENSE,             
        ];

        $rows = array();
        $cnt = 0;
        
        foreach ($projects as $proj)
        {
            $prjId = $proj->getFieldValue('PROJECT_ID');

            foreach ($docTypes as $dt)
            {
                $key = sprintf("%d|%d|%s", $prjId, $dt, $yyyymm);

                $r = null;
                if (array_key_exists($key, $hash))
                {
                    $r = $hash[$key];
                }

                $amt = 0.00;               
                if ($r != null)
                {
                    $amt = $r->getFieldValue('REVENUE_EXPENSE_AMT');
                }

                $docType = sprintf('%02d', $dt);
                $proj->setFieldValue($docType, $amt);          
            }

            array_push($rows, $proj);
            $cnt++;
        }

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'DOCTYPE_SUMMARY_LIST', $rows);

        return([$param, $p]);
    }
    
    public static function GetProfitByDocTypeProjectGroup($db, $param, $data)
    {
//CSql::SetDumpSQL(true);              
        self::populateDefaultDocStatusSet($data);
        $yyyy = $data->getFieldValue('DOCUMENT_YYYY');
        $mm = sprintf('%02d', $data->getFieldValue('DOCUMENT_MM'));
        $yyyymm = "$yyyy$mm";
        $data->setFieldValue('DOCUMENT_YYYYMM', $yyyymm);

        $u = new MAccountDocItem($db);

        $data->setFieldValue('INTERNAL_DRCR_FLAG', 'N');
        $data->setFieldValue('BY_VOID_FLAG', 'N');
        list($cnt, $rows) = $u->Query(10, $data);

        $hash = CHelper::RowToHash($rows, ['PROJECT_GROUP', 'DOCUMENT_TYPE', 'DOCUMENT_YYYYMM'], '|');
        $projects = CHelper::CreateDistinctRow($rows, 'PROJECT_GROUP', ['PROJECT_GROUP_CODE', 'PROJECT_GROUP_NAME']);

        $docTypes = [
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT,
            AccountDocument::ACCOUNT_DOC_CREDIT_NOTE,
            AccountDocument::ACCOUNT_DOC_DEBIT_NOTE,
            AccountDocument::ACCOUNT_DOC_MISC_REVENUE,

            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY,
            AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY,
            AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY,            
            AccountDocument::ACCOUNT_DOC_MISC_EXPENSE,             
        ];

        $rows = array();
        $cnt = 0;
        
        foreach ($projects as $proj)
        {
            $prjId = $proj->getFieldValue('PROJECT_GROUP');

            foreach ($docTypes as $dt)
            {
                $key = sprintf("%d|%d|%s", $prjId, $dt, $yyyymm);

                $r = null;
                if (array_key_exists($key, $hash))
                {
                    $r = $hash[$key];
                }

                $amt = 0.00;               
                if ($r != null)
                {
                    $amt = $r->getFieldValue('REVENUE_EXPENSE_AMT');
                }

                $docType = sprintf('%02d', $dt);
                $proj->setFieldValue($docType, $amt);          
            }

            array_push($rows, $proj);
            $cnt++;
        }

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'DOCTYPE_SUMMARY_LIST', $rows);

        return([$param, $p]);
    }    
}

?>