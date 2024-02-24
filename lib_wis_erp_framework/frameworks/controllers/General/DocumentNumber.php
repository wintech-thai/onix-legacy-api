<?php
/* 
    Purpose : Controller for Document number
    Created By : Supakit Tanyung
    Created Date : 09/19/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class DocumentNumber extends CBaseController
{
    public static function CreateDocumentNumber($db, $param, $data)
    {
        list($param, $pkg) = NumberGenerator::CreateDocumentNumber($db, $param, $data);
        return(array($param, $data));         
    }    

    public static function UpdateDocumentNumber($db, $param, $data)
    {      
        list($param, $pkg) = NumberGenerator::UpdateDocumentNumber($db, $param, $data);
        return(array($param, $data));       
    }      

    public static function DeleteDocumentNumber($db, $param, $data)
    {
        list($param, $pkg) = NumberGenerator::DeleteDocumentNumber($db, $param, $data);
        return(array($param, $data));
    }

    public static function GetDocumentNumberList($db, $param, $data)
    {
        //Moved code to core API instead
        list($param, $pkg) = NumberGenerator::GetDocumentNumberList($db, $param, $data);
        return(array($param, $pkg));
    }

    public static function GetDocumentNumberInfo($db, $param, $data)
    {
        $obj = self::GetDocumentNumberList($db, $param, $data);
        return($obj);        
    }

    public static function GenerateDocumentNumber($db, $param, $data)
    {
        list($param, $documentNumber) = NumberGenerator::GenerateDocumentNumber($db, $param, $data);
        return([$param, $documentNumber]);
    }

    public static function GenerateCustomDocumentNumber($db, $param, $data)
    {
        $t = new CTable("");
        $t->setFieldValue("DOC_TYPE", $data->getFieldValue('SEQ_DOC_TYPE'));
        CHelper::PopulateCustomVariables($data, $t, 1);

        list($param, $documentNumber) = NumberGenerator::GenerateDocumentNumber($db, $param, $t);
        return([$param, $documentNumber]);
    }

    public static function TestGenerateDocumentNumber($db, $param, $data)
    {
        list($param, $documentNumber) = NumberGenerator::TestGenerateDocumentNumber($db, $param, $data);
        return([$param, $documentNumber]);        
    }
}