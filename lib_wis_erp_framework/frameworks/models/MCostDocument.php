<?php
/* 
Purpose : Model for COST_DOCUMENT
Created By : Seubpong Monsar
Created Date : 06/08/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCostDocument extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'COST_DOC_ID:SPK:COST_DOC_ID:Y',
                    'COST_YEAR:NZ:COST_YEAR:Y',
                    'DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'BEGIN_STOCK_BALANCE:NZ:BEGIN_STOCK_BALANCE:N',
                    'END_STOCK_BALANCE:NZ:END_STOCK_BALANCE:N',
                    'IN_AMOUNT:NZ:IN_AMOUNT:N',
                    'OUT_AMOUNT:NZ:OUT_AMOUNT:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get List
                    'CD.COST_DOC_ID:SPK:COST_DOC_ID:Y',
                    'CD.COST_YEAR:NZ:COST_YEAR:Y',
                    'CD.DOCUMENT_STATUS:REFID:DOCUMENT_STATUS:Y',
                    'CD.BEGIN_STOCK_BALANCE:NZ:BEGIN_STOCK_BALANCE:N',
                    'CD.END_STOCK_BALANCE:NZ:END_STOCK_BALANCE:N',
                    'CD.IN_AMOUNT:NZ:IN_AMOUNT:N',
                    'CD.OUT_AMOUNT:NZ:OUT_AMOUNT:N',

                    'CD.CREATE_DATE:CD:CREATE_DATE:N',
                    'CD.MODIFY_DATE:MD:MODIFY_DATE:N',              
                  ],                 
    );

    private $froms = array(

                'FROM COST_DOCUMENT ',

                'FROM COST_DOCUMENT CD ',
    );

    private $orderby = array(
                'ORDER BY COST_DOC_ID DESC ',
                
                'ORDER BY CD.COST_DOC_ID DESC ',   
    );

    function __construct($db) 
    {
        parent::__construct($db, 'COST_DOCUMENT', 'COST_DOC_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>