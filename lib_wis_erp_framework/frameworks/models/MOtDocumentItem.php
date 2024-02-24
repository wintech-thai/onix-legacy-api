<?php
/* 
Purpose : Model for OT_DOC_ITEM
Created By : Seubpong Monsar
Created Date : 03/17/2019 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MOtDocumentItem extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'OT_DOC_ITEM_ID:SPK:OT_DOC_ITEM_ID:Y',
                    'OT_DOC_ID:REFID:OT_DOC_ID:Y',
                    'NOTE:S:NOTE:N',
                    'RECEIVE_AMOUNT:NZ:RECEIVE_AMOUNT:N',
                    'FROM_OT_DATE:S:FROM_OT_DATE:N',
                    'FROM_OT_TIME:S:FROM_OT_TIME:N',
                    'FROM_OT_HH:S:FROM_OT_HH:N',
                    'FROM_OT_MM:S:FROM_OT_MM:N',
                    'TO_OT_DATE:S:TO_OT_DATE:N',
                    'TO_OT_TIME:S:TO_OT_TIME:N',
                    'TO_OT_HH:S:TO_OT_HH:N',
                    'TO_OT_MM:S:TO_OT_MM:N',
                    'OT_HOUR:NZ:OT_HOUR:N',
                    'OT_RATE:NZ:OT_RATE:N',
                    'OT_AMOUNT:NZ:OT_AMOUNT:N',
                    'MULTIPLIER:NZ:MULTIPLIER:N',
                    'FROM_DAY_OF_WEEK:NZ:FROM_DAY_OF_WEEK:N',
                    'TO_DAY_OF_WEEK:NZ:TO_DAY_OF_WEEK:N',
                    'OT_ADJUST_HOUR:NZ:OT_ADJUST_HOUR:N',
                    'OT_ADJUSTED_TOTAL_HOUR:NZ:OT_ADJUSTED_TOTAL_HOUR:N',
                    'OT_FLAG:S:OT_FLAG:N',
                    'WORK_AMOUNT:NZ:WORK_AMOUNT:N',
                    'FROM_WORK_DATE:S:FROM_WORK_DATE:N',
                    'FROM_WORK_DAY_OF_WEEK:NZ:FROM_WORK_DAY_OF_WEEK:N',
                    'FROM_WORK_HH:S:FROM_WORK_HH:N',
                    'FROM_WORK_MM:S:FROM_WORK_MM:N',
                    'TO_WORK_HH:S:TO_WORK_HH:N',
                    'TO_WORK_MM:S:TO_WORK_MM:N',
                    'WORK_ADJUST_HOUR:NZ:WORK_ADJUST_HOUR:N',
                    'WORK_HOUR:NZ:WORK_HOUR:N',
                    'WORK_ADJUSTED_TOTAL_HOUR:NZ:WORK_ADJUSTED_TOTAL_HOUR:N',
                    'RECEIVE_TYPE:S:RECEIVE_TYPE:N',
                    
                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For delete by parent
                    'OT_DOC_ID:SPK:OT_DOC_ID:Y'
                  ],
                  
                  [ # 2
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',

                    'SUM(ODI.OT_AMOUNT):NZ:OT_AMOUNT:N',
                    'SUM(ODI.OT_AMOUNT):NZ:AMOUNT:N',
                    'SUM(ODI.OT_HOUR):NZ:OT_HOUR:N',
                    'SUM(ODI.OT_ADJUSTED_TOTAL_HOUR):NZ:OT_ADJUSTED_TOTAL_HOUR:N',

                    'OD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'OD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ],   
                  
                  [ # 3
                    'OD.EMPLOYEE_ID:REFID:EMPLOYEE_ID:Y',

                    'SUM(ODI.WORK_AMOUNT):NZ:WORK_AMOUNT:N',
                    'SUM(ODI.WORK_AMOUNT):NZ:AMOUNT:N',
                    'SUM(ODI.WORK_HOUR):NZ:WORK_HOUR:N',
                    'SUM(ODI.WORK_ADJUSTED_TOTAL_HOUR):NZ:WORK_ADJUSTED_TOTAL_HOUR:N',

                    'OD.DOCUMENT_DATE:FD:FROM_DOCUMENT_DATE:Y',
                    'OD.DOCUMENT_DATE:TD:TO_DOCUMENT_DATE:Y', 
                  ],                   
    );

    private $froms = array(

                'FROM OT_DOC_ITEM ',

                'FROM OT_DOC_ITEM ',

                'FROM OT_DOC_ITEM ODI ' .
                  'LEFT OUTER JOIN OT_DOCUMENT OD ON (ODI.OT_DOC_ID = OD.OT_DOC_ID) ',

                  'FROM OT_DOC_ITEM ODI ' .
                  'LEFT OUTER JOIN OT_DOCUMENT OD ON (ODI.OT_DOC_ID = OD.OT_DOC_ID) ',                  
    );

    private $orderby = array(

                'ORDER BY OT_DOC_ITEM_ID ASC ',

                'ORDER BY OT_DOC_ITEM_ID ASC ',

                'GROUP BY OD.EMPLOYEE_ID ORDER BY OD.EMPLOYEE_ID ASC ',

                'GROUP BY OD.EMPLOYEE_ID ORDER BY OD.EMPLOYEE_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'OT_DOC_ITEM', 'OT_DOC_ITEM_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>