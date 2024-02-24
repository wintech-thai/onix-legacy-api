<?php
/* 
Purpose : Model for COMMISSION_PROF_DETAIL
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCommissionProfileDetail extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'COMMISSION_PDETL_ID:SPK:COMMISSION_PDETL_ID:Y',
                    'COMMISSION_PROF_ID:REFID:COMMISSION_PROF_ID:N',
                    'SELECTION_TYPE:N:SELECTION_TYPE:N',
                    'ENABLE_FLAG:S:ENABLE_FLAG:N',
                    'LOOKUP_TYPE:NZ:LOOKUP_TYPE:N',
                    'COMM_DEFINITION:S:COMM_DEFINITION:N',
                    'SERVICE_ID:REFID:SERVICE_ID:Y',
                    'ITEM_ID:REFID:ITEM_ID:Y',
                    'ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],
                  [ # 1 For query
                    'CD.COMMISSION_PDETL_ID:SPK:COMMISSION_PDETL_ID:Y',
                    'CD.COMMISSION_PROF_ID:REFID:COMMISSION_PROF_ID:Y',
                    'CD.SELECTION_TYPE:N:SELECTION_TYPE:N',
                    'CD.ENABLE_FLAG:S:ENABLE_FLAG:N',
                    'CD.LOOKUP_TYPE:N:LOOKUP_TYPE:N',
                    'CD.COMM_DEFINITION:S:COMM_DEFINITION:N',
                    'CD.SERVICE_ID:REFID:SERVICE_ID:Y',
                    'CD.ITEM_ID:REFID:ITEM_ID:Y',
                    'CD.ITEM_CATEGORY:REFID:ITEM_CATEGORY:N',

                    'SV.SERVICE_CODE:S:SERVICE_CODE:N',
                    'SV.SERVICE_NAME:S:SERVICE_NAME:N',
                    
                    'IT.ITEM_CODE:S:ITEM_CODE:N',
                    'IT.ITEM_NAME_THAI:S:ITEM_NAME_THAI:N',
                    
                    'IC.CATEGORY_NAME:S:CATEGORY_NAME:N',  
                                        
                    'CD.CREATE_DATE:CD:CREATE_DATE:N',
                    'CD.MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],
                  [ # 2 delete from parent
                    'COMMISSION_PROF_ID:SPK:COMMISSION_PROF_ID:Y',
                  ]
              
    );

    private $froms = array(
                               
                'FROM COMMISSION_PROF_DETAIL ',

                'FROM COMMISSION_PROF_DETAIL CD '.
                    'LEFT OUTER JOIN SERVICE SV ON (CD.SERVICE_ID = SV.SERVICE_ID) '.
                    'LEFT OUTER JOIN ITEM IT ON (CD.ITEM_ID = IT.ITEM_ID) '.
                    'LEFT OUTER JOIN ITEM_CATEGORY IC ON (CD.ITEM_CATEGORY = IC.ITEM_CATEGORY_ID) ',

                'FROM COMMISSION_PROF_DETAIL ',
            
    );

    private $orderby = array(

                'ORDER BY COMMISSION_PDETL_ID ASC ',
                'ORDER BY COMMISSION_PDETL_ID ASC ',
                'ORDER BY COMMISSION_PDETL_ID ASC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'COMMISSION_PROF_DETAIL', 'COMMISSION_PDETL_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>