<?php
/* 
    Purpose : One base class for Controller
    Created By : Seubpong Monsar
    Created Date : 09/03/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class CFrameworkServices extends CBaseController
{
    private static $frwPatcheModel = 'MFrameworkPatchHistory';
    private static $frwModelPHP = 'phar://onix_core_framework.phar/MFrameworkPatchHistory.php';
    private static $frwSqlPath = 'phar://onix_core_framework.phar';
    private static $frwPostPatches = [];

    private static $frwPatchList = [    
        ['1.0.3', 'frw_onix_1.0.3.20170910.sql'],
    ];

    public static function Patch($db, $param, $data)
    {
        $fh = CUtils::LockEntity('FRW_PATCH_HISTORY');                

        //Patch framework first
        CPatchDB::RegisterPatchConfig(self::$frwPatchList, self::$frwPatcheModel, self::$frwModelPHP, 
            self::$frwSqlPath, self::$frwPostPatches);

        CPatchDB::Patch($db, $param, $data);

        $extraes = CPatchDB::GetExtraPatchList();
        foreach ($extraes as $extra)
        {
            list($plist, $pm, $md, $sqlp, $custp, $postp) = $extra;

            CPatchDB::RegisterPatchConfig($plist, $pm, $md, $sqlp, $postp);
            CPatchDB::RegisterCustomPatchConfig($custp);
            CPatchDB::Patch($db, $param, $data);
        }

        CUtils::UnlockEntity($fh);

        return(array($param, $data));        
    }

    public static function Echo($db, $param, $data)
    {
        $echo_message = $data->GetFieldValue("ECHO_MESSAGE");   
        $data->SetFieldValue("RETURN_MESSAGE", $echo_message);

        return(array($param, $data));
    }    

    public static function TestLock($db, $param, $data)
    {
//print("Trying to lock file...\n");
        $fh = CUtils::LockEntity('FRW_PATCH_HISTORY');
//print("Locking ...\n");
//sleep(5);

//print("Trying to unlock file...\n");
        CUtils::UnlockEntity($fh);
//print("Unlocked!!!\n");

        return(array($param, $data));
    }     
}

?>