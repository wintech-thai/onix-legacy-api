<?php
/* 
    Purpose : Controller for Cash Report
    Created By : Seubpong Monsar
    Created Date : 09/27/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class InventoryReport extends CBaseController
{
    private static $cfg = NULL;

    private static function createTxObject($db)
    {
        $u = new MBalanceTx($db);
        return($u);
    }

    private static function createItemTxObject($db)
    {
        $u = new MInventoryTx($db);
        return($u);
    }    
    
    private static function createAccumObject($db)
    {
        $u = new MBalanceAccum($db);
        return($u);
    }

    private static function populateArea($data)
    {
        $data->SetFieldValue('BAL_OWNER_TYPE', BalanceAPI::BAL_OWNER_TYPE_LOCATION);
        $data->SetFieldValue('BAL_ITEM_TYPE', BalanceAPI::BAL_ITEM_TYPE_INVENTORY);
    }

    public static function GetBorrowedItemList($db, $param, $data)
    {
        $u = self::createItemTxObject($db);
        $u->OverideOrderBy(3, 'ORDER BY IVD.DOCUMENT_DATE ASC, IT.ITEM_CODE ASC, EMP.EMPLOYEE_NAME ASC ');
        $data->setFieldValue('DOCUMENT_TYPE', InventoryDocument::INV_DOCTYPE_BORROW);
        $data->setFieldValue('DOCUMENT_STATUS', InventoryDocument::INV_DOC_APPROVED);

        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(3, $data);
        $p = new CTable($u->GetTableName());        
        self::PopulateRow($p, $item_cnt, $chunk_cnt, 'BORROWED_ITEM_LIST', $rows);

        return(array($param, $p));
    }

    public static function GetInventoryBalanceList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = self::createAccumObject($db);
        $u->OverideOrderBy(1, 'ORDER BY IT.ITEM_CODE ASC, LC.DESCRIPTION ASC ');

        self::populateArea($data);
        $data->SetFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_GLOBAL);
        list($cnt, $rows) = $u->Query(1, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'INVENTORY_BALANCE_LIST', $rows);
        
        return(array($param, $p));
    }

    public static function GetInventoryItemMovementList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = self::createTxObject($db);
        $u->OverideOrderBy(1, 'ORDER BY IT.ITEM_NAME_THAI ASC, IT.ITEM_ID ASC, LC1.DESCRIPTION ASC, LC1.LOCATION_ID ASC, BDD.BAL_DOC_DTL_ID ASC ');

        self::populateArea($data);
        list($cnt, $rows) = $u->Query(1, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'INVENTORY_MOVEMENT_LIST', $rows);
        
        return(array($param, $p));
    }    

    public static function GetInventoryBalanceSummaryList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = self::createAccumObject($db);
        $u->OverideOrderBy(1, 'ORDER BY IT.ITEM_NAME_THAI ASC, IT.ITEM_ID ASC, LC.DESCRIPTION ASC, LC.LOCATION_ID ASC, BA.BAL_DATE ASC ');

        self::populateArea($data);
        $data->SetFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_DAILY);
        list($cnt, $rows) = $u->Query(1, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SUMMARY_ITEM', $rows);
        
        return(array($param, $p));
    }    

    public static function GetInventoryItemBalanceInfo($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = self::createAccumObject($db);
        $u->OverideOrderBy(1, 'ORDER BY LC.DESCRIPTION ASC, LC.LOCATION_ID ASC ');

        $d = new CTable('');
        $d->SetFieldValue('ITEM_ID', $data->GetFieldValue('ITEM_ID'));

        self::populateArea($d);
        $d->SetFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_GLOBAL);
        list($cnt, $rows) = $u->Query(1, $d);
        
        self::PopulateRow($data, $cnt, 1, 'CURRENT_BALANCE_ITEM', $rows);
        
        return(array($param, $data));
    }      

    public static function GetCurrentBalanceInfo($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $item_id = $data->getFieldValue("ITEM_ID");
        $loc_id = $data->getFieldValue("LOCATION_ID");
        $bd = $data->getFieldValue("BALANCE_DATE");
        $start_date = CUtils::GetDateStart(CUtils::DateAdd($bd, -30));
        
        $u = self::createAccumObject($db);
        $u->OverideOrderBy(1, 'ORDER BY BA.BAL_DATE ASC ');

        $d = new CTable('');
        $d->SetFieldValue('ITEM_ID', $item_id);
        $d->SetFieldValue('LOCATION_ID', $loc_id);
        $d->SetFieldValue('FROM_BALANCE_DATE', $start_date);

        self::populateArea($d);
        $d->SetFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_DAILY);
        list($cnt, $rows1) = $u->Query(1, $d);
        
        self::PopulateRow($data, $cnt, 1, 'SUMMARY_ITEM', $rows1);
        
        //=== TX ===
        $t = self::createTxObject($db);
        $d->SetFieldValue('FROM_DOCUMENT_DATE', $start_date);

        list($cnt, $rows2) = $t->Query(1, $d);
        self::PopulateRow($data, $cnt, 1, 'MOVEMENT_ITEM', $rows2);

        return(array($param, $data));
    }

    public static function GetInventoryTransactionList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);        
        $u = new MInventoryTx($db);
        $u->OverideOrderBy(2, 'ORDER BY IVD.DOCUMENT_DATE ASC, IVD.DOCUMENT_NO ASC, IVT.TX_ID ASC ');

        self::populateArea($data);
        list($cnt, $rows) = $u->Query(2, $data);
        
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'INVENTORY_TRANSACION_LIST', $rows);
        
        return(array($param, $p));
    } 
    
    public static function GetInventoryMovementSummaryList($db, $param, $data)
    {
//CSql::SetDumpSQL(true);
        $u = self::createAccumObject($db);
        $u->OverideOrderBy(1, 'ORDER BY IT.ITEM_CODE ASC, IT.ITEM_ID ASC, LC.DESCRIPTION ASC, LC.LOCATION_ID ASC, BA.BAL_DATE ASC ');

        self::populateArea($data);
        $data->SetFieldValue('BAL_LEVEL', BalanceAPI::BAL_LEVEL_DAILY);
        list($cnt, $rows) = $u->Query(1, $data);
  
        $itemAccumAvg = [];
        $itemAccumFifo = [];
        $items = [];

        $beginFields = ['BEGIN_QUANTITY', 'BEGIN_AMOUNT_AVG', 'BEGIN_QTY_FIFO', 'BEGIN_AMOUNT_FIFO'];
        $endFields = ['END_QUANTITY', 'END_AMOUNT_AVG', 'END_QTY_FIFO', 'END_AMOUNT_FIFO'];

        $fieldsAvg = ['IN_QUANTITY', 'IN_AMOUNT_AVG', 'OUT_QUANTITY', 'OUT_AMOUNT_AVG'];
        $fieldsFifo = ['IN_QUANTITY_FIFO', 'IN_AMOUNT_FIFO', 'OUT_QUANTITY_FIFO', 'OUT_AMOUNT_FIFO'];
        $fieldsItem = ['ITEM_ID', 'ITEM_CODE', 'ITEM_NAME_THAI', 'LOCATION_NAME', 'LOCATION_ID'];

        $key = '';
        $prevKey = '';

        //Collect the accum value
        foreach ($rows as $row)
        {
            $itemID = $row->GetFieldValue('ITEM_ID');
            $locID = $row->GetFieldValue('LOCATION_ID');
            $key = $itemID . '-' . $locID;

            if ($prevKey != $key)
            {
                $prevKey = $key;

                //Begin Initialize
                $initAvg = new CTable('');
                self::copyFields($initAvg, $row, $fieldsAvg);
                //Keep ending balance in the $initAvg for both AVG and FIFO
                self::copyFields($initAvg, $row, $endFields);             
                $itemAccumAvg[$key] = $initAvg;

                $initFifo = new CTable('');
                self::copyFields($initFifo, $row, $fieldsFifo);
                $itemAccumFifo[$key] = $initFifo;
                //End Initialize

                $item = new CTable('');
                self::copyFields($item, $row, $fieldsItem);
                //Copy begin balance
                self::copyFields($item, $row, $beginFields);
                array_push($items, $item);
            }
            else
            {
                $accumAvg = $itemAccumAvg[$key];
                $accumAvg = self::sumFields($row, $accumAvg, $fieldsAvg);

                //Keep ending balance in the $accumAvg for both AVG and FIFO
                self::copyFields($accumAvg, $row, $endFields);
                $itemAccumAvg[$key] = $accumAvg;

                $accumFifo = $itemAccumFifo[$key];
                $accumFifo = self::sumFields($row, $accumFifo, $fieldsFifo);
                $itemAccumFifo[$key] = $accumFifo;
            }         
        }

        //We can control the order of report by controlling the order in $items
        foreach ($items as $item)
        {
            $itemID = $item->GetFieldValue('ITEM_ID');
            $locID = $item->GetFieldValue('LOCATION_ID');
            $key = $itemID . '-' . $locID;
            
            $accumAvg = $itemAccumAvg[$key];
            $accumFifo = $itemAccumFifo[$key];

            self::copyFields($item, $accumAvg, $fieldsAvg);
            self::copyFields($item, $accumAvg, $endFields);

            self::copyFields($item, $accumFifo, $fieldsFifo);
        }
        
        $cnt = count($items);
        $p = new CTable($u->GetTableName());
        self::PopulateRow($p, $cnt, 1, 'SUMMARY_ITEM', $items);

        return(array($param, $p));
    }

    private static function copyFields($destObj, $srcObj, $fields)
    {
        foreach ($fields as $f)
        {
            $value = $srcObj->GetFieldValue($f);
            $destObj->SetFieldValue($f, $value);
        }
    }

    private static function sumFields($row, $accumObj, $fields)
    {
        $obj = new CTable('');

        foreach ($fields as $f)
        {
            $total = $accumObj->GetFieldValue($f);
            if ($total == '')
            {
                $total = 0.00;
            }

            $value = $row->GetFieldValue($f);
            if ($value == '')
            {
                $value = 0.00;
            }

            $obj->SetFieldValue($f, $total+$value);
        }

        return($obj);
    }
}

?>