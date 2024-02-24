<?php
/* 
    Purpose : Controller for Report Filter
    Created By : Seubpong Monsar
    Created Date : 12/25/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class ReportFilter extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['REPORT_FILTER_ITEM', new MReportFilter($db), 0, 0, 0],
        );

        self::$cfg = $config;

        return($config);
    }

    public static function GetReportFilterInfo($db, $param, $data)
    {
        $cfg = self::initSqlConfig($db);

        $u = new MVirtualModel('REPORT_NS');
        $obj = new CTable("");
        $obj->SetFieldValue($u->GetPKName(), "0");

        self::PopulateChildItems($obj, $u, $cfg);

        return([$param, $obj]);        
    }

    public static function UpdateReportFilter($db, $param, $data)
    {
        $u = new MVirtualModel('REPORT_NS');
        
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return([$param, $data]);        
    }  

    public static function CreateReportFilter($db, $param, $data)
    {
        $u = new MVirtualModel('REPORT_NS');
        
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return([$param, $data]);        
    } 

    public static function GetSingleReportFilterInfo($db, $param, $data)
    {
        $id = $data->GetFieldValue('REPORT_FILTER_ID');

        $u = new MReportFilter($db);
        if ($id != '')
        {
            //By ID            
            $obj = self::GetRowByID($data, $u, 0);
            
            if (!isset($obj))
            {
                throw new Exception("No Report Filter ID [$id] in database!!!");
            }
        }
        else
        {
            throw new Exception("ID must be specified for getting a report filter!!!");
        }    
        
        return([$param, $obj]);       
    }    
}

?>