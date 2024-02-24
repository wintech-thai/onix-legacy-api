<?php
/* 
    Purpose : Base class for model
    Created By : Seubpong Monsar
    Created Date : 09/04/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class MBaseModel
{
    private $tbName = "";
    private $dbConnection = NULL;
    private $primaryKey = "";
    private $cols = array();
    private $froms = array();
    private $orders = array();
    private $autoseq = false;
    private $lastSQL = '';

    function __construct($db, $tableName, $pkName, $colDefs, $fromDefs, $orderDefs) 
    {
        $this->tbName = $tableName;
        $this->dbConnection = $db;
        $this->primaryKey = $pkName;
        $this->cols = $colDefs;
        $this->froms = $fromDefs;
        $this->orders = $orderDefs;
    }

    /* Private functions here */

    /* End private function */

    public function GetTableName()
    {
        return($this->tbName);
    } 

    public function GetPKName()
    {
        return($this->primaryKey);
    }   
    
    public function SetAutoSequence($flag)
    {
        $this->autoseq = $flag;
    }

    public function GetAutoSequence()
    {
        return($this->autoseq);
    }    

    public function Query($ind, $data)
    {
        $arr = $this->cols[$ind];
        $collist = CSql::CreateSelectColumnList($arr);
        $wherelst = CSql::CreateSelectWhereList($data, $arr);

        $froms = $this->froms;
        $orders = $this->orders;

        $from_list = $froms[$ind];
        $order_list = $orders[$ind];
        
        $stmt = "SELECT $collist $from_list $wherelst $order_list";

        list($cnt, $rows) = CSql::QuerySQL($this->dbConnection, $stmt, $arr, -1, -1, "");
     
        //foreach ($rows as $r)
        //{
        //    printf("[%s]\n", $r->getFieldValue("VERSION"));              
        //}

        return(array($cnt, $rows));
    }

    public function GetLastSQL()
    {
        return(CSql::GetLastSQL());
    }

    public function QueryChunk($ind, $data)
    {
        list($rc, $item_cnt, $chunk_cnt, $result) = CSql::QueryChunk($this->dbConnection, $ind, $data, 
            $this->cols, $this->froms, $this->orders);
                 
        return(array($rc, $item_cnt, $chunk_cnt, $result));        
    }

    public function Insert($ind, $data, $autoSeq)
    {
        #No auto key generate for primary key
        $rv = CSql::Insert($this->dbConnection, $ind, $data, 
            $this->cols, $this->tbName, $this->primaryKey, $autoSeq);            
        
        return($rv);          
    }

    public function Delete($ind, $data)
    {
        $rv = CSql::Delete($this->dbConnection, $ind, $data, $this->cols, $this->tbName);          
        return($rv);            
    }

    public function Update($ind, $data)
    {
        $rv = CSql::Update($this->dbConnection, $ind, $data, $this->cols, $this->tbName);    
        return($rv);
    }    

    public function GetColumnDef($ind)
    {        
        $arr = $this->cols[$ind];
        return($arr);
    }      

    public function GetColumnDefs()
    {        
        return($this->cols);
    }     
    
    public function GetFromDefs()
    {        
        return($this->froms);
    }    
    
    public function GetOrderDefs()
    {        
        return($this->orders);
    } 

    public function AddColumnDef($ind, $coldef)
    { 
        array_push($this->cols[$ind], $coldef);       
    }
    
    public function OverideOrderBy($ind, $order)
    {        
        $this->orders[$ind] = $order;         
    }     
}

?>