<?php
/* 
    Purpose : Controller for General Report
    Created By : Seubpong Monsar
    Created Date : 10/07/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class GeneralReport extends CBaseController
{
    public static function GetServiceListAll($db, $param, $data)
    {
        ProductService::populateCategoryFlagForQuery($data);

        $u = new MService($db);
        $u->OverideOrderBy(1, 'ORDER BY SV.SERVICE_CODE ASC, SV.SERVICE_NAME ASC ');

        list($cnt, $rows) = $u->Query(1, $data);

        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SERVICE_LIST', $rows);
        
        return(array($param, $p));
    }
}

?>