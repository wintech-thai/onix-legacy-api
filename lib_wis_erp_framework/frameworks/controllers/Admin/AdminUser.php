<?php
/*
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AdminUser extends CBaseController
{
    public static function GetUserList($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::GetUserList($db, $param, $data);
        return(array($p, $d));
    }

    public static function GetUserInfo($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::GetUserInfo($db, $param, $data);
        return(array($p, $d));
    }

    public static function IsUserExist($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::IsUserExist($db, $param, $data);
        return(array($p, $d));
    }

    public static function CreateInitAdminUser($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::CreateInitAdminUser($db, $param, $data);
        return(array($p, $d));
    }

    public static function CreateUser($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::CreateUser($db, $param, $data);
        return(array($p, $d));
    }

    public static function UpdateUser($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::UpdateUser($db, $param, $data);
        return(array($p, $d));
    }

    public static function UpdateUserVariables($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::UpdateUserVariables($db, $param, $data);
        return(array($p, $d));
    }

    public static function DeleteUser($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::DeleteUser($db, $param, $data);
        return(array($p, $d));
    }

    public static function CopyUser($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::CopyUser($db, $param, $data);
        return(array($p, $d));
    }

    public static function GetLoginHistoryList($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationUser::GetLoginHistoryList($db, $param, $data);
        return(array($p, $d));
    }
}

?>