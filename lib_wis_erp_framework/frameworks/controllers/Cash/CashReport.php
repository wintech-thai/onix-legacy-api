<?php
/* 
    Purpose : Controller for Cash Report
    Created By : Seubpong Monsar
    Created Date : 09/21/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CashReport extends CBaseController
{
    private static $cfg = NULL;

    private static function createTxObject($db)
    {
        $u = new MBalanceTx($db);
        return($u);
    }
    
    private static function createAccumObject($db)
    {
        $u = new MBalanceAccum($db);
        return($u);
    }

    private static function populateArea($data)
    {
        $data->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_CASHACCT);
        $data->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_CASH);
    }
        
    public static function GetCashBalanceSummaryList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = self::createAccumObject($db);
        $u->OverideOrderBy(0, 'ORDER BY CA.ACCOUNT_NO ASC, BA.BAL_DATE ASC, BA.BAL_ID ASC ');

        self::populateArea($data);
        $data->SetFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_DAILY);
        list($cnt, $rows) = $u->Query(0, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SUMMARY_CASH', $rows);
        
        return(array($param, $p));
    }

    public static function GetCashBalanceDailyList($db, $param, $data)
    {
        $u = self::createAccumObject($db);

        self::populateArea($data);
        list($cnt, $rows) = $u->Query(0, $data);

        $data->setFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_DAILY);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'CASH_BALANCE_LIST', $rows);
        
        return(array($param, $p));
    }

    private static function createCashBalanceItem($db, $data)
    {    
        $fromDocDate = $data->getFieldValue('FROM_DOCUMENT_DATE');
        $cashAccountID = $data->getFieldValue('CASH_ACCOUNT_ID');
//CSql::SetDumpSQL(true);
        $accum = new CTable('');
        
        $u = new MCashDoc($db);
        
        if ($cashAccountID != '')
        {
            $customWhere = "(CD.CASH_ACCOUNT_ID1 = $cashAccountID) OR (CD.CASH_ACCOUNT_ID2 = $cashAccountID)";
            $accum->setFieldValue('CUSTOM_WHERE_FIELD', $customWhere);
        }

        $beginBalance = new CTable('');
        $beginBalance->setFieldValue('BALANCE_FLAG', "Y");
        $beginBalance->setFieldValue('DEPOSIT_AMOUNT', "0.00");
        $beginBalance->setFieldValue('WITHDRAW_AMOUNT', "0.00");
        $beginBalance->setFieldValue('TOTAL_AMOUNT', "0.00");   

        if ($fromDocDate == '')
        {
            return($beginBalance);
        }

        $toDocDate = CUtils::GetDateEnd(CUtils::DateAdd($fromDocDate, -1));
        $accum->setFieldValue('FROM_DOCUMENT_DATE', '');
        $accum->setFieldValue('TO_DOCUMENT_DATE', $toDocDate);

        //Only approved document, we need to force users to always approve documents
        $accum->setFieldValue('DOCUMENT_STATUS', '2'); 
        list($cnt, $rows) = $u->Query(3, $accum);

        $out = 0.00;
        $in = 0.00;

        foreach ($rows as $row)
        {
            $from = $row->getFieldValue('CASH_ACCOUNT_ID1');
            $to = $row->getFieldValue('CASH_ACCOUNT_ID2');
            $amt = $row->getFieldValue('TOTAL_AMOUNT');

            //$from and $to will never be equal

            if ($cashAccountID == $from)
            {
                $out = $out + $amt;
            }
            
            if ($cashAccountID == $to)
            {
                $in = $in + $amt;
            }            
        }

        $balance = $in - $out;
        $beginBalance->setFieldValue('TOTAL_AMOUNT', $balance);     

        return($beginBalance);
    }

    private static function createCashMovementList($db, $rows, $data)
    {
        $cashAccountID = $data->getFieldValue('CASH_ACCOUNT_ID');
        $movements = [];

        $beginBalance = self::createCashBalanceItem($db, $data);
        $balance = $beginBalance->getFieldValue('TOTAL_AMOUNT');

        array_push($movements, $beginBalance);
        
        foreach ($rows as $row)
        {
            $from = $row->getFieldValue('CASH_ACCOUNT_ID1');
            $to = $row->getFieldValue('CASH_ACCOUNT_ID2');
            $amt = $row->getFieldValue('TOTAL_AMOUNT');

            $factor = 1;
            $deposit = 0.00;
            $withdraw = 0.00;

            if ($from == $cashAccountID)
            {
                //Withdraw
                $factor = -1;
                $withdraw = $amt;
            }
            elseif ($to == $cashAccountID)
            {
                //deposit
                $factor = 1;
                $deposit = $amt;
            }

            $balance = $balance + ($factor * $amt);

            $row->setFieldValue('DEPOSIT_AMOUNT', $deposit);
            $row->setFieldValue('WITHDRAW_AMOUNT', $withdraw);
            $row->setFieldValue('TOTAL_AMOUNT', $balance);

            array_push($movements, $row);
        }

        return($movements);
    }

    public static function GetCashMovementList($db, $param, $data)
    {
        $u = new MCashDoc($db);

        //TODO : We should check to make sure CASH_ACCOUNT_ID is only number to prevent injection by hacker
        $cashAccountID = $data->getFieldValue('CASH_ACCOUNT_ID');
        if ($cashAccountID != '')
        {
            $customWhere = "(CD.CASH_ACCOUNT_ID1 = $cashAccountID) OR (CD.CASH_ACCOUNT_ID2 = $cashAccountID)";
            $data->setFieldValue('CUSTOM_WHERE_FIELD', $customWhere);
        }

        //Only approved document, we need to force users to always approve documents
        //$data->setFieldValue('DOCUMENT_STATUS', '2'); 
        list($cnt, $rows) = $u->Query(2, $data);

        $movements = self::createCashMovementList($db, $rows, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'CASH_MOVEMENT_LIST', $movements);
        
        return(array($param, $p));
    }

    public static function GetCashBalanceList($db, $param, $data)
    {
        $u = self::createAccumObject($db);

        self::populateArea($data);
        list($cnt, $rows) = $u->Query(0, $data);

        $data->setFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_GLOBAL);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'CASH_BALANCE_LIST', $rows);
        
        return(array($param, $p));
    }

    public static function GetCashTransactionList($db, $param, $data)
    {
        $u = self::createTxObject($db);
        $u->OverideOrderBy(0, 'ORDER BY CA.ACCOUNT_NO ASC, CD.APPROVED_SEQ ASC ');
        
        self::populateArea($data);
        list($cnt, $itemCnt, $chnkCnt, $rows) = $u->QueryChunk(0, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $itemCnt, $chnkCnt, 'CASH_TRANSACTION_LIST', $rows);
        
        return(array($param, $p));
    }

    public static function GetCashTransactionMovementList($db, $param, $data)
    {
        $u = self::createTxObject($db);
        $u->OverideOrderBy(0, 'ORDER BY CA.ACCOUNT_NO ASC, CD.APPROVED_SEQ ASC ');

        self::populateArea($data);
        list($cnt, $rows) = $u->Query(0, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'CASH_MOVEMENT_LIST', $rows);
        
        return(array($param, $p));
    }
}

?>