<?php
/* 
    Purpose : Controller for User Group
    Created By : Seubpong Monsar
    Created Date : 09/12/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class UserGroup extends CBaseController
{
    public static function GetUserGroupList($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationGroup::GetUserGroupList($db, $param, $data);
        return(array($p, $d));
    }

    public static function GetUserGroupInfo($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationGroup::GetUserGroupInfo($db, $param, $data);
        return(array($p, $d));    
    }

    public static function IsUserGroupExist($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationGroup::IsUserGroupExist($db, $param, $data);
        return(array($p, $d));        
    }

    public static function CreateUserGroup($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationGroup::CreateUserGroup($db, $param, $data);
        return(array($p, $d));     
    }    

    public static function UpdateUserGroup($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationGroup::UpdateUserGroup($db, $param, $data);
        return(array($p, $d));    
    }      

    public static function DeleteUserGroup($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationGroup::DeleteUserGroup($db, $param, $data);
        return(array($p, $d));         
    }     

    public static function CopyUserGroup($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationGroup::CopyUserGroup($db, $param, $data);
        return(array($p, $d));        
    }    
    
    public static function GetPermissionList($db, $param, $data)
    {
        list($p, $d) = AdminAuthenticationGroup::GetPermissionList($db, $param, $data);
        return(array($p, $d));          
    }
}

?>