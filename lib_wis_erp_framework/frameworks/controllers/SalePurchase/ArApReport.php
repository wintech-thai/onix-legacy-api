<?php
/* 
    Purpose : Controller for Cash Report
    Created By : Seubpong Monsar
    Created Date : 10/08/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class ArApReport extends CBaseController
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
        $category = $data->GetFieldValue('CATEGORY');
        if ($category == '1')
        {
            //AR
            $data->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_CUSTOMER);
            $data->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AR);                
        }      
        else
        {
            //AR
            $data->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_SUPPLIER);
            $data->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_AP);                
        }          
    }
        
    public static function GetArApBalanceSummaryList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = self::createAccumObject($db);
        $u->OverideOrderBy(0, 'ORDER BY EN.ENTITY_CODE ASC, BA.BAL_DATE ASC, BA.BAL_ID ASC ');

        self::populateArea($data);
        $data->SetFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_DAILY);
        list($cnt, $rows) = $u->Query(2, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SUMMARY_AR_AP', $rows);
        
        return([$param, $p]);
    }

    public static function GetArApDailyList($db, $param, $data)
    {
        $u = self::createAccumObject($db);

        self::populateArea($data);
        list($cnt, $rows) = $u->Query(2, $data);

        $data->setFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_DAILY);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'AR_AP_BALANCE_LIST', $rows);
        
        return([$param, $p]);
    }

    public static function GetArApBalanceList($db, $param, $data)
    {
        $u = self::createAccumObject($db);

        self::populateArea($data);
        list($cnt, $rows) = $u->Query(2, $data);

        $data->setFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_GLOBAL);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'AR_AP_BALANCE_LIST', $rows);
        
        return(array($param, $p));
    }

    public static function GetArApTransactionList($db, $param, $data)
    {
        $u = self::createTxObject($db);

        self::populateArea($data);
        list($cnt, $itemCnt, $chnkCnt, $rows) = $u->QueryChunk(2, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $itemCnt, $chnkCnt, 'AR_AP_TRANSACTION_LIST', $rows);
        
        return(array($param, $p));
    }

    public static function GetArApTransactionMovementList($db, $param, $data)
    {
        $u = self::createTxObject($db);
        $u->OverideOrderBy(2, 'ORDER BY EN.ENTITY_CODE ASC, AD.APPROVED_SEQ ASC ');
//CSql::SetDumpSQL(true); 
        $toDate = $data->GetFieldValue("TO_DOCUMENT_DATE");
        $fromDate = $data->GetFieldValue("FROM_DOCUMENT_DATE");
        $currentDate = CUtils::GetCurrentDateTimeInternal(); 
/*
        if ($fromDate == '') 
        {
            $data->SetFieldValue("FROM_DOCUMENT_DATE", CUtils::DateAdd($currentDate, -30));
            self::PopulateStartEndDate($data, 'FROM_DOCUMENT_DATE', true);
        }

        if ($toDate == '') 
        {
            $data->SetFieldValue("TO_DOCUMENT_DATE", $currentDate);
            self::PopulateStartEndDate($data, 'TO_DOCUMENT_DATE', false);
        }
*/
        self::populateArea($data);
        list($cnt, $rows) = $u->Query(2, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'AR_AP_MOVEMENT_LIST', $rows);
        
        return(array($param, $p));
    }
}

?>