<?php
/*
    Purpose : Controller for COMMISSION_BATCH
    Created By : Supakit Tanyung
    Created Date : 10/05/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class Commission extends CBaseController
{
  public static function GetCommissionList($db, $param, $data)
  {
    $u = new MCommission($db);
    list($cnt, $rows) = $u->Query(2, $data);

    $pkg = new CTable($u->GetTableName());
    self::PopulateRow($pkg, null, null, 'COMMISSION_LIST', $rows);

    return([$param, $pkg]);
  }
}