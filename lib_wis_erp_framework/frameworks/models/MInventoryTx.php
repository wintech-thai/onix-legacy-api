<?php
/* 
Purpose : Model for INVENTORY_TX
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MInventoryTx extends MBaseModel
{
    private $cols = array(

                  [ # 0 For Insert, Update, Delete
                   'TX_ID:SPK:TX_ID:Y', 
                   'DOC_ID:N:DOC_ID:Y', 
                   'LOCATION_ID:REFID:LOCATION_ID:Y', 
                   'ITEM_ID:REFID:ITEM_ID:Y', 
                   'ITEM_QUANTITY:NZ:ITEM_QUANTITY:N', 
                   'ITEM_AMOUNT:NZ:ITEM_AMOUNT:N', 
                   'ITEM_PRICE:NZ:ITEM_PRICE:N',
                   'FIFO_AMOUNT:NZ:FIFO_AMOUNT:N', 
                   'AVG_AMOUNT:NZ:AVG_AMOUNT:N', 
                   'LOT_FLAG:S:LOT_FLAG:N',
                   'LOT_NO:S:LOT_NO:N', 
                   'LOT_NOTE:S:LOT_NOTE:N', 
                   'TX_TYPE:S:TX_TYPE:Y', 
                   'FACTOR:N:FACTOR:N',
                   'AMOUNT_AVG:NZ:AMOUNT_AVG:N', 
                   'UNIT_PRICE_AVG:NZ:UNIT_PRICE_AVG:N', 
                   'AMOUNT_FIFO:NZ:AMOUNT_FIFO:N', 
                   'UNIT_PRICE_FIFO:NZ:UNIT_PRICE_FIFO:N', 
                   'TRACKING_HISTORY:S:TRACKING_HISTORY:N',                   
                   'UI_ITEM_UNIT_PRICE:NZ:UI_ITEM_UNIT_PRICE:N',                         
                   'UI_ITEM_AMOUNT:NZ:UI_ITEM_AMOUNT:N',
                   'UI_ITEM_DISCOUNT:NZ:UI_ITEM_DISCOUNT:N', 
                   'UI_WEIGHT_DISCOUNT:NZ:UI_WEIGHT_DISCOUNT:N',
                   'UI_TOTAL_AMOUNT:NZ:UI_TOTAL_AMOUNT:N',
                   'PROJECT_ID:REFID:PROJECT_ID:N', 
                   'RETURNED_QUANTITY:NZ:RETURNED_QUANTITY:N', 
                   'RETURNED_ALL_FLAG:S:RETURNED_ALL_FLAG:Y',
                   'RETURNED_QUANTITY_NEED:NZ:RETURNED_QUANTITY_NEED:N', 
                   'BORROW_ID:REFID:BORROW_ID:N', 
                   'BORROW_DOCUMENT_NO:S:BORROW_DOCUMENT_NO:N',     
                   'REASON_TYPE:REFID:REASON_TYPE:N', 
                   
                   'END_QUANTITY:NZ:END_QUANTITY:N', 
                   'END_AMOUNT_AVG:NZ:END_AMOUNT_AVG:N', 
                   'END_AMOUNT_FIFO:NZ:END_AMOUNT_FIFO:N', 
                   'BEGIN_QUANTITY:NZ:BEGIN_QUANTITY:N', 
                   'BEGIN_AMOUNT_AVG:NZ:BEGIN_AMOUNT_AVG:N', 
                   'BEGIN_AMOUNT_FIFO:NZ:BEGIN_AMOUNT_FIFO:N', 
                   
                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'                                                                               
                  ],    
                  
                  [ # 1 For delete by parent
                   'DOC_ID:SPK:DOC_ID:Y' 
                  ],  
                  
                  [ # 2 For select items by parent
                   'IVT.TX_ID:SPK:TX_ID:N', 
                   'IVT.DOC_ID:N:DOC_ID:Y', 
                   'IVT.LOCATION_ID:REFID:LOCATION_ID:Y', 
                   'IVT.ITEM_ID:REFID:ITEM_ID:Y', 
                   'IVT.ITEM_QUANTITY:N:ITEM_QUANTITY:Y', 
                   'IVT.ITEM_PRICE:N:ITEM_PRICE:Y', 
                   'IVT.ITEM_AMOUNT:N:ITEM_AMOUNT:Y',
                   'IVT.FIFO_AMOUNT:PK:FIFO_AMOUNT:N', 
                   'IVT.AVG_AMOUNT:N:AVG_AMOUNT:Y', 
                   'IVT.LOT_FLAG:S:LOT_FLAG:N',
                   'IVT.LOT_NO:S:LOT_NO:Y', 
                   'IVT.LOT_NOTE:S:LOT_NOTE:Y',
                   'IVT.AMOUNT_AVG:NZ:AMOUNT_AVG:Y', 
                   'IVT.UNIT_PRICE_AVG:NZ:UNIT_PRICE_AVG:Y', 
                   'IVT.AMOUNT_FIFO:NZ:AMOUNT_FIFO:Y', 
                   'IVT.UNIT_PRICE_FIFO:NZ:UNIT_PRICE_FIFO:Y',
                   'IVT.TRACKING_HISTORY:S:TRACKING_HISTORY:Y',                   
                   'IVT.TX_TYPE:S:TX_TYPE:Y', 
                   'IVT.FACTOR:N:FACTOR:N',
                   'IVT.END_QUANTITY:N:END_QUANTITY:N', 
                   'IVT.END_AMOUNT_AVG:N:END_AMOUNT_AVG:N', 
                   'IVT.END_AMOUNT_FIFO:N:END_AMOUNT_FIFO:N',
                   'IVT.BEGIN_QUANTITY:N:BEGIN_QUANTITY:N', 
                   'IVT.BEGIN_AMOUNT_AVG:N:BEGIN_AMOUNT_AVG:N', 
                   'IVT.BEGIN_AMOUNT_FIFO:N:BEGIN_AMOUNT_FIFO:N',                   
                   'IVT.UI_ITEM_UNIT_PRICE:NZ:UI_ITEM_UNIT_PRICE:N',                         
                   'IVT.UI_ITEM_AMOUNT:NZ:UI_ITEM_AMOUNT:N',
                   'IVT.UI_ITEM_DISCOUNT:NZ:UI_ITEM_DISCOUNT:N', 
                   'IVT.UI_WEIGHT_DISCOUNT:NZ:UI_WEIGHT_DISCOUNT:N',
                   'IVT.UI_TOTAL_AMOUNT:NZ:UI_TOTAL_AMOUNT:N',
                   'IVT.PROJECT_ID:REFID:PROJECT_ID:N', 
                   'IVT.RETURNED_QUANTITY:NZ:RETURNED_QUANTITY:N', 
                   'IVT.RETURNED_ALL_FLAG:S:RETURNED_ALL_FLAG:Y',
                   'IVT.RETURNED_QUANTITY_NEED:NZ:RETURNED_QUANTITY_NEED:N', 
                   'IVT.BORROW_ID:REFID:BORROW_ID:N', 
                   'IVT.BORROW_DOCUMENT_NO:S:BORROW_DOCUMENT_NO:N',
                   'IVT.REASON_TYPE:REFID:REASON_TYPE:N', 
                   
                   'IVD.DOCUMENT_DATE:S:DOCUMENT_DATE:N', 
                   'IVD.DOCUMENT_TYPE:REFID:DOCUMENT_TYPE:Y',
                   'IVD.DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',   
                   'IVD.DOCUMENT_NO:S:DOCUMENT_NO:Y',                                   
                   'LC1.DESCRIPTION:S:LOCATION_NAME:Y',
                   'LC2.DESCRIPTION:S:LOCATION_TO_NAME:Y',                   
                   'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
                   'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y', 
                   'IT.ITEM_CODE:S:ITEM_CODE:Y',    
                   
                   'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
                   'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',
                   'PG.CODE:S:PROJECT_GROUP_CODE:Y',
                   'PG.DESCRIPTION:S:PROJECT_GROUP_NAME:Y',
                   'RT.CODE:S:REASON_CODE:Y',
                   'RT.DESCRIPTION:S:REASON_DESC:Y',
                   
                   'UN1.DESCRIPTION:S:ITEM_UNIT_NAME:N',
                   'UN1.DESCRIPTION_ENG:S:ITEM_UNIT_NAME_ENG:N',     

                   #Always put these at the end
                   'IVD.DOCUMENT_DATE:FD:FROM_DATE:Y', 
                   'IVD.DOCUMENT_DATE:TD:TO_DATE:Y'                                                   
                  ],                                                                                                                                
              
                  [ # 3 For select items by parent
                   'IVT.TX_ID:SPK:TX_ID:N', 
                   'IVT.DOC_ID:N:DOC_ID:Y', 
                   'IVT.LOCATION_ID:REFID:LOCATION_ID:Y', 
                   'IVT.ITEM_ID:REFID:ITEM_ID:Y', 
                   'IVT.ITEM_QUANTITY:N:ITEM_QUANTITY:Y', 
                   'IVT.ITEM_PRICE:N:ITEM_PRICE:Y', 
                   'IVT.ITEM_AMOUNT:N:ITEM_AMOUNT:Y',
                   'IVT.FIFO_AMOUNT:PK:FIFO_AMOUNT:N', 
                   'IVT.AVG_AMOUNT:N:AVG_AMOUNT:Y', 
                   'IVT.LOT_FLAG:S:LOT_FLAG:N',
                   'IVT.LOT_NO:S:LOT_NO:Y', 
                   'IVT.LOT_NOTE:S:LOT_NOTE:Y',
                   'IVT.AMOUNT_AVG:NZ:AMOUNT_AVG:Y', 
                   'IVT.UNIT_PRICE_AVG:NZ:UNIT_PRICE_AVG:Y', 
                   'IVT.AMOUNT_FIFO:NZ:AMOUNT_FIFO:Y', 
                   'IVT.UNIT_PRICE_FIFO:NZ:UNIT_PRICE_FIFO:Y',
                   'IVT.TRACKING_HISTORY:S:TRACKING_HISTORY:Y',                   
                   'IVT.TX_TYPE:S:TX_TYPE:Y', 
                   'IVT.FACTOR:N:FACTOR:N',
                   'IVT.END_QUANTITY:N:END_QUANTITY:N', 
                   'IVT.END_AMOUNT_AVG:N:END_AMOUNT_AVG:N', 
                   'IVT.END_AMOUNT_FIFO:N:END_AMOUNT_FIFO:N',
                   'IVT.BEGIN_QUANTITY:N:BEGIN_QUANTITY:N', 
                   'IVT.BEGIN_AMOUNT_AVG:N:BEGIN_AMOUNT_AVG:N', 
                   'IVT.BEGIN_AMOUNT_FIFO:N:BEGIN_AMOUNT_FIFO:N',                   
                   'IVT.UI_ITEM_UNIT_PRICE:NZ:UI_ITEM_UNIT_PRICE:N',                         
                   'IVT.UI_ITEM_AMOUNT:NZ:UI_ITEM_AMOUNT:N',
                   'IVT.UI_ITEM_DISCOUNT:NZ:UI_ITEM_DISCOUNT:N', 
                   'IVT.UI_WEIGHT_DISCOUNT:NZ:UI_WEIGHT_DISCOUNT:N',
                   'IVT.UI_TOTAL_AMOUNT:NZ:UI_TOTAL_AMOUNT:N',
                   'IVT.PROJECT_ID:REFID:PROJECT_ID:N', 
                   'IVT.RETURNED_QUANTITY:NZ:RETURNED_QUANTITY:N', 
                   'IVT.RETURNED_ALL_FLAG:S:RETURNED_ALL_FLAG:Y',
                   'IVT.RETURNED_QUANTITY_NEED:NZ:RETURNED_QUANTITY_NEED:N', 
                   'IVT.BORROW_ID:REFID:BORROW_ID:N', 
                   'IVT.BORROW_DOCUMENT_NO:S:BORROW_DOCUMENT_NO:N',  
                   'IVT.REASON_TYPE:REFID:REASON_TYPE:N', 
                   
                   'IVD.DOCUMENT_DATE:S:DOCUMENT_DATE:N', 
                   'IVD.DOCUMENT_TYPE:REFID:DOCUMENT_TYPE:Y',
                   'IVD.DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',   
                   'IVD.DOCUMENT_NO:S:DOCUMENT_NO:Y',                                                    
                   'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
                   'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y', 
                   'IT.ITEM_CODE:S:ITEM_CODE:Y',    
                   'EMP.EMPLOYEE_NAME:S:EMPLOYEE_NAME:Y',    

                   'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
                   'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',
                   'PG.CODE:S:PROJECT_GROUP_CODE:Y',
                   'PG.DESCRIPTION:S:PROJECT_GROUP_NAME:Y',
                   'RT.CODE:S:REASON_CODE:Y',
                   'RT.DESCRIPTION:S:REASON_DESC:Y',


                   #Always put these at the end
                   'IVD.DOCUMENT_DATE:FD:FROM_DATE:Y', 
                   'IVD.DOCUMENT_DATE:TD:TO_DATE:Y'                                                   
                  ],  
                  
                  [ # 4 Deduct borrow amount
                  'TX_ID:SPK:TX_ID:Y',
                  'RETURNED_QUANTITY:NZ:RETURNED_QUANTITY:N', 
                  'RETURNED_QUANTITY_NEED:NZ:RETURNED_QUANTITY_NEED:N',
                  'RETURNED_ALL_FLAG:S:RETURNED_ALL_FLAG:N',                  
                  ],
    );

    private $froms = array(
                                                    
                   'FROM INVENTORY_TX ',            
                   
                   'FROM INVENTORY_TX ',       

                   'FROM INVENTORY_TX IVT  ' . 
                       'LEFT OUTER JOIN ITEM IT ON (IVT.ITEM_ID = IT.ITEM_ID) ' .    
                       'LEFT OUTER JOIN LOCATION LC1 ON (IVT.LOCATION_ID = LC1.LOCATION_ID) ' . 
                       'LEFT OUTER JOIN INVENTORY_DOC IVD ON (IVT.DOC_ID = IVD.DOC_ID) ' .      
                       'LEFT OUTER JOIN MASTER_REF UN1 ON (UN1.MASTER_ID = IT.ITEM_UOM) '.  
                       'LEFT OUTER JOIN PROJECT PJ ON (IVT.PROJECT_ID = PJ.PROJECT_ID) '.
                       'LEFT OUTER JOIN MASTER_REF PG ON (PJ.PROJECT_GROUP = PG.MASTER_ID) '. 
                       'LEFT OUTER JOIN MASTER_REF RT ON (IVT.REASON_TYPE = RT.MASTER_ID) '.                                         
                       'LEFT OUTER JOIN LOCATION LC2 ON (IVD.LOCATION_ID2 = LC2.LOCATION_ID) ',

                    'FROM INVENTORY_TX IVT ' . 
                       'LEFT OUTER JOIN ITEM IT ON (IVT.ITEM_ID = IT.ITEM_ID) ' .    
                       'LEFT OUTER JOIN LOCATION LC1 ON (IVT.LOCATION_ID = LC1.LOCATION_ID) ' . 
                       'LEFT OUTER JOIN INVENTORY_DOC IVD ON (IVT.DOC_ID = IVD.DOC_ID) ' .
                       'LEFT OUTER JOIN PROJECT PJ ON (IVT.PROJECT_ID = PJ.PROJECT_ID) '.
                       'LEFT OUTER JOIN MASTER_REF PG ON (PJ.PROJECT_GROUP = PG.MASTER_ID) '.
                       'LEFT OUTER JOIN MASTER_REF RT ON (IVT.REASON_TYPE = RT.MASTER_ID) '.                         
                       'LEFT OUTER JOIN EMPLOYEE EMP ON (IVD.EMPLOYEE_ID = EMP.EMPLOYEE_ID) ',

                    'FROM INVENTORY_TX ',    

    );

    private $orderby = array(

                   'ORDER BY TX_ID ASC ',
                   
                   'ORDER BY TX_ID ASC ',
                   
                   'ORDER BY TX_ID ASC ',

                   'ORDER BY TX_ID ASC ',            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'INVENTORY_TX', 'TX_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>