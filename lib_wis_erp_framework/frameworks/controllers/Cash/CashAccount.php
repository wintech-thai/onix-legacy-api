<?php
/* 
    Purpose : Controller for Bill SImulate
    Created By : Seubpong Monsar
    Created Date : 09/12/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CashAccount extends CBaseController
{
    const CASH_ITEM_CODE = '##CASH##';

    private static $cfg = NULL;

    private static $orderByConfig = [
        'AccNo' => 'ACCOUNT_NO',
        'AccName' => 'ACCOUNT_NNAME',
        'Bank' => 'BANK_NAME',
        'Branch' => 'BANK_BRANCH_NAME',
        'money_quantity' => 'TOTAL_AMOUNT',
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
        $u = new MCashAccount($db);
        return($u);
    }

    public static function GetCashAccountList($db, $param, $data)
    {
        $u = self::createObject($db);

        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'CASH_ACCOUNT_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetCashAccountInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No cash account in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsCashAccountExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "ACCOUNT_NO", "ACCOUNT_NO", 0);
        
        return(array($param, $o));        
    }

    public static function RegisterBalanceItem($db)
    {
        $it = new CTable("");
        //No actual ID for cash
        $it->SetFieldValue("ACTUAL_ID", "");
        $it->SetFieldValue("BAL_ITEM_CODE", self::CASH_ITEM_CODE);
        $it->SetFieldValue("BAL_ITEM_NAME", self::CASH_ITEM_CODE);
        $it->SetFieldValue("BAL_ITEM_TYPE", BalanceAPI::BAL_ITEM_TYPE_CASH);

        BalanceAPI::RegisterItem($db, $it);
    }

    public static function RegisterBalanceOwner($db, $data)
    {
        $it = new CTable("");
        //No actual ID for cash
        $it->SetFieldValue("ACTUAL_ID", $data->GetFieldValue('CASH_ACCOUNT_ID'));
        $it->SetFieldValue("BAL_OWNER_CODE", $data->GetFieldValue('ACCOUNT_NO'));
        $it->SetFieldValue("BAL_OWNER_NAME", $data->GetFieldValue('ACCOUNT_NNAME'));
        $it->SetFieldValue("REF1", $data->GetFieldValue('BANK_ID'));
        $it->SetFieldValue("BAL_OWNER_TYPE", BalanceAPI::BAL_OWNER_TYPE_CASHACCT);

        BalanceAPI::RegisterOwner($db, $it);
    }

    private static function updateDefaultFields($data)
    {
        $chequeFlag = $data->GetFieldValue('IS_FOR_CHEQUE');

        if ($chequeFlag == '')
        {
            $data->SetFieldValue('IS_FOR_CHEQUE', 'N');
        }
    }

    public static function CreateCashAccount($db, $param, $data)
    {
        $u = self::createObject($db);
        self::updateDefaultFields($data);

        $db->beginTransaction();

        $childs = self::initSqlConfig($db);

        self::CreateData($db, $data, $u, 0, $childs);
        self::RegisterBalanceItem($db);
        self::RegisterBalanceOwner($db, $data);

        $db->commit();

        return(array($param, $data));        
    }    

    public static function UpdateCashAccount($db, $param, $data)
    {
        $u = self::createObject($db);
        self::updateDefaultFields($data);
        
        $db->beginTransaction();

        $childs = self::initSqlConfig($db);

        self::UpdateData($db, $data, $u, 0, $childs);
        self::RegisterBalanceItem($db);
        self::RegisterBalanceOwner($db, $data);

        $db->commit();

        return(array($param, $data));        
    }      

    public static function DeleteCashAccount($db, $param, $data)
    {     
        $u = self::createObject($db);

        $db->beginTransaction();

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        $data->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('CASH_ACCOUNT_ID'));   
        $data->SetFieldValue("BAL_OWNER_TYPE", BalanceAPI::BAL_OWNER_TYPE_CASHACCT);     
        BalanceAPI::UnRegisterOwner($db, $data);

        $db->commit();

        return(array($param, $data));
    }

    public static function CopyCashAccount($db, $param, $data)
    {
        list($p, $d) = self::GetCashAccountInfo($db, $param, $data);
        self::PopulateNewCode($d, 'ACCOUNT_NO');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateCashAccount($db, $param, $d);
        list($p, $d) = self::GetCashAccountInfo($db, $param, $d);
        
        return(array($param, $d));        
    }   
    
    public static function UpdateCashAccountTotalAmount($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 2, $childs);

        return(array($param, $data));      
    }        
}

?>