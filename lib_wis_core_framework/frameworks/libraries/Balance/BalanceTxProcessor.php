<?php
/* 
    Purpose : Library for managing Balance
    Created By : Seubpong Monsar
    Created Date : 09/18/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class BalanceTxProcessor extends CBaseController
{
    private static $globalAccum = [];
    private static $dailyAccum = [];
    private static $cbArray = [];
    
    //private static $currGlobalAccum = NULL;
    //private static $currDailyAccum = NULL;

    private static $db = NULL;

    private static function getBalanceKey($data, $tx, $level)
    {
        $item = $tx->GetFieldValue('BAL_ITEM_ID');
        $owner = $tx->GetFieldValue('BAL_OWNER_ID');
        $date = CUtils::GetDateStart($data->GetFieldValue('BAL_DOC_DATE'));

        $key = '';
        $hash = [];

        if ($level == BalanceAPI::BAL_LEVEL_GLOBAL)
        {
            $key = sprintf('%s_%s', $owner, $item);
            $hash = self::$globalAccum;
        }
        else
        {
            $key = sprintf('%s_%s_%s', $date, $owner, $item);
            $hash = self::$dailyAccum;
        }

        return([$key, $hash]);
    }

    private static function getCurrentBalAccumCache($data, $tx, $level)
    {
        list($key, $hash) = self::getBalanceKey($data, $tx, $level);

        if (array_key_exists($key, $hash))
        {
            return([$key, $hash[$key]]);
        }

        return([$key, NULL]);
    }

    private static function getCurrentBalAccumDB($data, $tx, $level)
    {
        $m = new MFrwBalAccumulate(self::$db);
        
        $item = $tx->GetFieldValue('BAL_ITEM_ID');
        $owner = $tx->GetFieldValue('BAL_OWNER_ID');
        
        $dat = new CTable("");

        $dat->SetFieldValue('BAL_LEVEL', $level);
        $dat->SetFieldValue('BAL_ITEM_ID', $item);
        $dat->SetFieldValue('BAL_OWNER_ID', $owner);
        
        if ($level == BalanceAPI::BAL_LEVEL_DAILY)
        {
            //Use date as a key too
            $date = $data->GetFieldValue('BAL_DOC_DATE');
            $fd = CUtils::GetDateStart($date);
            $td = CUtils::GetDateEnd($date);

            $dat->SetFieldValue('FROM_BAL_DATE', $fd);
            $dat->SetFieldValue('TO_BAL_DATE', $td);                         
        }

        $curr = self::GetFirstRow($dat, $m, 0, '');

        return($curr);
    }

    private static function addCurrentBalAccumCache($bal, $level, $key)
    {
        if ($level == BalanceAPI::BAL_LEVEL_GLOBAL)
        {            
            self::$globalAccum[$key] = $bal;           
        } 
        else
        {            
            self::$dailyAccum[$key] = $bal;            
        }       

        $id = $bal->GetFieldValue('BAL_ID');

        $bal->SetFieldValue('EXT_FLAG', 'A');
        if ($id != '')
        {
            $bal->SetFieldValue('EXT_FLAG', 'E');
        }
    }

    private static function initCurrentBalanceAccum($data, $tx, $level, $glbBalAccum)
    {
        $item = $tx->GetFieldValue('BAL_ITEM_ID');
        $owner = $tx->GetFieldValue('BAL_OWNER_ID');
        $date = $data->GetFieldValue('BAL_DATE');
        $itemCode = $tx->GetFieldValue('BAL_ITEM_CODE');
        $ownerCode = $tx->GetFieldValue('BAL_OWNER_CODE');
                
        $curr = new CTable("");

        $curr->SetFieldValue('BAL_ITEM_ID', $item);
        $curr->SetFieldValue('BAL_OWNER_ID', $owner);
        $curr->SetFieldValue('BAL_ITEM_CODE', $itemCode);
        $curr->SetFieldValue('BAL_OWNER_CODE', $ownerCode);
        $curr->SetFieldValue('BAL_LEVEL', $level);
        $curr->SetFieldValue('BAL_DATE', $date);

        $curr->SetFieldValue('BAL_OWNER_ACTUAL_ID', $tx->GetFieldValue('BAL_OWNER_ACTUAL_ID'));
        $curr->SetFieldValue('BAL_ITEM_ACTUAL_ID', $tx->GetFieldValue('BAL_ITEM_ACTUAL_ID'));

        $names = ['QTY_AVG', 'AMOUNT_AVG', 'PRICE_AVG', 'QTY_FIFO', 'AMOUNT_FIFO', 'PRICE_FIFO']; 
        $prefixes = ['BEGIN', 'IN', 'OUT', 'END']; 

        foreach ($prefixes as $prefix)
        {
            foreach ($names as $name)
            {
                $fn = sprintf("%s_%s", $prefix, $name);
                $curr->SetFieldValue($fn, "0.00");
            }
        }

        if ($level == BalanceAPI::BAL_LEVEL_DAILY)
        {
            //Init BEGIN_* and END_* fields here
            $qtyAvg = $glbBalAccum->GetFieldValue('END_QTY_AVG');
            $amtAvg = $glbBalAccum->GetFieldValue('END_AMOUNT_AVG');

            $curr->SetFieldValue('BEGIN_QTY_AVG', $qtyAvg);
            $curr->SetFieldValue('END_QTY_AVG', $qtyAvg);

            $curr->SetFieldValue('BEGIN_AMOUNT_AVG', $amtAvg);
            $curr->SetFieldValue('END_AMOUNT_AVG', $amtAvg);
//CLog::WriteLn("INIT : qty=[$qtyAvg], amt=[$amtAvg]");
            $curr->SetFieldValue('BEGIN_QTY_FIFO', $glbBalAccum->GetFieldValue('END_QTY_FIFO'));
            $curr->SetFieldValue('END_QTY_FIFO', $glbBalAccum->GetFieldValue('END_QTY_FIFO'));
            
            $curr->SetFieldValue('BEGIN_AMOUNT_FIFO', $glbBalAccum->GetFieldValue('END_AMOUNT_FIFO'));
            $curr->SetFieldValue('END_AOUNT_FIFO', $glbBalAccum->GetFieldValue('END_AMOUNT_FIFO'));                        
        } 

        return($curr);
    }

    private static function getCurrentBalAccum($data, $tx, $level, $glbCurr)
    {
        list($key, $curr) = self::getCurrentBalAccumCache($data, $tx, $level);        
        if (isset($curr))
        {
            return($curr);
        }

        $curr = self::getCurrentBalAccumDB($data, $tx, $level);

        if (!isset($curr))
        {
            //Create new one
            $curr = self::initCurrentBalanceAccum($data, $tx, $level, $glbCurr);
        }

        self::addCurrentBalAccumCache($curr, $level, $key);

        return($curr);
    }

    private static function getPrice($curr, $amtFld, $qtyFld)
    {
        $qty = $curr->GetFieldValue($qtyFld);
        $amt = $curr->GetFieldValue($amtFld);

        $price = 0.00;
        if ($qty != 0)
        {
            $price = $amt / $qty;
        }
//CLog::WriteLn("[$amt]/[$qty]=[$price]");        
        return($price);
    }

    private static function getExportAmountAvg($curr, $qtyTx, $tx)
    {
        $amt = 0.00;

        $availableQty = $curr->GetFieldValue('END_QTY_AVG');
        $availableAmt = $curr->GetFieldValue('END_AMOUNT_AVG');

        $txCat = $tx->GetFieldValue('TX_CATEGORY');

        if ($txCat == 'ADJ')
        {
            //Adjustment will have it own export amount
            $expAmt = $tx->GetFieldValue('TX_AMT_AVG');
            $amt = $expAmt;
            if ($expAmt >= $availableAmt)
            {
                $amt = $availableAmt;
            }
        }
        else
        {
            //Import, Export, Xfer
            if ($qtyTx >= $availableQty)
            {
                //Get as much as possible
                $amt = $availableAmt;
            }
            else
            {
                $price = 0.00;
                if ($availableQty != 0)
                {
                    $price = $availableAmt / $availableQty;
                }

                $amt = $price * $qtyTx;
            }
        }

        return($amt);
    }

    private static function applyTxAvg($data, $tx, $currAccum, $multiplier, $level)
    {
        $param = new CTable('');

        $currQtyAvg = $currAccum->GetFieldValue('END_QTY_AVG');
        $currAmtAvg = $currAccum->GetFieldValue('END_AMOUNT_AVG');

        $currInQtyAvg = $currAccum->GetFieldValue('IN_QTY_AVG');
        $currInAmtAvg = $currAccum->GetFieldValue('IN_AMOUNT_AVG');
        
        $currOutQtyAvg = $currAccum->GetFieldValue('OUT_QTY_AVG');
        $currOutAmtAvg = $currAccum->GetFieldValue('OUT_AMOUNT_AVG');

        /* Begin Param */
        $param->SetFieldValue('BEGIN_QTY_AVG', $currQtyAvg);
        $param->SetFieldValue('BEGIN_AMOUNT_AVG', $currAmtAvg);
        $price = self::getPrice($param, 'BEGIN_AMOUNT_AVG', 'BEGIN_QTY_AVG');
        $param->SetFieldValue('BEGIN_PRICE_AVG', $price);
        /* End Param */

        $txQtyAvg = $tx->GetFieldValue('TX_QTY_AVG');        

        //$grpID = $tx->GetFieldValue('GROUP_ID');
        $txCategory = $tx->GetFieldValue('TX_CATEGORY');
        if ($multiplier == 1)
        {
            //IN/Import
            $txAmtAvg = $tx->GetFieldValue('TX_AMT_AVG');
            if ($txCategory == 'XFER')
            {
                //Xfer - Get import amount from previous export in case of Xfer
                $txAmtAvg = $_ENV['XFER_PREV_EXPORT_AMOUNT'];
            }

            $currInQtyAvg = $currInQtyAvg + $txQtyAvg;
            $currInAmtAvg = $currInAmtAvg + $txAmtAvg;
        }
        else
        {
            //OUT/Export
            $txAmtAvg = self::getExportAmountAvg($currAccum, $txQtyAvg, $tx);

            $currOutQtyAvg = $currOutQtyAvg + $txQtyAvg;
            $currOutAmtAvg = $currOutAmtAvg + $txAmtAvg; 
            
            if ($txCategory == 'XFER')
            {
                //Xfer - Keep amount for further use in case of Xfer
                $_ENV['XFER_PREV_EXPORT_AMOUNT'] = $txAmtAvg;
            }            
        }

        $endQtyAvg = $currQtyAvg + ($multiplier * $txQtyAvg);
        $endAmtAvg = $currAmtAvg + ($multiplier * $txAmtAvg);
//CLog::WriteLn("[$endAmtAvg]=[$currAmtAvg]+[$multiplier]*[$txAmtAvg]");        
//        if ($endQtyAvg == 0)
//        {
//            $endAmtAvg = 0.00;
//        }

        $currAccum->SetFieldValue('END_QTY_AVG', $endQtyAvg);
        $currAccum->SetFieldValue('END_AMOUNT_AVG', $endAmtAvg);  

        $price = self::getPrice($currAccum, 'END_AMOUNT_AVG', 'END_QTY_AVG');
        $currAccum->SetFieldValue('END_PRICE_AVG', $price);

        /* Begin Param */
        $param->SetFieldValue('TX_QTY_AVG', $txQtyAvg);
        $param->SetFieldValue('TX_AMT_AVG', $txAmtAvg);
        $price = self::getPrice($param, 'TX_AMT_AVG', 'TX_QTY_AVG');
        $param->SetFieldValue('TX_PRICE_AVG', $price);

        $param->SetFieldValue('END_QTY_AVG', $endQtyAvg);
        $param->SetFieldValue('END_AMOUNT_AVG', $endAmtAvg);
        $param->SetFieldValue('END_PRICE_AVG', $price);
        /* End Param */

        $currAccum->SetFieldValue('IN_QTY_AVG', $currInQtyAvg);
        $currAccum->SetFieldValue('IN_AMOUNT_AVG', $currInAmtAvg);        
        
        $currAccum->SetFieldValue('OUT_QTY_AVG', $currOutQtyAvg);
        $currAccum->SetFieldValue('OUT_AMOUNT_AVG', $currOutAmtAvg); 
        
        $currAccum->SetFieldValue('BAL_DATE', $data->GetFieldValue('BAL_DOC_DATE')); 

        self::setUpdateFlag($currAccum);

        if ($level == BalanceAPI::BAL_LEVEL_GLOBAL)
        {
            //Call only one for GLOBAL level
            array_push(self::$cbArray, ['AVG', $param, $tx]);            
        }
    }

    private static function setUpdateFlag($curr)
    {
        $flag = $curr->GetFieldValue('EXT_FLAG');
        if ($flag != 'A')
        {
            $curr->SetFieldValue('EXT_FLAG', 'E');
        }
    }

    private static function applyTransaction($data, $tx, $currAccum, $level)
    {
        $txType = $tx->GetFieldValue('DIRECTION');

        if ($txType == 'I')
        {
            self::applyTxAvg($data, $tx, $currAccum, 1, $level);
        }
        elseif ($txType == 'E')
        {
            self::applyTxAvg($data, $tx, $currAccum, -1, $level);
        }        
    }

    private static function hashToArray($hs)
    {                    
        $arr = [];
        foreach ($hs as $key => $obj)
        {
            array_push($arr, $obj);
        }

        return($arr);
    }

    private static function initSqlConfig($db)
    {
        $config = [
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['GLOBAL_ACCUM_LIST', new MFrwBalAccumulate($db), 1, 1, 1],
            ['DAILY_ACCUM_LIST', new MFrwBalAccumulate($db), 1, 1, 1],
        ];

        return($config);
    }

    private static function callCallBack($callBack)
    {
        foreach (self::$cbArray as $cb)
        {
            if (isset($callBack))
            {        
                call_user_func_array($callBack, $cb);
            }
        }    
    }

    private static function validateBalance($data, $allowBelowZero)
    {
        $errors = [];        
        $errCnt = 0;     

        $accums = self::hashToArray(self::$globalAccum);
        $docDate = $data->GetFieldValue('BAL_DOC_DATE');

        foreach ($accums as $accum)
        {
            $qty = $accum->GetFieldValue('END_QTY_AVG');
            $amt = $accum->GetFieldValue('END_AMOUNT_AVG');
            $item = $accum->GetFieldValue('BAL_ITEM_CODE');
            $owner = $accum->GetFieldValue('BAL_OWNER_CODE');

            if (($qty < 0) && !$allowBelowZero)
            {
                //Create error record here
                $e = new CTable("ERROR");
                $e->SetFieldValue("ERROR_DESC", "ERROR_BALANCE|$owner|$qty|$item|$docDate");
                array_push($errors, $e);
                    
                $errCnt++;
            }
        }
        
        return([$errCnt, $errors]);
    }

    private static function validateDate($db, $data, $arr)
    {
        $m = new MFrwBalAccumulate(self::$db);
        $docDate = CUtils::GetDateStart($data->GetFieldValue('BAL_DOC_DATE'));

        $errCnt = 0;
        $errors = []; 

        foreach ($arr as $tx)
        {
            $item = $tx->GetFieldValue('BAL_ITEM_ID');
            $owner = $tx->GetFieldValue('BAL_OWNER_ID');
            $itemCode = $tx->GetFieldValue('BAL_ITEM_CODE');
            $ownerCode = $tx->GetFieldValue('BAL_OWNER_CODE');
            
            $dat = new CTable("");
    
            $dat->SetFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_GLOBAL);
            $dat->SetFieldValue('BAL_ITEM_ID', $item);
            $dat->SetFieldValue('BAL_OWNER_ID', $owner);        
            
            $curr = self::GetFirstRow($dat, $m, 0, '');
            if (isset($curr))
            {
                $balDate = CUtils::GetDateStart($curr->GetFieldValue('BAL_DATE'));

                if ($docDate < $balDate)
                {
                    //Key back date data
                    $e = new CTable("ERROR");
                    $e->SetFieldValue("ERROR_DESC", "ERROR_DOCUMENT_DATE|$ownerCode|$itemCode|$balDate");
                    array_push($errors, $e);
                        
                    $errCnt++;                    
                }
            }
        }

        return([$errCnt, $errors]);
    }

    private static function apply($db, $data, $allowBelowZero)
    {
        self::$db = $db;
        self::$cbArray = [];

        self::$globalAccum = [];
        self::$dailyAccum = [];

        $arr = $data->GetChildArray('TEMP_BAL_DOC_ITEMS');
        
        list($errCnt, $errArr) = self::validateDate($db,  $data, $arr);
        if ($errCnt > 0)
        {
            $data->AddChildArray('ERROR_ITEM', $errArr);
            return(false);
        }        

        $currGlobalAccum = [];
        $currDailyAccum = [];
        
        foreach ($arr as $tx)
        {
            $currGlobalAccum = self::getCurrentBalAccum($data, $tx, BalanceAPI::BAL_LEVEL_GLOBAL, NULL);
            $currDailyAccum = self::getCurrentBalAccum($data, $tx, BalanceAPI::BAL_LEVEL_DAILY, $currGlobalAccum);
        
            self::applyTransaction($data, $tx, $currGlobalAccum, BalanceAPI::BAL_LEVEL_GLOBAL);
            self::applyTransaction($data, $tx, $currDailyAccum, BalanceAPI::BAL_LEVEL_DAILY);    
        } 
        
        //Check remain balance
        list($errCnt, $errArr) = self::validateBalance($data, $allowBelowZero);
        if ($errCnt > 0)
        {
            $data->AddChildArray('ERROR_ITEM', $errArr);
        }

        return($errCnt == 0);
    }

    private static function updateDatabase($db, $data)
    {
        $glbArr = self::hashToArray(self::$globalAccum);
        $dlyArr = self::hashToArray(self::$dailyAccum);

        $obj = new CTable('');
        $obj->AddChildArray('GLOBAL_ACCUM_LIST', $glbArr);
        $obj->AddChildArray('DAILY_ACCUM_LIST', $dlyArr);

        $u = new MVirtualModel('DUMMY_ID');

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $obj, $u, 0, $childs);      
        
        $data->AddChildArray('GLOBAL_ACCUM_LIST', $glbArr);
    }

    public static function Verify($db, $data, $allowBelowZero)
    {    
        $isSuccess = self::apply($db, $data, $allowBelowZero);
        return($isSuccess);      
    }
    
    public static function Process($db, $data, $callBack, $allowBelowZero)
    {
        $isSuccess = self::apply($db, $data, $allowBelowZero);
        if (!$isSuccess)
        {
            return(false);
        }

        //Call callback function
        self::callCallBack($callBack);

        //Update database
        self::updateDatabase($db, $data);

        return(true);
    }
}

?>