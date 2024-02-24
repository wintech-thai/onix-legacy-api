<?php
/* 
    Purpose : Controller for Cash Document
    Created By : Seubpong Monsar
    Created Date : 09/18/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CHelper extends CBaseController
{
    public static function IsApprovedDocNumberRequired($db, $data)
    {
        $docType = $data->getFieldValue("DOCUMENT_TYPE");
        $map = [
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_CASH => 0,
            AccountDocument::ACCOUNT_DOC_INVOICE_BY_DEBT => 0,
            AccountDocument::ACCOUNT_DOC_RECEIPT => 1,
        ];

        if (!array_key_exists($docType, $map))
        {
            return(false);
        }

        $idx = $map[$docType];

        $g = new CTable('');
        $g->SetFieldValue('VARIABLE_NAME', 'APPROVED_DOC_NUMBER_STRING');
        list($p, $gv) = GlobalVariable::GetSingleGlobalVariableInfo($db, new CTable(''), $g);

        $hopper = $gv->GetFieldValue('VARIABLE_VALUE'); 
        $flag = substr($hopper, $idx, 1);
        
        return($flag == 'Y');
    }
    
    public static function OverrideOrderBy($model, $ind, $data, $orderByCfg)
    {
        //Use this map, we don't want user/hacker to pass string directory to our query
        $orderMap = ['ASC' => 'ASC', 'DESC' => 'DESC'];

        $arr = $data->GetChildArray('@ORDER_BY_COLUMNS');
        if (count($arr) <= 0)
        {
            return;
        }

        $tmp = "";
        $clause = [];

        foreach ($arr as $o)
        {
            $colKey = $o->GetFieldValue('COLUMN_KEY');
            $orderBy = $o->GetFieldValue('ORDER_BY');

            $column = $orderByCfg[$colKey];
            $order = $orderMap[$orderBy];

            array_push($clause, "$column $order");
        }

        $str = 'ORDER BY ' . implode(', ', $clause);

        $model->OverideOrderBy($ind, $str);

        return;
    }
    
    private static function createVariable($name, $value)
    {
        $var = new CTable('');
        $var->setFieldValue('VARIABLE_NAME', $name);    
        $var->setFieldValue('VARIABLE_VALUE', $value);

        return($var);
    }

    public static function PopulateCustomVariables($data, $docNumber, $nmSpace)
    {
//CSql::SetDumpSQL(true);           
//CLog::WriteLn("DEBUG0 nmSpace=[$nmSpace]");           
        if ($nmSpace == 1)
        {
            //Account Doc
            $docDate = $data->getFieldValue('DOCUMENT_DATE');
            $yy = substr($docDate, 2, 2);
            $mm = substr($docDate, 5, 2);
            $yyyy = substr($docDate, 0, 4);

//CLog::WriteLn("DEBUG1 docDate=[$docDate], yy=[$yy], mm=[$mm]");           

            $var1 = self::createVariable('${ud_2m2y}', "$mm$yy");
            $var2 = self::createVariable('${ud_4y-2m}', "$yyyy-$mm");
            $var3 = self::createVariable('${ud_2y2m}', "$yy$mm");
            $custom_vars = [$var1, $var2, $var3];
            
            $docNumber->addChildArray('CUSTOM_VARIABLES', $custom_vars);
        }
    }

    public static function ValidateDateBoundary($db, $param, $data, $dtFld)
    {
        $docNo = $data->GetFieldValue('DOCUMENT_NO');
        $docDate = CUtils::GetDateStart($data->GetFieldValue("$dtFld"));
        $curDate = CUtils::GetCurrentDateTimeInternal();
        
        $g = new CTable('');
        $g->SetFieldValue('VARIABLE_NAME', 'MAX_OVERHEAD_APPROVE_DATE');
        list($p, $gv) = GlobalVariable::GetSingleGlobalVariableInfo($db, $param, $g);

        $gap = $gv->GetFieldValue('VARIABLE_VALUE');
        
        $cmpDate = CUtils::GetDateStart(CUtils::DateAdd($curDate, $gap));

        if (strcmp($docDate, $cmpDate) <= 0)
        {
            return(true);
        }

        $errors = [];
        
        $e = new CTable("ERROR");
        $e->SetFieldValue("ERROR_DESC", "ERROR_INVALID_DATE|$docNo|$docDate|$gap|$cmpDate");
        array_push($errors, $e);
        
        $data->AddChildArray('ERROR_ITEM', $errors);

        return(false);
    }

    public static function PopulateDocumentDateKey($data, $dateField)
    {
        $docDate = $data->GetFieldValue($dateField);

        $mm = substr($docDate, 5, 2);
        $yyyy = substr($docDate, 0, 4);

        $data->SetFieldValue('DOCUMENT_YYYYMM', "$yyyy$mm");
        $data->SetFieldValue('DOCUMENT_YYYY', "$yyyy");

        return;
    } 

    public static function PopulateBalanceDate($db, $balDoc, $data, $dateField)
    {
        $docDate = $data->GetFieldValue($dateField);
        $curDate = CUtils::GetCurrentDateTimeInternal();
        
        $g = new CTable('');
        $g->SetFieldValue('VARIABLE_NAME', 'BALANCE_DATE_BY_CURRENT_DATE');
        list($p, $gv) = GlobalVariable::GetSingleGlobalVariableInfo($db, new CTable(''), $g);

        $flag = $gv->GetFieldValue('VARIABLE_VALUE');
        
        if ($flag == 'Y')
        {
            $balDoc->SetFieldValue('BAL_DOC_DATE', $curDate);
            $balDoc->SetFieldValue('ACTUAL_DOC_DATE', $docDate);
        }
        else
        {
            $balDoc->SetFieldValue('BAL_DOC_DATE', $docDate);
            $balDoc->SetFieldValue('ACTUAL_DOC_DATE', '');
        }

        return;
    }    

    public static function PopulateFromToDate($data, $fld, $fromField, $endField)
    {
        $dtm = $data->getFieldValue($fld);

        if ($dtm == '')
        {
            return;
        }

        list($s, $e) = CUtils::DateStartEOM($dtm);

        $data->setFieldValue($fromField, $s);
        $data->setFieldValue($endField, $e);
    }

    public static function CreateSetFromArray($data, $arrName, $field)
    {
        $arr = $data->getChildArray($arrName);
        $temps = [];
        foreach ($arr as $t)
        {
            $flag = $t->getFieldValue('EXT_FLAG');
            if ($flag == 'D')
            {
                continue;
            }

            $value = $t->getFieldValue("$field");
            array_push($temps, $value);
        }

        $clause = implode(', ', $temps);

        $set = '';
        if ($clause != '')
        {
            $set = "($clause)";
        }

        return($set);
    }   

    public static function RowToHash($rows, $fields, $delim)
    {
        $arr = array();
        foreach ($rows as $r)
        {
            $values = array();
            foreach ($fields as $f)
            {
                $value = $r->GetFieldValue($f);  
                array_push($values, $value);  
            }

            $key = join($delim, $values);
            $arr[$key] = $r;
        }

        return($arr);
    }

    public static function SuppressField($rows, $fieldName, $fieldValue)
    {
        foreach ($rows as $r)
        {
            $r->setFieldValue($fieldName, $fieldValue);
        }
    }

    public static function AddWildCardSearch($data, $fieldName)
    {
        $value = $data->getFieldValue($fieldName);
        if ($value != '')
        {
            //Wildcard search by default
            $data->setFieldValue($fieldName, '%' . $value);
        }
    }

    public static function CreateDistinctRow($rows, $idField, $fields)
    {
        $tmpHash = [];        
        $arr = array();

        foreach ($rows as $r)
        {
            $id = $r->getFieldValue($idField);
            if (array_key_exists($id, $tmpHash))
            {
                continue;                
            }

            $tmpHash[$id] = $id;
            
            $obj = new CTable('');
            $obj->setFieldValue($idField, $id);

            foreach ($fields as $f)
            {
                $value = $r->GetFieldValue($f);  
                $obj->setFieldValue($f, $value);
            }

            array_push($arr, $obj);
        }

        return($arr);
    }

    public static function ImportCustomerCSV($db, $param, $data)
    {
        $fname = $data->GetFieldValue('FILE_NAME');

        $fh = fopen($fname, 'r');
        if (!$fh)
        {
            throw new Exception("Unable to open file [$fname]!!!!");
        }

        $db->beginTransaction();
        
        while ($row = fgets($fh))
        {
            $fields = explode(',', $row);

            $cd = sprintf('%04d', trim($fields[0]));
            $nm = trim($fields[1]);
            $addr = trim($fields[2]);
            $phone = trim($fields[3]);
printf("%s|%s|%s|%s\n", $cd, $nm, $addr, $phone);
            $cust = new CTable("");
            $cust->SetFieldValue('ENTITY_CODE', $cd);
            $cust->SetFieldValue('ENTITY_NAME', $nm);
            $cust->SetFieldValue('ADDRESS', $addr);
            $cust->SetFieldValue('PHONE', $phone);
            $cust->SetFieldValue('EMAIL', '-');
            $cust->SetFieldValue('WEBSITE', '-');
            $cust->SetFieldValue('FAX', '-');
            $cust->SetFieldValue('CREDIT_TERM', "0");
            $cust->SetFieldValue('CREDIT_LIMIT', "0");
            $cust->SetFieldValue('CATEGORY', "1");
            $cust->SetFieldValue('ENTITY_TYPE', "1");
            $cust->SetFieldValue('ENTITY_GROUP', "3");

            CustomerSupplier::CreateEntity($db, $param, $cust);
        }

        fclose($fh);

        $db->commit();

        return([$param, $data]);
    }

}

?>