<?php
/* 
    Purpose : Clase to represent the table that not really present in database.
    Created By : Seubpong Monsar
    Created Date : 09/11/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

class MVirtualModel
{
    private $primaryKey = "";

    function __construct($pkName) 
    {
        $this->primaryKey = $pkName;
    }

    /* Private functions here */

    /* End private function */

    public function GetTableName()
    {
        return('');
    } 

    public function GetPKName()
    {
        return($this->primaryKey);
    }   

    public function Insert($ind, $data, $autoSeq)
    {
        return(1);          
    }

    public function Delete($ind, $data)
    {         
        return(1);            
    }

    public function Update($ind, $data)
    {     
        return(1);             
    }    
}

?>