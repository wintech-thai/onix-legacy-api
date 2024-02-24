<?php

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class CloudBase 
{
    private static $cmd = 'gcloud';
    private static $format = 'json';
    private static $debugMode = false;

    public static function SetDebugMode($flag)
    {
        self::$debugMode = $flag;
    }

    protected static function constructArgument($defaultMaps, $paramMaps, $data)
    {
        $id = $data->getFieldValue('ID');

        $flagArr = [];

        foreach ($paramMaps as $key => $value)
        {
            $paramValue = $data->getFieldValue($key);

            if ($paramValue == '')
            {
                if (array_key_exists($key, $defaultMaps))
                {
                    $paramValue = $defaultMaps[$key];
                }
            }

            $option = sprintf('%s=%s', $value, $paramValue);
            array_push($flagArr, $option);
        } 

        $flagParam = join(' ', $flagArr);

        return([$id, $flagParam]);
    }

    protected static function executeCommand($group, $subgroup, $command, $id, $args)
    {
        $cmd = sprintf("%s %s %s %s %s %s 2>&1 ", self::$cmd, $group, $subgroup, $command, $id, $args);

        if (self::$debugMode)
        {
            printf("DEBUG : Executing ... [%s]\n", $cmd);
        }

        exec($cmd, $lines, $result);

        $output = implode("\n", $lines);

        return([$result, $lines, $output]);
    }

    protected static function executeQueryCommand($group, $subgroup, $command, $id, $args)
    {
        $cmd = sprintf("%s %s %s %s %s %s 2>&1 ", self::$cmd, $group, $subgroup, $command, $id, $args);

        if (self::$debugMode)
        {
            printf("DEBUG : Executing ... [%s]\n", $cmd);
        }

        exec($cmd, $lines, $result);

        $delim = "\n";
        if ($result == 0)
        {
            $delim = "";
        }

        $output = implode($delim, $lines);

        return([$result, $lines, $output]);
    }
}

?>
