<?php
/* 
    Created By : Seubpong Monsar
    Created Date : 09/02/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

class CTable
{
    private $tbName = "";
    private $fieldNames = array();
    private $hashFields = array();
    private $childHash = array();

    /* Begin Private functions */
    
    private function addOrUpdateField($fldName, $fldValue)
    {
        $arrlength = count($this->fieldNames);       
        $found = false;
        $idx = 0;

        for ($x = 0; $x < $arrlength; $x++) 
        {
            $nm = $this->fieldNames[$x];
            if ($fldName == $nm) 
            {
                $idx = $x;
                $found = true;
                break;
            }   
        }

        if (!$found)
        {
            array_push($this->fieldNames, $fldName);                    
        }

        $this->hashFields[$fldName] = $fldValue;
    }

    /* End Private functions */

    function __construct($tableName) 
    {
        $this->tbName = $tableName;
    }

    function SetFieldValue($fldName, $fldValue)
    {
        $this->addOrUpdateField($fldName, $fldValue);
    }

    function GetFieldValue($fldName)
    {
        if (array_key_exists($fldName, $this->hashFields))
        {
            $value = $this->hashFields[$fldName];
        }
        else
        {
            $value = "";
        }

        return($value);
    }

    function AddChildArray($arrName, $childArr)
    {
        $this->childHash[$arrName] = $childArr;
    }

    function GetChildArray($arrName)
    {
        if (array_key_exists($arrName, $this->childHash))
        {
            $arr = $this->childHash[$arrName];
        }
        else
        {
            $arr = array();
        }

        return($arr);
    }

    function GetNameArray()
    {
        return($this->fieldNames);
    }

    function GetChildHash()
    {
        return($this->childHash);
    }
    
    function ToXMLElement()
    {

    }

    function GetTableName()
    {
        return($this->tbName);
    }
    
    function SetTableName($tbName)
    {
        $this->tbName = $tbName;
    }    

    function DumpFields()
    {
        $arrlength = count($this->fieldNames);       
        printf("Table name [%s]\n", $this->tbName);

        for ($x = 0; $x < $arrlength; $x++) 
        {
            $nm = $this->fieldNames[$x];
            $value = $this->hashFields[$nm];

            printf("Field=[%s] Value=[%s]\n", $nm, $value);  
        }        
    }
}

?>