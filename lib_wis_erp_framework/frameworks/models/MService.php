<?php
/* 
Purpose : Model for SERVICE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MService extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update
                   'SERVICE_ID:SPK:SERVICE_ID:Y', 
                   'SERVICE_CODE:S:SERVICE_CODE:Y', 
                   'SERVICE_NAME:S:SERVICE_NAME:Y', 
                   'SERVICE_TYPE:REFID:SERVICE_TYPE:Y', 
                   'SERVICE_UOM:REFID:SERVICE_UOM:Y', 
                   'SERVICE_CATEGORY:REFID:SERVICE_CATEGORY:Y',
                   'WH_TAX_FLAG:S:WH_TAX_FLAG:Y',
                   'WH_TAX_PCT:NZ:WH_TAX_PCT:N',
                   'PRICING_DEFINITION:S:PRICING_DEFINITION:N',
                   'VAT_FLAG:S:VAT_FLAG:Y', 
                   'CATEGORY:NZ:CATEGORY:N', 
                   'IS_FOR_SALE:S:IS_FOR_SALE:Y', 
                   'IS_FOR_PURCHASE:S:IS_FOR_PURCHASE:Y', 
                   'WH_GROUP:REFID:WH_GROUP:Y', 
                   'SERVICE_LEVEL:REFID:SERVICE_LEVEL:Y',
                   'IS_SALARY:S:IS_SALARY:Y',  
                   
                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                  
                  [ # 1 For query
                   'SV.SERVICE_ID:SPK:SERVICE_ID:Y', 
                   'SV.SERVICE_CODE:S:SERVICE_CODE:Y', 
                   'SV.SERVICE_NAME:S:SERVICE_NAME:Y', 
                   'SV.SERVICE_TYPE:REFID:SERVICE_TYPE:Y', 
                   'SV.SERVICE_UOM:REFID:SERVICE_UOM:Y', 
                   'SV.SERVICE_CATEGORY:REFID:SERVICE_CATEGORY:Y',
                   'SV.WH_TAX_FLAG:S:WH_TAX_FLAG:Y',
                   'SV.WH_TAX_PCT:NZ:WH_TAX_PCT:N',
                   'SV.VAT_FLAG:S:VAT_FLAG:Y',                    
                   'SV.PRICING_DEFINITION:S:PRICING_DEFINITION:N',     
                   'SV.CATEGORY:NZ:CATEGORY:N', /* DO NOT ALLOW CATEGORY TO BE SELECTABLE */
                   'SV.IS_FOR_SALE:S:IS_FOR_SALE:Y', 
                   'SV.IS_FOR_PURCHASE:S:IS_FOR_PURCHASE:Y', 
                   'SV.WH_GROUP:REFID:WH_GROUP:Y', 
                   'SV.SERVICE_LEVEL:REFID:SERVICE_LEVEL:Y', 
                   'SV.IS_SALARY:S:IS_SALARY:Y',

                   'MR1.DESCRIPTION:S:SERVICE_TYPE_NAME:N', 
                   'MR2.DESCRIPTION:S:SERVICE_UOM_NAME:N',

                   'MR3.DESCRIPTION:S:WH_GROUP_NAME:N',
                   'MR3.DESCRIPTION_ENG:S:WH_GROUP_NAME_ENG:N',
                   
                   'SV.CREATE_DATE:CD:CREATE_DATE:N',
                   'SV.MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],      
                  
                  [ # 2 For update
                   'SERVICE_ID:SPK:SERVICE_ID:Y', 
                   'SERVICE_CODE:S:SERVICE_CODE:Y', 
                   
                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],                    
    );

    private $froms = array(

                   'FROM SERVICE ',
                   
                   'FROM SERVICE SV ' .
                       'LEFT OUTER JOIN MASTER_REF MR1 ON (SV.SERVICE_TYPE = MR1.MASTER_ID) ' .                       
                       'LEFT OUTER JOIN MASTER_REF MR2 ON (SV.SERVICE_UOM = MR2.MASTER_ID) ' .
                       'LEFT OUTER JOIN MASTER_REF MR3 ON (SV.WH_GROUP = MR3.MASTER_ID) ',  
                       
                    'FROM SERVICE ',                       
    );

    private $orderby = array(

                   'ORDER BY SERVICE_ID DESC ',  
                   
                   'ORDER BY SV.SERVICE_ID DESC ', 
                   
                   'ORDER BY SERVICE_ID DESC ',  
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'SERVICE', 'SERVICE_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>