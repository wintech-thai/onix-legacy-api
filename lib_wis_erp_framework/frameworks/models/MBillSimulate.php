<?php
/* 
Purpose : Model for BILL_SIMULATE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MBillSimulate extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update
                   'BILL_SIMULATE_ID:SPK:BILL_SIMULATE_ID:Y',
                   'DOCUMENT_NO:S:DOCUMENT_NO:Y',
                   'DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                   'DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                   'DOCUMENT_STATUS:N:DOCUMENT_STATUS:Y',
                   'CUSTOMER_ID:REFID:CUSTOMER_ID:Y',
                   'SIMULATE_TIME:S:SIMULATE_TIME:N',
                   'NOTE:S:NOTE:N',
                   'BRANCH_ID:REFID:BRANCH_ID:Y',
                   'SIMULATION_FLAG:S:SIMULATION_FLAG:Y',

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For query
                   'BS.BILL_SIMULATE_ID:SPK:BILL_SIMULATE_ID:Y',
                   'BS.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                   'BS.DOCUMENT_DATE:S:DOCUMENT_DATE:N',
                   'BS.DOCUMENT_TYPE:N:DOCUMENT_TYPE:Y',
                   'BS.DOCUMENT_STATUS:N:DOCUMENT_STATUS:Y',
                   'BS.CUSTOMER_ID:REFID:CUSTOMER_ID:Y',
                   'BS.SIMULATE_TIME:S:SIMULATE_TIME:N',    
                   'BS.NOTE:S:NOTE:N',
                   'BS.BRANCH_ID:REFID:BRANCH_ID:Y',
                   'BS.SIMULATION_FLAG:S:SIMULATION_FLAG:Y',

                   'BS.CREATE_DATE:CD:CREATE_DATE:N',
                   'BS.MODIFY_DATE:MD:MODIFY_DATE:N',

                   'EN.ENTITY_CODE:S:CUSTOMER_CODE:Y',
                   'EN.ENTITY_NAME:S:CUSTOMER_NAME:Y',
                   'EN.ENTITY_TYPE:REFID:CUSTOMER_TYPE:Y',
                   'EN.ENTITY_GROUP:REFID:CUSTOMER_GROUP:Y',

                   'MR.CODE:S:BRANCH_CODE:Y',
                   'MR.DESCRIPTION:S:BRANCH_NAME:Y',
                                      
                    #Always put these at the end
                   'BS.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                   'BS.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y',
                  ],
              
    );

    private $froms = array(

                   'FROM BILL_SIMULATE ',

                   'FROM BILL_SIMULATE BS '.
                        'LEFT OUTER JOIN ENTITY EN ON (BS.CUSTOMER_ID = EN.ENTITY_ID) ' .
                        'LEFT OUTER JOIN MASTER_REF MR ON (BS.BRANCH_ID = MR.MASTER_ID) ',

            
    );

    private $orderby = array(

                   'ORDER BY BILL_SIMULATE_ID DESC ',

                   'ORDER BY BS.BILL_SIMULATE_ID DESC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'BILL_SIMULATE', 'BILL_SIMULATE_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>