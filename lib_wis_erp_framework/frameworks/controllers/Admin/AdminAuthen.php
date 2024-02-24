<?php
/*
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/03/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AdminAuthen extends CBaseController
{
    const PASSWORD_PATTERN = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';

    public static function Logout($db, $param, $data)
    {
        list($p, $d) = AdminAuthentication::Logout($db, $param, $data);
        return(array($p, $d));
    }

    public static function ChangeUserPassword($db, $param, $data)
    {
        list($p, $d) = AdminAuthentication::ChangeUserPassword($db, $param, $data);
        return(array($p, $d));
    }

    public static function ChangePassword($db, $param, $data)
    {
        list($p, $d) = AdminAuthentication::ChangePassword($db, $param, $data);
        return(array($p, $d));
    }

    public static function CheckPermission($db, $param, $data)
    {
        list($p, $d) = AdminAuthentication::CheckPermission($db, $param, $data);
        return(array($p, $d));
    }

    public static function Login($db, $param, $data)
    {
        list($p, $d) = AdminAuthentication::Login($db, $param, $data);
        return(array($p, $d));
    }
}

?>