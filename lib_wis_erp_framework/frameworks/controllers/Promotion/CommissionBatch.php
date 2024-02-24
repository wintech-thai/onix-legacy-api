<?php
/*
    Purpose : Controller for COMMISSION_BATCH
    Created By : Seubpong Monsar
    Created Date : 10/05/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CommissionBatch extends CBaseController
{
    const PENDING = 1;
    const APROVED = 2;
    const WEEKLY_CYCLE = 1;
    const MONTHY_CYCLE = 2;

    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = array
        (
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['COMMISSION_BATCH_ITEM', new MCommissionBatchDetail($db), 1, 0, 2],
        );

        self::$cfg = $config;

        return($config);
    }

    public static function GetCommissionBatchList($db, $param, $data)
    {
        $u = new MCommissionBatch($db);
        [$cnt, $item_cnt, $chunk_cnt, $rows] = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'COMMISSION_BATCH_LIST', $rows);

        return([$param, $pkg]);
    }

    public static function GetCommissionBatchInfoList($db, $param, $data)
    {
        $u = new MCommissionBatch($db);
        [$cnt, $rows] = $u->Query(1, $data);

        $arr = [];

        foreach ($rows as $key => $row) {
            [$p, $info] = self::GetCommissionBatchInfo($db, $param, $row);
            $arr[] = $info;
        }

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, null, null, 'COMMISSION_BATCH_INFO_LIST', $arr);

        return([$param, $pkg]);
    }

    public static function GetCommissionBatchInfo($db, $param, $data)
    {
        $cfg = self::initSqlConfig($db);

        $u = new MCommissionBatch($db);
        $obj = self::GetRowByID($data, $u, 1);

        if (!isset($obj))
        {
            throw new Exception("No commission batch in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return([$param, $obj]);
    }

    public static function IsCommissionBatchExist($db, $param, $data)
    {
        // CSql::SetDumpSQL(true);
        $u = new MCommissionBatch($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "DOCUMENT_NO", "DOCUMENT_NO", 0);
        return(array($param, $o));
    }

    public static function CreateCommissionBatch($db, $param, $data)
    {
        $u = new MCommissionBatch($db);

        $data->SetFieldValue('BATCH_STATUS', self::PENDING);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return([$param, $data]);
    }

    public static function UpdateCommissionBatch($db, $param, $data)
    {
        $u = new MCommissionBatch($db);

        $obj = self::GetRowByID($data, $u, 1);

        if ($obj->GetFieldValue('BATCH_STATUS') == self::APROVED) {
            throw new Exception("This document has been approved and not allowed to update");
        }
        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return([$param, $data]);
    }

    public static function DeleteCommissionBatch($db, $param, $data)
    {
        $u = new MCommissionBatch($db);

        $obj = self::GetRowByID($data, $u, 1);

        if ($obj->GetFieldValue('BATCH_STATUS') == self::APROVED) {
            throw new Exception("This document has been approved and not allowed to delete");
        }

        self::deleteCommission($db, $data);

        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);

        return([$param, $data]);
    }

    public static function CopyCommissionBatch($db, $param, $data)
    {
        [$p, $d] = self::GetCommissionBatchInfo($db, $param, $data);
        self::PopulateNewCode($d, 'DOCUMENT_NO');
        self::InitCopyItems($d, self::$cfg);

        [$p, $d] = self::CreateCommissionBatch($db, $param, $d);
        [$p, $d] = self::GetCommissionBatchInfo($db, $param, $d);

        return([$param, $d]);
    }

    public static function InitCommissionBatchItem($db, $param, $data)
    {
        // CSql::SetDumpSQL(true);
        $mc = new MCycle($db);
        $cycle = self::GetRowByID($data, $mc, 0);

        $date = CUtils::GetDateStart($data->GetFieldValue('RUN_DATE'));
        $date = new DateTime($date);


        $getAd = self::getAccountDocByCycle($cycle, $date);

        $ad = new MAccountDoc($db);
        [$cnt, $rows] = $ad->Query(2,$getAd);

        $data->AddChildArray('COMMISSION_BATCH_ITEM', $rows);

        return([$param, $data]);
    }

    private function getAccountDocByCycle($cycle, $date)
    {
        $cycleType = $cycle->GetFieldValue('CYCLE_TYPE');

        switch ($cycleType) {
            case self::WEEKLY_CYCLE:
            $date->setISODate((int)$date->format('Y'), (int)$date->format('W'),
                (int)$cycle->GetFieldValue('DAY_OF_WEEK'));

            $to = $date->sub(new DateInterval('P1D'))->format('Y/m/d');
            $from = $date->sub(new DateInterval('P1W'))->format('Y/m/d');
            break;

            case self::MONTHY_CYCLE:
            $date->setDate((int)$date->format('Y'), (int)$date->format('m'),
                (int)$cycle->GetFieldValue('DAY_OF_MONTH'));

            $to = $date->format('Y/m/d 23:59:59');
            $from = $date->sub(new DateInterval('P1M'))->format('Y/m/d 00:00:00');
            break;
        }

        $getAd = new CTable('');
        $getAd->SetFieldValue('IS_NULL_COMMISSION','Y');
        $getAd->SetFieldValue('TO_DOCUMENT_DATE',$to);
        $getAd->SetFieldValue('FROM_DOCUMENT_DATE',$from);
        $getAd->SetFieldValue('DOCUMENT_TYPE_SET','(1,2)');


        return $getAd;
    }

    public static function ProcessCommissionBatch($db, $param, $data, $isCreateCommission = false)
    {
        $commissionBatItem = $data->GetChildArray('COMMISSION_BATCH_ITEM');

        $mc = new MCycle($db);
        $cycle = self::GetRowByID($data, $mc, 0);

        $date = CUtils::GetDateStart($data->GetFieldValue('RUN_DATE'));
        $date = new DateTime($date);

        $getAd = self::getAccountDocByCycle($cycle, $date);

        $ad = new MAccountDoc($db);

        $pkg = [];

        [$p, $company] = CompanyCommProfile::GetCompanyCommProfileInfo($db, $param, new CTable(''));

        foreach ($commissionBatItem as $item) {
            $t = $getAd;
            $employeeID = $item->GetFieldValue('EMPLOYEE_ID');

            $t->SetFieldValue('EMPLOYEE_ID', $employeeID);

            [$cnt, $rows] = $ad->Query(1,$t);

            $litsInfo = [];

            foreach ($rows as $row) {
                [$p, $info] = AccountDocument::GetAccountDocInfo($db, $param, $row);

                $x = new CTable('');

                $litsInfo = $info->GetChildArray('ACCOUNT_DOC_ITEM');

                $x->AddChildArray('BILL_ITEM', $litsInfo);


                $calcObj = new CTable('');
                $calcObj->AddChildArray('BILL_LIST', [$x]);
                $calcObj->AddChildArray('COMMISSION_LIST', $company->GetChildArray('COMPANY_COMM_PROFILE_ITEM'));

                [$p, $commission] = CalculateCommission::CalculateBillCommission($db, $param, $calcObj);

                $commissionList = $commission->GetChildArray('BILL_LIST');
                $commissionItem = $commissionList[0]->GetChildArray('BILL_ITEM');

                $com = 0;
                foreach ($commissionItem as $i) {
                    $fCom = (float)$i->GetFieldValue('COMMISION');
                    $com += $fCom;

                }
                if ($isCreateCommission) {
                    $accountDocID = $row->GetFieldValue('ACCOUNT_DOC_ID');
                    $commissionBatID = $data->GetFieldValue('COMMISSION_BATCH_ID');

                    $comdata = new CTable('');
                    $comdata->SetFieldValue('COMMISSION_BATCH_ID', $commissionBatID);
                    $comdata->SetFieldValue('EMPLOYEE_ID', $employeeID);
                    $comdata->SetFieldValue('ACCOUNT_DOC_ID', $accountDocID);
                    $comdata->SetFieldValue('COMMISSION_AMT', $com);

                    self::createOrUpdateCommission($db, $comdata);
                }
            }


            $item->SetFieldValue('TOTAL_COMMISSION_AMT',$com);
            // $item->AddChildArray('COMMISSION_INPUT',[$calcObj]);
            // $item->AddChildArray('COMMISSION_ITEM', $commissionItem);

            $pkg[] = $item;
        }
        $data->AddChildArray('COMMISSION_BATCH_ITEM', $pkg);

        return([$param, $data]);
    }

    private static function createOrUpdateCommission($db, $data)
    {
        // CSql::SetDumpSQL(true);
        $m = new MCommission($db);
        $obj = self::GetFirstRow($data, $m, 0, '');

        // var_dump($data);

        if (empty($obj)) {
            self::CreateData($db, $data, $m, 0, []);
        } else {
            self::UpdateData($db, $obj, $m, 0, []);
        }


        return $data;
    }

    private static function deleteCommission($db, $data)
    {
        $m = new MCommission($db);
        self::DeleteData($db, $data, $m, 1, []);

        return $data;
    }

    public static function ApproveCommissionBatch($db, $param, $data)
    {
        $db->beginTransaction();
        $id = $data->GetFieldValue('COMMISSION_BATCH_ID');
        $data->SetFieldValue('BATCH_STATUS', self::APROVED);
        $data->setFieldValue('APPROVED_DATE', CUtils::GetCurrentDateTimeInternal());
        $data->setFieldValue('APPROVED_SEQ', CSql::GetSeq($db, 'COMMISSION_BATCH_APPROVED_SEQ', 1));

        $u = new MCommissionBatch($db);

        $childs = self::initSqlConfig($db);

        if (!empty($id)) {
            $obj = self::GetRowByID($data, $u, 1);

            if ($obj->GetFieldValue('BATCH_STATUS') == self::APROVED) {
                throw new Exception("This document has been approved and not allowed to delete");
            }

            self::UpdateData($db, $data, $u, 2, $childs);
        } else {
            self::UpdateData($db, $data, $u, 0, $childs);
        }

        self::ProcessCommissionBatch($db, $param, $data, true);

        $db->commit();

        return([$param, $data]);
    }
}

?>