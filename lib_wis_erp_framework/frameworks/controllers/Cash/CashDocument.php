<?php
/* 
    Purpose : Controller for Cash Document
    Created By : Seubpong Monsar
    Created Date : 09/18/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CashDocument extends CBaseController
{
    const CASH_DOC_PENDING = '1';
    const CASH_DOC_APPROVED = '2';

    const CASH_DOC_IN = '1';
    const CASH_DOC_OUT = '2';
    const CASH_DOC_TRANSFER = '3';

    private static $cfg = NULL;

    private static $orderByConfig_out = [
        'inventory_doc_no' => 'DOCUMENT_NO',
        'date' => 'DOCUMENT_DATE',
        'AccNo' => 'FROM_ACCOUNT_NO',
        'Bank' => 'FROM_BANK_NAME',
        'inventory_doc_status' => 'DOCUMENT_STATUS',
    ];

    private static $orderByConfig_in = [
        'inventory_doc_no' => 'DOCUMENT_NO',
        'date' => 'DOCUMENT_DATE',
        'AccNo' => 'TO_ACCOUNT_NO',
        'Bank' => 'TO_BANK_NAME',
        'inventory_doc_status' => 'DOCUMENT_STATUS',
    ];

    private static $orderByConfig_xfer = [
        'inventory_doc_no' => 'DOCUMENT_NO',
        'date' => 'DOCUMENT_DATE',
        'FromAcc' => 'FROM_ACCOUNT_NNAME',
        'ToAcc' => 'TO_ACCOUNT_NNAME',
        'inventory_doc_status' => 'DOCUMENT_STATUS',
    ];

    private static function initSqlConfig($db)
    {
        $config = [
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['CASH_XFER_ITEM', new MCashXferDetail($db), 1, 0, 2],            
        ];

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MCashDoc($db);
        return($u);
    }

    public static function GetCashDocList($db, $param, $data)
    {
        $orderByHash = [
            self::CASH_DOC_IN => self::$orderByConfig_in,
            self::CASH_DOC_OUT => self::$orderByConfig_out,
            self::CASH_DOC_TRANSFER => self::$orderByConfig_xfer
        ];

        $docType = $data->GetFieldValue('DOCUMENT_TYPE');
        $orderCfg = $orderByHash[$docType];

        $u = self::createObject($db);

        CHelper::OverrideOrderBy($u, 1, $data, $orderCfg);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'CASH_DOC_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetCashDocInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No cash document in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsCashDocExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "DOCUMENT_NO", "DOCUMENT_NO", 0);
        
        return(array($param, $o));        
    }

    public static function CreateCashDoc($db, $param, $data)
    {
        $u = self::createObject($db);
        self::updateDefaultFields($data);

        $data->SetFieldValue('DOCUMENT_STATUS', self::CASH_DOC_PENDING);    
        self::PopulateStartEndDate($data, 'DOCUMENT_DATE', true);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateCashDoc($db, $param, $data)
    {
        $u = self::createObject($db);
        self::updateDefaultFields($data);

        $obj = self::GetRowByID($data, $u, 1);  
        if (!isset($obj))
        {
            throw new Exception("No cash document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") == self::CASH_DOC_APPROVED) 
        {
            throw new Exception("This document has been approved and not allowed to update");
        }        

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteCashDoc($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $obj = self::GetRowByID($data, $u, 1);        
        if (!isset($obj))
        {
            throw new Exception("No cash document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") == self::CASH_DOC_APPROVED) 
        {
            throw new Exception("This document has been approved and not allowed to delete");
        }    

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));
    }

    public static function CopyCashDoc($db, $param, $data)
    {
        list($p, $d) = self::GetCashDocInfo($db, $param, $data);
        self::PopulateNewCode($d, 'DOCUMENT_NO');
        self::InitCopyItems($d, self::$cfg);

        $d->SetFieldValue("DOCUMENT_STATUS", self::CASH_DOC_PENDING);
        $d->SetFieldValue("APPROVED_DATE","");
        $d->SetFieldValue("APPROVED_SEQ","");

        list($p, $d) = self::CreateCashDoc($db, $param, $d);
        list($p, $d) = self::GetCashDocInfo($db, $param, $d);
        
        return(array($param, $d));        
    }   

    private static function deriveDocTypeAndOwner($data, $bal)
    {
        $dt = '';
        $docType = $data->GetFieldValue('DOCUMENT_TYPE');
        $code = '';

        if ($docType == self::CASH_DOC_IN)
        {
            $dt = BalanceAPI::BAL_DOC_IMPORT;
            $code = $data->GetFieldValue('TO_ACCOUNT_NO');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
        }
        elseif ($docType == self::CASH_DOC_OUT)
        {
            $dt = BalanceAPI::BAL_DOC_EXPORT;
            $code = $data->GetFieldValue('FROM_ACCOUNT_NO');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
        }
        elseif ($docType == self::CASH_DOC_TRANSFER)
        {
            $dt = BalanceAPI::BAL_DOC_MOVE;

            $from = $data->GetFieldValue('FROM_ACCOUNT_NO');
            $to = $data->GetFieldValue('TO_ACCOUNT_NO');

            $bal->SetFieldValue('BAL_OWNER_CODE_FROM', $from);
            $bal->SetFieldValue('BAL_OWNER_CODE_TO', $to);
        }

        $bal->SetFieldValue('BAL_DOC_TYPE', $dt);        
    }

    private static function deriveBalanceDoc($db, $data)
    {
        $dt = '';

        $bal = new CTable("");
        self::deriveDocTypeAndOwner($data, $bal);
        
        $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_CASH);
        $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_CASHACCT);

        $bal->SetFieldValue('BAL_DOC_NO', $data->GetFieldValue('DOCUMENT_NO'));
        //$bal->SetFieldValue('BAL_DOC_DATE', $data->GetFieldValue('DOCUMENT_DATE'));
        CHelper::PopulateBalanceDate($db, $bal, $data, 'DOCUMENT_DATE');
        $bal->SetFieldValue('BAL_DOC_NOTE', $data->GetFieldValue('NOTE'));
        $bal->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('CASH_DOC_ID'));

        $items = [];

        $tx = new CTable('');
        $tx->SetFieldValue('BAL_ITEM_CODE', CashAccount::CASH_ITEM_CODE);
        $tx->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('CASH_DOC_ID'));

        $qty = $data->GetFieldValue('TOTAL_AMOUNT');
        $amt = $data->GetFieldValue('TOTAL_AMOUNT');

        $tx->SetFieldValue('TX_QTY_AVG', $qty);
        $tx->SetFieldValue('TX_AMT_AVG', $amt);
        $tx->SetFieldValue('TX_QTY_FIFO', $qty);
        $tx->SetFieldValue('TX_AMT_FIFO', $amt);
        
        array_push($items, $tx);

        $bal->AddChildArray('BAL_DOC_ITEMS', $items);

        return($bal);
    }

    private static function updateCashAccountBalance($db, $arr)
    {
        $ca = new MCashAccount($db);
        $dat = new CTable("");

        foreach ($arr as $accum)
        {
            $id = $accum->GetFieldValue('BAL_OWNER_ACTUAL_ID');
            $amt = $accum->GetFieldValue('END_QTY_AVG');

            $dat->SetFieldValue('CASH_ACCOUNT_ID', $id);
            $dat->SetFieldValue('TOTAL_AMOUNT', $amt);
            $ca->Update(2, $dat);
        }
    }

    private static function updateRedeemStatus($db, $data)
    {
        $arr = $data->GetChildArray('CASH_XFER_ITEM');
        
        $ma = new MAccountDoc($db);
        $dat = new CTable('');

        foreach ($arr as $item)
        {
            $id = $item->GetFieldValue('ACCOUNT_DOC_ID');
            $cashDocId = $data->GetFieldValue('CASH_DOC_ID');

            $dat->SetFieldValue('ACCOUNT_DOC_ID', $id);
            $dat->SetFieldValue('REDEEM_DOCUMENT_ID', $cashDocId);
            $ma->Update(14, $dat);
        }
    }    

    private static function updateDefaultFields($data)
    {
        $internalFlag = $data->GetFieldValue('INTERNAL_FLAG');
        if ($internalFlag == '')
        {
            //This CashDoc is created by AccountDocument.php
            $data->SetFieldValue('INTERNAL_FLAG', 'Y');
        }
    }
    
    public static function ApproveCashDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $fh = CUtils::LockEntity('ApproveCashDoc');

        $allowNegative = $data->GetFieldValue('ALLOW_NEGATIVE');

        $tx = false;    
        if (!$db->inTransaction()) 
        {
            $db->beginTransaction();
            $tx = true;
        }

        $id = $data->GetFieldValue("CASH_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateCashDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateCashDoc($db, $param, $data);
        }

        $bal = self::deriveBalanceDoc($db, $d);
        $type = $bal->GetFieldValue('BAL_DOC_TYPE');
        $result = BalanceAPI::Apply($db, $type, $bal, $allowNegative=='Y', NULL);
        if (!$result)
        {
            if ($tx) $db->rollBack();

            CUtils::UnlockEntity($fh);
            
            $param->SetFieldValue('ERROR_CODE', '2');
            $param->SetFieldValue('ERROR_DESC', 'ERRORS');
            return(array($param, $bal));
        }

        $accumArr = $bal->GetChildArray('GLOBAL_ACCUM_LIST');
        $d->AddChildArray('GLOBAL_ACCUM_LIST', $accumArr);
        self::updateCashAccountBalance($db, $accumArr);

        self::updateRedeemStatus($db, $data);

        //Update value back such as begin and end balance, approve_date etc.
        $d->setFieldValue('DOCUMENT_STATUS', self::CASH_DOC_APPROVED);
        $d->setFieldValue('APPROVED_DATE', CUtils::GetCurrentDateTimeInternal());
        $d->setFieldValue('APPROVED_SEQ', CSql::GetSeq($db, 'CASH_DOC_APPROVED_SEQ', 1));        
        list($p, $d) = self::UpdateCashDoc($db, $param, $d);

        if ($tx)
        {
            $db->commit();
        }

        CUtils::UnlockEntity($fh);

        return(array($param, $d));
    }   

    public static function VerifyCashDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $allowNegative = $data->GetFieldValue('ALLOW_NEGATIVE');

        $bal = self::deriveBalanceDoc($db, $data);
        $type = $bal->GetFieldValue('BAL_DOC_TYPE');
        
        $result = BalanceAPI::Verify($db, $type, $bal, $allowNegative=='Y');
        if (!$result)
        {
            $param->SetFieldValue('ERROR_CODE', '2');
            $param->SetFieldValue('ERROR_DESC', 'ERRORS');
            return(array($param, $bal));
        }

        return(array($param, $data));
    }

    public static function VerifyCashDocSet($db, $param, $docSet)
    {
        //This should already be in transaction
        $data = new CTable("");

        $totalErr = 0;
        $totalErrors = [];

        foreach ($docSet as $cashDoc)
        {
            list($p, $d) = self::VerifyCashDoc($db, $param, $cashDoc);

            $errors = $d->GetChildArray('ERROR_ITEM');
            $errCnt = count($errors);

            if ($errCnt > 0)
            {
                foreach ($errors as $err)
                {
                    array_push($totalErrors, $err);   
                }
            }

            $totalErr = $totalErr + $errCnt;
        }

        if ($totalErr > 0)
        {
            $data->AddChildArray('ERROR_ITEM', $totalErrors);
        }

        return(array($param, $data));
    }

    public static function ApproveCashDocSet($db, $param, $docSet)
    {
        //This should already be in transaction
        $data = new CTable("");

        $totalErr = 0;
        $totalErrors = [];

        foreach ($docSet as $cashDoc)
        {
            list($p, $d) = self::ApproveCashDoc($db, $param, $cashDoc);

            $errors = $d->GetChildArray('ERROR_ITEM');
            $errCnt = count($errors);

            if ($errCnt > 0)
            {
                foreach ($errors as $err)
                {
                    array_push($totalErrors, $err);   
                }
            }

            $totalErr = $totalErr + $errCnt;
        }

        if ($totalErr > 0)
        {
            $data->AddChildArray('ERROR_ITEM', $totalErrors);
        }

        return(array($param, $data));
    }    
}

?>