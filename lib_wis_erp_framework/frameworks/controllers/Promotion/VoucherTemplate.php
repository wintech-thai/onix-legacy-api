<?php
/* 
    Purpose : Controller for Bill SImulate
    Created By : Seubpong Monsar
    Created Date : 09/12/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class VoucherTemplate extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MVoucherTemplate($db);
        return($u);
    }

    public static function GetVoucherTemplateList($db, $param, $data)
    {
        $u = self::createObject($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'VOUCHER_TEMPLATE_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetVoucherTemplateInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No voucher template in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsVoucherTemplateExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "VC_TEMPLATE_NO", "VC_TEMPLATE_NO", 0);
        
        return(array($param, $o));        
    }

    public static function CreateVoucherTemplate($db, $param, $data)
    {
        $u = self::createObject($db);
        
        self::PopulateStartEndDate($data, 'EFFECTIVE_DATE', true);
        self::PopulateStartEndDate($data, 'EXPIRE_DATE', false);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateVoucherTemplate($db, $param, $data)
    {
        $u = self::createObject($db);
        
        self::PopulateStartEndDate($data, 'EFFECTIVE_DATE', true);
        self::PopulateStartEndDate($data, 'EXPIRE_DATE', false);
                
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteVoucherTemplate($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     

    public static function CopyVoucherTemplate($db, $param, $data)
    {
        list($p, $d) = self::GetVoucherTemplateInfo($db, $param, $data);
        self::PopulateNewCode($d, 'VC_TEMPLATE_NO');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateVoucherTemplate($db, $param, $d);
        list($p, $d) = self::GetVoucherTemplateInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      
}

?>