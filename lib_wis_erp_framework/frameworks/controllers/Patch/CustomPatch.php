<?php
/* 
    Purpose : Controller for POS
    Created By : Seubpong Monsar
    Created Date : 09/08/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class CustomPatch extends CBaseController
{
    private static function addColumnDefs($arr, $model, $ind)
    {
        foreach ($arr as $def)
        {
            $model->AddColumnDef($ind, $def);
        }
    }

    public static function Patch001($db)
    {
        return(true);
    } 

    public static function Patch002($db)
    {
        return(true);
    } 

    public static function PatchRegisterItemAndOwnerTest($db)
    {
        //No need now
        return(true);
    } 

    public static function PatchRegisterItemAndOwnerInventory($db)
    {
        //No need now 
        return(true);
    }
    
    public static function PatchRegisterItemAndOwnerCash($db)
    {
        //No need now         
        return(true);
    }    

    public static function PatchRegisterItemAndOwnerAR($db)
    {
        //No need now         
        return(true);
    } 
    
    public static function PatchCreateMissingSequences($db)
    {
        //No need now         
        return(true);
    }

    public static function PatchMigrateDocumentNumber($db)
    {
        $param = new CTable('');
        $dat = new CTable('');

        $m = new MDocumentNumber($db);

        list($cnt, $rows) = $m->Query(0, $dat);
        foreach ($rows as $row)
        {
            NumberGenerator::CreateDocumentNumber($db, $param, $row);
        }

//throw new Exception("Manually Throw");

        return(true);
    }

    public static function PatchPopulateEntityAddress($db)
    {
        //No need now   
        return(true);
    }  
    
    public static function PatchCreateSaleDocumentNumberNV($db)
    {
        $docs = [
            'ACCOUNT_DOC_CASH_TEMP_NV',
            'ACCOUNT_DOC_CASH_APPROVED_NV',
            'ACCOUNT_DOC_DEPT_TEMP_NV',
            'ACCOUNT_DOC_DEPT_APPROVED_NV',
            'ACCOUNT_DOC_CR_TEMP_NV',
            'ACCOUNT_DOC_CR_APPROVED_NV',
            'ACCOUNT_DOC_DR_TEMP_NV',
            'ACCOUNT_DOC_DR_APPROVED_NV'
        ];

        $docArr = [
            'ACCOUNT_DOC_CASH_TEMP_NV'     => ['2017', '11', 'สด*-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'], 
            'ACCOUNT_DOC_CASH_APPROVED_NV' => ['2017', '11', 'สด-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DEPT_TEMP_NV'     => ['2017', '11', 'เชื่อ*-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'], 
            'ACCOUNT_DOC_DEPT_APPROVED_NV' => ['2017', '11', 'เชื่อ-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_CR_TEMP_NV'       => ['2017', '11', 'cr*-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'], 
            'ACCOUNT_DOC_CR_APPROVED_NV'   => ['2017', '11', 'cr-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'],
            'ACCOUNT_DOC_DR_TEMP_NV'       => ['2017', '11', 'dr*-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0'], 
            'ACCOUNT_DOC_DR_APPROVED_NV'   => ['2017', '11', 'dr-${yyyy}-${seq}-nv', '2', '0', '1', '5', '0']
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

//throw new Exception("Manually Throw");

        return(true);        
    }
}

?>