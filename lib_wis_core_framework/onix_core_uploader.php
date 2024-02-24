<?php
/*
IBSRevision : 1.0 (12/01/2018)
FILE    : onix_core_uploader.php
AUTHOR  : Seubpong Monsar
PURPOSE : Perl script to upload file
*/

declare(strict_types=1);
require_once "phar://onix_core_framework.phar/onix_core_include.php";

$sessionDir = '../../session';
$wipDir = '../../wip';
$KNOWN_KEY = ['ImageUpload', 'Session', 'PublicShare', 'UserName'];
$PROPERTIES = [];

$tmpFile = realpath($_FILES['file']['tmp_name']);
$errCode = $_FILES['file']['error'];
$mimeType = $_FILES['file']['type'];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmpFile);
finfo_close($finfo);

$imageCheck = $_GET['ImageUpload'];
$session = $_GET['Session'];

//basename() to prevent path traversal
$baseName = basename($tmpFile);
$isExist = file_exists($tmpFile);

if ($errCode > 0)
{
    printf("%s|%s|%s|%s|%s", 'ERROR', $errCode, $tmpFile, $mime, 'Check php error code for more details');
    return;
}

if ((trim($session) == '') || (preg_match('/\./', $session)))
{
    //Session is blank and contains one or more dot
    printf("%s|%s|%s|%s|%s", 'ERROR', $errCode, $tmpFile, $mime, 'Session is required');
    return;
}

if ($imageCheck == 'Y')
{
    if (!preg_match('/image/', $mime))
    {
        printf("%s|%s|%s|%s|%s", 'ERROR', $errCode, $tmpFile, $mime, "Not valid image file [$mime]");
        return;
    }

    list($width, $height, $type, $attr) = getimagesize($tmpFile);
    $PROPERTIES['ImageWidth'] = $width;
    $PROPERTIES['ImageHeight'] = $height;
    $PROPERTIES['ImageType'] = $type;
}

if (!file_exists("$sessionDir/$session"))
{
    printf("%s|%s|%s|%s|%s", 'ERROR', $errCode, $tmpFile, $mime, 'Session not found');
    return;
}

$PROPERTIES['MimeType'] = $mime;

$token = md5($baseName);

$movedFile = "$wipDir/$token";
$result = move_uploaded_file($tmpFile, $movedFile);

$metaFile = sprintf('%s.meta', $movedFile);
create_meta_file($KNOWN_KEY, $PROPERTIES, $metaFile);

printf("%s|%s|%s|%s|%s", 'SUCCESS', 0, $tmpFile, $mime, $token);
exit(0);

function create_meta_file($keys, $properties, $fname)
{
    $fh = fopen($fname, 'w');
    foreach ($keys as $key)
    {
        $value = $_GET[$key];

        $line = sprintf("%s=%s\n", $key, $value);
        fwrite($fh, $line);
    }

    foreach ($properties as $key => $value)
    {
        $line = sprintf("%s=%s\n", $key, $value);
        fwrite($fh, $line);
    }

    fclose($fh);
}

