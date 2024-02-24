<?php
/* 
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 12/18/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class ReportConfig extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = [];

        self::$cfg = $config;

        return($config);
    }

    public static function GetReportConfigList($db, $param, $data)
    {
        $u = new MReportConfig($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'REPORT_CONFIG_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetReportConfigInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MReportConfig($db);
        $obj = self::GetRowByID($data, $u, 0);
        
        if (!isset($obj))
        {
            throw new Exception("No report config in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        $xml = $obj->GetFieldValue('CONFIG_VALUE');
        $rpc = new CTable("");
        
        if ($xml != '')
        {
            list($param, $rpc) = CUtils::ProcessRequest($xml);                      
        }

        $rpc->SetFieldValue('REPORT_CONFIG_ID', $obj->GetFieldValue('REPORT_CONFIG_ID'));
        $rpc->SetFieldValue('REPORT_NAME', $obj->GetFieldValue('REPORT_NAME'));
        $rpc->SetFieldValue('USER_ID', $obj->GetFieldValue('USER_ID'));

        return(array($param, $rpc));  
    }

    public static function IsReportConfigExist($db, $param, $data)
    {
        $u = new MReportConfig($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "REPORT_NAME", "REPORT_NAME", 0);
        
        return(array($param, $o));
    }

    private static function deriveObject($db, $param, $data)
    {
        $o = new CTable("");
        
        $xml = CUtils::CreateResultXML(new CTable("PARAM"), $data);

        $o->SetFieldValue('REPORT_NAME', $data->GetFieldValue('REPORT_NAME'));
        $o->SetFieldValue('REPORT_CONFIG_ID', $data->GetFieldValue('REPORT_CONFIG_ID'));
        $o->SetFieldValue('USER_ID', $data->GetFieldValue('USER_ID'));
        $o->SetFieldValue('CONFIG_VALUE', $xml);

        return($o);
    }

    public static function CreateReportConfig($db, $param, $data)
    {
        $u = new MReportConfig($db);

        $o = self::deriveObject($db, $param, $data);
     
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $o, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateReportConfig($db, $param, $data)
    {
        $u = new MReportConfig($db);

        $o = self::deriveObject($db, $param, $data);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $o, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteReportConfig($db, $param, $data)
    {
        $u = new MReportConfig($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     

    public static function CopyReportConfig($db, $param, $data)
    {
        list($p, $d) = self::GetReportConfigInfo($db, $param, $data);
        self::PopulateNewCode($d, 'REPORT_NAME');
        self::InitCopyItems($d, self::$cfg);

        list($p, $d) = self::CreateReportConfig($db, $param, $d);
        list($p, $d) = self::GetReportConfigInfo($db, $param, $d);
        
        return(array($param, $d));        
    }

    public static function SaveReportConfig($db, $param, $data)
    {
        $id = $data->GetFieldValue("REPORT_CONFIG_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateReportConfig($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateReportConfig($db, $param, $data);
        }
        
        return(array($p, $d));
    }    
}

?>