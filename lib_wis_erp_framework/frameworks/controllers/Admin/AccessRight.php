<?php
/*
    Purpose : Controller for Access Right
    Created By : Seubpong Monsar
    Created Date : 01/11/2018 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";
require_once "phar://onix_erp_framework.phar/controllers/Admin/access_rights.php";

class AccessRight extends CBaseController
{
    public static function GetAccessRightList($db, $param, $data)
    {
        list($p, $d) = AdminAccessRight::GetAccessRightList($db, $param, $data);
        return(array($p, $d));
    }

    public static function GetAccessRightInfo($db, $param, $data)
    {
        list($p, $d) = AdminAccessRight::GetAccessRightInfo($db, $param, $data);
        return(array($p, $d));        
    }

    public static function CreateAccessRight($db, $param, $data)
    {
        list($p, $d) = AdminAccessRight::CreateAccessRight($db, $param, $data);
        return(array($p, $d));        
    }    

    public static function UpdateAccessRight($db, $param, $data)
    {
        list($p, $d) = AdminAccessRight::UpdateAccessRight($db, $param, $data);
        return(array($p, $d));        
    }      

    public static function DeleteAccessRight($db, $param, $data)
    {
        list($p, $d) = AdminAccessRight::DeleteAccessRight($db, $param, $data);
        return(array($p, $d));        
    } 

    public static function GetUserContextAccessRight($db, $param, $data)
    {
        list($p, $d) = AdminAccessRight::GetUserContextAccessRight($db, $param, $data);
        return(array($p, $d));  
    }

    public static function UpdateGroupAccessRight($db, $param, $data)
    {
        list($p, $d) = AdminAccessRight::UpdateGroupAccessRight($db, $param, $data);
        return(array($p, $d));          
    } 
    
    public static function GetGroupAccessRightList($db, $param, $data)
    {
        list($p, $d) = AdminAccessRight::GetGroupAccessRightList($db, $param, $data);
        return(array($p, $d));        
    } 

    public static function PopulateAccessRight($db, $param, $data)
    {
        global $cfg_access_right;

        $products = ['onix' => 0, 'lotto' => 1, 'sass' => 2];
        $prd = $_ENV['PRODUCT'];

        $idx = 0;
        if (array_key_exists($prd, $products))
        {
            $idx = $products[$prd];
        }
        
        $data->SetFieldValue('CHUNK_FLAG', 'N');
        list($p, $d) = AdminAccessRight::GetAccessRightList($db, $param, $data);
        $currAccessRights = $d->getChildArray('ACCESS_RIGHT_LIST');

        $hashAccessRights = CUtils::RowToHash($currAccessRights, 'ACCESS_RIGHT_CODE');

        foreach ($cfg_access_right as $tuple)
        {
            list($code, $desc, $defaultValue, $flags) = $tuple;
            $cmpKey = sprintf('%s|%s', $flags, $desc);
            $prodFlag = substr($flags, $idx, 1);

            if ($prodFlag != 'Y')
            {
                continue;
            }

            if (array_key_exists($code, $hashAccessRights))
            {               
                $acr = $hashAccessRights[$code];
                $existingFlag = $acr->getFieldValue('DEFAULT_VALUE');
                $existingDesc = $acr->getFieldValue('RIGHT_DESCRIPTION');

                $existingKey = sprintf('%s|%s', $existingFlag, $existingDesc);

                if ($cmpKey != $existingKey)
                {
                    $acr->setFieldValue('RIGHT_DESCRIPTION', $desc);
                    $acr->setFieldValue('DEFAULT_VALUE', $defaultValue);

                    AdminAccessRight::UpdateAccessRight($db, $param, $acr);
                }
            }
            else
            {                          
                $acr = new CTable('');

                $acr->setFieldValue('ACCESS_RIGHT_CODE', $code);
                $acr->setFieldValue('DEFAULT_VALUE', $defaultValue);
                $acr->setFieldValue('RIGHT_DESCRIPTION', $desc);
                $acr->setFieldValue('ACCESS_CATEGORY', 'GENERIC');
                $acr->setFieldValue('ACCESS_NS', 'GLOBAL');

                AdminAccessRight::CreateAccessRight($db, $param, $acr);
            }
        }
    }
}

?>