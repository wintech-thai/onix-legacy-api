<?php
/* 
    Purpose : One another abstract layer for PDO
    Created By : Seubpong Monsar
    Created Date : 09/03/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

class COnixPDO extends PDO
{
    private $autoCommit = false;

    function __construct($dsn, $user, $password) 
    {
        parent::__construct($dsn, $user, $password);
    }

    public function IsAPIAutoCommit()
    {
        return($this->autoCommit);
    }

    public function SetAPIAutoCommit($comm)
    {
        $this->autoCommit = $comm;
    }
}

?>