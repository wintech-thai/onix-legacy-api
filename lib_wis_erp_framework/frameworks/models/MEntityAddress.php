<?php
/* 
Purpose : Model for ITEM_BARCODE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MEntityAddress extends MBaseModel
{
    private $cols = array(

                  [ # 0 For query, insert, delete, update
                   'ENTITY_ADDRESS_ID:SPK:ENTITY_ADDRESS_ID:Y', 
                   'ENTITY_ID:REFID:ENTITY_ID:Y',                    
                   'ENTITY_ADDRESS:S:ENTITY_ADDRESS:Y', 
                   'ADDRESS_TYPE:REFID:ADDRESS_TYPE:Y', 
                   'SORT_ORDER:NZ:SORT_ORDER:Y',                    
                                                         
                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                  
                  [ # 1 For delete by parent
                   'ENTITY_ID:SPK:ENTITY_ID:N'
                  ],                                                                                   
              
    );

    private $froms = array(

                   'FROM ENTITY_ADDRESS ',        
                       
                   'FROM ENTITY_ADDRESS '                                                 
            
    );

    private $orderby = array(

                   'ORDER BY SORT_ORDER ASC, ENTITY_ADDRESS_ID ASC ',     
                   
                   'ORDER BY ENTITY_ADDRESS_ID ASC ',                                                                                       
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ENTITY_ADDRESS', 'ENTITY_ADDRESS_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>