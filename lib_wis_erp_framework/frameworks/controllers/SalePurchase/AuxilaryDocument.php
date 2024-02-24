<?php
/*
    Purpose : Controller for Auxilary Document
    Created By : Seubpong Monsar
    Created Date : 01/11/2018 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class AuxilaryDocument extends CBaseController
{
    const AUXILARY_DOC_PENDING = '1';
    const AUXILARY_DOC_APPROVED = '2';
    const AUXILARY_DOC_CANCEL = '3';

    const AUXILARY_DOC_PO = 1;
    const AUXILARY_DOC_QUOTATION = 2;

    private static $cfg = NULL;

    private static $orderByConfig = [
        'inventory_doc_no' => 'DOCUMENT_NO',
        'inventory_doc_date' => 'DOCUMENT_DATE',
        'inventory_doc_desc' => 'NOTE',
        'customer_name' => 'ENTITY_NAME',
        'supplier_name' => 'ENTITY_NAME',
        'inventory_doc_status' => 'DOCUMENT_STATUS',
    ];

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['AUXILARY_DOC_ITEM', new MAuxilaryDocItem($db), 1, 0, 2],
            ['PAYMENT_CRITERIA_ITEM', new MPaymentCriteria($db), 1, 0, 2],
            ['REMARK_ITEM', new MAuxilaryDocRemark($db), 1, 0, 2],
        );

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MAuxilaryDoc($db);
        return($u);
    }

    private static function populateIndexField($data)
    {
        //Items Index
        $arr = $data->GetChildArray('AUXILARY_DOC_ITEM');
        $temp = [];
        foreach ($arr as $row)
        {
            $flag = $row->GetFieldValue('EXT_FLAG');
            if ($flag == 'D')
            {
                continue;
            }

            $code = "";
            $name = "";

            $selectType = $row->GetFieldValue('SELECTION_TYPE');
            if ($selectType == '1')
            {
                $code = $row->GetFieldValue('SERVICE_CODE');
                $name = $row->GetFieldValue('SERVICE_NAME');
            }
            elseif ($selectType == '2')
            {
                $code = $row->GetFieldValue('ITEM_CODE');
                $name = $row->GetFieldValue('ITEM_NAME_ENG') . " : " . $row->GetFieldValue('ITEM_NAME_THAI');
            }
            else
            {
                $code = "";
                $name = $row->GetFieldValue('FREE_TEXT');
            }

            $indexDef = "$code|$name";
            array_push($temp, $indexDef);            
        }
        
        $index = join(';', $temp);
        $data->SetFieldValue('INDEX_ITEMS', $index);
    }    

    public static function UpdateItemsIndex($db, $data)
    {
        self::populateIndexField($data);
        $u = new MAuxilaryDoc($db);
        $u->Update(3, $data);
    }

    public static function GetAuxilaryDocCriteriaList($db, $param, $data)
    {        
        $u = new MPaymentCriteria($db);
        
        //$data->SetFieldValue('INCLUDE_ABLE_FLAG', 'Y'); //Inject by client instead
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(3, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'ACCOUNT_DOC_CRITERIA_LIST', $rows);

        return(array($param, $pkg));
    }
        
    public static function GetAuxilaryDocItemList($db, $param, $data)
    {        
        $u = new MAuxilaryDocItem($db);

        //$data->SetFieldValue('INCLUDE_ABLE_FLAG', 'Y'); //Inject by client instead
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(3, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'ACCOUNT_DOC_ITEM_LIST', $rows);

        return(array($param, $pkg));
    }

    public static function GetAuxilaryDocList($db, $param, $data)
    {
        $u = self::createObject($db);

        CHelper::AddWildCardSearch($data, 'INDEX_ITEMS');

        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);
        CHelper::SuppressField($rows, 'NOTE_TEXT', '');
        
        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'AUXILARY_DOC_LIST', $rows);

        return(array($param, $pkg));
    }

    public static function GetAuxilaryDocInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No auxilary document in database!!!");
        }

        $obj->SetFieldValue('ENTITY_ADDRESS_FLAG', $data->GetFieldValue('ENTITY_ADDRESS_FLAG'));

        self::PopulateChildItems($obj, $u, $cfg);
        self::populateEntityAddresses($db, $param, $obj);
        self::populateEntityBankAccount($db, $param, $obj);

        return(array($param, $obj));
    }

    private static function populateEntityAddresses($db, $param, $data)
    {
        $flag = $data->GetFieldValue('ENTITY_ADDRESS_FLAG');

        if ($flag != 'Y')
        {
            return;
        }

        $dat = new CTable("");
        $dat->SetFieldValue('ENTITY_ID', $data->GetFieldValue('ENTITY_ID'));

        $en = new MEntityAddress($db);
        list($cnt, $rows) = $en->Query(0, $dat);

        $arr = [];
        foreach ($rows as $row)
        {
            array_push($arr, $row);
        }

        $data->AddChildArray('ENTITY_ADDRESS_ITEMS', $arr);
    }

    private static function populateEntityBankAccount($db, $param, $data)
    {
        //Intend to use this flag ENTITY_ADDRESS_FLAG as same as populateEntityAddresses()
        $flag = $data->GetFieldValue('ENTITY_ADDRESS_FLAG');

        if ($flag != 'Y')
        {
            return;
        }

        $dat = new CTable("");
        $dat->SetFieldValue('ENTITY_ID', $data->GetFieldValue('ENTITY_ID'));

        $en = new MEntityBankAccount($db);
        list($cnt, $rows) = $en->Query(0, $dat);

        $arr = [];
        foreach ($rows as $row)
        {
            array_push($arr, $row);
        }

        $data->AddChildArray('ENTITY_BANK_ACCOUNT_ITEMS', $arr);
    }

    public static function IsAuxilaryDocExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "DOCUMENT_NO", "DOCUMENT_NO", 0);

        return(array($param, $o));
    }

    private static function getDocumentConfig($data)
    {
        $dt = $data->getFieldValue("DOCUMENT_TYPE");

        $cfgs = [
            self::AUXILARY_DOC_PO => ['AUX_DOC_PO_TEMP'],
            self::AUXILARY_DOC_QUOTATION => ['AUX_DOC_QUOTATION_TEMP'],
        ];

        list($temp) = $cfgs[$dt];

        return($temp);
    }

    private static function updateDefaultFields($data)
    {
        $docDate = $data->GetFieldValue('DOCUMENT_DATE');
        $dueDate = $data->GetFieldValue('DUE_DATE');
        $inUsedBySo = $data->GetFieldValue('IN_USED_BY_SO');

        if ($dueDate == '')
        {
            $data->SetFieldValue('DUE_DATE', $docDate);
        }

        if ($inUsedBySo == '')
        {
            $data->SetFieldValue('IN_USED_BY_SO', 'N');
        }

        self::populateIndexField($data);
    }

    public static function CreateAuxilaryDoc($db, $param, $data)
    {
        $auto_no = $param->GetFieldValue('AUTO_NUMBER');

        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::updateDefaultFields($data);

        //Start Create document number
        
        if ($auto_no != 'N')
        {
            $cfg = self::getDocumentConfig($data);

            $t = new CTable("");
            $t->setFieldValue("DOC_TYPE", $cfg);

            list($p, $d) = DocumentNumber::GenerateDocumentNumber($db, $param, $t);
            $docNo = $d->getFieldValue("LAST_DOCUMENT_NO");
            $data->setFieldValue("DOCUMENT_NO", $docNo);
            //End Create document number
        }

        $u = self::createObject($db);

        $data->SetFieldValue('DOCUMENT_STATUS', self::AUXILARY_DOC_PENDING);
        self::PopulateStartEndDate($data, 'DOCUMENT_DATE', true);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    public static function UpdateAuxilaryDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        self::updateDefaultFields($data);

        $u = self::createObject($db);

        $obj = self::GetRowByID($data, $u, 1);
        if (!isset($obj))
        {
            throw new Exception("No auxilary document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") == self::AUXILARY_DOC_APPROVED)
        {
            throw new Exception("This document has been approved and not allowed to update");
        }

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    public static function DeleteAuxilaryDoc($db, $param, $data)
    {
        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        $u = self::createObject($db);

        $obj = self::GetRowByID($data, $u, 1);
        if (!isset($obj))
        {
            throw new Exception("No auxilary document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") == self::AUXILARY_DOC_APPROVED)
        {
            throw new Exception("This document has been approved and not allowed to delete");
        }

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $data));
    }

    private static function resetItemReference($data)
    {
        $arr = $data->getChildArray('AUXILARY_DOC_ITEM');
        foreach ($arr as $row)
        {
            $row->setFieldValue('REF_BY_ID', '');
        }

        $arr = $data->getChildArray('PAYMENT_CRITERIA_ITEM');
        foreach ($arr as $row)
        {
            $row->setFieldValue('REF_BY_ID', '');
        }                
    }

    public static function CopyAuxilaryDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        list($p, $d) = self::GetAuxilaryDocInfo($db, $param, $data);
        self::PopulateNewCode($d, 'DOCUMENT_NO');
        self::InitCopyItems($d, self::$cfg);
                
        $d->SetFieldValue("DOCUMENT_STATUS", self::AUXILARY_DOC_PENDING);
        $d->SetFieldValue("APPROVED_DATE","");
        $d->SetFieldValue("APPROVED_SEQ","");
        $d->SetFieldValue("IN_USED_BY_SO","N");
        self::resetItemReference($d);

        list($p, $d) = self::CreateAuxilaryDoc($db, $param, $d);
        list($p, $d) = self::GetAuxilaryDocInfo($db, $param, $d);

        return(array($param, $d));
    }

    public static function SaveAuxilaryDoc($db, $param, $data)
    {
        $id = $data->GetFieldValue("AUXILARY_DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateAuxilaryDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateAuxilaryDoc($db, $param, $data);
        }
        
        return(array($p, $d));
    } 

    public static function ApproveAuxilaryDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);

        $tx = false;
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        list($p, $d) = self::SaveAuxilaryDoc($db, $param, $data);

        //Update value back such as begin and end balance, approve_date etc.
        $d->setFieldValue('DOCUMENT_STATUS', self::AUXILARY_DOC_APPROVED);
        list($p, $d) = self::UpdateAuxilaryDoc($db, $param, $d);

        if ($tx)
        {
            $db->commit();
        }

        return(array($param, $d));
    }

}

?>