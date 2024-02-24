<?php
/* 
Purpose : Model for MASTER_REF
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MMasterRef extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                   'MASTER_ID:SPK:MASTER_ID:Y', 
                   'CODE:S:CODE:Y', 
                   'DESCRIPTION:S:DESCRIPTION:Y', 
                   'DESCRIPTION_ENG:S:DESCRIPTION_ENG:Y', 
                   'OPTIONAL:S:OPTIONAL:N', 
                   'OPTIONAL_ENG:S:OPTIONAL_ENG:N', 
                   'REF_TYPE:REFID:REF_TYPE:Y',

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ]                                                                                                               
              
    );

    private $froms = array(
                               
                'FROM MASTER_REF '
            
    );

    private $orderby = array(

                'ORDER BY REF_TYPE ASC, DESCRIPTION ASC '
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'MASTER_REF', 'MASTER_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>