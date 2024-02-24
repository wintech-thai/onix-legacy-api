<?php
/*
    Purpose : Controller for Authentication Module
    Created By : Seubpong Monsar
    Created Date : 22/01/2018 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class AdminAuthentication extends CBaseController
{
    const PASSWORD_PATTERN = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/';

    public static function Logout($db, $param, $data)
    {
        self::UnregisterSession($db, $param, $data);

        $history = new CTable("");
        $history->SetFieldValue("LOGIN_ID", $param->GetFieldValue("LOGIN_ID"));
        $history->SetFieldValue("LOGOUT_DATE", CUtils::GetCurrentDateTimeInternal());
        $lh = new MFrwLoginHistory($db);
        $lh->Update(2, $history);

        return(array($param, $data));
    }

    private static function passwd($db, $param, $data, $chkOwner)
    {
        $u = new MFrwUser($db);
        $user = self::GetRowByID($data, $u, 4);

        if (!isset($user))
        {
            throw new Exception("No this user in database!!!");
        }

        $req_password = $data->GetFieldValue("PASSWORD");
        $enc_password = $user->GetFieldValue("PASSWORD");
        $new_password = $data->GetFieldValue("NEW_PASSWORD");

        if (!preg_match(self::PASSWORD_PATTERN, $new_password))
        {
            throw new Exception("Password is not secure enough, please create the new password!!!");
        }

        if ($chkOwner)
        {
            if (!password_verify($req_password, $enc_password))
            {
                #Incorrect password
                throw new Exception("Password is incorrect!!!!");
            }

            $sessionObj = CUtils::GetSessionObject($param);
            if (!isset($sessionObj))
            {
                throw new Exception("Session not found!!!!");
            }

            if ($sessionObj->GetFieldValue('USER_ID') != $data->GetFieldValue('USER_ID'))
            {
                throw new Exception("You are not the owner of this user. Not allow to change password of the others!!!");
            }
        }
        
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);

        $user->SetFieldValue("PASSWORD", $new_password);
        $u->Update(3, $user);

        return(array($param, $user));
    }

    public static function ChangeUserPassword($db, $param, $data)
    {
        list($p, $d) = self::passwd($db, $param, $data, false);
        return(array($p, $d));
    }

    public static function ChangePassword($db, $param, $data)
    {
        list($p, $d) = self::passwd($db, $param, $data, true);
        return(array($p, $d));
    }

    public static function CheckPermission($db, $param, $data)
    {
        return(array($param, $data));
    }

    public static function Login($db, $param, $data)
    {
        //Made sure we use only username field
        $username = $data->GetFieldValue("USER_NAME");
        if (trim($username) == '')
        {
            throw new Exception("No this user name in database [BLANK].");
        }

        $o = new CTable("");
        $o->SetFieldValue("USER_NAME", $username);

        $u = new MFrwUser($db);
        $obj = self::GetFirstRow($o, $u, 4, 'USER_NAME');

        if (!isset($obj))
        {
            throw new Exception("No this user name in database [$username].");
        }

        $req_password = $data->GetFieldValue("PASSWORD");
        $req_password_md5 = md5($req_password);
        $req_password_sha256 = password_hash($req_password, PASSWORD_DEFAULT);

        $enc_password = $obj->getFieldValue("PASSWORD");

        if (($enc_password != $req_password_md5) && !password_verify($req_password, $enc_password))
        {
            throw new Exception("Password is incorrect.");
        }

        if ($obj->GetFieldValue("IS_ENABLE") != 'Y')
        {
            throw new Exception("This user is disable.");
        }

        if ($enc_password == $req_password_md5)
        {
            //Need upgrade
            $obj->SetFieldValue("PASSWORD", $req_password_sha256);
            $u->Update(3, $obj);            
        }

        $md5 = self::RegisterSession($db, $param, $obj);

        /* Create login history, create only for success login */
        $history = new CTable("");
        $history->SetFieldValue("LOGIN_SUCCESS", "Y");
        $history->SetFieldValue("ERROR_DESC", "SUCCESS");
        $history->SetFieldValue("SESSION", $param->GetFieldValue('SESSION'));
        $history->SetFieldValue("FAILED_PASSWORD", '==xxxxxxxxx==');
        $history->SetFieldValue("USER_NAME", $o->GetFieldValue("USER_NAME"));
        if (array_key_exists('REMOTE_ADDR', $_SERVER))
        {
            $history->setFieldValue("IP_ADDRESS", $_SERVER['REMOTE_ADDR']);
        }

        $lh = new MFrwLoginHistory($db);
        $lh->Insert(0, $history, true);

        $param->SetFieldValue("LOGIN_ID", $history->GetFieldValue("LOGIN_ID"));

        return(array($param, $obj));
    }
}

?>