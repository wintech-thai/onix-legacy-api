<?php
/*
    Purpose : Controller for Cancelation Document
    Created By : Seubpong Monsar
    Created Date : 06/08/2019 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CostDocument extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['COST_DOC_ITEMS', new MCostDocumentItem($db), 0, 0, 1],            
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MCostDocument($db);
        return($u);
    }

    public static function GetCostDocumentList($db, $param, $data)
    {
        $u = new MCostDocument($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'COST_DOC_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetCostDocumentInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MCostDocument($db);
        $obj = self::GetRowByID($data, $u, 0);
        
        if (!isset($obj))
        {
            throw new Exception("No cost document in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));  
    }

    public static function CreateCostDocument($db, $param, $data)
    {
        $u = new MCostDocument($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));
    }    

    public static function UpdateCostDocument($db, $param, $data)
    {
        $id = $data->getFieldValue('COST_DOC_ID');

        $ci = new MCostDocumentItem($db);
        $item = new CTable('');
        $item->setFieldValue('COST_DOC_ID', $id);
        $ci->Delete(1, $item);

        $u = new MCostDocument($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }


    public static function SaveCostDocument($db, $param, $data)
    {
        $id = $data->GetFieldValue("COST_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateCostDocument($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateCostDocument($db, $param, $data);
        }
        
        return(array($p, $d));
    }
    

    public static function DeleteCostDocument($db, $param, $data)
    {
        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        $u = self::createObject($db);

        $obj = self::GetRowByID($data, $u, 0);
        if (!isset($obj))
        {
            throw new Exception("No cost document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") != AccountDocument::ACCOUNT_DOC_PENDING)
        {
            throw new Exception("This document has been approved/canceled and not allowed to delete");
        }

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    public static function ApproveCostDocument($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        $u = self::createObject($db);
        
        list($p, $obj) = AccountDocument::GetAccountDocInfo($db, $param, $data);
        $obj->SetFieldValue('DOCUMENT_DATE', $data->GetFieldValue('DOCUMENT_DATE'));
        $obj->SetFieldValue('ALLOW_INVENTORY_NEGATIVE', $data->GetFieldValue('ALLOW_INVENTORY_NEGATIVE'));
        $obj->SetFieldValue('ALLOW_CASH_NEGATIVE', $data->GetFieldValue('ALLOW_CASH_NEGATIVE'));
        $obj->SetFieldValue('ALLOW_AR_AP_NEGATIVE', $data->GetFieldValue('ALLOW_AR_AP_NEGATIVE'));

        //Check status if it is void able
        if ($obj->GetFieldValue("DOCUMENT_STATUS") != AccountDocument::ACCOUNT_DOC_APPROVED)
        {
            throw new Exception("This document has not been approved so not allowed to cancel!!!");
        }

        if ($obj->GetFieldValue("RECEIPT_ID") != '')
        {
            throw new Exception("This document has been assigned to a receipt!!!");
        }

        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::SaveCostDocument($db, $param, $data);        

        $doc = self::createAccountDocObject($db, $obj);
        list($errCnt, $cashDoc, $invDoc) = $doc->ApproveDocument();
        if ($errCnt > 0)
        {
            if ($tx)
            {
                $db->rollBack();
            }

            return([$param, $cashDoc]);
        }

        self::updateOriginalDocStatus($db, $param, $obj);        

        if ($tx)
        {
            $db->commit();
        }

        list($p, $d) = AccountDocument::GetAccountDocInfo($db, $param, $obj);

        return(array($param, $d));
    }

}

?>