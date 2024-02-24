<?php
/* 
Purpose : Model for linking to FRW_BAL_DOCUMENT_DETAIL
Created By : Seubpong Monsar
Created Date : 09/21/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MBalanceTx extends MBaseModel
{
    private $cols = array(

                [ # 0 For Cash transaction query
                    'BDD.BAL_DOC_DTL_ID:SPK:BAL_DOC_DTL_ID:Y',
                    'BDD.BAL_DOC_ID:REFID:BAL_DOC_ID:Y',
                    'BDD.DIRECTION:S:TX_TYPE:Y',
                    //Intend to use QUANTITY and mapping to AMOUNT
                    'BDD.TX_QTY_AVG:NZ:TX_AMOUNT:N',
                    //Intend to use QUANTITY and mapping to AMOUNT
                    'BDD.BEGIN_QTY_AVG:NZ:BEGIN_AMOUNT:N',
                    'BDD.END_QTY_AVG:NZ:END_AMOUNT:N',

                    'BO.BAL_OWNER_TYPE:NZ:BAL_OWNER_TYPE:Y',
                    'BI.BAL_ITEM_TYPE:NZ:BAL_ITEM_TYPE:Y',
                    
                    'BD.BAL_DOC_NO:S:BAL_DOC_NO:N',

                    'CD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'CD.DOCUMENT_DATE:S:DOCUMENT_DATE:Y',
                    'CD.DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                    'CD.DOCUMENT_STATUS:N:DOCUMENT_STATUS:Y',
                    'CD.APPROVED_DATE:S:APPROVED_DATE:N',
                    'CD.APPROVED_SEQ:NZ:APPROVED_SEQ:N',
                    'CD.NOTE:S:DOCUMENT_NOTE:N',

                    'CA.CASH_ACCOUNT_ID:REFID:CASH_ACCOUNT_ID:Y',                    
                    'CA.ACCOUNT_NO:S:ACCOUNT_NO:Y',
                    'CA.ACCOUNT_NNAME:S:ACCOUNT_NNAME:Y',
                    'CA.BANK_ID:N:BANK_ID:N',
                    'MR.DESCRIPTION:S:BANK_NAME:Y',
                    'CA.BANK_BRANCH_NAME:S:BANK_BRANCH_NAME:N',

                    //Always put these at the end
                    'CD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'CD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                ],
    
                [ # 1 For Inventory transaction query
                    'BDD.BAL_DOC_DTL_ID:SPK:BAL_DOC_DTL_ID:N', 
                    'BDD.BAL_DOC_ID:N:DOC_ID:Y', 
                    "BDD.DIRECTION:S:DIRECTION:Y",
                    "BDD.ACTUAL_ID:N:ACTUAL_ID:Y",
                    "BDD.BEGIN_QTY_AVG:NZ:BEGIN_QTY_AVG:N",
                    "BDD.BEGIN_AMOUNT_AVG:NZ:BEGIN_AMOUNT_AVG:N",
                    "BDD.BEGIN_QTY_FIFO:NZ:BEGIN_QTY_FIFO:N",
                    "BDD.BEGIN_AMOUNT_FIFO:NZ:BEGIN_AMOUNT_FIFO:N",
                    "BDD.TX_QTY_AVG:NZ:TX_QTY_AVG:N",
                    "BDD.TX_AMT_AVG:NZ:TX_AMT_AVG:N",
                    "BDD.TX_QTY_FIFO:NZ:TX_QTY_FIFO:N",
                    "BDD.TX_AMT_FIFO:NZ:TX_AMT_FIFO:N",            
                    "BDD.END_QTY_AVG:NZ:END_QTY_AVG:N",
                    "BDD.END_AMOUNT_AVG:NZ:END_AMOUNT_AVG:N",
                    "BDD.END_QTY_FIFO:NZ:END_QTY_FIFO:N",
                    "BDD.END_AMOUNT_FIFO:NZ:END_AMOUNT_FIFO:N",
                    "BDD.LOT_NO:S:LOT_NO:N",
                    "BDD.LOT_DESC:S:LOT_DESC:N",
                    "BDD.LOT_EXPIRE_DATE:S:LOT_EXPIRE_DATE:N",
                    "BDD.GROUP_ID:NZ:GROUP_ID:Y",

                    'BO.BAL_OWNER_TYPE:NZ:BAL_OWNER_TYPE:Y',
                    'BI.BAL_ITEM_TYPE:NZ:BAL_ITEM_TYPE:Y',
                                        
                    'IVD.DOCUMENT_DATE:S:DOCUMENT_DATE:N', 
                    'IVD.DOCUMENT_TYPE:REFID:DOCUMENT_TYPE:Y',
                    'IVD.DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',   
                    'IVD.DOCUMENT_NO:S:DOCUMENT_NO:Y', 
                    'IVD.NOTE:S:NOTE:N', 

                    'LC1.DESCRIPTION:S:LOCATION_NAME:Y',
                    'LC1.LOCATION_ID:REFID:LOCATION_ID:Y',                                         
                    'LC2.DESCRIPTION:S:LOCATION_TO_NAME:Y',                   
                    
                    'IT.ITEM_ID:REFID:ITEM_ID:Y', 
                    'IT.ITEM_NAME_ENG:S:ITEM_NAME_ENG:Y', 
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:Y', 
                    'IT.ITEM_CODE:S:ITEM_CODE:Y',

                    //Always put these at the end
                    'IVD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'IVD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                ],

                [ # 2 For ArAp transaction query
                    'BDD.BAL_DOC_DTL_ID:SPK:BAL_DOC_DTL_ID:Y',
                    'BDD.BAL_DOC_ID:REFID:BAL_DOC_ID:Y',
                    'BDD.DIRECTION:S:TX_TYPE:Y',
                    //Intend to use QUANTITY and mapping to AMOUNT
                    'BDD.TX_QTY_AVG:NZ:TX_AMOUNT:N',
                    //Intend to use QUANTITY and mapping to AMOUNT
                    'BDD.BEGIN_QTY_AVG:NZ:BEGIN_AMOUNT:N',
                    'BDD.END_QTY_AVG:NZ:END_AMOUNT:N',

                    'BO.BAL_OWNER_TYPE:NZ:BAL_OWNER_TYPE:Y',
                    'BI.BAL_ITEM_TYPE:NZ:BAL_ITEM_TYPE:Y',
                                    
                    'AD.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'AD.DOCUMENT_DATE:S:DOCUMENT_DATE:Y',
                    'AD.DUE_DATE:S:DUE_DATE:Y',
                    'AD.DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                    'AD.DOCUMENT_STATUS:N:DOCUMENT_STATUS:Y',
                    'AD.APPROVED_DATE:S:APPROVED_DATE:N',
                    'AD.APPROVED_SEQ:NZ:APPROVED_SEQ:N',
                    'AD.DOCUMENT_DESC:S:DOCUMENT_DESC:N',
                    'AD.RECEIPT_FLAG:S:RECEIPT_FLAG:N',

                    'EN.ENTITY_ID:REFID:ENTITY_ID:Y',                    
                    'EN.ENTITY_CODE:S:ENTITY_CODE:Y',
                    'EN.ENTITY_NAME:S:ENTITY_NAME:Y',
                    'EN.ENTITY_TYPE:REFID:ENTITY_TYPE:Y',
                    'EN.ENTITY_GROUP:REFID:ENTITY_GROUP:Y',
                    'MR1.DESCRIPTION:S:ENTITY_TYPE_NAME:N',
                    'MR2.DESCRIPTION:S:ENTITY_GROUP_NAME:N',

                    //Always put these at the end
                    'AD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'AD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
            ],
               
    );

    private $froms = array(
                'FROM FRW_BAL_DOCUMENT_DETAIL BDD ' .
                    'LEFT OUTER JOIN FRW_BAL_DOCUMENT BD ON (BDD.BAL_DOC_ID = BD.BAL_DOC_ID) ' .
                    'LEFT OUTER JOIN FRW_BAL_OWNER BO ON (BDD.BAL_OWNER_ID = BO.BAL_OWNER_ID) ' .
                    'LEFT OUTER JOIN FRW_BAL_ITEM BI ON (BDD.BAL_ITEM_ID = BI.BAL_ITEM_ID) ' .
                    'LEFT OUTER JOIN CASH_DOC CD ON (BD.ACTUAL_ID = CD.CASH_DOC_ID) ' .
                    'LEFT OUTER JOIN CASH_ACCOUNT CA ON (BO.ACTUAL_ID = CA.CASH_ACCOUNT_ID) ' .
                    'LEFT OUTER JOIN MASTER_REF MR ON (CA.BANK_ID = MR.MASTER_ID) ',

                'FROM FRW_BAL_DOCUMENT_DETAIL BDD ' .
                    'LEFT OUTER JOIN FRW_BAL_DOCUMENT BD ON (BDD.BAL_DOC_ID = BD.BAL_DOC_ID) ' .
                    'LEFT OUTER JOIN FRW_BAL_OWNER BO ON (BDD.BAL_OWNER_ID = BO.BAL_OWNER_ID) ' .  
                    'LEFT OUTER JOIN LOCATION LC1 ON (BO.ACTUAL_ID = LC1.LOCATION_ID) ' .   
                    'LEFT OUTER JOIN FRW_BAL_ITEM BI ON (BDD.BAL_ITEM_ID = BI.BAL_ITEM_ID) ' .
                    'LEFT OUTER JOIN ITEM IT ON (BI.ACTUAL_ID = IT.ITEM_ID) ' .                    
                    'LEFT OUTER JOIN INVENTORY_DOC IVD ON (BD.ACTUAL_ID = IVD.DOC_ID) ' .
                    'LEFT OUTER JOIN LOCATION LC2 ON (IVD.LOCATION_ID2 = LC2.LOCATION_ID) ',   
                    
                'FROM FRW_BAL_DOCUMENT_DETAIL BDD ' .
                    'LEFT OUTER JOIN FRW_BAL_DOCUMENT BD ON (BDD.BAL_DOC_ID = BD.BAL_DOC_ID) ' .
                    'LEFT OUTER JOIN FRW_BAL_OWNER BO ON (BDD.BAL_OWNER_ID = BO.BAL_OWNER_ID) ' .
                    'LEFT OUTER JOIN FRW_BAL_ITEM BI ON (BDD.BAL_ITEM_ID = BI.BAL_ITEM_ID) ' .
                    'LEFT OUTER JOIN ACCOUNT_DOC AD ON (BD.ACTUAL_ID = AD.ACCOUNT_DOC_ID) ' .
                    'LEFT OUTER JOIN ENTITY EN ON (BO.ACTUAL_ID = EN.ENTITY_ID) ' .
                    'LEFT OUTER JOIN MASTER_REF MR1 ON (EN.ENTITY_TYPE = MR1.MASTER_ID) ' .
                    'LEFT OUTER JOIN MASTER_REF MR2 ON (EN.ENTITY_GROUP = MR2.MASTER_ID) ',                               
                    
    );

    private $orderby = array(
                'ORDER BY BDD.BAL_DOC_DTL_ID ASC ',
                'ORDER BY BDD.BAL_DOC_DTL_ID ASC ',
                'ORDER BY BDD.BAL_DOC_DTL_ID ASC '                                              
    );

    function __construct($db) 
    {
        parent::__construct($db, 'FRW_BAL_DOCUMENT_DETAIL', 'BAL_DOC_DTL_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>