<?php

$buildFile = 'onix_erp_framework.phar';

$srcRoot = './frameworks';
$buildRoot = './build';


print("Creating build.php ...\n");

$fname = "$srcRoot/build.php";
$oh = fopen($fname, "w") or die("Unable to open file [$fname]!");
date_default_timezone_set('Asia/Bangkok');
$dt = date("m/d/Y h:i:sa"); //date("m/d/Y");
$tmp = '$ONIX_ERP_FRAMEWORK_BUILT_DATE = ' . "'$dt'";

$stmt = <<<EOD
<?php
/* 
Purpose : Auto generated built time file (DO NOT MODIFY)
Created By : Seubpong Monsar
*/

$tmp;

?>
EOD;

fwrite($oh, $stmt);
fclose($oh);


$phar = new Phar($buildRoot . "/$buildFile", 0, $buildFile);

print("Starting building [$buildFile] to directory [$buildRoot]\n");

$phar->buildFromIterator(
    new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcRoot, FilesystemIterator::SKIP_DOTS)),
    $srcRoot);

print("Building file DONE\n");

?>