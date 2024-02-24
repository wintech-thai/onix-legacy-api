<?php

/*
IBSRevision : 1.0 (11/11/2018)
FILE    : content.php
AUTHOR  : Seubpong Monsar
PURPOSE : Perl script to show file content
*/

declare(strict_types=1);
require_once "phar://onix_core_framework.phar/onix_core_include.php";

$binDir = $_ENV['BIN_DIR'];

$wipDir = "$binDir/../../wip";
$docDir = "$binDir/../../storage";
$sessionDir = "$binDir/../../session";

//Later used by CUtils API
$_ENV['SESSION_DIR'] = $sessionDir;

$name = '';
if (array_key_exists('name', $_GET))
{
    $name = $_GET['name'];
}
else
{
    die("Parameter [name] not found!!!!");
}

$area = 'wip';
if (array_key_exists('area', $_GET))
{
    $area = $_GET['area'];
}

if (($area != 'wip') && ($area != 'storage'))
{
    die("Area [$area] unknown, only wip or storage is allowed!!!!");
}

if (preg_match('/\.\.|%/', $name))
{
    die("File name not allowed!!!!");
}

$session = $_GET['session'];

$baseDocument = $wipDir;
if ($area == 'storage')
{
    $baseDocument = $docDir;
}

$path_to_files = "$baseDocument/$name";
$path_to_meta = "$path_to_files.meta";

if (!file_exists($path_to_files))
{
    die("File [$path_to_files] not found!!!!");
}

if (!file_exists($path_to_meta))
{
    die("Meta file [$path_to_meta] not found!!!!");
}

$metaHash = CUtils::ReadMetaFile($path_to_meta);

$publicShare = getMetaValue($metaHash, 'PublicShare', 'N');
if ($publicShare != 'Y')
{
    //Check session here
    $data = new CTable('');
    $data->setFieldValue('SESSION', $session);
    $sessionObj = CUtils::GetSessionObject($data);
    
    if ($sessionObj == NULL)
    {
        die("Session [$session] not found!!!!");
    }
}

$filesize = filesize($path_to_files);

$bname = basename($path_to_files);

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $path_to_files);
finfo_close($finfo);

header("Content-length: $filesize");
header("Content-Type: $mime");
header("Content-Disposition: attachment;filename=\"$bname\"");

$content = file_get_contents($path_to_files);
print($content);

function getMetaValue($metaHash, $key, $defaultValue)
{
    $value = $defaultValue;
    if (array_key_exists($key, $metaHash))
    {
        $value = $metaHash[$key];
    }

    return($value);
}

?>
