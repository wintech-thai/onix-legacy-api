<?php
/* 
    Purpose : Controller for Cash Document
    Created By : Seubpong Monsar
    Created Date : 03/07/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class Cheque extends CBaseController
{
    const CHEQUE_PENDING = '1';
    const CHEQUE_APPROVED = '2';
    const CHEQUE_CANCLE = '3';


    const CHEQUE_IN = '1';
    const CHEQUE_OUT = '2';

    private static $cfg = NULL;

    private static $orderByConfig = [
        'cheque_no' => 'CHEQUE_NO',
        'cheque_due_date' => 'CHEQUE_DATE',
        'bank_name' => 'BANK_NAME',
        'payee_name' => 'PAYEE_NAME',
        'cheque_status' => 'CHEQUE_STATUS',
    ];

    private static function initSqlConfig($db)
    {
        $config = [
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
        ];

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MCheque($db);
        return($u);
    }

    public static function GetChequeList($db, $param, $data)
    {
        $docType = $data->GetFieldValue('DIRECTION');
        $orderCfg = self::$orderByConfig;

        $u = self::createObject($db);

        CHelper::OverrideOrderBy($u, 1, $data, $orderCfg);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'CHEQUE_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetChequeInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No cheque in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsChequeExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "CHEQUE_NO", "CHEQUE_NO", 0);
        
        return(array($param, $o));        
    }

    public static function CreateCheque($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $data->SetFieldValue('CHEQUE_STATUS', self::CHEQUE_PENDING);    
        self::PopulateStartEndDate($data, 'CHEQUE_DATE', true);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateCheque($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $obj = self::GetRowByID($data, $u, 1);        
        if (!isset($obj))
        {
            throw new Exception("No cheque in database!!!");
        }

        if ($obj->GetFieldValue("CHEQUE_STATUS") == self::CHEQUE_APPROVED) 
        {
            throw new Exception("This cheque has been approved and not allowed to update");
        }        

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteCheque($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $obj = self::GetRowByID($data, $u, 1);        
        if (!isset($obj))
        {
            throw new Exception("No cheque document in database!!!");
        }

        if ($obj->GetFieldValue("CHEQUE_STATUS") == self::CHEQUE_APPROVED) 
        {
            throw new Exception("This cheque has been approved and not allowed to delete");
        }    

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));
    }

    public static function CopyCheque($db, $param, $data)
    {
        list($p, $d) = self::GetChequeInfo($db, $param, $data);
        self::PopulateNewCode($d, 'CHEQUE_NO');
        self::InitCopyItems($d, self::$cfg);

        $d->SetFieldValue("CHEQUE_STATUS", self::CHEQUE_PENDING);
        $d->SetFieldValue("APPROVED_DATE","");
        $d->SetFieldValue("APPROVED_SEQ","");

        list($p, $d) = self::CreateCheque($db, $param, $d);
        list($p, $d) = self::GetChequeInfo($db, $param, $d);
        
        return(array($param, $d));        
    }   

    private static function deriveCashDoc($db, $data)
    {
        $docNo = $data->GetFieldValue("CHEQUE_NO");
        $issueDate = $data->GetFieldValue("ISSUE_DATE");
        $dir = $data->GetFieldValue("DIRECTION");
        $accID = $data->GetFieldValue("CASH_ACCT_ID");
        $accNo = $data->GetFieldValue("ACCOUNT_NO");
        $note = $data->GetFieldValue("CHEQUE_NOTE");
        $allowNegative = $data->GetFieldValue("ALLOW_NEGATIVE");
        $amt = $data->GetFieldValue("CHEQUE_AMOUNT");
        $chequeID = $data->GetFieldValue("CHEQUE_ID");

        $cd = new CTable("");
        
        $cd->SetFieldValue('DOCUMENT_NO', $docNo);
        $cd->SetFieldValue('DOCUMENT_DATE', $issueDate);
        
        if ($dir == self::CHEQUE_IN)
        {
            $dt = CashDocument::CASH_DOC_IN;
            $cd->SetFieldValue('CASH_ACCOUNT_ID2', $accID);
            $cd->SetFieldValue('TO_ACCOUNT_NO', $accNo);
        }
        else
        {
            $dt = CashDocument::CASH_DOC_OUT;
            $cd->SetFieldValue('CASH_ACCOUNT_ID1', $accID);
            $cd->SetFieldValue('FROM_ACCOUNT_NO', $accNo);
        }

        $cd->SetFieldValue('DOCUMENT_TYPE', $dt);
        $cd->SetFieldValue('CHEQUE_ID', $chequeID);
        $cd->SetFieldValue('NOTE', $note);
        $cd->SetFieldValue('ALLOW_NEGATIVE', $allowNegative);
        $cd->SetFieldValue('TOTAL_AMOUNT', $amt);

        return($cd);        
    }
    
    public static function ApproveCheque($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'ISSUE_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $fh = CUtils::LockEntity('ApproveCheque');

        $allowNegative = $data->GetFieldValue('ALLOW_NEGATIVE');

        $tx = false;
        if (!$db->inTransaction()) 
        {
            $db->beginTransaction();
            $tx = true;
        }

        $id = $data->GetFieldValue("CHEQUE_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateCheque($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateCheque($db, $param, $data);
        }

        $cd = self::deriveCashDoc($db, $d);
        list($prm, $cshd) = CashDocument::ApproveCashDoc($db, $param, $cd);

        $errors = $cshd->GetChildArray('ERROR_ITEM');
        $errCnt = count($errors);
        
        if ($errCnt > 0)
        {
            if ($tx) $db->rollBack();

            CUtils::UnlockEntity($fh);
            return([$param, $cshd]);
        }    

        //Update value back such as begin and end balance, approve_date etc.
        $d->setFieldValue('CHEQUE_STATUS', self::CHEQUE_APPROVED);
        $d->setFieldValue('APPROVED_DATE', CUtils::GetCurrentDateTimeInternal());
        $d->setFieldValue('APPROVED_SEQ', CSql::GetSeq($db, 'CHEQUE_APPROVED_SEQ', 1));        
        list($p, $d) = self::UpdateCheque($db, $param, $d);

        if ($tx)
        {
            $db->commit();
        }

        CUtils::UnlockEntity($fh);

        return(array($param, $d));
    }   

    public static function VerifyCheque($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'ISSUE_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $cd = self::deriveCashDoc($db, $data);
        list($prm, $cshd) = CashDocument::VerifyCashDoc($db, $param, $cd);

        $errors = $cshd->GetChildArray('ERROR_ITEM');
        $errCnt = count($errors);
        
        if ($errCnt > 0)
        {
            return([$param, $cshd]);
        } 

        return(array($param, $data));
    }
}

?>