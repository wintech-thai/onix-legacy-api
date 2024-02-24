<?php
/*
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class AdminAuthenticationUser extends CBaseController
{
    private static function populateServerVariables($data, $arrName)
    {
        $sv = new CTable("");
        #$sv->SetFieldValue('VARIABLE_NAME', 'API_VERSION');
        #$sv->SetFieldValue('VARIABLE_VALUE', $_ENV['API_VERSION']);
        $vars = array($sv);

        $data->AddChildArray($arrName, $vars);
    }

    public static function GetUserList($db, $param, $data)
    {
        $u = new MFrwUser($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $user = new CTable("USER");
        self::PopulateRow($user, $item_cnt, $chunk_cnt, 'USER_LIST', $rows);

        return(array($param, $user));
    }

    public static function GetUserInfo($db, $param, $data)
    {
        $u = new MFrwUser($db);
        $user = self::GetRowByID($data, $u, 1);

        if (!isset($user))
        {
            throw new Exception("No this user in database!!!");
        }
        
        return(array($param, $user));
    }

    public static function IsUserExist($db, $param, $data)
    {
        $u = new MFrwUser($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "USER_NAME", "US.USER_NAME", 1);

        return(array($param, $o));
    }

    public static function CreateInitAdminUser($db, $param, $data)
    {
        //This command should be able to run only one
        $u = new MFrwUser($db);

        //Get only row only IS_INTIAL = 'Y'
        $temp = new CTable('');
        $temp->SetFieldValue('IS_INTIAL', 'Y');
        list($initCnt, $rows) = $u->Query(4, $temp);
        if ($initCnt > 0)
        {
            throw new Exception("There is an initial user created!!!");
        }

        $dummy = $data->GetFieldValue('PASSWORD');
        if (!preg_match(AdminAuthen::PASSWORD_PATTERN, $dummy))
        {
            throw new Exception("Password is not secure enough, please create the new password!!!");
        }

        $req_password = password_hash($dummy, PASSWORD_DEFAULT);
        $data->setFieldValue("PASSWORD", $req_password);
        $data->setFieldValue("IS_INTIAL", 'Y');

        $childs = array();
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    }

    public static function CreateUser($db, $param, $data)
    {
        $u = new MFrwUser($db);

        //No one can use this password, admin need to change the password
        $dummy = "xxxxxx";
        $req_password = $dummy;
        $data->setFieldValue("PASSWORD", $req_password);
        $data->setFieldValue("IS_INTIAL", 'N');

        $childs = array();
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    }

    public static function UpdateUser($db, $param, $data)
    {
        $u = new MFrwUser($db);

        $childs = array();
        self::UpdateData($db, $data, $u, 2, $childs);

        return(array($param, $data));
    }

    public static function UpdateUserVariables($db, $param, $data)
    {
        //TODO : Need to verify only owner can call this function
//CSql::SetDumpSQL(true);
        $u = new MVirtualModel('USER_ID');

        $childs = [
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['USER_VARIABLE_ITEM', new MUserVariable($db), 1, 0, 2],
        ];

        self::UpdateData($db, $data, $u, 2, $childs);

        return(array($param, $data));
    }

    public static function DeleteUser($db, $param, $data)
    {
        $u = new MFrwUser($db);
        
        $childs = [
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['USER_VARIABLE_ITEM', new MUserVariable($db), 1, 0, 2],
        ];
        self::DeleteData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    }

    public static function CopyUser($db, $param, $data)
    {
        list($p, $d) = self::GetUserInfo($db, $param, $data);
        self::PopulateNewCode($d, 'USER_NAME');
        list($p, $d) = self::CreateUser($db, $param, $d);
        list($p, $d) = self::GetUserInfo($db, $param, $d);

        return(array($param, $d));
    }

    public static function GetLoginHistoryList($db, $param, $data)
    {
        $u = new MFrwLoginHistory($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $user = new CTable($u->GetTableName());
        self::PopulateRow($user, $item_cnt, $chunk_cnt, 'LOGIN_HISTORY_LIST', $rows);

        return(array($param, $user));
    }
}

?>