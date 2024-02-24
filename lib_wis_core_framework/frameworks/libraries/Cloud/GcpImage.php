<?php
/*
    Purpose : Controller for VM Image module
    Created By : Seubpong Monsar
    Created Date : 09/03/2018 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class GcpImage extends CloudBase
{
    private static $gcloudGroup = 'compute';
    private static $gcloudSubGroup = 'images';

    public static function CreateImageFromDisk($data)
    {
        $gcloudCmd = 'create';
        
        $defaultMaps = [
        ];  
        
        $paramMaps = [
            'SOURCE_DISK' => '--source-disk',
            'SOURCE_DISK_ZONE' => '--source-disk-zone',
        ];
        
        list($id, $args) = self::constructArgument($defaultMaps, $paramMaps, $data);
        list($result, $lines, $output) = self::executeCommand(self::$gcloudGroup, self::$gcloudSubGroup, $gcloudCmd, $id, $args);
        
        return([$result, $lines, $output]);
    }
}

?>
