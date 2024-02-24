<?php
/* 
Purpose : Model for LOCATION
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MLocation extends MBaseModel
{
    private $cols = array(

                  [ # 0 Insert, Delete, Update
                   'LOCATION_ID:SPK:LOCATION_ID:Y', 
                   'LOCATION_CODE:S:LOCATION_CODE:Y', 
                   'DESCRIPTION:S:DESCRIPTION:Y', 
                   'SALE_FLAG:S:SALE_FLAG:Y',  
                   'LOCATION_TYPE:REFID:LOCATION_TYPE:Y',  
                   'BRANCH_ID:REFID:BRANCH_ID:Y',  
                   'BORROW_FLAG:S:BORROW_FLAG:Y',  

                   'CREATE_DATE:CD:CREATE_DATE:N', 
                   'MODIFY_DATE:MD:MODIFY_DATE:N'                                                                                               
                  ],   
                  
                  [ # 1 Query
                   'LC.LOCATION_ID:SPK:LOCATION_ID:Y', 
                   'LC.LOCATION_CODE:S:LOCATION_CODE:Y',
                   'LC.BORROW_FLAG:S:BORROW_FLAG:Y',   
                   'MR1.DESCRIPTION:S:TYPE_NAME:N', 
                   'LC.DESCRIPTION:S:DESCRIPTION:Y', 
                   'LC.SALE_FLAG:S:SALE_FLAG:Y',  
                   'LC.LOCATION_TYPE:REFID:LOCATION_TYPE:Y',
                   'LC.BRANCH_ID:REFID:BRANCH_ID:Y',                          
                  ],
                  
                  [ # 2 Generic purpose, usually used by Patch functions. Do not modify!!!
                                                                          
                  ],
    );

    private $froms = array(
                                                    
                   'FROM LOCATION ',            
                
                   'FROM LOCATION LC ' . 
                       'LEFT OUTER JOIN MASTER_REF MR1 ON (LC.LOCATION_TYPE = MR1.MASTER_ID) ',

                   'FROM LOCATION ',                                   
            
    );

    private $orderby = array(

                   'ORDER BY LOCATION_ID DESC ',   
                   
                   'ORDER BY LOCATION_ID DESC ',

                   'ORDER BY LOCATION_ID DESC ',                   
    );

    function __construct($db) 
    {
        parent::__construct($db, 'LOCATION', 'LOCATION_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>