<?php
/*
    Purpose : Controller for calculating commission
    Created By : Seubpong Monsar
    Created Date : 09/18/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CalculateCommission extends CBaseController
{
    const LOOK_UP_BY_UNIT_PRICE = 1;
    const LOOK_UP_BY_QUANTITY = 2;
    const PROFILE_BY_ITEM = 1;
    const PROFILE_BY_GROUP = 2;
    const SELECT_BY_SERVICE = 1;
    const SELECT_BY_ITEM = 2;
    const SELECT_BY_CATEGORY = 3;
    const DEFINITION_BY_UNIT_PRICE = 0;
    const DEFINITION_BY_AMOUNT = 1;

    /*
    Input array : COMPANY_COMMISSION_ITEMS
    Input array : BILL_ITEMS
    Output array : COMMISSION_ITEMS
    */
    public static function CalculateBillCommission($db, $param, $data)
    {
        $bills = $data->GetChildArray('BILL_LIST');
        $commissions = $data->GetChildArray('COMMISSION_LIST');

        [ $commissionsByItem, $commissionsByGroup ] = self::separateCommission($commissions);

        $arr = [];

        foreach ($bills as $bill) {
            $items = $bill->GetChildArray('BILL_ITEM');

            [$arr1, $is1] = self::calculateBillCommissionByItem($db, $param, $items, $commissionsByItem);
            [$arr2, $is2] = self::calculateBillCommissionByGroup($db, $param, $is1, $commissionsByGroup);

            $billItem = new CTable('BILL');
            $billItem->AddChildArray("BILL_ITEM", array_merge($arr1, $arr2));

            $arr[] = $billItem;
        }


        $t = new CTable('');
        self::PopulateRow($t, 0, 0, 'BILL_LIST', $arr);


        return([$param, $data]);
    }

    private static function separateCommission($commissions)
    {
        $commissionsByItem = [];
        $commissionsByGroup = [];

        foreach ($commissions as $commission) {
            $profileType = $commission->GetFieldValue('PROFILE_TYPE');

            switch ($profileType) {
                case self::PROFILE_BY_ITEM :
                    $commissionsByItem[] = $commission;
                    break;
                case self::PROFILE_BY_GROUP :
                    $commissionsByGroup[] = $commission;
                    break;

                default:
                    // code...
                    break;
            }

        }

        return [ $commissionsByItem, $commissionsByGroup ];
    }

    private static function calculateBillCommissionByItem($db, $param, $items, $commissions)
    {
        $arr2 = [];

        foreach ($commissions as $commission) {

            $arr3 = [];
            $com = 0;

            $commissionInfo = self::getCommissionProfile($db, $param, $commission);

            $commItem = $commissionInfo->GetChildArray('COMMISSION_DETAIL_ITEM');


            foreach ($items as $key => $item) {
                $com2 = 0;
                foreach ($commItem as $i) {

                    if (self::isMatch($i, $item)) {

                        $definition = self::getDefinition($i->GetFieldValue('COMM_DEFINITION'));

                        $x = isset($definition[3]) ? $definition[3] : null;

                        $lookup = $i->GetFieldValue('LOOKUP_TYPE');

                        $base = self::getBase($i->GetFieldValue('LOOKUP_TYPE'), $item);

                        $quantity = $item->GetFieldValue('QUANTITY');

                        $str = '';

                        if (isset($definition[2])) {
                            $c = $definition[5];
                            switch ($definition[2]) {
                                case 1:
                                // step
                                [$com2, $str] = self::stepCalc($c, $base);

                                break;

                                case 2:
                                // tier
                                [$com2, $str] = self::tierCalc($c, $base, $quantity, $x);
                                break;
                            }
                        }

                        $com += $com2;

                        $item->SetFieldValue('COMMISION', $com2);
                        $item->SetFieldValue('TEST', $str.' = '.$com2);
                        $item->SetFieldValue('DEFINITION', $i->GetFieldValue('COMM_DEFINITION'));

                        $arr3[] = $item;

                        unset($items[$key]);

                    }
                }
            }


            // $commission->AddChildArray('COMMISSION_DETAIL_ITEM', $arr3);
            $commission->SetFieldValue('COMMISION', $com);

            $arr2[] = $commission;
        }

        return [$arr2, $items];
    }

    private static function calculateBillCommissionByGroup($db, $param, $items, $commissions)
    {
        $arr2 = [];

        foreach ($commissions as $commission) {

            $arr3 = [];
            $com = 0;
            $base = 0;
            $quantity = 0;

            $commissionInfo = self::getCommissionProfile($db, $param, $commission);

            $commItem = $commissionInfo->GetChildArray('COMMISSION_DETAIL_ITEM');

            $definition = self::getDefinition($commissionInfo->GetFieldValue('COMMISSION_DEFINITION'));
            $x = $definition[3];

            foreach ($commItem as $i) {
                foreach ($items as $key => $item) {

                    if (self::isMatch($i, $item)) {

                        $lookup = self::LOOK_UP_BY_QUANTITY;

                        $base += (float) $item->GetFieldValue('TOTAL_AMOUNT');

                        $quantity += (float) $item->GetFieldValue('QUANTITY');

                        $arr3[] = $item;

                        unset($items[$key]);
                    }
                }
            }

            $c = $definition[5];

            // tier
            [$com, $str] = self::tierCalc($c, $base, $quantity, self::DEFINITION_BY_AMOUNT);


            // $commission->AddChildArray('COMMISSION_DETAIL_ITEM', $arr3);
            // $commission->SetFieldValue('TEST', $str.' = '.$com);
            $commission->SetFieldValue('COMMISION', $com);

            $arr2[] = $commission;
        }

        return [$arr2, $items];
    }

    private static function getCommissionProfile($db, $param, $commission)
    {
        [$pr, $comm] = CommissionProfile::GetCommissionProfileInfo($db, $param, $commission);

        return $comm;
    }

    private static function getBase($type, $item)
    {
        switch ($type) {
            // by unit price
            case self::LOOK_UP_BY_UNIT_PRICE:
                return $item->GetFieldValue('UNIT_PRICE');

            // by quantity
            case self::LOOK_UP_BY_QUANTITY:
                return $item->GetFieldValue('QUANTITY');

            default :
                return 0;
        }
    }

    private static function getValue($type, $item)
    {
        switch ($type) {
            // unit price
            case self::DEFINITION_BY_UNIT_PRICE :
                return $item->GetFieldValue('UNIT_PRICE');

            // amount
            case self::DEFINITION_BY_AMOUNT :
                return $item->GetFieldValue('TOTAL_AMOUNT') || $item->GetFieldValue('TOTAL_AMT');

            default :
                return 0;
        }
    }

    private static function stepCalc($c, $base)
    {
        $comm = 0;
        $str = [];
        $x = 0;

        foreach ($c as $v) {
            $from = $v[0];
            $to = $v[1];
            $value = $v[2];

            $m = $base - $from;
            $n = $to - $from;

            $x += $n;

            if ($m > 0) {
                if ($m < $n) {
                    $comm += $value*$m;
                    $str[] = '('.$value.' X '.$m.')';
                } else {
                    $comm += $value*$n;
                    $str[] = '('.$value.' X '.$n.')';
                }
            }
        }

        if ($base - $x > 0) {
            [$rcom, $rstr] = self::stepCalc($c, ($base - $x));
            $comm += $rcom;
            $str[] = $rstr;
        }

        $str = implode(' + ', $str);

        return [$comm, $str];
    }

    private static function tierCalc($c, $base, $quantity, $type)
    {
        $comm = 0;
        $str = [];
        switch ($type) {
            case self::DEFINITION_BY_UNIT_PRICE :
                foreach ($c as $v) {
                    // var_dump("$base > $v[0] && $base <= $v[1] : $v[2]");
                    // var_dump($base > $v[0] && $base <= $v[1]);
                    // var_dump('-----------------------------------');
                    if ($base > $v[0] && $base <= $v[1]) {
                        $comm = $v[2]*$quantity;
                        $str[] = '('.$v[2].' X '.$quantity.')';
                    }
                }
                break;

            case self::DEFINITION_BY_AMOUNT :
                foreach ($c as $v) {
                    // var_dump("$base > $v[0] && $base <= $v[1] : $v[2]");
                    // var_dump($base > $v[0] && $base <= $v[1]);
                    // var_dump('-----------------------------------');
                    if ($base > $v[0] && $base <= $v[1]) {
                        $comm = $v[2];
                        $str[] = '('.$v[2].')';
                    }
                }
                break;
        }


        $str = implode(' + ', $str);

        return [$comm, $str];
    }

    private static function isMatch($i, $item)
    {
        $x = false;
        switch ($item->GetFieldValue('SELECTION_TYPE')) {
            case 1:
                $x = $i->GetFieldValue('SERVICE_ID') == $item->GetFieldValue('SERVICE_ID');
                break;

            case 2:
                $x = $i->GetFieldValue('ITEM_ID') == $item->GetFieldValue('ITEM_ID');
                break;

            case 3:
                $category = empty($item->GetFieldValue('ITEM_CATERGORY_ID')) ? $item->GetFieldValue('ITEM_CATERGORY') : $item->GetFieldValue('ITEM_CATERGORY_ID');
                $x = $i->GetFieldValue('ITEM_CATEGORY') == $category;
                break;

        }

        return $x;
    }

    private function getDefinition($definition)
    {
        $arr = explode('|', $definition);

        if (isset($arr[5])) {
            $a= [];
            $rows = explode(';', $arr[5]);
            foreach ($rows as $row) {
                if ($row) {
                    $a[] = explode(':', $row);
                }
            }
            $arr[5] = $a;
        }
        return $arr;
    }
}

?>