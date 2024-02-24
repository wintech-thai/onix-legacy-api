<?php
/* 
    Purpose : API for Storage
    Created By : Seubpong Monsar
    Created Date : 11/17/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class StorageAPI extends CBaseController
{
    private static function deriveFileName($fname, $mode)
    {
        if (preg_match('/\.\./', $fname))
        {
            throw new Exception("File name [$fname] is invalid!!!");
        }

        $storagePath = $_ENV['WIP_DIR'];
        if ($mode == 'storage')
        {
            $storagePath = $_ENV['STORAGE_DIR'];
            if (!file_exists($storagePath))
            {
//throw new Exception("Fake [$storagePath] is invalid!!!");
                mkdir($storagePath, 0700, true);
            }            
        }

        $path = sprintf('%s/%s', $storagePath, $fname);

        return($path);
    }

    private static function deriveSrcDestFile($data, $flag)
    {
        $srcName = $data->getFieldValue('SRC_FILE_NAME');
        $dstName = $data->getFieldValue('DST_FILE_NAME');

        $srcFileName = self::deriveFileName($srcName, 'wip');
        $destFileName = self::deriveFileName($dstName, 'storage');

        if (!file_exists($srcFileName))
        {
            throw new Exception("File name [$srcFileName] does not exist!!!");
        }
        else if (!file_exists($destFileName) && ($flag == 'U'))
        {
            //Update and file does not exist
            throw new Exception("File name [$destFileName] does not exist!!!");            
        }

        return([$srcFileName, $destFileName]);
    }

    public static function StorageCreateFile($db, $param, $data)
    {
        list($srcFileName, $destFileName) = self::deriveSrcDestFile($data, 'C');

        $srcMetaName = "$srcFileName.meta";
        $destMetaName = "$destFileName.meta";

        $destDir = dirname($destFileName);
        if (!file_exists($destDir))
        {
            mkdir($destDir, 0700, true);
        } 

        copy($srcFileName, $destFileName);
        copy($srcMetaName, $destMetaName);

        unlink($srcFileName);
        unlink($srcMetaName);

        return(array($param, $data));
    }

    public static function StorageUpdateFile($db, $param, $data)
    {
        list($srcFileName, $destFileName) = self::deriveSrcDestFile($data, 'U');

        $srcMetaName = "$srcFileName.meta";
        $destMetaName = "$destFileName.meta";

        copy($srcFileName, $destFileName);
        copy($srcMetaName, $destMetaName);

        unlink($srcFileName);
        unlink($srcMetaName);
                
        return(array($param, $data));
    }

    public static function StorageDeleteFile($db, $param, $data)
    {
        $fname = $data->getFieldValue('DST_FILE_NAME');
        $destFileName = self::deriveFileName($fname, 'storage');

        $destMetaName = "$destFileName.meta";

        unlink($destFileName);
        unlink($destMetaName);

        return(array($param, $data));
    }   

    public static function StorageFileExist($fname)
    {
        $fullPath = self::deriveFileName($fname, 'storage');
        $result = file_exists($fullPath);

        return($result);
    }         
}

?>