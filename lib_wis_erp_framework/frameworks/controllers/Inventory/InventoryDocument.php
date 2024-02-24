<?php
/* 
    Purpose : Controller for Inventory Document
    Created By : Seubpong Monsar
    Created Date : 09/26/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class InventoryDocument extends CBaseController
{
    const INV_DOC_PENDING = '1';
    const INV_DOC_APPROVED = '2';

    const INV_DOCTYPE_IMPORT = '1';
    const INV_DOCTYPE_EXPORT = '2';
    const INV_DOCTYPE_XFER = '3';
    const INV_DOCTYPE_ADJUST = '4';
    const INV_DOCTYPE_BORROW = '5';
    const INV_DOCTYPE_RETURN = '6';    

    private static $cfg = NULL;

    private static $orderByConfig = [
        'inventory_doc_no' => 'DOCUMENT_NO',
        'inventory_doc_date' => 'DOCUMENT_DATE',
        'inventory_doc_desc' => 'NOTE',
        'location_name' => 'FROM_LOCATION',
        'inventory_doc_status' => 'DOCUMENT_STATUS',
    ];

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update ind, delete ind
            ['TX_ITEM', new MInventoryTx($db), 2, 0, 1],
            ['ADJUSTMENT_ITEM', new MInventoryAdjustment($db), 1, 0, 2],         
        );        

        self::$cfg = $config;

        return($config);
    }

    private static function createObject($db)
    {
        $u = new MInventoryDoc($db);
        return($u);
    }

    public static function GetInventoryDocList($db, $param, $data)
    {
        $chunkFlag = $data->GetFieldValue('CHUNK_FLAG');
        $u = self::createObject($db);
        $pkg = new CTable($u->GetTableName());
       
        CHelper::OverrideOrderBy($u, 1, $data, self::$orderByConfig);

        if ($chunkFlag != 'N')
        {
            list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);        
            self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'INVENTORY_DOC_LIST', $rows);
        }
        else
        {
            list($cnt, $rows) = $u->Query(1, $data);
            self::PopulateRow($pkg, $cnt, 1, 'INVENTORY_DOC_LIST', $rows);   
        }

        return(array($param, $pkg));
    }

    public static function GetInventoryDocInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = self::createObject($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No inventory document in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsInventoryDocExist($db, $param, $data)
    {
        $u = self::createObject($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "DOCUMENT_NO", "DOCUMENT_NO", 0);
        
        return(array($param, $o));        
    }

    private static function getDocumentConfig($dt, $ds)
    {
        $cfgs = [
            self::INV_DOCTYPE_IMPORT => ['INVENTORY_DOC_IMPORT_TEMP', 'INVENTORY_DOC_IMPORT_APPROVED'],
            self::INV_DOCTYPE_EXPORT => ['INVENTORY_DOC_EXPORT_TEMP', 'INVENTORY_DOC_EXPORT_APPROVED'],
            self::INV_DOCTYPE_XFER => ['INVENTORY_DOC_EXFER_TEMP', 'INVENTORY_DOC_EXFER_APPROVED'],
            self::INV_DOCTYPE_ADJUST => ['INVENTORY_DOC_ADJ_TEMP', 'INVENTORY_DOC_ADJ_APPROVED'],
            self::INV_DOCTYPE_BORROW => ['INVENTORY_DOC_BORROW_TEMP', 'INVENTORY_DOC_BORROW_APPROVED'],
            self::INV_DOCTYPE_RETURN => ['INVENTORY_DOC_RETURN_TEMP', 'INVENTORY_DOC_RETURN_APPROVED'],
        ];
        
        list($temp, $approved) = $cfgs[$dt];
    
        if ($ds == self::INV_DOC_PENDING)
        {
            return($temp);
        }
    
        return($approved);
    }

    public static function CreateInventoryDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);           
        $auto_no = $param->GetFieldValue('AUTO_NUMBER');

        if ($auto_no != 'N') 
        {
            $cfg = self::getDocumentConfig($data->getFieldValue("DOCUMENT_TYPE"), self::INV_DOC_PENDING);

            $t = new CTable("");
            $t->setFieldValue("DOC_TYPE", $cfg);
            CHelper::PopulateCustomVariables($data, $t, 1);

            list($p, $d) = DocumentNumber::GenerateDocumentNumber($db, $param, $t);
            $docNo = $d->getFieldValue("LAST_DOCUMENT_NO");
            $data->setFieldValue("DOCUMENT_NO", $docNo);
        }

        $u = self::createObject($db);
        
        $data->SetFieldValue('DOCUMENT_STATUS', self::INV_DOC_PENDING);    
        self::PopulateStartEndDate($data, 'DOCUMENT_DATE', true);

        $childs = self::initSqlConfig($db);
        self::normalizeDocument($data);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateInventoryDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);               
        $u = self::createObject($db);
        
        $obj = self::GetRowByID($data, $u, 1);        
        if (!isset($obj))
        {
            throw new Exception("No inventory document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") == self::INV_DOC_APPROVED) 
        {
            throw new Exception("This document has been approved and not allowed to update");
        }        

        $childs = self::initSqlConfig($db);
        self::normalizeDocument($data);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteInventoryDoc($db, $param, $data)
    {
        $u = self::createObject($db);
        
        $obj = self::GetRowByID($data, $u, 1);        
        if (!isset($obj))
        {
            throw new Exception("No inventory document in database!!!");
        }

        if ($obj->GetFieldValue("DOCUMENT_STATUS") == self::INV_DOC_APPROVED) 
        {
            throw new Exception("This document has been approved and not allowed to delete");
        }    

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));
    }

    public static function CopyInventoryDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        list($p, $d) = self::GetInventoryDocInfo($db, $param, $data);
        self::PopulateNewCode($d, 'DOCUMENT_NO');
        self::InitCopyItems($d, self::$cfg);

        $d->SetFieldValue("DOCUMENT_STATUS", self::INV_DOC_PENDING);
        $d->SetFieldValue("APPROVED_DATE","");
        $d->SetFieldValue("APPROVED_SEQ","");

        list($p, $d) = self::CreateInventoryDoc($db, $param, $d);
        list($p, $d) = self::GetInventoryDocInfo($db, $param, $d);
        
        return(array($param, $d));        
    }      

    private static function deriveDocTypeAndOwner($data, $bal)
    {
        $dt = '';
        $docType = $data->GetFieldValue('DOCUMENT_TYPE');
        $code = '';

        if (($docType == self::INV_DOCTYPE_IMPORT) || ($docType == self::INV_DOCTYPE_RETURN))
        {
            $dt = BalanceAPI::BAL_DOC_IMPORT;
            $code = $data->GetFieldValue('TO_LOCATION_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
        }
        elseif (($docType == self::INV_DOCTYPE_EXPORT) || ($docType == self::INV_DOCTYPE_BORROW))
        {
            $dt = BalanceAPI::BAL_DOC_EXPORT;
            $code = $data->GetFieldValue('FROM_LOCATION_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $code);
        }
        elseif ($docType == self::INV_DOCTYPE_XFER)
        {
            $dt = BalanceAPI::BAL_DOC_MOVE;

            $from = $data->GetFieldValue('FROM_LOCATION_CODE');
            $to = $data->GetFieldValue('TO_LOCATION_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE_FROM', $from);
            $bal->SetFieldValue('BAL_OWNER_CODE_TO', $to);
        }
        elseif ($docType == self::INV_DOCTYPE_ADJUST)
        {
            $dt = BalanceAPI::BAL_DOC_ADJUST;
            $to = $data->GetFieldValue('TO_LOCATION_CODE');

            $bal->SetFieldValue('BAL_OWNER_CODE', $to);
        }

        $bal->SetFieldValue('BAL_DOC_TYPE', $dt);        
    }
    
    private static function normalizeDocument($data)
    {
        $internalFlag = $data->GetFieldValue('INTERNAL_DOC_FLAG');
        if ($internalFlag == '')
        {
            //If generated from ACCOUNT_DOCUMENT then this field will be blank
            //We will asign it to 'Y' to indicate it comes from ACCOUNT_DOCUMENT
            $data->SetFieldValue('INTERNAL_DOC_FLAG', 'Y');
        }

        $docType = $data->GetFieldValue('DOCUMENT_TYPE');   

        if (($docType == self::INV_DOCTYPE_IMPORT) || ($docType == self::INV_DOCTYPE_RETURN))
        {
            $locationID = $data->GetFieldValue("LOCATION_ID2");
            $tx = "I";
            $factor = "1";
        }
        elseif (($docType == self::INV_DOCTYPE_EXPORT) || ($docType == self::INV_DOCTYPE_BORROW))
        {
             $locationID = $data->GetFieldValue("LOCATION_ID1");
             $tx = "E";
             $factor = "-1";
        }
        elseif ($docType == self::INV_DOCTYPE_XFER)
        {
            $locationID = $data->GetFieldValue("LOCATION_ID1");
            $tx = "X";
            $factor = "0";
        }
        elseif ($docType == self::INV_DOCTYPE_ADJUST)
        {
            $locationID = $data->GetFieldValue("LOCATION_ID2");    
            $factor = "1";
        }

        $arr = $data->GetChildArray('TX_ITEM');
        foreach ($arr as $itm)
        {
            $flag = $itm->GetFieldValue("EXT_FLAG");

            if ($flag == 'D')
            {
                continue;
            }
                        
            $locID = $itm->GetFieldValue("LOCATION_ID");
    
            if ($docType != self::INV_DOCTYPE_ADJUST)
            {
                #Adjustment has it own TX_TYPE and factor
                $itm->SetFieldValue("TX_TYPE", $tx);
                $itm->SetFieldValue("FACTOR", $factor);
            }

            if (($docType == self::INV_DOCTYPE_IMPORT) || ($docType == self::INV_DOCTYPE_RETURN))
            {
                $itm->SetFieldValue("ITEM_AMOUNT", $itm->GetFieldValue('UI_ITEM_AMOUNT'));
                $itm->SetFieldValue("ITEM_PRICE", $itm->GetFieldValue('UI_ITEM_UNIT_PRICE'));                
            }

            if ($docType == self::INV_DOCTYPE_BORROW)
            {
                $itm->SetFieldValue("RETURNED_QUANTITY", "0.00");
                $itm->SetFieldValue("RETURNED_QUANTITY_NEED", $itm->GetFieldValue('ITEM_QUANTITY'));                
            }

            $itm->SetFieldValue("LOCATION_ID", $locationID);
            
            if ($flag != 'A')
            {
                $itm->SetFieldValue("EXT_FLAG", "E");
            }
        }
    }

    private static function getBalanceHash($db, $data)
    {
        $hash = [];

        list($p, $d) = InventoryReport::GetInventoryBalanceList($db, new CTable(''), $data);
        $arr = $d->GetChildArray('INVENTORY_BALANCE_LIST');

        foreach ($arr as $bal)
        {
            $key = 'KEY-' . $bal->GetFieldValue('ITEM_ID');
            $hash[$key] = $bal;
        }

        return($hash);
    }

    private static function createAdjustItem($data, $bal, $adj)
    {
        $adjustBy = $data->GetFieldValue('ADJUSTMENT_BY');

        $qty = $adj->GetFieldValue('QUANTITY'); 
        $amt = $adj->GetFieldValue('AMOUNT');
        if ($adjustBy == '2')
        {
            //By unit price; AMOUNT hold the unit price
            $price = $amt;
            $amt = $price * $qty; 
        }

        $arr = [];

        $reqQty = $qty;
        $reqAmt = $amt;            
        $curQty = $bal->GetFieldValue('END_QUANTITY');
        $curAmt = $bal->GetFieldValue('END_AMOUNT_AVG');

        $difQty = $curQty - $reqQty;
        $difAmt = $curAmt - $reqAmt;

        $tx1 = new CTable('');
        $tx1->SetFieldValue('ITEM_ID', $adj->GetFieldValue('ITEM_ID'));
        $tx1->SetFieldValue('ITEM_CODE', $adj->GetFieldValue('ITEM_CODE'));
        $tx1->SetFieldValue('TX_ID', $adj->GetFieldValue('INVENTORY_ADJ_ID'));
        $tx1->SetFieldValue('EXT_FLAG', 'A');
        $tx1->SetFieldValue('FACTOR', '1');

        $tx2 = new CTable('');
        $tx2->SetFieldValue('ITEM_ID', $adj->GetFieldValue('ITEM_ID'));
        $tx2->SetFieldValue('ITEM_CODE', $adj->GetFieldValue('ITEM_CODE'));
        $tx2->SetFieldValue('TX_ID', $adj->GetFieldValue('INVENTORY_ADJ_ID'));
        $tx2->SetFieldValue('EXT_FLAG', 'A');
        $tx2->SetFieldValue('FACTOR', '1');

        if (($difQty >= 0.00) && ($difAmt >= 0.00))
        {
            $tx1->SetFieldValue('DIRECTION', 'E');
            $tx1->SetFieldValue('TX_TYPE', 'E');
            $tx1->SetFieldValue('ITEM_QUANTITY', abs($difQty));
            $tx1->SetFieldValue('ITEM_AMOUNT', abs($difAmt));

            array_push($arr, $tx1);
        }
        elseif (($difQty < 0.00) && ($difAmt < 0.00))
        {
            $tx1->SetFieldValue('DIRECTION', 'I');
            $tx1->SetFieldValue('TX_TYPE', 'I');
            $tx1->SetFieldValue('ITEM_QUANTITY', abs($difQty));
            $tx1->SetFieldValue('ITEM_AMOUNT', abs($difAmt));

            array_push($arr, $tx1);
        }
        elseif (($difQty >= 0.00) && ($difAmt < 0.00))
        {
            $tx1->SetFieldValue('DIRECTION', 'E');
            $tx1->SetFieldValue('TX_TYPE', 'E');
            $tx1->SetFieldValue('ITEM_QUANTITY', abs($difQty));
            $tx1->SetFieldValue('ITEM_AMOUNT', '0.00');

            array_push($arr, $tx1);

            $tx2->SetFieldValue('DIRECTION', 'I');
            $tx2->SetFieldValue('TX_TYPE', 'I');
            $tx2->SetFieldValue('ITEM_QUANTITY', '0.00');
            $tx2->SetFieldValue('ITEM_AMOUNT', abs($difAmt));

            array_push($arr, $tx2);            
        }
        elseif (($difQty < 0.00) && ($difAmt >= 0.00))
        {
            $tx1->SetFieldValue('DIRECTION', 'I');
            $tx1->SetFieldValue('TX_TYPE', 'I');
            $tx1->SetFieldValue('ITEM_QUANTITY', abs($difQty));
            $tx1->SetFieldValue('ITEM_AMOUNT', '0.00');

            array_push($arr, $tx1);

            $tx2->SetFieldValue('DIRECTION', 'E');
            $tx2->SetFieldValue('TX_TYPE', 'E');
            $tx2->SetFieldValue('ITEM_QUANTITY', '0.00');
            $tx2->SetFieldValue('ITEM_AMOUNT', abs($difAmt));

            array_push($arr, $tx2);
        }

        return($arr);
    }

    private static function deriveAdjustItem($db, $data)
    {
        $docType = $data->GetFieldValue('DOCUMENT_TYPE');   
        $adjustByDelta = $data->GetFieldValue('ADJUST_BY_DELTA_FLAG');   

        if ($docType != self::INV_DOCTYPE_ADJUST)
        {
            return;
        }

        if ($adjustByDelta == 'Y')
        {
            return;
        }

        $data->SetFieldValue('LOCATION_ID', $data->GetFieldValue('LOCATION_ID2'));
        $balHash = self::getBalanceHash($db, $data);
        $trans = [];

        $arr = $data->GetChildArray('ADJUSTMENT_ITEM');
        foreach ($arr as $adj)
        {
            $extFlag = $adj->GetFieldValue('EXT_FLAG');
            if ($extFlag == 'D')
            {
                continue;
            }

            $key = 'KEY-' . $adj->GetFieldValue('ITEM_ID');

            if (!array_key_exists($key, $balHash))
            {
                $bal = new CTable('');
                
                $bal->SetFieldValue('END_QUANTITY', "0.00");
                $bal->SetFieldValue('END_AMOUNT_AVG', "0.00");
            }
            else
            {
                $bal = $balHash[$key];
            }

            $txs = self::createAdjustItem($data, $bal, $adj);
            foreach ($txs as $tx)
            {
                array_push($trans, $tx);
            }  
        }

        $data->AddChildArray('TX_ITEM', $trans);
    }

    private static function deriveBalanceDoc($db, $data)
    {
        $dt = '';        

        $bal = new CTable("");
        self::deriveDocTypeAndOwner($data, $bal);
        
        $bal->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_INVENTORY);
        $bal->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_LOCATION);

        $bal->SetFieldValue('BAL_DOC_NO', $data->GetFieldValue('DOCUMENT_NO'));

        CHelper::PopulateBalanceDate($db, $bal, $data, 'DOCUMENT_DATE');
        $bal->SetFieldValue('BAL_DOC_NOTE', $data->GetFieldValue('NOTE'));
        $bal->SetFieldValue('ACTUAL_ID', $data->GetFieldValue('DOC_ID'));

        $items = [];

        $arr = $data->GetChildArray('TX_ITEM');
        foreach ($arr as $itm)
        {
            $flag = $itm->GetFieldValue('EXT_FLAG');
            if ($flag == 'D')
            {
                continue;
            }

            $itemCode = $itm->GetFieldValue('ITEM_CODE');
            $txID = $itm->GetFieldValue('TX_ID');
            $qty = $itm->GetFieldValue('ITEM_QUANTITY');
            $amt = $itm->GetFieldValue('ITEM_AMOUNT');
 
            $tx = new CTable('');
            $tx->SetFieldValue('BAL_ITEM_CODE', $itemCode);
            $tx->SetFieldValue('ACTUAL_ID', $txID);

            $tx->SetFieldValue('TX_QTY_AVG', $qty);
            $tx->SetFieldValue('TX_AMT_AVG', $amt);
            $tx->SetFieldValue('TX_QTY_FIFO', $qty);
            $tx->SetFieldValue('TX_AMT_FIFO', '0.00');

            //Use by Adjust
            $tx->SetFieldValue('DIRECTION', $itm->GetFieldValue('TX_TYPE'));

            array_push($items, $tx);
        }

        $bal->AddChildArray('BAL_DOC_ITEMS', $items);

        return($bal);
    }

    public static function VerifyInventoryDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        self::deriveAdjustItem($db, $data);

        $allowNegative = $data->GetFieldValue('ALLOW_NEGATIVE');

        self::normalizeDocument($data);
        
        $bal = self::deriveBalanceDoc($db, $data);
        $type = $bal->GetFieldValue('BAL_DOC_TYPE');
        
        $result = BalanceAPI::Verify($db, $type, $bal, $allowNegative=='Y');
        if (!$result)
        {
            $param->SetFieldValue('ERROR_CODE', '2');
            $param->SetFieldValue('ERROR_DESC', 'ERRORS');
            return(array($param, $bal));
        }

        return(array($param, $data));
    }   

    private static function updateReturnedValue($bal, $data)
    {
        //This is array returned from Balance API
        $arr = $bal->GetChildArray('TEMP_BAL_DOC_ITEMS'); 
        $items = $data->GetChildArray('TX_ITEM');
        
        foreach ($arr as $tx)
        {
//CLog::WriteLn("This is in updateReturnedValue!!!");
            $actualID = $tx->GetFieldValue('ACTUAL_ID');

            foreach ($items as $origin)
            {
                $id = $origin->GetFieldValue('TX_ID');
                if ($actualID == $id)
                {
                    $flag = $origin->GetFieldValue('EXT_FLAG');
                    if (($flag != 'A') && ($flag != 'D'))
                    {
                        $origin->SetFieldValue('EXT_FLAG', 'E');
                    }       
                    
                    $amt = $tx->GetFieldValue('TX_AMT_AVG');
                    $qty = $tx->GetFieldValue('TX_QTY_AVG');
        
                    $price = 0.00;
                    //ACtuall, $qty is always greater than 0
                    if ($qty > 0)
                    {
                        $price = $amt/$qty;
                    }
        
                    $origin->SetFieldValue('ITEM_AMOUNT', $amt);
                    $origin->SetFieldValue('ITEM_PRICE', $price);                    
                }
            }
        }
    }

    private static function deductBorrowQuantity($db, $data)
    {
        $dt = $data->getFieldValue('DOCUMENT_TYPE');
        if ($dt != self::INV_DOCTYPE_RETURN)
        {
            return;
        }

        $m = new MInventoryTx($db);

        $arr = $data->GetChildArray('TX_ITEM');
        foreach ($arr as $row)
        {
            $flag = $row->GetFieldValue('EXT_FLAG');
            $txID = $row->GetFieldValue('BORROW_ID');
            $itemCode = $row->GetFieldValue('ITEM_CODE');

            if ($flag == 'D')
            {
                continue;
            }
            
            $d = new CTable('');
            $d->SetFieldValue('TX_ID', $txID);
            $obj = self::GetRowByID($d, $m, 0);

            $itemQty = $row->getFieldValue('ITEM_QUANTITY');
            $currentReturnedQty = $obj->getFieldValue('RETURNED_QUANTITY');            
            $returnedQtyNeed = $obj->getFieldValue('RETURNED_QUANTITY_NEED');

            if ($itemQty > $returnedQtyNeed)
            {
                throw new Exception("Quantity greather than quantity need [$itemCode] [$returnedQtyNeed]!!!");
            }

            $returnedQuantity = $currentReturnedQty + $itemQty;
            $returnedQtyNeed = $returnedQtyNeed - $itemQty;

            $returnedAllFlag = 'N';
            if ($returnedQtyNeed <= 0)
            {
                $returnedAllFlag = 'Y';
            }

            $obj->SetFieldValue('RETURNED_QUANTITY', $returnedQuantity);
            $obj->SetFieldValue('RETURNED_QUANTITY_NEED', $returnedQtyNeed);
            $obj->SetFieldValue('RETURNED_ALL_FLAG', $returnedAllFlag);

            $m->Update(4, $obj);
        }
    }

    public static function ApproveInventoryDoc($db, $param, $data)
    {
//CSql::SetDumpSQL(true);

        $valid = CHelper::ValidateDateBoundary($db, $param, $data, 'DOCUMENT_DATE');
        if (!$valid)
        {
            return(array($param, $data));
        }

        self::deriveAdjustItem($db, $data);

        $fh = CUtils::LockEntity('ApproveInventoryDoc');

        $auto_no = $param->GetFieldValue('AUTO_NUMBER');    
        $allowNegative = $data->GetFieldValue('ALLOW_NEGATIVE');

        $tx = false;    
        if (!$db->inTransaction()) 
        {
            $db->beginTransaction();
            $tx = true;
        }

        $id = $data->GetFieldValue("DOC_ID");
        if ($id == '')
        {
            //Has not been created
            list($p, $d) = self::CreateInventoryDoc($db, $param, $data);
        }
        else
        {
            //Already exist
            list($p, $d) = self::UpdateInventoryDoc($db, $param, $data);
        }

        if ($auto_no != 'N') 
        {
            $cfg = self::getDocumentConfig($data->getFieldValue("DOCUMENT_TYPE"), self::INV_DOC_APPROVED);

            $t = new CTable("");
            $t->setFieldValue("DOC_TYPE", $cfg);
            CHelper::PopulateCustomVariables($data, $t, 1);

            list($p1, $d1) = DocumentNumber::GenerateDocumentNumber($db, $param, $t);
            $docNo = $d1->getFieldValue("LAST_DOCUMENT_NO");
            $d->SetFieldValue('DOCUMENT_NO', $docNo);
        }

        $bal = self::deriveBalanceDoc($db, $d);
        $type = $bal->GetFieldValue('BAL_DOC_TYPE');
        $result = BalanceAPI::Apply($db, $type, $bal, $allowNegative=='Y', NULL);
        if (!$result)
        {
            if ($tx) $db->rollBack();

            CUtils::UnlockEntity($fh);
            
            $param->SetFieldValue('ERROR_CODE', '2');
            $param->SetFieldValue('ERROR_DESC', 'ERRORS');
            return(array($param, $bal));
        }

        //Get value return from API and update back to INVENTORY_TX table
        self::updateReturnedValue($bal, $d);

        self::deductBorrowQuantity($db, $d);

        //Update value back such as begin and end balance, approve_date etc.
        $d->setFieldValue('DOCUMENT_STATUS', self::INV_DOC_APPROVED);
        $d->setFieldValue('APPROVED_DATE', CUtils::GetCurrentDateTimeInternal());
        $d->setFieldValue('APPROVED_SEQ', CSql::GetSeq($db, 'INVENTORY_DOC_APPROVED_SEQ', 1));        
        list($p, $d) = self::UpdateInventoryDoc($db, $param, $d);

        if ($tx)
        {
            $db->commit();
        }

        CUtils::UnlockEntity($fh);

        return(array($param, $d));
    }       
    
}

?>