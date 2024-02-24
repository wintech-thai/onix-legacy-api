<?php
/* 
    Purpose : Controller for Bill SImulate
    Created By : Seubpong Monsar
    Created Date : 09/12/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class BillSimulate extends CBaseController
{
    const BS_DISPLAY_RESULT_ITEM = '1';
    const BS_DISPLAY_FREE_ITEM = '2';
    const BS_DISPLAY_VOUCHER_ITEM = '3';
    const BS_DISPLAY_POSTFREE_ITEM = '4';

    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['BILL_SIMULATE_ITEM', new MBillSimulateItem($db), 2, 0, 1],
            ['BILL_RESULT_ITEM', new MBillSimulateDisplay($db), 2, 0, 1],
            ['BILL_FREE_ITEM', new MBillSimulateDisplay($db), 2, 0, 1],
            ['BILL_VOUCHER_ITEM', new MBillSimulateDisplay($db), 2, 0, 1],
            ['BILL_POSTGIFT_ITEM', new MBillSimulateDisplay($db), 2, 0, 1],
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MBillSimulate($db);
        return($u);
    }

    public static function GetBillSimulateList($db, $param, $data)
    {
        $u = self::createObject($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'BILL_SIMULATE_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetBillSimulateInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No bill simulate in database!!!");
        }

        $categoryArr = ['0', self::BS_DISPLAY_RESULT_ITEM, self::BS_DISPLAY_FREE_ITEM, 
            self::BS_DISPLAY_VOUCHER_ITEM, self::BS_DISPLAY_POSTFREE_ITEM];

        $idx = 0;
        foreach($cfg as $c)
        {
            list($arrName, $m, $queryInd, $addEditInd, $delInd) = $c;  
            
            $parentPK = $u->GetPKName();
            $pid = $obj->GetFieldValue($parentPK);
    
            $d = new CTable("");
            $d->SetFieldValue($parentPK, $pid);
            $d->SetFieldValue('DISPLAY_CATEGORY', $categoryArr[$idx]);

            list($cnt, $rows) = $m->query($queryInd, $d);                
            $obj->AddChildArray($arrName, $rows);  
            
            $idx++;
        }

        //self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsBillSimulateExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "DOCUMENT_NO", "DOCUMENT_NO", 0);
        
        return(array($param, $o));        
    }

    public static function CreateBillSimulate($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateBillSimulate($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteBillSimulate($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     

    public static function CopyBillSimulate($db, $param, $data)
    {
        list($p, $d) = self::GetBillSimulateInfo($db, $param, $data);
        self::PopulateNewCode($d, 'DOCUMENT_NO');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateBillSimulate($db, $param, $d);
        list($p, $d) = self::GetBillSimulateInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>