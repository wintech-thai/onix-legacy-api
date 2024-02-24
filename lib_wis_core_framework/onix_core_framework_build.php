<?php

$buildFile = 'onix_core_framework.phar';

$srcRoot = './frameworks';
$buildRoot = './build';

print("Creating build.php ...\n");

$fname = "$srcRoot/build.php";
$oh = fopen($fname, "w") or die("Unable to open file [$fname]!");
date_default_timezone_set('Asia/Bangkok');
$dt = $dt = date("m/d/Y h:i:sa");
$tmp = '$ONIX_CORE_BUILT_DATE = ' . "'$dt'";

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

#====
print("Creating CBuild.cs ...\n");
#====

$phar = new Phar($buildRoot . "/$buildFile", 
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, 
    $buildFile);

print("Starting building [$buildFile] to directory [$buildRoot]\n");

$phar->addFile('./onix_core_dispatcher.php', 'onix_core_dispatcher.php');
$phar->addFile('./onix_core_include.php', 'onix_core_include.php');
$phar->addFile('./onix_core_content.php', 'onix_core_content.php');
$phar->addFile('./onix_core_uploader.php', 'onix_core_uploader.php');
$phar->addFile('./index.php', 'index.php');
$phar->buildFromDirectory("$srcRoot", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/controllers", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/models", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/libraries/Balance", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/libraries/NumberGenerator", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/libraries/Authentication", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/libraries/Http", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/libraries/Cloud", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/libraries/Storage", '/.+\.php$/');
$phar->buildFromDirectory("$srcRoot/patch", '/.+\.sql$/');

print("Building file DONE\n");

?>
