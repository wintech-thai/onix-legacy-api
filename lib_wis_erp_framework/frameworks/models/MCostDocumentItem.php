<?php
/* 
Purpose : Model for COST_DOCUMENT_ITEM
Created By : Seubpong Monsar
Created Date : 06/08/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCostDocumentItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'COST_DOC_ITEM_ID:SPK:COST_DOC_ITEM_ID:Y',
                    'COST_DOC_ID:REFID:COST_DOC_ID:Y',
                    'ITEM_TYPE:REFID:ITEM_TYPE:Y',
            
                    'JAN_AMOUNT:NZ:JAN_AMOUNT:N',
                    'FEB_AMOUNT:NZ:FEB_AMOUNT:N',
                    'MAR_AMOUNT:NZ:MAR_AMOUNT:N',
                    'APR_AMOUNT:NZ:APR_AMOUNT:N',
                    'MAY_AMOUNT:NZ:MAY_AMOUNT:N',
                    'JUN_AMOUNT:NZ:JUN_AMOUNT:N',
                    'JUL_AMOUNT:NZ:JUL_AMOUNT:N',
                    'AUG_AMOUNT:NZ:AUG_AMOUNT:N',
                    'SEP_AMOUNT:NZ:SEP_AMOUNT:N',
                    'OCT_AMOUNT:NZ:OCT_AMOUNT:N',
                    'NOV_AMOUNT:NZ:NOV_AMOUNT:N',
                    'DEC_AMOUNT:NZ:DEC_AMOUNT:N',
                    'TOT_AMOUNT:NZ:TOT_AMOUNT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For delete by parent
                    'COST_DOC_ID:SPK:COST_DOC_ID:Y'
                  ],                  
    );

    private $froms = array(

                'FROM COST_DOCUMENT_ITEM ',
                'FROM COST_DOCUMENT_ITEM ',
    );

    private $orderby = array(
                'ORDER BY ITEM_TYPE ASC ',
                'ORDER BY ITEM_TYPE ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'COST_DOCUMENT_ITEM', 'COST_DOC_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>