<?php
/*
    Purpose : Controller for Tax Document
    Created By : Seubpong Monsar
    Created Date : 01/06/2019 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class TaxDocument extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['PP30_DOC_LIST', new MTaxDocumentPP30($db), 0, 0, 1], 
            ['WH_ITEMS', new MTaxDocumentRv3_53($db), 0, 0, 1], 
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MTaxDocument($db);
        return($u);
    }

    public static function GetTaxDocList($db, $param, $data)
    {
        $u = new MTaxDocument($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'TAX_DOC_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetTaxDocInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MTaxDocument($db);
        $obj = self::GetRowByID($data, $u, 1);
        
        if (!isset($obj))
        {
            throw new Exception("No tax document in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);
        
        return(array($param, $obj));  
    }

    public static function CreateTaxDoc($db, $param, $data)
    {
        $u = new MTaxDocument($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    }    

    public static function UpdateTaxDoc($db, $param, $data)
    {
        $u = new MTaxDocument($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }


    public static function SaveTaxDoc($db, $param, $data)
    {
        $id = $data->GetFieldValue("TAX_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateTaxDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateTaxDoc($db, $param, $data);
        }
        
        return(array($p, $d));
    }
    
    public static function DeleteTaxDoc($db, $param, $data)
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
            throw new Exception("No tax document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") != AccountDocument::ACCOUNT_DOC_PENDING)
        {
            throw new Exception("This document has been approved/canceled and not allowed to delete");
        }

        $td = new MTaxDocumentRv3_53($db);
        $td->Delete(1, $data);

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    private static function createAccountDocObject($db, $data)
    {

    }    

    private static function updateOriginalDocStatus($db, $param, $d)
    {
        //Document Type and ID are already in the passed objet
        $obj = new CTable('');

        $obj->SetFieldValue('TAX_DOC_ID', $d->GetFieldValue('TAX_DOC_ID'));
        $obj->SetFieldValue('DOCUMENT_TYPE', $d->GetFieldValue('DOCUMENT_TYPE'));        
        $obj->SetFieldValue("DOCUMENT_STATUS", AccountDocument::ACCOUNT_DOC_APPROVED);

        $u = self::createObject($db);

        $rc = $u->Update(2, $obj);

        //Update back to caller
        $d->SetFieldValue('DOCUMENT_STATUS', AccountDocument::ACCOUNT_DOC_APPROVED);
    }

    public static function VerifyTaxDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $docDate = $data->GetFieldValue('DOCUMENT_DATE');

        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        list($p, $obj) = AccountDocument::GetAccountDocInfo($db, $param, $data);
/*
        $doc = self::createAccountDocObject($db, $obj);
        list($errCnt, $d) = $doc->VerifyDocument();
*/
        return(array($param, $d));
    }

    public static function ApproveTaxDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $u = self::createObject($db);
        
        list($p, $obj) = self::GetTaxDocInfo($db, $param, $data);

        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::SaveTaxDoc($db, $param, $data);        
/*
        $doc = self::createAccountDocObject($db, $obj);
        list($errCnt, $cashDoc, $invDoc) = $doc->ApproveDocument();
        if ($errCnt > 0)
        {
            if ($tx)
            {
                $db->rollBack();
            }

            return([$param, $cashDoc]);
        }
*/
        self::updateOriginalDocStatus($db, $param, $obj);        

        if ($tx)
        {
            $db->commit();
        }

        list($p, $d) = self::GetTaxDocInfo($db, $param, $obj);

        return(array($param, $d));
    }

    public static function GetTaxDocRv3Rv53List($db, $param, $data)
    {
        $id = $data->getFieldValue("TAX_DOC_ID");

        if ($id == '')
        {
            throw new Exception("TAX_DOC_ID is empty, need to save document first!!!");
        }

        $u = new MTaxDocumentRv3_53($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(0, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $item_cnt, $chunk_cnt, 'TAX_DOC_REV_LIST', $rows);
        
        return(array($param, $p));
    }

    public static function PopulatePayrollItems($db, $param, $data)
    {
        $year = $data->getFieldValue("TAX_YEAR");
        $month = $data->getFieldValue("TAX_MONTH");
        $id = $data->getFieldValue("TAX_DOC_ID");

        if ($id == '')
        {
            throw new Exception("TAX_DOC_ID is empty, need to save document first!!!");
        }

        if (($year == '') || ($month == ''))
        {
            throw new Exception("TAX_YEAR or TAX_MONTH is empty!!!");
        }

        $date = sprintf("%s/%02d/15 00:00:00", $year, $month);
        list($fromDate, $toDate) = CUtils::DateStartEOM($date);
        $dat = new CTable('');

        $dat->setFieldValue('FROM_DOCUMENT_DATE', $fromDate);
        $dat->setFieldValue('TO_DOCUMENT_DATE', $toDate);
        $dat->setFieldValue('DOCUMENT_STATUS', '2');
        $dat->setFieldValue('EMPLOYEE_TYPE', '2'); //Monthly
        
        list($p, $d) = HrPayrollReport::GetEmployeePayrollByDateList($db, $param, $dat);

        $accum = self::processPayrollItems($db, $data, $d);
        $accum->setFieldValue('FROM_DOCUMENT_DATE', $fromDate);
        $accum->setFieldValue('TO_DOCUMENT_DATE', $toDate);
        $accum->setFieldValue('PREVIOUS_RUN_YEAR', $year);
        $accum->setFieldValue('PREVIOUS_RUN_MONTH', $month);

        return(array($param, $accum));
    }

    private static function processPayrollItems($db, $origData, $data)
    {
        $deductFlag = $origData->getFieldValue('IS_TAX_DEDUCTABLE');
        $arr = $data->getChildArray('PAYROLL_EMPLOYEE_LIST');

        //Delete old data and insert the new ones
        $td = new MTaxDocumentRv3_53($db);
        $td->Delete(1, $origData);

        $accum = new CTable('');

        $id = $origData->getFieldValue('TAX_DOC_ID');
        $cnt = 0;

        $expAccumAmt = 0.00;
        $whAccumAmt = 0.00;

        $items = [];
        foreach ($arr as $o)
        {
            $rev = new CTable('');
            self::copyPayrollFields($o, $rev);

            $whAmt = $rev->getFieldValue('WH_AMOUNT');
            $expAmt = $rev->getFieldValue('EXPENSE_REVENUE_AMT');
            
            if (($deductFlag == 'Y') && ($whAmt <= 0))
            {
                continue;
            }

            $rev->setFieldValue('WH_AMOUNT', $whAmt);
            $rev->setFieldValue('EXPENSE_REVENUE_AMT', $expAmt);

            $expAccumAmt = $expAccumAmt + $expAmt;
            $whAccumAmt = $whAccumAmt + $whAmt;

            $rev->setFieldValue('TAX_DOC_ID', $id);
            $td->Insert(0, $rev, true);

            array_push($items, $rev);
            $cnt++;
        }

        $accum->setFieldValue("WH_AMOUNT", $whAccumAmt);
        $accum->setFieldValue("EXPENSE_REVENUE_AMT", $expAccumAmt);
        $accum->setFieldValue("ITEM_COUNT", $cnt);
        $accum->addChildArray('WH_ITEMS', $items);
        $accum->addChildArray('DEDUCT_FLAG', $deductFlag);

        return($accum);
    }    

    private static function copyPayrollFields($src, $dst)
    {
        $fieldDefaults = [
            'DOCUMENT_TYPE' => '1',
            'RECEIPT_DATE' => 'N/A',
            'WH_NO' => 'N/A',
            'INVOICE_NO' => 'N/A',
            'INVOICE_DATE' => 'N/A',
            'SUPPLIER_ADDRESS' => 'N/A',
            'WH_GROUP' => '1',
            'WH_PAY_TYPE' => '1',
            'SUPPLIER_REV_TYPE' => '1',
            'WH_PCT' => '0.00',       
        ];

        $fieldMaps = [
            'DOCUMENT_NO' => 'NOTE',
            'ACCOUNT_DOC_ID' => 'PAYROLL_DOC_ID',
            'ACCOUNT_DOC_ITEM_ID' => 'PAYROLL_DOC_ITEM_ID',
            'DOCUMENT_DATE' => 'TO_SALARY_DATE',
            'SUPPLIER_ID' => 'EMPLOYEE_ID',     
            'SUPPLIER_NAME' => 'EMPLOYEE_NAME',
            'SUPPLIER_TAX_ID' => 'ID_NUMBER',
            'EXPENSE_REVENUE_AMT' => 'RECEIVE_INCOME',
            'WH_AMOUNT' => 'DEDUCT_TAX',   
        ];

        foreach ($fieldDefaults as $key => $value)
        {
            $dst->setFieldValue($key, $value);
        }

        foreach ($fieldMaps as $key => $field)
        {
            if ($key == 'SUPPLIER_NAME')
            {
                $tmp = $src->getFieldValue('EMPLOYEE_NAME') . " " . $src->getFieldValue('EMPLOYEE_LASTNAME');;
                $dst->setFieldValue($key, $tmp);
            }
            else
            {
                $v = $src->getFieldValue($field);
                $dst->setFieldValue($key, $v);    
            }
        }
    }    

    public static function PopulateWhItems($db, $param, $data)
    {
        $year = $data->getFieldValue("TAX_YEAR");
        $month = $data->getFieldValue("TAX_MONTH");
        $id = $data->getFieldValue("TAX_DOC_ID");

        if ($id == '')
        {
            throw new Exception("TAX_DOC_ID is empty, need to save document first!!!");
        }

        if (($year == '') || ($month == ''))
        {
            throw new Exception("TAX_YEAR or TAX_MONTH is empty!!!");
        }

        $date = sprintf("%s/%02d/15 00:00:00", $year, $month);
        list($fromDate, $toDate) = CUtils::DateStartEOM($date);

        $dat = new CTable('');
        //Populate fromdate, todate, CATEGORY = 2, RV_TAX_TYPE=(3,53), DOC_STATUS=2
        //WH_TAX_FLAG=Y
        $dat->setFieldValue('FROM_DOCUMENT_DATE', $fromDate);
        $dat->setFieldValue('TO_DOCUMENT_DATE', $toDate);
        $dat->setFieldValue('FROM_TAX_DOC', 'Y');
        $dat->setFieldValue('CATEGORY', '2');
        $dat->setFieldValue('RV_TAX_TYPE', $data->getFieldValue('RV_TAX_TYPE'));
        $dat->setFieldValue('WH_TAX_FLAG', 'Y');
        $dat->setFieldValue('DOCUMENT_STATUS', '2');
        $dat->setFieldValue('ACTUAL_PAY', 'Y'); //หัก ณ ที่จ่าย ที่จ่ายจริง ๆ แล้ว
        
        //ต้องเอาแต่เฉพาะที่จ่ายแล้วจริง ๆ มา ไม่รวม จากลดหนี้ เพิ่มหนี้
        list($p, $d) = SalePurchaseReport::GetSalePurchaseWhDocList($db, $param, $dat);

        $accum = self::processWhItems($db, $data, $d);
        $accum->setFieldValue('FROM_DOCUMENT_DATE', $fromDate);
        $accum->setFieldValue('TO_DOCUMENT_DATE', $toDate);
        $accum->setFieldValue('PREVIOUS_RUN_YEAR', $year);
        $accum->setFieldValue('PREVIOUS_RUN_MONTH', $month);

        return(array($param, $accum));
    }

    private static function processWhItems($db, $origData, $data)
    {
        //Firstly we limited the value to 300, if in the future we hit the limt 
        //then we will fix the code to support the chunk query instead.
        //We limit the number because we don't want to display all items on the screen.
        $limit = 300;

        $arr = $data->getChildArray('SALE_PURCHASE_DOC_LIST');
        $cnt = count($arr);

        if ($cnt > $limit)
        {
            $str = sprintf("Result size is greater than [%s] limit [%s]", $cnt, $limit);
            throw new Exception($str);
        }

        //Delete old data and insert the new ones
        $td = new MTaxDocumentRv3_53($db);
        $td->Delete(1, $origData);

        $accum = new CTable('');

        $id = $origData->getFieldValue('TAX_DOC_ID');
        $cnt = 0;

        $expAccumAmt = 0.00;
        $whAccumAmt = 0.00;

        $items = [];
        foreach ($arr as $o)
        {
            $rev = new CTable('');
            self::copyFields($o, $rev);

            $coef = self::docTypeToCoefficient($rev);
            $whAmt = $coef * $rev->getFieldValue('WH_AMOUNT');
            $expAmt = $coef * $rev->getFieldValue('EXPENSE_REVENUE_AMT');
            
            $rev->setFieldValue('WH_AMOUNT', $whAmt);
            $rev->setFieldValue('EXPENSE_REVENUE_AMT', $expAmt);

            $expAccumAmt = $expAccumAmt + $expAmt;
            $whAccumAmt = $whAccumAmt + $whAmt;

            $rev->setFieldValue('TAX_DOC_ID', $id);
            $td->Insert(0, $rev, true);

            array_push($items, $rev);
            $cnt++;
        }

        $accum->setFieldValue("WH_AMOUNT", $whAccumAmt);
        $accum->setFieldValue("EXPENSE_REVENUE_AMT", $expAccumAmt);
        $accum->setFieldValue("ITEM_COUNT", $cnt);
        $accum->addChildArray('WH_ITEMS', $items);

        return($accum);
    }
    
    private static function docTypeToCoefficient($dat)
    {
        $refDt = $dat->getFieldValue("REF_DOCUMENT_TYPE");
        if ($refDt == "")
        {
            //ซื้อสด            
            $refDt = $dat->getFieldValue("DOCUMENT_TYPE");
        }
        else
        {
            //จ่ายชำระหนี้
            //Do nothing
        }

        $coef = 1;
        if ($refDt == AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY)
        {
            $coef = -1;
        }

        return($coef);
    }

    private static function copyFields($src, $dst)
    {
        $fieldMaps = [
            'ACCOUNT_DOC_ID' => 'ACCOUNT_DOC_ID',
            'ACCOUNT_DOC_ITEM_ID' => 'ACCOUNT_DOC_ITEM_ID',
            'DOCUMENT_TYPE' => 'DOCUMENT_TYPE',
            'REF_DOCUMENT_TYPE' => 'REF_DOCUMENT_TYPE',
            'RECEIPT_DATE' => 'DOCUMENT_DATE',
            'WH_NO' => 'REF_WH_DOC_NO',
            'INVOICE_NO' => 'REF_DOCUMENT_NO',
            'INVOICE_DATE' => 'REF_DOCUMENT_DATE',
            'DOCUMENT_NO' => 'DOCUMENT_NO',
            'DOCUMENT_DATE' => 'DOCUMENT_DATE',
            'SUPPLIER_NAME' => 'ENTITY_NAME',
            'SUPPLIER_ADDRESS' => 'ADDRESS',
            'SUPPLIER_ID' => 'ENTITY_ID',
            'SUPPLIER_TAX_ID' => 'ID_NUMBER',
            'EXPENSE_REVENUE_AMT' => 'REVENUE_EXPENSE_AMT',
            'WH_GROUP' => 'WH_GROUP_CRITERIA',
            'WH_PAY_TYPE' => 'WH_PAY_TYPE',
            'SUPPLIER_REV_TYPE' => 'RV_TAX_TYPE',
            'WH_PCT' => 'WH_TAX_PCT',
            'WH_AMOUNT' => 'WH_TAX_AMT',
        ];

        foreach ($fieldMaps as $key => $field)
        {
            $v = $src->getFieldValue($field);
            $dst->setFieldValue($key, $v);
        }
    }
}

?>