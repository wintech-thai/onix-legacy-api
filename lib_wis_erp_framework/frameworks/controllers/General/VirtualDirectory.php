<?php
/* 
    Purpose : Controller for Virtual Directory
    Created By : Seubpong Monsar
    Created Date : 12/23/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class VirtualDirectory extends CBaseController
{
    private static $cfg = NULL;

    private static function initSqlConfig($db)
    {
        $config = [];

        self::$cfg = $config;

        return($config);
    }

    public static function GetVirtualDirectoryList($db, $param, $data)
    {
        $parentID = $data->getFieldValue("PARENT_DIRECTORY_ID");
        
        $data->setFieldValue("IS_NULL_PARENT", '');
        if ($parentID == '')
        {
            //Top lavel directory
            $data->setFieldValue("IS_NULL_PARENT", 'Y');
        }

        $u = new MVirtualDirectory($db);
        list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

        $pkg = new CTable($u->GetTableName());
        self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'DIRECTORY_LIST', $rows);
        
        return(array($param, $pkg));
    }

    public static function GetVirtualPathList($db, $param, $data)
    {
        $u = new MVirtualDirectory($db);
        list($cnt, $rows) = $u->Query(0, $data);

        $obj = new CTable('');        
        $paths = self::getVirtualPath($obj, $rows, '');
        
        foreach ($paths as $p)
        {
            $pt = $p->GetFieldValue('PATH'); 
            $flds = explode('/', $pt);
            $cnt = count($flds);
            if ($cnt > 0)
            {
                $p->SetFieldValue('PARENT_NAME', $flds[$cnt-1]);
            }
        }

        $obj->AddChildArray("DIRECTORY_LIST", $paths);

        return(array($param, $obj));        
    }      

    private static function getVirtualPath($obj, $arr, $path)
    {
        $tot_arr = array();
    
        $cnt = 0;
        foreach ($arr as $tp)
        {
            $id = $tp->GetFieldValue("DIRECTORY_ID");
            $pid = $tp->GetFieldValue("PARENT_DIRECTORY_ID");
            $name = $tp->GetFieldValue("DIRECTORY_NAME");
    
            if ($obj->GetFieldValue("DIRECTORY_ID") == $pid)
            {
                $curr_path = "$path/$name";
                $cnt++;

                $path_arr = self::getVirtualPath($tp, $arr, $curr_path);
                $tot_arr = array_merge($tot_arr, $path_arr);
            }
        }
    
        if ($cnt == 0)
        {
            $obj->SetFieldValue("PATH", $path);
            $obj->SetFieldValue("DIRECTORY_NAME", basename($path));
            array_push($tot_arr, $obj);
        }
    
        return($tot_arr);
    }   

    public static function GetVirtualDirectoryInfo($db, $param, $data)
    {
        //self::SetDumpSQL(true);
        $cfg = self::initSqlConfig($db);

        $u = new MVirtualDirectory($db);
        $obj = self::GetRowByID($data, $u, 0);

        if (!isset($obj))
        {
            throw new Exception("No virtual directory in database!!!");
        }

        self::PopulateChildItems($obj, $u, $cfg);

        return(array($param, $obj));        
    }

    public static function IsVirtualDirectoryExist($db, $param, $data)
    {
        $u = new MVirtualDirectory($db);
        $o = self::ValidateForDuplicate($db, $data, $u, "DIRECTORY_NAME", "DIRECTORY_NAME", 0);
        
        return(array($param, $o));        
    }

    public static function CreateVirtualDirectory($db, $param, $data)
    {
        $u = new MVirtualDirectory($db);

        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }    

    public static function UpdateVirtualDirectory($db, $param, $data)
    {
        $u = new MVirtualDirectory($db);

        $childs = self::initSqlConfig($db);
        self::UpdateData($db, $data, $u, 0, $childs);

        return(array($param, $data));        
    }      

    public static function DeleteVirtualDirectory($db, $param, $data)
    {
        $u = new MVirtualDirectory($db);
        
        $childs = self::initSqlConfig($db);
        self::DeleteData($db, $data, $u, 0, $childs);
        
        return(array($param, $data));        
    }     
}

?>