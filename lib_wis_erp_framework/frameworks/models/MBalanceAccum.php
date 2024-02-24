<?php
/* 
Purpose : Model for BALANCE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MBalanceAccum extends MBaseModel
{
    private $cols = array(

                [ # 0 For Cash Balance Query
                    'BA.BAL_ID:SPK:BAL_ID:Y',
                    'BA.BAL_LEVEL:NZ:BAL_LEVEL:Y',
                    'BA.BAL_DATE:S:DOCUMENT_DATE:Y',
                    'BA.BEGIN_QTY_AVG:N:BEGIN_AMOUNT:N',
                    //Intend to use QUANTITY and mapping to AMOUNT
                    'BA.IN_QTY_AVG:N:IN_AMOUNT:N',
                    'BA.OUT_QTY_AVG:N:OUT_AMOUNT:N',
                    'BA.END_QTY_AVG:N:END_AMOUNT:N',

                    'BO.BAL_OWNER_TYPE:NZ:BAL_OWNER_TYPE:Y',
                    'BI.BAL_ITEM_TYPE:NZ:BAL_ITEM_TYPE:Y',

                    'CA.CASH_ACCOUNT_ID:REFID:CASH_ACCOUNT_ID:Y',                        
                    'CA.ACCOUNT_NO:S:ACCOUNT_NO:Y',
                    'CA.ACCOUNT_NNAME:S:ACCOUNT_NNAME:Y',
                    'CA.BANK_ID:N:BANK_ID:N',
                    'CA.BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:N',
                    'MR.DESCRIPTION:S:BANK_NAME:Y',

                    //Always put these at the end
                    'BA.BAL_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'BA.BAL_DATE:TD:TO_DOCUMENT_DATE:Y',                    
                ],

                [ # 1 For Inventory Balance Query
                    'BA.BAL_ID:SPK:BAL_ID:Y',
                    'BA.BAL_LEVEL:NZ:BAL_LEVEL:Y',
                    'BA.BAL_DATE:S:BALANCE_DATE:Y',

                    'IT.ITEM_ID:NZ:ITEM_ID:Y',
                    'IT.ITEM_CODE:S:ITEM_CODE:Y',
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y',
                    'IT.MINIMUM_ALLOW:N:MINIMUM_ALLOW:N',
                    'LC.DESCRIPTION:S:LOCATION_NAME:Y',
                    'LC.LOCATION_ID:NZ:LOCATION_ID:Y',

                    //===
                    "BA.BEGIN_QTY_AVG:N:BEGIN_QUANTITY:N",
                    "BA.BEGIN_AMOUNT_AVG:N:BEGIN_AMOUNT_AVG:N",
                    "BA.BEGIN_PRICE_AVG:N:BEGIN_UNIT_PRICE_AVG:N",
        
                    "BA.BEGIN_QTY_FIFO:N:BEGIN_QTY_FIFO:N",
                    "BA.BEGIN_AMOUNT_FIFO:N:BEGIN_AMOUNT_FIFO:N",
                    "BA.BEGIN_PRICE_FIFO:N:BEGIN_UNIT_PRICE_FIFO:N",
        
                    "BA.IN_QTY_AVG:N:IN_QUANTITY:N",
                    "BA.IN_AMOUNT_AVG:N:IN_AMOUNT_AVG:N",
                    "BA.IN_PRICE_AVG:N:IN_PRICE_AVG:N",
        
                    "BA.IN_QTY_FIFO:N:IN_QTY_FIFO:N",
                    "BA.IN_AMOUNT_FIFO:N:IN_AMOUNT_FIFO:N",
                    "BA.IN_PRICE_FIFO:N:IN_PRICE_FIFO:N",
        
                    "BA.OUT_QTY_AVG:N:OUT_QUANTITY:N",
                    "BA.OUT_AMOUNT_AVG:N:OUT_AMOUNT_AVG:N",
                    "BA.OUT_PRICE_AVG:N:OUT_PRICE_AVG:N",
        
                    "BA.OUT_QTY_FIFO:N:OUT_QTY_FIFO:N",
                    "BA.OUT_AMOUNT_FIFO:N:OUT_AMOUNT_FIFO:N",
                    "BA.OUT_PRICE_FIFO:N:OUT_PRICE_FIFO:N",
        
                    "BA.END_QTY_AVG:N:END_QUANTITY:N",
                    "BA.END_AMOUNT_AVG:N:END_AMOUNT_AVG:N",
                    "BA.END_PRICE_AVG:N:END_UNIT_PRICE_AVG:N",

                    "BA.END_QTY_FIFO:N:END_QTY_FIFO:N",
                    "BA.END_AMOUNT_FIFO:N:END_AMOUNT_FIFO:N",
                    "BA.END_PRICE_FIFO:N:END_UNIT_PRICE_FIFO:N",                    
                    //===

                    'BO.BAL_OWNER_TYPE:NZ:BAL_OWNER_TYPE:Y',
                    'BI.BAL_ITEM_TYPE:NZ:BAL_ITEM_TYPE:Y',

                    //Always put these at the end
                    'BA.BAL_DATE:FD:FROM_BALANCE_DATE:Y',
                    'BA.BAL_DATE:TD:TO_BALANCE_DATE:Y',                    
                ],

                [ # 2 For Ar/Ap Balance Query
                    'BA.BAL_ID:SPK:BAL_ID:Y',
                    'BA.BAL_LEVEL:NZ:BAL_LEVEL:Y',
                    'BA.BAL_DATE:S:DOCUMENT_DATE:Y',
                    'BA.BEGIN_AMOUNT_AVG:N:BEGIN_AMOUNT:N',
                    //Intend to use QUANTITY and mapping to AMOUNT
                    'BA.IN_QTY_AVG:N:IN_AMOUNT:N',
                    'BA.OUT_QTY_AVG:N:OUT_AMOUNT:N',
                    'BA.END_QTY_AVG:N:END_AMOUNT:N',

                    'BO.BAL_OWNER_TYPE:NZ:BAL_OWNER_TYPE:Y',
                    'BI.BAL_ITEM_TYPE:NZ:BAL_ITEM_TYPE:Y',

                    'EN.ENTITY_ID:REFID:ENTITY_ID:Y',                    
                    'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
                    'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
                    'EN.ENTITY_TYPE:REFID:ENTITY_TYPE:Y',
                    'EN.ENTITY_GROUP:REFID:ENTITY_GROUP:Y',
                    'EN.CREDIT_LIMIT:NZ:CREDIT_LIMIT:N',                
                    'MR1.DESCRIPTION:S:ENTITY_TYPE_NAME:N',
                    'MR2.DESCRIPTION:S:ENTITY_GROUP_NAME:N',

                    //Always put these at the end
                    'BA.BAL_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'BA.BAL_DATE:TD:TO_DOCUMENT_DATE:Y',                    
            ],
    );

    private $froms = array(
                'FROM FRW_BAL_ACCUMULATE BA '.
                    'LEFT OUTER JOIN FRW_BAL_OWNER BO ON (BA.BAL_OWNER_ID = BO.BAL_OWNER_ID) ' .   
                    'LEFT OUTER JOIN FRW_BAL_ITEM BI ON (BA.BAL_ITEM_ID = BI.BAL_ITEM_ID) ' .                                       
                    'LEFT OUTER JOIN CASH_ACCOUNT CA ON (BO.ACTUAL_ID = CA.CASH_ACCOUNT_ID) ' .
                    'LEFT OUTER JOIN MASTER_REF MR ON (CA.BANK_ID = MR.MASTER_ID) ',

                'FROM FRW_BAL_ACCUMULATE BA '.
                    'LEFT OUTER JOIN FRW_BAL_OWNER BO ON (BA.BAL_OWNER_ID = BO.BAL_OWNER_ID) ' .   
                    'LEFT OUTER JOIN LOCATION LC ON (BO.ACTUAL_ID = LC.LOCATION_ID) ' .                     
                    'LEFT OUTER JOIN FRW_BAL_ITEM BI ON (BA.BAL_ITEM_ID = BI.BAL_ITEM_ID) ' . 
                    'LEFT OUTER JOIN ITEM IT ON (BI.ACTUAL_ID = IT.ITEM_ID) ',
                    
                'FROM FRW_BAL_ACCUMULATE BA '.
                    'LEFT OUTER JOIN FRW_BAL_OWNER BO ON (BA.BAL_OWNER_ID = BO.BAL_OWNER_ID) ' .   
                    'LEFT OUTER JOIN FRW_BAL_ITEM BI ON (BA.BAL_ITEM_ID = BI.BAL_ITEM_ID) ' .                                       
                    'LEFT OUTER JOIN ENTITY EN ON (BO.ACTUAL_ID = EN.ENTITY_ID) ' .
                    'LEFT OUTER JOIN MASTER_REF MR1 ON (EN.ENTITY_TYPE = MR1.MASTER_ID) ' .
                    'LEFT OUTER JOIN MASTER_REF MR2 ON (EN.ENTITY_GROUP = MR2.MASTER_ID) ',                 
    );

    private $orderby = array(

                'ORDER BY BA.BAL_DATE DESC ',
                'ORDER BY BA.BAL_DATE DESC ',
                'ORDER BY BA.BAL_DATE DESC '                            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_BAL_ACCUMULATE', 'BAL_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>