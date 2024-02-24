<?php

/*
IBSRevision : 1.0 (10/10/2017)
FILE    : downloader.php
AUTHOR  : Seubpong Monsar
PURPOSE : PHP script to process download command.
*/

declare(strict_types=1);
$bin = dirname(__FILE__);
$dldir = "$bin/../../download";

$name = '';
if (array_key_exists('name', $_GET))
{
    $name = $_GET['name'];
}
else
{
    die("Parameter [name] not found!!!!");
}

if (!preg_match('/(\.png$)|(\.zip$)|(\.jpg$)|(\.js$)|(\.css$)/', $name))
{
    die("File type not allowed!!!!");
}

//Not allow .. for security reason
$name = basename($name);

$path_to_files = "$dldir/$name";
if (!file_exists($path_to_files))
{
    die("File [$path_to_files] not found!!!!");
}

$filesize = filesize($path_to_files);

$bname = basename($path_to_files);

header("Content-length: $filesize");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment;filename=\"$bname\"");

$content = file_get_contents($path_to_files);
print($content);

?>
