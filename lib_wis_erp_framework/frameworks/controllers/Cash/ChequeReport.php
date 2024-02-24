<?php
/* 
    Purpose : Controller for Cheque Report
    Created By : Seubpong Monsar
    Created Date : 04/21/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class ChequeReport extends CBaseController
{
    private static $cfg = NULL;

    private static function createObject($db)
    {
        $u = new MCheque($db);
        return($u);
    }
        
    public static function GetChequeListAll($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = self::createObject($db);
        $u->OverideOrderBy(1, 'ORDER BY CQ.CHEQUE_DATE ASC, CQ.CHEQUE_NO ASC ');

        list($cnt, $rows) = $u->Query(1, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'CHEQUE_LIST', $rows);
        
        return(array($param, $p));
    }
}

?>