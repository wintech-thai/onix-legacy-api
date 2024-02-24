<?php
/* 
Purpose : Model for INVENTORY_DOC
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MInventoryDoc extends MBaseModel
{
    private $cols = array(

                  [ # 0 For Insert, Update, Delete
                   'DOC_ID:SPK:DOC_ID:Y', 
                   'DOCUMENT_NO:S:DOCUMENT_NO:N', 
                   'DOCUMENT_DATE:S:DOCUMENT_DATE:N', 
                   'RETURN_DUE_DATE:S:RETURN_DUE_DATE:N', 
                   'DOCUMENT_TYPE:N:DOCUMENT_TYPE:N', 
                   'DOCUMENT_STATUS:N:DOCUMENT_STATUS:N',
                   'APPROVED_DATE:S:APPROVED_DATE:N', 
                   'APPROVED_SEQ:NZ:APPROVED_SEQ:N',
                   'LOCATION_ID1:REFID:LOCATION_ID1:N', 
                   'LOCATION_ID2:REFID:LOCATION_ID2:N',
                   'NOTE:S:NOTE:N',                    
                   'FINAL_DISCOUNT:NZ:FINAL_DISCOUNT:N',                         
                   'VAT_PCT:NZ:VAT_PCT:N',
                   'TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N',
                   'VAT_AMOUNT:NZ:VAT_AMOUNT:N',
                   'ALLOW_NEGATIVE:S:ALLOW_NEGATIVE:N',
                   'ADJUSTMENT_BY:REFID:ADJUSTMENT_BY:N',
                   'ADJUST_BY_DELTA_FLAG:S:ADJUST_BY_DELTA_FLAG:Y',
                   'EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                   'INTERNAL_DOC_FLAG:S:INTERNAL_DOC_FLAG:Y',

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'                                                                           
                  ],
                  
                  [ # 1 For query
                   'INV.DOC_ID:SPK:DOC_ID:Y', 
                   'INV.DOCUMENT_NO:S:DOCUMENT_NO:Y', 
                   'INV.DOCUMENT_DATE:S:DOCUMENT_DATE:N', 
                   'INV.RETURN_DUE_DATE:S:RETURN_DUE_DATE:N', 
                   'INV.DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y', 
                   'INV.DOCUMENT_STATUS:N:DOCUMENT_STATUS:Y',
                   'INV.APPROVED_DATE:S:APPROVED_DATE:N', 
                   'INV.APPROVED_SEQ:NZ:APPROVED_SEQ:N',
                   'INV.LOCATION_ID1:REFID:LOCATION_ID1:Y', 
                   'INV.LOCATION_ID2:REFID:LOCATION_ID2:Y',
                   'INV.NOTE:S:NOTE:Y',  
                   'INV.FINAL_DISCOUNT:NZ:FINAL_DISCOUNT:N',                         
                   'INV.VAT_PCT:NZ:VAT_PCT:N',
                   'INV.TOTAL_AMOUNT:NZ:TOTAL_AMOUNT:N',
                   'INV.VAT_AMOUNT:NZ:VAT_AMOUNT:N',    
                   'INV.ALLOW_NEGATIVE:S:ALLOW_NEGATIVE:N',
                   'INV.ADJUSTMENT_BY:REFID:ADJUSTMENT_BY:N',
                   'INV.ADJUST_BY_DELTA_FLAG:S:ADJUST_BY_DELTA_FLAG:Y',
                   'INV.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',
                   'INV.INTERNAL_DOC_FLAG:S:INTERNAL_DOC_FLAG:Y',

                   'LC1.LOCATION_CODE:S:FROM_LOCATION_CODE:N',                                   
                   'LC1.DESCRIPTION:S:FROM_LOCATION:N', 
                   'LC2.LOCATION_CODE:S:TO_LOCATION_CODE:N',
                   'LC2.DESCRIPTION:S:TO_LOCATION:N',                   
                   
                   'EM.EMPLOYEE_CODE:S:EMPLOYEE_CODE:N',
                   'EM.EMPLOYEE_NAME:S:EMPLOYEE_NAME:N',
                   "CONCAT(EM.EMPLOYEE_NAME, ' ', EM.EMPLOYEE_LASTNAME):S:EMPLOYEE_NAME_LASTNAME:N:Y",
                   
                   #Always put these at the end
                   'INV.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y', 
                   'INV.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y'
                  ],
                  
                  [ # 2 For update approved document
                   'DOC_ID:SPK:DOC_ID:Y', 
                   'DOCUMENT_NO:S:DOCUMENT_NO:N', 
                   'DOCUMENT_STATUS:N:DOCUMENT_STATUS:N',
                   'APPROVED_DATE:CD:APPROVED_DATE:N', 
                   'APPROVED_SEQ:NZ:APPROVED_SEQ:N',      
                                                  
                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'                                                                           
                  ],                                                                                                                                                       
              
    );

    private $froms = array(
     
                                               
                   'FROM INVENTORY_DOC ', 
                   
                   'FROM INVENTORY_DOC INV ' .
                       'LEFT OUTER JOIN LOCATION LC1 ON (INV.LOCATION_ID1 = LC1.LOCATION_ID) ' .
                       'LEFT OUTER JOIN EMPLOYEE EM ON (EM.EMPLOYEE_ID = INV.EMPLOYEE_ID) '.
                       'LEFT OUTER JOIN LOCATION LC2 ON (INV.LOCATION_ID2 = LC2.LOCATION_ID) ',  
                       
                   'FROM INVENTORY_DOC ',                                                                                                                                                           
            
    );

    private $orderby = array(
                        
                   'ORDER BY DOC_ID DESC ', 
                   
                   'ORDER BY INV.DOC_ID DESC ', 
                   
                   'ORDER BY DOC_ID DESC ',                                                                                                                                                                               
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'INVENTORY_DOC', 'DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>