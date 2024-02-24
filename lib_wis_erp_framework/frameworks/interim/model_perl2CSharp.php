#!/usr/local/bin/php

<?php

$_ENV['BIN_DIR']='/wis/onix/dev/wis/framework/system/bin';

require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackage.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackageBonus.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackageBranch.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackageBundle.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackageCustomer.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackageDiscount.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackageFinalDiscount.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackagePeriod.php"; 
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackagePrice.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackageTrayPrice.php";
require_once "phar://$_ENV[BIN_DIR]/onix_erp_framework.phar/models/MPackageVoucher.php";   

$obj_arr = [
/*    
        new MPackage(null),
*/
        new MPackageBonus(null),
        new MPackageBranch(null),
        new MPackageBundle(null),
        new MPackageCustomer(null),
        new MPackageDiscount(null),
        new MPackageFinalDiscount(null),
        new MPackagePeriod(null),
        new MPackagePrice(null),
        new MPackageTrayPrice(null),
        new MPackageVoucher(null),
    ];

    foreach ($obj_arr as $obj)
    {
        CreateCSharpModel($obj);        
    }

exit(0);

function CreateCSharpModel($obj)
{
    $className = get_class($obj);
    $tableName = $obj->GetTableName();
    $tName = substr($className, 1);
    $tName = "T$tName";
    $fName = "$tName.cs";
    $pkName = $obj->GetPKName();

    $fname = "/home/seubpong/csharp/$fName";
    $oh = fopen($fname, "w") or die("Unable to open file [$fname]!");

    CreateContent($obj, $tName, $tableName, $pkName, $oh);

    fclose($oh);
}

function CreateDefinition($obj, $idx)
{
    $cols = $obj->GetColumnDefs()[$idx];
    $froms = $obj->GetFromDefs()[$idx];
    $orders = $obj->GetOrderDefs()[$idx];

    $froms = str_replace("FROM ", "", $froms);
    $orders = str_replace("ORDER BY ", "", $orders);

    $tmp = '';
    foreach ($cols as $col)
    {
        $tmp = $tmp . "                \"$col\",\n";
    }

$stmt = <<<EODX

            //====== $idx =======
            ArrayList cols$idx = new ArrayList()
            {
$tmp
            };

            AddColumnDefinition(cols$idx);
            String from$idx = "$froms";
            AddFrom(from$idx);
            AddOrderBy("$orders ");
EODX;

    return($stmt);
}

function CreateContent($obj, $className, $tableName, $pkName, $oh)
{
    $cols = $obj->GetColumnDefs();
    $cnt = count($cols);

    $block = '';
    for ($i=0; $i<$cnt; $i++)
    {
        $def = CreateDefinition($obj, $i);
        $block = $block . "$def\n";
    }

$stmt = <<<EOD
using System;
using System.Collections;

namespace Onix.ClientPos
{
    class $className : TBase
    {
        public $className() : base("$tableName", "$pkName")
        {
$block            
        }        
    }
}
EOD;

//print($stmt);
fwrite($oh, $stmt);
}

?>
