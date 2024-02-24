<?php
/* 
    Purpose : Controller for Sale Report (Customized only for ACDesign)
    Created By : Seubpong Monsar
    Created Date : 09/16/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AcdSalePurchaseReport extends SalePurchaseReport
{
    private static $costHash = [];
    private static $saleCost = 'N';
    private static $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    private static $docTypes = 
    [
        #type, factor, group
        ['r001', 1, 1], 
        ['r002', 1, 1], 
        ['r_tot', 1, 1], 
        ['e001', -1, 1],
        ['c001', -1, 1], 
        ['e002', -1, 1], 
        ['e003', -1, 1], 
        ['e004', -1, 1], 
        ['e_tot', -1, 1], 
        ['p001', 0, 1], 
        ['s_wh001', 1, 1], 
        ['s_wh002', 1, 1], 
        ['p_wh001', 1, 1], 
        ['p_wh002', 1, 1], 
        ['m_001', 1, 1], 
        ['m_002', 1, 1],
        ['dummy', 1, 1]
    ];

    private static $saleInvoices = [
        AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH => 1, 
        AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT => 1, 
        AccountDocument::ACCOUNT_DOC_CREDIT_NOTE => -1, 
        AccountDocument::ACCOUNT_DOC_DEBIT_NOTE => 1, 
    ];

    private static $purchaseInvoices = [
        AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY => 1, 
        AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT_BUY => 1, 
        AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY => -1, 
        AccountDocument::ACCOUNT_DOC_DEBIT_NOTE_BUY => 1, 
    ];

    private static $miscSaleInvoices = [
        AccountDocument::ACCOUNT_DOC_MISC_REVENUE => 1,         
    ];

    private static $miscPurchaseInvoices = [
        AccountDocument::ACCOUNT_DOC_MISC_EXPENSE => 1,         
    ];

    private static $whSalePurchaseKey = [
        AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH => ['s_wh001', 's_wh002'], 
        AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY => ['p_wh001', 'p_wh002'], 
        AccountDocument::ACCOUNT_DOC_MISC_REVENUE => ['s_wh001', 's_wh002'], 
        AccountDocument::ACCOUNT_DOC_MISC_EXPENSE => ['p_wh001', 'p_wh002'], 
        AccountDocument::ACCOUNT_DOC_RECEIPT => ['s_wh001', 's_wh002'], 
        AccountDocument::ACCOUNT_DOC_RECEIPT_BUY => ['p_wh001', 'p_wh002'], 
    ];    

    private static $docTypeHash = [];
    private static $isHideSpecial = false;

    private static function isExcludeSaleCost($dt)
    {
        $flag = (self::$saleCost == 'N') && ($dt == 'c001');
        return($flag);
    }

    private static function initDoctypesHash()
    {
        foreach (self::$docTypes as $tupple)
        {
            list($dt, $factor, $group) = $tupple;
            
            if (self::isExcludeSaleCost($dt))
            {
                continue;
            }

            $obj = new CTable('');
            self::$docTypeHash[$dt] = $obj;

            $obj->setFieldValue('DOCUMENT_TYPE', $dt);
            $obj->setFieldValue('FACTOR', $factor);
            $obj->setFieldValue('GROUP', $group);

            foreach (self::$months as $m)
            {
                $obj->setFieldValue($m, "0.00");
            }

            $obj->setFieldValue('TOTAL', "0.00");
        }
    }

    private static function getExpenseRevenueRows($db, $data)
    {
        self::$isHideSpecial = ($data->getFieldValue('IS_EXPENSE_EXCEPTION') == 'Y');
//CSql::SetDumpSQL(true);
        $u = new MAccountDocItem($db);
        $data->setFieldValue('INTERNAL_DRCR_FLAG', 'N');
        $data->setFieldValue('BY_VOID_FLAG', 'N');
        $data->setFieldValue('INTERNAL_DRCR_FLAG', 'N'); //เพิ่มหนี้ลดหนี้ที่เป็นส่วนของรายรับ/จ่าย
        list($cnt, $rows) = $u->Query(6, $data);
//CSql::SetDumpSQL(false);
        return($rows);
    }

    private static function isSalary($item)
    {
        $dt = $item->getFieldValue('DOCUMENT_TYPE');
        $serviceCode = $item->getFieldValue('SERVICE_CODE');

        $flag = ($serviceCode == '5-007') || ($serviceCode == 'OFF-SALARY');
        $isOtherExpense = ($dt == AccountDocument::ACCOUNT_DOC_MISC_EXPENSE);

        return($isOtherExpense && $flag);
    }

    private static function isExpenseException($item)
    {
        $dt = $item->getFieldValue('DOCUMENT_TYPE');
        $serviceCode = $item->getFieldValue('SERVICE_CODE');

        $flag = ($serviceCode == 'AC-ACC-0001-03') || ($serviceCode == 'AC-ACC-0001-01') || ($serviceCode == 'OFF-EXAT');
        $isOtherExpense = ($dt == AccountDocument::ACCOUNT_DOC_MISC_EXPENSE);

        return($isOtherExpense && $flag);
    }

    private static function getAggregateKey($item)
    {
        $docType = $item->getFieldValue('DOCUMENT_TYPE');
        $serviceCode = $item->getFieldValue('SERVICE_CODE');

        $key = "unknown";
        $factor = 1;

        if (self::isSalary($item))
        {
            $factor = 1;
            $key = "e003";
        }
        else if (self::isExpenseException($item))
        {
            $factor = 1;
            $key = "e004";
        }
        else if (array_key_exists($docType, self::$saleInvoices))
        {
            $factor = self::$saleInvoices[$docType];
            $key = "r001";
        }
        else if (array_key_exists($docType, self::$miscSaleInvoices))
        {
            $factor = self::$miscSaleInvoices[$docType];
            $key = "r002";
        }
        else if (array_key_exists($docType, self::$purchaseInvoices))
        {
            $factor = self::$purchaseInvoices[$docType];
            $key = "e001";
        }
        else if (array_key_exists($docType, self::$miscPurchaseInvoices))
        {
            $factor = self::$miscPurchaseInvoices[$docType];
            $key = "e002";
        }

        return([$key, $factor]);
    }

    private static function getWhKeyAndFactor($item)
    {
        $dt = $item->getFieldValue('DOCUMENT_TYPE');
        list($amtKey, $whKey) = self::$whSalePurchaseKey[$dt];

        $factor = 1;
        $refDt = $item->getFieldValue("REF_DOCUMENT_TYPE"); //จะไม่เป็น null เมื่อมาจากรายการของใบเสร็จชำระหนี้

        if (($refDt == AccountDocument::ACCOUNT_DOC_CREDIT_NOTE) || 
            ($refDt == AccountDocument::ACCOUNT_DOC_CREDIT_NOTE_BUY))
        {
            $factor = -1;
        }

        return([$amtKey, $whKey, $factor]);
    }

    private static function populateRecord($key, $dat, $record)
    {
        $yyyymm = $record->getFieldValue('DOCUMENT_YYYYMM');
        $mm = substr($yyyymm, 4, 2);

        $dat->setFieldValue($mm, '0.00');
    }

    private static function hashToArray($hash)
    {
        $rows = [];
        foreach (self::$docTypes as $tupple)
        {
            list($dt, $factor, $group) = $tupple;
            if ($dt == 'dummy')
            {
                continue;
            }

            if (self::$isHideSpecial && ($dt == 'e004'))
            {
                continue;
            }            

            if (self::isExcludeSaleCost($dt))
            {
                continue;
            }

            if (array_key_exists($dt, $hash))
            {
                $obj = $hash[$dt];
                array_push($rows, $obj);
            }            
        }

        return($rows);
    }

    private static function parseRevExpItems($arr)
    {
        foreach ($arr as $item)
        {            
            if (self::$isHideSpecial && self::isExpenseException($item))
            {
                continue;
            }

            list($key, $factor) = self::getAggregateKey($item); //Only r00x and e00x
            $currMatchKeyObj = self::$docTypeHash[$key];

            $yyyymm = $item->getFieldValue('DOCUMENT_YYYYMM');
            $mm = substr($yyyymm, 4, 2);

            //Get value for that month
            $currRevExp = $currMatchKeyObj->getFieldValue($mm);            
            $revenueOrExpense = $item->getFieldValue('REVENUE_EXPENSE_AMT');
            
            $totalAmt = $currRevExp + $factor * $revenueOrExpense; //Need to think about dr/cr note
            $currMatchKeyObj->setFieldValue($mm, $totalAmt);                              
        }

        if (!self::isExcludeSaleCost('c001'))
        {
            $stock = self::$costHash['3']; //In stock
            if (!isset($stock))
            {
                $stock = new CTable('');
            }
            $costObj = new CTable('');
            $costObj->setFieldValue('01', $stock->getFieldValue('JAN_AMOUNT'));  
            $costObj->setFieldValue('02', $stock->getFieldValue('FEB_AMOUNT'));  
            $costObj->setFieldValue('03', $stock->getFieldValue('MAR_AMOUNT'));  
            $costObj->setFieldValue('04', $stock->getFieldValue('APR_AMOUNT'));  
            $costObj->setFieldValue('05', $stock->getFieldValue('MAY_AMOUNT'));  
            $costObj->setFieldValue('06', $stock->getFieldValue('JUN_AMOUNT'));  
            $costObj->setFieldValue('07', $stock->getFieldValue('JUL_AMOUNT'));  
            $costObj->setFieldValue('08', $stock->getFieldValue('AUG_AMOUNT'));  
            $costObj->setFieldValue('09', $stock->getFieldValue('SEP_AMOUNT'));  
            $costObj->setFieldValue('10', $stock->getFieldValue('OCT_AMOUNT'));  
            $costObj->setFieldValue('11', $stock->getFieldValue('NOV_AMOUNT'));  
            $costObj->setFieldValue('12', $stock->getFieldValue('DEC_AMOUNT')); 

            foreach (self::$months as $m)
            {
                $expObj = self::$docTypeHash['e001'];                    
                $exp = $expObj->getFieldValue($m);
                $cost = $costObj->getFieldValue($m);

                if ($exp == '')
                {
                    $exp = 0.00;
                }
                if ($cost == '')
                {
                    $cost = 0.00;
                }

                $tot = $exp - $cost;
                $expObj->setFieldValue($m, $tot);
            }  
        }              
    }

    private static function parseCostItems($db, $data)
    {    
        $type = 'c001';
        if (self::isExcludeSaleCost($type))
        {
            return;
        }

        $cost = self::$costHash['4']; //Out stock
        if (!isset($cost))
        {
            $cost = new CTable('');
        }

        $currMatchKeyObj = self::$docTypeHash[$type];
        $currMatchKeyObj->setFieldValue('01', $cost->getFieldValue('JAN_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('02', $cost->getFieldValue('FEB_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('03', $cost->getFieldValue('MAR_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('04', $cost->getFieldValue('APR_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('05', $cost->getFieldValue('MAY_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('06', $cost->getFieldValue('JUN_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('07', $cost->getFieldValue('JUL_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('08', $cost->getFieldValue('AUG_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('09', $cost->getFieldValue('SEP_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('10', $cost->getFieldValue('OCT_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('11', $cost->getFieldValue('NOV_AMOUNT'));  
        $currMatchKeyObj->setFieldValue('12', $cost->getFieldValue('DEC_AMOUNT'));  
    }

    private static function parseWhItems($arr)
    {
        foreach ($arr as $item)
        {            
            $whFlag = $item->getFieldValue('WH_TAX_FLAG');
            if ($whFlag != 'Y')
            {
                continue;
            }

            $yyyymm = $item->getFieldValue('DOCUMENT_YYYYMM');
            $mm = substr($yyyymm, 4, 2);            

            list($saleExpForWhKey, $whKey, $factor) = self::getWhKeyAndFactor($item);

            $currSaleExpForWhObj = self::$docTypeHash[$saleExpForWhKey];
            $currWhObj = self::$docTypeHash[$whKey];                

            $tmp = $currSaleExpForWhObj->getFieldValue($mm);
            $tmp = $tmp + $factor * $item->getFieldValue('REVENUE_EXPENSE_AMT');
            $currSaleExpForWhObj->setFieldValue($mm, $tmp);

            $tmp = $currWhObj->getFieldValue($mm);
            $tmp = $tmp + $factor * $item->getFieldValue('WH_TAX_AMT');
            $currWhObj->setFieldValue($mm, $tmp);
        }
    }

    private static function createProfitDocType()
    {
        $revObj = self::$docTypeHash['r_tot'];
        $expObj = self::$docTypeHash['e_tot'];
        $profitObj = self::$docTypeHash['p001'];

        $costRateObj = self::$docTypeHash['m_001'];
        $profitRateObj = self::$docTypeHash['m_002'];

        $totalProfit = 0.00;
        foreach (self::$months as $m)
        {
            $rev = $revObj->getFieldValue($m);
            $exp = $expObj->getFieldValue($m);
            $profit = $rev - $exp;

            $costRate = 100;
            $profitRate = 0;
            if ($rev != 0)
            {
                $costRate = ($exp/$rev) * 100;
                $profitRate = ($profit/$rev) * 100;
            }    

            $totalProfit = $totalProfit + $profit;

            $profitObj->setFieldValue($m, $profit);

            $costRateObj->setFieldValue($m, $costRate);
            $profitRateObj->setFieldValue($m, $profitRate);
        }

        $profitObj->setFieldValue('TOTAL', $totalProfit); 
        
        $revTotal = $revObj->getFieldValue('TOTAL');
        $expTotal = $expObj->getFieldValue('TOTAL');

        $costRateTotal = 100;
        $profitRateTotal = 0;
        if ($revTotal != 0)
        {
            $costRateTotal = ($expTotal/$revTotal) * 100;
            $profitRateTotal = ($totalProfit/$revTotal) * 100;
        }     
        
        $costRateObj->setFieldValue('TOTAL', $costRateTotal);
        $profitRateObj->setFieldValue('TOTAL', $profitRateTotal);        
    }

    private static function accumulateByDocType()
    {
        $revTot = 0.00;
        $expTot = 0.00;

        foreach (self::$docTypes as $tupple)
        {
            list($dt, $factor, $group) = $tupple;
            if (self::isExcludeSaleCost($dt))
            {
                continue;
            }

            $totDocType = '';
            if (preg_match('/^r0.+$/', $dt))
            {
                $totDocType = 'r_tot';
            }
            else if (preg_match('/^e0.+$/', $dt))
            {
                $totDocType = 'e_tot';
            }
            else if (preg_match('/^c0.+$/', $dt))
            {
                $totDocType = 'e_tot';
            }            
            else if (preg_match('/^s_wh.+$/', $dt) || preg_match('/^p_wh.+$/', $dt))
            {
                $totDocType = 'dummy';
            }

            if ($totDocType == '')
            {
                continue;
            }
            
            $obj = self::$docTypeHash[$dt];
            $totObj = self::$docTypeHash[$totDocType];

            $totalInMonth = 0.00;
            $grandTotalInMonth = 0.00;

            foreach (self::$months as $m)
            {
                $amt = $obj->getFieldValue($m);
                $totalInMonth = $totalInMonth + $amt;

                $currAmt = $totObj->getFieldValue($m);                
                $currAmt = $currAmt + $amt;
                $totObj->setFieldValue($m, $currAmt);

                $grandTotalInMonth = $grandTotalInMonth + $currAmt;
            }

            $obj->setFieldValue('TOTAL', $totalInMonth);
            $totObj->setFieldValue('TOTAL', $grandTotalInMonth);
        }
    }

    private static function getSalePurchaseWhRows($db, $data)
    {
        //ต้องเอาแต่เฉพาะที่จ่ายแล้วจริง ๆ มา ไม่รวม จากใบลดหนี้ เพิ่มหนี้ ที่ยังไม่ได้ดึงมาชำระ
        $data->setFieldValue('RV_TAX_TYPE', '');
        $data->setFieldValue('WH_TAX_FLAG', 'Y');
        $data->setFieldValue('BY_VOID_FLAG', 'N');

        $docSet = sprintf('(%s, %s, %s, %s, %s, %s)', 
            AccountDocument::ACCOUNT_DOC_MISC_REVENUE,
            AccountDocument::ACCOUNT_DOC_RECEIPT,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH,

            AccountDocument::ACCOUNT_DOC_MISC_EXPENSE,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH_BUY,
            AccountDocument::ACCOUNT_DOC_RECEIPT_BUY);

        $data->SetFieldValue('DOCUMENT_TYPE_SET', $docSet);
//CSql::SetDumpSQL(true);
        $u = new MAccountDocItem($db);        
        list($cnt, $rows) = $u->Query(5, $data);
//CSql::SetDumpSQL(false);
        return($rows);
    }

    private static function getCostItems($db, $param, $data)
    {        
        $year = $data->getFieldValue('DOCUMENT_YYYY');
        $cost = new CTable('');
        $cost->setFieldValue('COST_YEAR', $year);

        list($p, $c) = CostDocument::GetCostDocumentList($db, $param, $cost); 
        $arr = $c->getChildArray('COST_DOC_LIST');

        if (count($arr) <= 0)
        {
            return;
        }

        $c = $arr[0];
      
        list($p, $c) = CostDocument::GetCostDocumentInfo($db, $param, $c); 

        $costItems = $c->getChildArray('COST_DOC_ITEMS');

        self::$costHash = CHelper::RowToHash($costItems, ['ITEM_TYPE'], '');
    }
    
    public static function AcdGetProfitByDocTypeMonth($db, $param, $data)
    {        
        self::$saleCost = $data->getFieldValue('IS_SALE_COST');

        self::initDoctypesHash();

        self::populateDefaultDocStatusSet($data);
        
        self::getCostItems($db, $param, $data);
        self::parseCostItems($db, $data);

        self::populateExpRevDocTypeSet($data);
        $revExpitems = self::getExpenseRevenueRows($db, $data);
        self::parseRevExpItems($revExpitems);

        $whItems = self::getSalePurchaseWhRows($db, $data);
        self::parseWhItems($whItems);

        //Order is significant        
        self::accumulateByDocType();
        self::createProfitDocType();

        $rows = self::hashToArray(self::$docTypeHash);

        $p = new CTable('');
        $p->addChildArray('DOCTYPE_SUMMARY_LIST', $rows);

        return([$param, $p]);
    } 
}

?>