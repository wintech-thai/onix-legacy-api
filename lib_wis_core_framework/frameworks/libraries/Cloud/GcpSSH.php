<?php
/*
    Purpose : Controller for SSH module
    Created By : Seubpong Monsar
    Created Date : 09/02/2018 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class GcpSSH extends CloudBase
{
    private static $gcloudGroup = 'compute';
    private static $gcloudSubGroup = '';

    public static function SSHExecute($data)
    {
        $instance = $data->getFieldValue('ID');

        $defaultMaps = [
            'STRICT_HOST_KEY_CHECKING' => 'no',
        ];

        $paramMaps = [
            'STRICT_HOST_KEY_CHECKING' => '--strict-host-key-checking',
            'ZONE' => '--zone',
            'COMMAND' => '--command',
        ];

        list($id, $args) = self::constructArgument($defaultMaps, $paramMaps, $data);
        list($result, $lines, $output) = self::executeCommand(self::$gcloudGroup, 'ssh', $instance, '', $args);

        return([$result, $lines, $output]);
    }

    public static function SSHDownload($data)
    {
        $instance = $data->getFieldValue('ID');
        $source = $data->getFieldValue('SOURCE');
        $target = $data->getFieldValue('TARGET');

        $src = "$instance:$source";
    
        $defaultMaps = [
            'STRICT_HOST_KEY_CHECKING' => 'no',
        ];

        $paramMaps = [
            'STRICT_HOST_KEY_CHECKING' => '--strict-host-key-checking',
            'ZONE' => '--zone',
        ];

        list($id, $args) = self::constructArgument($defaultMaps, $paramMaps, $data);
        list($result, $lines, $output) = self::executeCommand(self::$gcloudGroup, 'scp', $src, $target, $args);

        return([$result, $lines, $output]);
    }

    public static function SSHCopy($data)
    {
        $instance = $data->getFieldValue('ID');
        $source = $data->getFieldValue('SOURCE');
        $target = $data->getFieldValue('TARGET');

        $dest = "$instance:$target";
    
        $defaultMaps = [
            'STRICT_HOST_KEY_CHECKING' => 'no',
        ];

        $paramMaps = [
            'STRICT_HOST_KEY_CHECKING' => '--strict-host-key-checking',
            'ZONE' => '--zone',
        ];

        list($id, $args) = self::constructArgument($defaultMaps, $paramMaps, $data);
        list($result, $lines, $output) = self::executeCommand(self::$gcloudGroup, 'scp', $source, $dest, $args);

        return([$result, $lines, $output]);
    }
}

?>
