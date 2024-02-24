<?php
/* 
    Purpose : Controller for ArAp Document
    Created By : Seubpong Monsar
    Created Date : 10/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class ArApDocument extends CBaseController
{
    private static function deriveDocTypeAndOwner($data, $bal)
    {
        $dt = '';
        $docType = $data->GetFieldValue('DOCUMENT_TYPE');
        $code = '';

        if ($docType == AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT)
        {
            $dt = BalanceAPI::BAL_DOC_IMPORT;
            $code = $data->GetFieldValue('ENTITY_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
            $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AR);
            $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_CUSTOMER);            
        }
        elseif ($docType == AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY)
        {
            $dt = BalanceAPI::BAL_DOC_IMPORT;
            $code = $data->GetFieldValue('ENTITY_CODE'); //Supplier code

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
            $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AP);
            $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_SUPPLIER);            
        }
        elseif ($docType == AccountDocument::ACCOUNT_DOC_CREDIT_NOTE)
        {
            //reduce debt
            $dt = BalanceAPI::BAL_DOC_EXPORT;
            $code = $data->GetFieldValue('ENTITY_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
            $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AR);
            $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_CUSTOMER);              
        }
        elseif ($docType == AccountDocument::ACCOUNT_DOC_DEBIT_NOTE)
        {
            //increase debt
            $dt = BalanceAPI::BAL_DOC_IMPORT;
            $code = $data->GetFieldValue('ENTITY_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
            $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AR);
            $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_CUSTOMER);              
        }
        elseif ($docType == AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY)
        {
            //reduce Payable debt
            $dt = BalanceAPI::BAL_DOC_EXPORT;
            $code = $data->GetFieldValue('ENTITY_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
            $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AP);
            $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_SUPPLIER);              
        }
        elseif ($docType == AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY)
        {
            //increase Payable debt
            $dt = BalanceAPI::BAL_DOC_IMPORT;
            $code = $data->GetFieldValue('ENTITY_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
            $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AP);
            $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_SUPPLIER);          
        }

        elseif ($docType == AccountDocument::ACCOUNT_DOC_RECEIPT)
        {
            //reduce Receivable debt
            $dt = BalanceAPI::BAL_DOC_EXPORT;
            $code = $data->GetFieldValue('ENTITY_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
            $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AR);
            $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_CUSTOMER);              
        }
        elseif ($docType == AccountDocument::ACCOUNT_DOC_RECEIPT_BUY)
        {
            //reduce Payable debt
            $dt = BalanceAPI::BAL_DOC_EXPORT;
            $code = $data->GetFieldValue('ENTITY_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
            $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AP);
            $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_SUPPLIER);          
        }        

        $bal->SetFieldValue('BAL_DOC_TYPE', $dt);        
    }

    private static function deriveBalanceDoc($db, $data)
    {
        $dt = '';

        $bal = new CTable("");
        self::deriveDocTypeAndOwner($data, $bal);
        
        $bal->SetFieldValue('BAL_DOC_NO', $data->GetFieldValue('DOCUMENT_NO'));
        //$bal->SetFieldValue('BAL_DOC_DATE', $data->GetFieldValue('DOCUMENT_DATE'));
        CHelper::PopulateBalanceDate($db, $bal, $data, 'DOCUMENT_DATE');
        $bal->SetFieldValue('BAL_DOC_NOTE', $data->GetFieldValue('DOCUMENT_DESC'));
        $bal->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('ACCOUNT_DOC_ID'));

        $items = [];
        $docType = $data->GetFieldValue('DOCUMENT_TYPE');

        $tx = new CTable('');
        $tx->SetFieldValue('BAL_ITEM_CODE', AccountDocument::AR_ITEM_CODE);

        if (($docType == AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY) ||
            ($docType == AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY) ||
            ($docType == AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY) ||
            ($docType == AccountDocument::ACCOUNT_DOC_RECEIPT_BUY))
        {
            $tx->SetFieldValue('BAL_ITEM_CODE', AccountDocument::AP_ITEM_CODE);
        }

        $tx->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('ACCOUNT_DOC_ID'));

        $qty = $data->GetFieldValue('AR_AP_AMT');
        $amt = $data->GetFieldValue('AR_AP_AMT');

        $tx->SetFieldValue('TX_QTY_AVG', $qty);
        $tx->SetFieldValue('TX_AMT_AVG', $amt);
        $tx->SetFieldValue('TX_QTY_FIFO', $qty);
        $tx->SetFieldValue('TX_AMT_FIFO', $amt);
        
        array_push($items, $tx);

        $bal->AddChildArray('BAL_DOC_ITEMS', $items);

        return($bal);
    }

    private static function updateArApBalance($db, $arr)
    {
        $ca = new MEntity($db);
        $dat = new CTable("");

        foreach ($arr as $accum)
        {
            $id = $accum->GetFieldValue('BAL_OWNER_ACTUAL_ID');
            $amt = $accum->GetFieldValue('END_QTY_AVG');

            $dat->SetFieldValue('ENTITY_ID', $id);
            $dat->SetFieldValue('AR_AP_BALANCE', $amt);
            $ca->Update(2, $dat);
        }
    }

    public static function ApproveArApDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);    
        $fh = CUtils::LockEntity('ApproveArApDoc');

        $allowNegative = $data->GetFieldValue('ALLOW_AR_AP_NEGATIVE');

        $tx = false;
        if (!$db->inTransaction()) 
        {
            $db->beginTransaction();
            $tx = true;
        }

        $bal = self::deriveBalanceDoc($db, $data);
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
        self::updateArApBalance($db, $accumArr);

        if ($tx)
        {
            $db->commit();
        }

        CUtils::UnlockEntity($fh);

        return(array($param, $data));
    }   

    public static function VerifyArApDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);   
        $allowNegative = $data->GetFieldValue('ALLOW_AR_AP_NEGATIVE');

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
}

?>