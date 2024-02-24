<?php
/* 
    Purpose : Log manager
    Created By : Seubpong Monsar
    Created Date : 09/11/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class CLog
{
    private static $fh = NULL;
    private static $logName = '';

    public static function Open($param, $data)
    {
    }

    public static function WriteLn($msg)
    {
        if (!isset(self::$fh))
        {
            return;
        }

        $dtm = date('Y-m-d_H:i:s');
        fprintf(self::$fh, "$dtm : %s\n", $msg);
    }    

    public static function WriteLnMt($funcName, $msg)
    {
        if (!isset(self::$fh))
        {
            return;
        }

        $dtm = date('Y-m-d_H:i:s');
        fprintf(self::$fh, "$dtm : $funcName : %s\n", $msg);
    }  

    public static function Close()
    {
        if (isset(self::$fh))
        {
            fclose(self::$fh);
        }
    }

    public static function GetLogName()
    {
        return(self::$logName);
    }
}

?>