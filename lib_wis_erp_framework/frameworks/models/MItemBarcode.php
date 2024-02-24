<?php
/* 
Purpose : Model for ITEM_BARCODE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MItemBarcode extends MBaseModel
{
    private $cols = array(

                  [ # 0 For query, insert, delete, update
                   'ITEM_BC_ID:SPK:ITEM_BC_ID:Y', 
                   'ITEM_ID:REFID:ITEM_ID:Y',                    
                   'BARCODE:S:BARCODE:Y', 
                   'BARCODE_TYPE:REFID:BARCODE_TYPE:Y', 
                                                         
                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                  
                  [ # 1 For delete by parent
                   'ITEM_ID:SPK:ITEM_ID:N'
                  ],                                                                                   
              
    );

    private $froms = array(

                   'FROM ITEM_BARCODE ',        
                       
                   'FROM ITEM_BARCODE '                                                 
            
    );

    private $orderby = array(

                   'ORDER BY ITEM_BC_ID ASC ',     
                   
                   'ORDER BY ITEM_BC_ID ASC ',                                                                                       
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ITEM_BARCODE', 'ITEM_BC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>