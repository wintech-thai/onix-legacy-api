<?php
/* 
    Purpose : Controller for item
    Created By : Seubpong Monsar
    Created Date : 09/08/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class ItemCategory extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config =
        [
            //Array name, model, query ind, insert/update ind, delete ind
        ];

        self::$cfg = $config;

        return($config);
    }

    public static function GetItemCategoryList($db, $param, $data)
    {
        $u = new MItemCategory($db);
        list($cnt1, $rows) = $u->Query(0, $data);

        $it = new MItem($db);
        list($cnt2, $crows) = $it->Query(3, $data);

        $item = new CTable($u->GetTableName());

        $hs = CUtils::RowToHash($crows, 'ITEM_CATEGORY');
        self::updateCategoryProperty($rows, $hs, "ITEM_CATEGORY_ID", "ITEM_COUNT", "CHILD_COUNT");

        $item->AddChildArray("ITEM_CATEGORY_LIST", $rows);
        
        return(array($param, $item));        
    }
/*
    protected static function RowToHash($rows, $keyField)
    {
        $arr = array();
        foreach ($rows as $r)
        {
            $key = $r->GetFieldValue($keyField);
            $arr[$key] = $r;
        }

        return($arr);
    }
*/
    private static function updateCategoryProperty($result, $hash, $key_name, $fld_name, $child_cnt_fld)
    {
//print_r($hash);        
        foreach ($result as $h)
        {
            $key = $h->GetFieldValue($key_name);
            $t = NULL;
            if (array_key_exists($key, $hash))
            {
                $t = $hash[$key];
            }

            $value = 0;
            if (isset($t))
            {
                $value = $t->GetFieldValue($fld_name);
            }
    
            $h->SetFieldValue($fld_name, $value);
    
            $child_cnt = self::countChildNode($result, $key);
            $h->SetFieldValue($child_cnt_fld, $child_cnt);
        }
    }
    
    private static function countChildNode($result, $id)
    {
        $cnt = 0;
        foreach ($result as $o)
        {
            $pid = $o->GetFieldValue('PARENT_ID');
            if ($id == $pid)
            {
                $cnt++;
            }
        }
    
        return($cnt);
    }    

    public static function GetItemCategoryInfo($db, $param, $data)
    {
        $cfg = self::initSqlConfig($db);

        $u = new MItemCategory($db);
        $obj = self::GetRowByID($data, $u, 0);
        //printf("[%s]\n", CSql::GetLastSQL());

        if (!isset($obj))
        {
            throw new Exception("No this category in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function CreateItemCategory($db, $param, $data)
    {
        $u = new MItemCategory($db);

        $childs = self::initSqlConfig($db);

        $db->beginTransaction();
        
        $data->SetFieldValue("ITEM_COUNT", "0");
        self::CreateData($db, $data, $u, 0, $childs);  

        $id = $data->GetFieldValue($u->GetPKName());
        $pid = $data->GetFieldValue("PARENT_ID");
    
        if ($id == $pid)
        {
            //Hacker might manually set ID = Parent ID
            $db->rollBack();
            throw new Exception("Parent ID must not be equal ID!!!");
        }

        $db->commit();

        return(array($param, $data));        
    } 

    public static function UpdateItemCategory($db, $param, $data)
    {
        $u = new MItemCategory($db);
       
        $id = $data->GetFieldValue($u->GetPKName());
        $pid = $data->GetFieldValue("PARENT_ID");
        if ($id == $pid)
        {
            //Hacker might manually set ID = Parent ID
            throw new Exception("Parent ID must not be equal ID!!!");
        }

        $childs = self::initSqlConfig($db);        
        self::UpdateData($db, $data, $u, 0, $childs);  
                            
        return(array($param, $data));        
    } 

    public static function DeleteItemCategory($db, $param, $data)
    {
        $u = new MItemCategory($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    } 

    public static function IsItemCategoryExist($db, $param, $data)
    {
        $u = new MItemCategory($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "CATEGORY_NAME", "CATEGORY_NAME", 0);
        
        return(array($param, $o));        
    }         

    public static function GetItemCategoryPathList($db, $param, $data)
    {
        $u = new MItemCategory($db);
        list($cnt, $rows) = $u->Query(0, $data);

        $obj = new CTable($u->GetTableName());        
        $paths = self::getCategoryPath($obj, $rows, '');
        
        foreach ($paths as $p)
        {
            $pt = $p->GetFieldValue('PATH'); 
            $flds = explode('/', $pt);
            $cnt = count($flds);
            if ($cnt > 0)
            {
                $p->SetFieldValue('CATEGORY_NAME', $flds[$cnt-1]);
            }
        }

        $obj->AddChildArray("ITEM_CATEGORY_LIST", $paths);
//print_r($paths);
        return(array($param, $obj));        
    }      

    private static function getCategoryPath($obj, $arr, $path)
    {
        $tot_arr = array();
    
        $cnt = 0;
        foreach ($arr as $tp)
        {
            $id = $tp->GetFieldValue("ITEM_CATEGORY_ID");
            $pid = $tp->GetFieldValue("PARENT_ID");
            $name = $tp->GetFieldValue("CATEGORY_NAME");
    
            if ($obj->GetFieldValue("ITEM_CATEGORY_ID") == $pid)
            {
                $curr_path = "$path/$name";
                $cnt++;

                $path_arr = self::getCategoryPath($tp, $arr, $curr_path);
                $tot_arr = array_merge($tot_arr, $path_arr);
            }
        }
    
        if ($cnt == 0)
        {
            $obj->SetFieldValue("PATH", $path);
            $obj->SetFieldValue("CATEGORY_NAME", basename($path));
            array_push($tot_arr, $obj);
        }
    
        return($tot_arr);
    }    
   
}

?>