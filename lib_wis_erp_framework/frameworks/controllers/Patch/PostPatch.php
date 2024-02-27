<?php
/* 
    Purpose : Controller for POS
    Created By : Seubpong Monsar
    Created Date : 09/08/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class PostPatch extends CBaseController
{

    public static function PatchUpdateAccountDocRedeemed($db)
    { 
        $data = new CTable('');
        $u = new MCashXferDetail($db);
        list($cnt, $rows) = $u->Query(1, $data);

        $cnt = 0;

        $m = new MAccountDoc($db);
        foreach ($rows as $doc)
        {
            $id = $doc->getFieldValue('CASH_DOC_ID');
            $doc->setFieldValue('REDEEM_DOCUMENT_ID', $id);
            $m->Update(14, $doc);

            $cnt++;
        }

//throw new Exception("FAKE EXCEPTION [$cnt]");        

        return(true);
    }

    public static function PatchUpdateAccessRight7($db)
    { 
        AccessRight::PopulateAccessRight($db, new CTable(''), new CTable(''));
        return(true);        
    }

    public static function PatchUpdateAccessRight6($db)
    { 
        AccessRight::PopulateAccessRight($db, new CTable(''), new CTable(''));
        return(true);        
    }

    public static function PatchUpdateAccessRight5($db)
    { 
        AccessRight::PopulateAccessRight($db, new CTable(''), new CTable(''));
        return(true);        
    }

    public static function PatchCreatePoItemIndex($db)
    { 
        $param = new CTable('');        
        
        $data = new CTable('');
        $data->setFieldValue('FROM_DOCUMENT_DATE', ''); //'2019-01-01 00:00:00'
        $data->setFieldValue('TO_DOCUMENT_DATE', '');

        $u = new MAuxilaryDoc($db);
        list($cnt, $rows) = $u->Query(1, $data);

        $cnt = 0;

        foreach ($rows as $doc)
        {
            $indexValue = $doc->getFieldValue('INDEX_ITEMS');
            if ($indexValue != '')
            {
                continue;
            }

            list($p, $rcp) = AuxilaryDocument::GetAuxilaryDocInfo($db, $param, $doc);
            AuxilaryDocument::UpdateItemsIndex($db, $rcp);
            $cnt++;

            if (($cnt % 100) == 0)
            {
                $db->commit();
                $db->beginTransaction();
            }
        }

        return(true);
    }

    public static function PatchUpdateAccessRight4($db)
    { 
        AccessRight::PopulateAccessRight($db, new CTable(''), new CTable(''));
        return(true);        
    }

    public static function PatchUpdateAccessRight3($db)
    { 
        AccessRight::PopulateAccessRight($db, new CTable(''), new CTable(''));
        return(true);        
    }

    public static function PatchUpdateAccessRight2($db)
    { 
        AccessRight::PopulateAccessRight($db, new CTable(''), new CTable(''));
        return(true);        
    }

    public static function PatchUpdateAccessRight($db)
    { 
        AccessRight::PopulateAccessRight($db, new CTable(''), new CTable(''));
        return(true);        
    }

    public static function PatchApproveCheque($db)
    { 
        $param = new CTable('');
        
        $data = new CTable('');
        $data->setFieldValue('DOCUMENT_TYPE_SET', '(1, 9, 5, 10, 11, 12)'); 
        $data->setFieldValue('DOCUMENT_STATUS', '2'); 

        $u = new MAccountDoc($db);
        list($cnt, $rows) = $u->Query(1, $data);

        $cnt = 0;

        foreach ($rows as $doc)
        {
            $chequeID = $doc->getFieldValue('CHEQUE_ID');
            if ($chequeID == '')
            {
                continue;
            }
            
            list($p, $cq) = Cheque::GetChequeInfo($db, $param, $doc);
            $cheqStatus = $cq->getFieldValue('CHEQUE_STATUS');           

            if ($cheqStatus == '1')
            {
                //Not yet approved (pending)
                Cheque::ApproveCheque($db, $param, $cq);
                $cnt++;
//CLog::WriteLn("CHEQUE_ID=[$chequeID], STATUS=[$cheqStatus]");                 
            }            
        }

//throw new Exception("FAKE EXCEPTION [$cnt]");
        return(true);        
    }

    public static function PatchCreateBorrowReturnDocumentNumber($db)
    {
        $docs = [
            'INVENTORY_DOC_BORROW_TEMP',
            'INVENTORY_DOC_BORROW_APPROVED',
            'INVENTORY_DOC_RETURN_TEMP',
            'INVENTORY_DOC_RETURN_APPROVED',
        ];

        $docArr = [
            'INVENTORY_DOC_BORROW_TEMP' => ['2018', '09', 'BR*-${ud_4y-2m}-${seq}', '2', '0', '2', '5', '0'],
            'INVENTORY_DOC_BORROW_APPROVED' => ['2018', '09', 'BR-${ud_4y-2m}-${seq}', '2', '0', '2', '5', '0'],
            'INVENTORY_DOC_RETURN_TEMP' => ['2018', '09', 'RT*-${ud_4y-2m}-${seq}', '2', '0', '2', '5', '0'],
            'INVENTORY_DOC_RETURN_APPROVED' => ['2018', '09', 'RT-${ud_4y-2m}-${seq}', '2', '0', '2', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);
            $o->SetFieldValue("USE_CUSTOM_SEQ", 'Y');
            $o->SetFieldValue("CUSTOM_SEQ_VAR", '${ud_4y-2m}');
            $o->SetFieldValue("SEQUENCE_DEFINITION", '2018-09=0|');

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }

    public static function PatchCreateProjectIndex($db)
    { 
        $param = new CTable('');
        
        $data = new CTable('');
        $data->setFieldValue('DOCUMENT_TYPE_SET', '(1, 2, 3, 4, 5, 6, 7, 8)'); 

        $u = new MAccountDoc($db);
        list($cnt, $rows) = $u->Query(1, $data);

        $cnt = 0;

        foreach ($rows as $doc)
        {
            list($p, $rcp) = AccountDocument::GetAccountDocInfo($db, $param, $doc);
            AccountDocument::UpdatePaymentIndex($db, $rcp);

            $cnt++;
        }

//throw new Exception("FAKE EXCEPTION [$cnt]");
        return(true);        
    }

    public static function PatchPopulatePoIDByPoNum($db)
    {
        $data = new CTable('');

        $u = new MAccountDocItem($db);
        $poi = new MAuxilaryDocItem($db);
        $pmc = new MPaymentCriteria($db);
        $aux = new MAuxilaryDoc($db);

        list($cnt, $rows) = $u->Query(0, $data);
        $idx = 0;
        $obj = new CTable('');

        foreach ($rows as $r)
        {
            $poCriteriaID = $r->getFieldValue('PO_CRITERIA_ID');
            $poItemID = $r->getFieldValue('PO_ITEM_ID');
            $refPoNo = $r->getFieldValue('REF_PO_NO');
            $accountDocItemID = $r->getFieldValue('ACCOUNT_DOC_ITEM_ID');

            $cnt = 0;
            $data = new CTable('');

            if ($poItemID != '')
            {
                $data->setFieldValue('AUXILARY_DOC_ITEM_ID', $poItemID);
                list($cnt, $rows) = $poi->Query(0, $data);                
            }
            else if ($poCriteriaID != '')
            {
                $data->setFieldValue('PAYMENT_CRITERIA_ID', $poCriteriaID);
                list($cnt, $rows) = $pmc->Query(0, $data);
            }
            else if ($refPoNo != '')
            {
                $data->setFieldValue('DOCUMENT_NO', $refPoNo);
                list($cnt, $rows) = $aux->Query(0, $data);   
            }

            if ($cnt == 1)
            {
                //Now, AUXILARY_DOC_ID is populated in $obj
                $obj = $rows[0];
                $poID = $obj->getFieldValue('AUXILARY_DOC_ID');

                $obj->setFieldValue('PO_ID', $poID);
                $obj->setFieldValue('ACCOUNT_DOC_ITEM_ID', $accountDocItemID);
//printf("DEBUG [$refPoNo] [$accountDocItemID] [$poID]\n");                  
                $u->Update(7, $obj);

                $idx++;
            }            
        }
        
//throw new Exception("FAKE EXCEPTION [$idx]");
        return(true);        
    }

    public static function PatchPopulatePoID($db)
    {
        $data = new CTable('');

        $u = new MAccountDocItem($db);
        $poi = new MAuxilaryDocItem($db);
        $pmc = new MPaymentCriteria($db);

        list($cnt, $rows) = $u->Query(0, $data);
        $idx = 0;
        $obj = new CTable('');

        foreach ($rows as $r)
        {
            $poCriteriaID = $r->getFieldValue('PO_CRITERIA_ID');
            $poItemID = $r->getFieldValue('PO_ITEM_ID');
            $accountDocItemID = $r->getFieldValue('ACCOUNT_DOC_ITEM_ID');

            $cnt = 0;
            
            if ($poItemID != '')
            {
                $data->setFieldValue('AUXILARY_DOC_ITEM_ID', $poItemID);
                list($cnt, $rows) = $poi->Query(0, $data);                
            }
            else if ($poCriteriaID != '')
            {
                $data->setFieldValue('PAYMENT_CRITERIA_ID', $poCriteriaID);
                list($cnt, $rows) = $pmc->Query(0, $data);
            }

            if ($cnt == 1)
            {
                //Now, AUXILARY_DOC_ID is populated in $obj
                $obj = $rows[0];
                $poID = $obj->getFieldValue('AUXILARY_DOC_ID');

                $obj->setFieldValue('PO_ID', $poID);
                $obj->setFieldValue('ACCOUNT_DOC_ITEM_ID', $accountDocItemID);
//printf("DEBUG [$accountDocItemID] [$poID]\n");                  
                $u->Update(7, $obj);

                $idx++;
            }            
        }
        
//throw new Exception("FAKE EXCEPTION [$idx]");
        return(true);        
    }

    public static function PatchCreateBillSummaryDocumentNumber($db)
    {
        $docs = [
            'SALE_BILL_SUMMARY',
        ];

        $docArr = [
            'SALE_BILL_SUMMARY' => ['2018', '06', 'BS-${ud_4y-2m}-${seq}', '2', '0', '2', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);
            $o->SetFieldValue("USE_CUSTOM_SEQ", 'Y');
            $o->SetFieldValue("CUSTOM_SEQ_VAR", '${ud_4y-2m}');
            $o->SetFieldValue("SEQUENCE_DEFINITION", '2018-06=0|');

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }

    public static function PatchCreatePaymentIndex3($db)
    {
        self::PatchCreatePaymentIndex($db);
    }

    public static function PatchCreatePaymentIndex2($db)
    {
        self::PatchCreatePaymentIndex($db);
    }
    
    public static function PatchCreatePaymentIndex($db)
    { 
        $param = new CTable('');
        
        $data = new CTable('');
        $data->setFieldValue('DOCUMENT_TYPE_SET', '(1, 5, 9, 10, 11, 12)'); 

        $u = new MAccountDoc($db);
        list($cnt, $rows) = $u->Query(1, $data);

        $cnt = 0;

        foreach ($rows as $doc)
        {
            list($p, $rcp) = AccountDocument::GetAccountDocInfo($db, $param, $doc);
            AccountDocument::UpdatePaymentIndex($db, $rcp);
/*
$docNO = $rcp->getFieldValue('DOCUMENT_NO');
$docID = $rcp->getFieldValue('ACCOUNT_DOC_ID');
$temp = $rcp->getFieldValue('INDEX_PAYMENT');
printf("DEBUG [$docNO] [$docID] [$temp] \n");     
*/
            $cnt++;
        }

//throw new Exception("FAKE EXCEPTION [$cnt]");
        return(true);        
    }

    public static function PatchCreateReceiptAccountDocItem($db)
    {
        $param = new CTable('');
        $data = new CTable('');

        //Receipt
        $data->setFieldValue('DOCUMENT_TYPE_SET', '(9, 10)'); 
        list($p, $d) = AccountDocument::GetAccountDocList($db, $param, $data);

        $arr = $d->getChildArray('ACCOUNT_DOC_LIST');
        $cnt = 0;

        foreach ($arr as $doc)
        {
            list($p, $rcp) = AccountDocument::GetAccountDocInfo($db, $param, $doc);
            AccountDocument::CreateDocItemsForReceipt($db, $rcp);

            $cnt++;
        }
//throw new Exception("FAKE EXCEPTION [$cnt]");
        return(true);        
    }
    
    public static function PatchCreateSoDocumentNumber($db)
    {
        $docs = [
            'SO_DOC_NUMBER',
        ];

        $docArr = [
            'SO_DOC_NUMBER' => ['2018', '05', 'SO-${yyyy}-${mm}-${seq}', '2', '0', '2', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }
    
    public static function PatchCreateWhDocumentNumber($db)
    {
        $docs = [
            'WH_DOC_NUMBER',
        ];

        $docArr = [
            'WH_DOC_NUMBER' => ['2018', '05', 'WH-${yyyy}-${seq}', '1', '0', '2', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }
    
    public static function PatchCreateDepositDocumentNumber($db)
    {
        $docs = [
            'DEPOSIT_DOC_SALE_TEMP',
            'DEPOSIT_DOC_SALE_APPROVED',
            'DEPOSIT_DOC_PURCHASE_TEMP',
            'DEPOSIT_DOC_PURCHASE_APPROVED',
        ];

        $docArr = [
            'DEPOSIT_DOC_SALE_TEMP' => ['2018', '05', '*DEPS-${yyyy}-${mm}-${seq}', '2', '0', '1', '5', '0'],
            'DEPOSIT_DOC_SALE_APPROVED' => ['2018', '05', 'DEPS-${yyyy}-${mm}-${seq}', '2', '0', '1', '5', '0'],
            'DEPOSIT_DOC_PURCHASE_TEMP' => ['2018', '05', '*DEPP-${yyyy}-${mm}-${seq}', '2', '0', '1', '5', '0'],
            'DEPOSIT_DOC_PURCHASE_APPROVED' => ['2018', '05', 'DEPP-${yyyy}-${mm}-${seq}', '2', '0', '1', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }
    
    public static function PatchCreateRevExpDocumentNumber($db)
    {
        $docs = [
            'SALE_DOC_REVENUE_TEMP',
            'SALE_DOC_REVENUE_APPROVED',
            'PURCHASE_DOC_EXPENSE_TEMP',
            'PURCHASE_DOC_EXPENSE_APPROVED',
        ];

        $docArr = [
            'SALE_DOC_REVENUE_TEMP' => ['2018', '03', '*REV-${yyyy}-${mm}-${seq}', '1', '0', '1', '5', '0'],
            'SALE_DOC_REVENUE_APPROVED' => ['2018', '03', 'REV-${yyyy}-${mm}-${seq}', '1', '0', '1', '5', '0'],
            'PURCHASE_DOC_EXPENSE_TEMP' => ['2018', '03', '*EXP-${yyyy}-${mm}-${seq}', '1', '0', '1', '5', '0'],
            'PURCHASE_DOC_EXPENSE_APPROVED' => ['2018', '03', 'EXP-${yyyy}-${mm}-${seq}', '1', '0', '1', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }

    public static function PatchCreateReceiptDocumentNumber($db)
    {
        $docs = [
            'SALE_DOC_RECEIPT_TEMP',
            'SALE_DOC_RECEIPT_APPROVED',
            'PURCHASE_DOC_RECEIPT_TEMP',
            'PURCHASE_DOC_RECEIPT_APPROVED',
        ];

        $docArr = [
            'SALE_DOC_RECEIPT_TEMP' => ['2018', '02', '*RV-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'SALE_DOC_RECEIPT_APPROVED' => ['2018', '02', 'RV-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'PURCHASE_DOC_RECEIPT_TEMP' => ['2018', '02', '*PV-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'PURCHASE_DOC_RECEIPT_APPROVED' => ['2018', '02', 'PV-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }
    
    public static function PatchCreateSaleQuotationDocumentNumber($db)
    {
        $docs = [
            'AUX_DOC_QUOTATION_TEMP',
        ];

        $docArr = [
            'AUX_DOC_QUOTATION_TEMP' => ['2018', '01', 'QT-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }

    public static function PatchMigrateAdminToFramework($db)
    {
        $groupHash = [];
        $userHash = [];

        $g = new MUserGroup($db);
        list($cnt, $groups) = $g->Query(0, new CTable(''));

        foreach ($groups as $grp)
        {
            $oldID = $grp->GetFieldValue('GROUP_ID');

            $ng = new MFrwUserGroup($db);
            $rc = $ng->Insert(0, $grp, true);

            //Called to Insert() will modify primary key field internally
            $newID = $grp->GetFieldValue('GROUP_ID');
            $groupHash["$oldID"] = $newID;
        }

        //=== User ===
        $u = new MUser($db);
        list($cnt, $users) = $u->Query(0, new CTable(''));

        foreach ($users as $usr)
        {
            $oldID = $usr->GetFieldValue('USER_ID');
            $oldGroupID = $usr->GetFieldValue('GROUP_ID');

            $newGroupID = $groupHash["$oldGroupID"];
            $usr->SetFieldValue('GROUP_ID', $newGroupID);

            $nu = new MFrwUser($db);
            $rc = $nu->Insert(0, $usr, true);

            //Called to Insert() will modify primary key field internally
            $newID = $usr->GetFieldValue('USER_ID');
            $userHash["$oldID"] = $newID;
        }

        //=== User Variable ===
        $uv = new MUserVariable($db);
        list($cnt, $variables) = $uv->Query(0, new CTable(''));

        foreach ($variables as $vrb)
        {
            $oldID = $vrb->GetFieldValue('USER_ID');
            $newUserID = $userHash["$oldID"];
            $vrb->SetFieldValue('USER_ID', $newUserID);

            $nuv = new MFrwUserVariable($db);
            $rc = $nuv->Insert(0, $vrb, true);
        }

//throw new Exception('Just throw DUMMY');
        return(true);
    }

    public static function PatchTestPostPatch($db)
    {
        return(true);
    }

    public static function PatchTestPostPatch2($db)
    {
        return(true);
    } 

    public static function PatchCreatePurchasePoDocumentNumber($db)
    {
        $docs = [
            'AUX_DOC_PO_TEMP',
        ];

        $docArr = [
            'AUX_DOC_PO_TEMP' => ['2018', '01', 'PO-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }  

    public static function PatchCreatePurchaseDocumentNumber($db)
    {
        $docs = [
            'ACCOUNT_DOC_CASH_BUY_TEMP',
            'ACCOUNT_DOC_DEPT_BUY_TEMP',
            'ACCOUNT_DOC_CR_BUY_TEMP',
            'ACCOUNT_DOC_DR_BUY_TEMP',
            
            'ACCOUNT_DOC_CASH_BUY_APPROVED',
            'ACCOUNT_DOC_DEPT_BUY_APPROVED',
            'ACCOUNT_DOC_CR_BUY_APPROVED',
            'ACCOUNT_DOC_DR_BUY_APPROVED',
            
            'ACCOUNT_DOC_CASH_BUY_TEMP_NV',
            'ACCOUNT_DOC_DEPT_BUY_TEMP_NV',
            'ACCOUNT_DOC_CR_BUY_TEMP_NV',
            'ACCOUNT_DOC_DR_BUY_TEMP_NV',
            
            'ACCOUNT_DOC_CASH_BUY_APPROVED_NV',
            'ACCOUNT_DOC_DEPT_BUY_APPROVED_NV',
            'ACCOUNT_DOC_CR_BUY_APPROVED_NV',
            'ACCOUNT_DOC_DR_BUY_APPROVED_NV',
        ];

        $docArr = [
            'ACCOUNT_DOC_CASH_BUY_TEMP' => ['2017', '12', 'ซส*-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DEPT_BUY_TEMP' => ['2017', '12', 'ซช*-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_CR_BUY_TEMP' => ['2017', '12', 'ลดซ*-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DR_BUY_TEMP' => ['2017', '12', 'พนซ*-${yyyy}-${seq}', '2', '0', '1', '5', '0'], 
            
            'ACCOUNT_DOC_CASH_BUY_APPROVED' => ['2017', '12', 'ซส-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DEPT_BUY_APPROVED' => ['2017', '12', 'ซช-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_CR_BUY_APPROVED' => ['2017', '12', 'ลนซ-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DR_BUY_APPROVED' => ['2017', '12', 'พนซ-${yyyy}-${seq}', '2', '0', '1', '5', '0'],
            
            'ACCOUNT_DOC_CASH_BUY_TEMP_NV' => ['2017', '12', 'ซส*-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DEPT_BUY_TEMP_NV' => ['2017', '12', 'ซช*-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_CR_BUY_TEMP_NV' => ['2017', '12', 'ลนซ*-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DR_BUY_TEMP_NV' => ['2017', '12', 'พนซ*-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            
            'ACCOUNT_DOC_CASH_BUY_APPROVED_NV' => ['2017', '12', 'ซส-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DEPT_BUY_APPROVED_NV' => ['2017', '12', 'ซช-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_CR_BUY_APPROVED_NV' => ['2017', '12', 'ลนซ-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DR_BUY_APPROVED_NV' => ['2017', '12', 'พนซ-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
        ];

        foreach ($docs as $doc)
        {
            $arr = $docArr[$doc];

            $o = new CTable('');            
            $o->SetFieldValue('DOC_TYPE', $doc);
            $o->SetFieldValue('LAST_RUN_YEAR', $arr[0]);
            $o->SetFieldValue('LAST_RUN_MONTH', $arr[1]);
            $o->SetFieldValue('FORMULA', $arr[2]);
            $o->SetFieldValue('RESET_CRITERIA', $arr[3]);
            $o->SetFieldValue('CURRENT_SEQ', $arr[4]);
            $o->SetFieldValue('START_SEQ', $arr[5]);
            $o->SetFieldValue('SEQ_LENGTH', $arr[6]);
            $o->SetFieldValue('YEAR_OFFSET', $arr[7]);
            $o->SetFieldValue('PARENT_ID', 1);
            $o->SetFieldValue('GROUP_ID', 1);

            NumberGenerator::CreateDocumentNumber($db, new CTable(""), $o);
        }

        return(true);        
    }        
}

?>