<?php
/* 
Purpose : Model for ENTITY_BANK_ACCOUNT
Created By : Seubpong Monsar
Created Date : 01/21/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MEntityBankAccount extends MBaseModel
{
    private $cols = array(

                  [ # 0 For query, insert, delete, update
                   'ENTITY_BACCT_ID:SPK:ENTITY_BACCT_ID:Y', 
                   'ENTITY_ID:REFID:ENTITY_ID:Y',
                   'BANK_ID:REFID:BANK_ID:Y',
                   'ACCOUNT_NO:S:ACCOUNT_NO:Y', 
                   'ACCOUNT_NAME:S:ACCOUNT_NAME:Y', 
                   'ACCOUNT_TYPE:REFID:ACCOUNT_TYPE:Y', 
                   'SEQ_NO:NZ:SEQ_NO:Y',

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                  ],  
                  
                  [ # 1 For Entity Bank Account List
                   'EBA.ENTITY_BACCT_ID:SPK:ENTITY_BACCT_ID:Y', 
                   'EBA.ENTITY_ID:REFID:ENTITY_ID:Y',
                   'EBA.BANK_ID:REFID:BANK_ID:Y',
                   'EBA.ACCOUNT_NO:S:ACCOUNT_NO:Y', 
                   'EBA.ACCOUNT_NAME:S:ACCOUNT_NAME:Y', 
                   'EBA.ACCOUNT_TYPE:REFID:ACCOUNT_TYPE:Y', 
                   'EBA.SEQ_NO:NZ:SEQ_NO:Y',

                   'BK.DESCRIPTION:S:BANK_NAME:N',
                   'AT.DESCRIPTION:S:ACCOUNT_TYPE_NAME:N',
                  ],

                  [ # 2 For delete by parent
                   'ENTITY_ID:SPK:ENTITY_ID:N'
                  ],                                                                                   
              
    );

    private $froms = array(

                   'FROM ENTITY_BANK_ACCOUNT ',

                   'FROM ENTITY_BANK_ACCOUNT EBA '.
                       'LEFT OUTER JOIN MASTER_REF BK ON (EBA.BANK_ID = BK.MASTER_ID) '.
                       'LEFT OUTER JOIN MASTER_REF AT ON (EBA.ACCOUNT_TYPE = AT.MASTER_ID) ',

                   'FROM ENTITY_BANK_ACCOUNT '                                                 
            
    );

    private $orderby = array(

                   'ORDER BY ENTITY_BACCT_ID ASC ',     
                   'ORDER BY EBA.SEQ_NO ASC ',                        
                   'ORDER BY ENTITY_BACCT_ID ASC ',                                                                                       
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'ENTITY_BANK_ACCOUNT', 'ENTITY_BACCT_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>