<?php
/* 
Purpose : Model for ITEM_CATEGORY
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MItemCategory extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, delete and update
                   'ITEM_CATEGORY_ID:SPK:ITEM_CATEGORY_ID:Y', 
                   'CATEGORY_NAME:S:CATEGORY_NAME:Y', 
                   'PARENT_ID:REFID:PARENT_ID:Y', 
                   'ITEM_COUNT:N:ITEM_COUNT:N', 

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],                                                                  
              
    );

    private $froms = array(

                   'FROM ITEM_CATEGORY ',                                    
            
    );

    private $orderby = array(

                   'ORDER BY ITEM_CATEGORY_ID DESC ',                                                                      
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ITEM_CATEGORY', 'ITEM_CATEGORY_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>