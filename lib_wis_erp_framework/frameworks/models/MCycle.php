<?php
/* 
Purpose : Model for CYCLE
Created By : Seubpong Monsar
Created Date : 09/18/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCycle extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                   'CYCLE_ID:SPK:CYCLE_ID:Y', 
                   'CYCLE_CODE:S:CYCLE_CODE:Y', 
                   'DESCRIPTION:S:DESCRIPTION:Y', 
                   'CYCLE_TYPE:NZ:CYCLE_TYPE:Y',
                   'DAY_OF_MONTH:NZ:DAY_OF_MONTH:Y',
                   'DAY_OF_WEEK:NZ:DAY_OF_WEEK:Y',

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ]                                                                                                               
              
    );

    private $froms = array(
                               
                'FROM CYCLE '
            
    );

    private $orderby = array(

                'ORDER BY CYCLE_ID DESC '
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'CYCLE', 'CYCLE_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>