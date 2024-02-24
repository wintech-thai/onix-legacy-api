<?php
/* 
    Purpose : Controller for User account
    Created By : Seubpong Monsar
    Created Date : 09/06/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CompanyProfile extends CBaseController
{
    private static $cfg = NULL;
    private static $companyImages = [
        //ImageType, FileName
        [1, '/company/logo.jpg'],
        [2, '/company/signature.jpg']
    ];

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['COMPANY_IMAGES', new MCompanyImage($db), 1, 0, 0],
        );

        self::$cfg = $config;

        return($config);
    }

    public static function GetCompanyProfileList($db, $param, $data)
    {
        $u = new MCompanyProfile($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'COMPANY_LIST', $rows);
        
        return(array($param, $pkg));
    }

    private static function populateCompanyImages($data)
    {
        $companyID = $data->getFieldValue('COMPANY_ID');

        $images = $data->getChildArray('COMPANY_IMAGES');
        $imageHash = CHelper::RowToHash($images, ['IMAGE_TYPE'], '');

        foreach (self::$companyImages as $tuple)
        {
            list($imgType, $imgName) = $tuple;

            $imageFlag = "";

            if (!array_key_exists($imgType, $imageHash))
            {
                $obj = new CTable('');
                $obj->setFieldValue('EXT_FLAG', 'A');
                $obj->setFieldValue('IMAGE_TYPE', $imgType);
                $obj->setFieldValue('IMAGE_NAME', '');
                $obj->setFieldValue('IMAGE_NAME_WIP', '');
                $obj->setFieldValue('COMPANY_ID', $companyID);
                array_push($images, $obj);
            }
            else
            {
                $obj = $imageHash[$imgType];

                if (StorageAPI::StorageFileExist($imgName))
                {
                    $imageFlag = "I";
                }
            }

            $obj->setFieldValue('IMAGE_EXT_FLAG', $imageFlag);
        }

        $data->addChildArray('COMPANY_IMAGES', $images);        
    }

    public static function GetCompanyProfileInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MCompanyProfile($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No company profile in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);
        self::populateCompanyImages($obj);

        return(array($param, $obj));        
    }

    private static function preprocessStoragesV($data, $arrName)
    {
        $images = $data->getChildArray($arrName);
        $imageHash = CHelper::RowToHash($images, ['IMAGE_TYPE'], '');

        foreach (self::$companyImages as $tuple)
        {
            list($imgType, $imgName) = $tuple;

            if (array_key_exists($imgType, $imageHash))
            {
                $image = $imageHash[$imgType];
                $image->setFieldValue('IMAGE_NAME', $imgName);
                $image->setFieldValue('IMAGE_NAME_DEST', $imgName);
            }
        }     
    }

    public static function CreateCompanyProfile($db, $param, $data)
    {
        $u = new MCompanyProfile($db);
        
        self::preprocessStoragesV($data, 'COMPANY_IMAGES'); //Put this before CreateData

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);
        
        self::manipulateImages($db, $data, 'COMPANY_IMAGES', 'IMAGE_NAME_WIP', 'IMAGE_NAME_DEST', 'IMAGE_NAME');

        return(array($param, $data));
    }    

    public static function UpdateCompanyProfile($db, $param, $data)
    {
        $u = new MCompanyProfile($db);

        self::preprocessStoragesV($data, 'COMPANY_IMAGES'); //Put this before UpdateData

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);        
        
        self::manipulateImages($db, $data, 'COMPANY_IMAGES', 'IMAGE_NAME_WIP', 'IMAGE_NAME_DEST', 'IMAGE_NAME');

        return(array($param, $data));
    }      

    public static function DeleteCompanyProfile($db, $param, $data)
    {
        $u = new MCompanyProfile($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     
}

?>