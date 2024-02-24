<?php
/*
    Purpose : Controller for VM Instance module
    Created By : Seubpong Monsar
    Created Date : 25/08/2018 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class GcpVM extends CloudBase
{
    private static $gcloudGroup = 'compute';
    private static $gcloudSubGroup = 'instances';

    public static function GetVMInfo($data)
    {
        $gcloudCmd = 'list';

        $instance = $data->getFieldValue('ID');
        $filter = '"name=(\'' . $instance . '\')"';

        $data->setFieldValue('FORMAT', 'json');
        $data->setFieldValue('FILTER', $filter);

        $defaultMaps = [
            'FORMAT' => 'json',
        ];

        $paramMaps = [
            'PROJECT' => '--project',
            'FILTER' => '--filter',
            'FORMAT' => '--format',
        ];

        list($id, $args) = self::constructArgument($defaultMaps, $paramMaps, $data);
        list($result, $lines, $jsonStr) = self::executeQueryCommand(self::$gcloudGroup, self::$gcloudSubGroup, $gcloudCmd, '', $args);

        $obj = $jsonStr; //Error string
        if ($result == 0)
        {
            $instances = json_decode($jsonStr);
            $obj = $instances[0];
        }

        return([$result, $lines, $obj]);
    }

    public static function StopVM($data)
    {   
        $gcloudCmd = 'stop';
        
        $defaultMaps = [
        ];  
        
        $paramMaps = [
            'PROJECT' => '--project',
            'ZONE' => '--zone',
        ];
        
        list($id, $args) = self::constructArgument($defaultMaps, $paramMaps, $data);
        list($result, $lines, $output) = self::executeCommand(self::$gcloudGroup, self::$gcloudSubGroup, $gcloudCmd, $id, $args);
        
        return([$result, $lines, $output]);
    }

    public static function StartVM($data)
    {
        $gcloudCmd = 'start';

        $defaultMaps = [
        ];

        $paramMaps = [
            'PROJECT' => '--project',
            'ZONE' => '--zone',
        ];

        list($id, $args) = self::constructArgument($defaultMaps, $paramMaps, $data);
        list($result, $lines, $output) = self::executeCommand(self::$gcloudGroup, self::$gcloudSubGroup, $gcloudCmd, $id, $args);

        return([$result, $lines, $output]);
    }

    public static function CreateVM($data)
    {
        $gcloudCmd = 'create';
    
        $defaultMaps = [
            'TAGS' => 'http-server,https-server',
            'BOOT_DISK_SIZE' => '10GB',
            'BOOT_DISK_TYPE' => 'pd-standard',
            'MACHINE_TYPE' => 'g1-small',
        ];

        $paramMaps = [
            'PROJECT' => '--project',
            'ZONE' => '--zone',
            'MACHINE_TYPE' => '--machine-type',
            'TAGS' => '--tags',
            'IMAGE' => '--image',
            'IMAGE_PROJECT' => '--image-project',
            'BOOT_DISK_SIZE' => '--boot-disk-size',
            'BOOT_DISK_TYPE' => '--boot-disk-type',
            'BOOT_DISK_DEVICE_NAME' => '--boot-disk-device-name'
        ];

        list($id, $args) = self::constructArgument($defaultMaps, $paramMaps, $data);
        list($result, $lines, $output) = self::executeCommand(self::$gcloudGroup, self::$gcloudSubGroup, $gcloudCmd, $id, $args);

        return([$result, $lines, $output]);
    }
}

?>
