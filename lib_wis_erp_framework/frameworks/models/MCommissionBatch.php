<?php
/*
Purpose : Model for CommissionBatch
Created By : Supakit Tanyung
Created Date : 10/05/2017 (MM/DD/YYYY)
IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCommissionBatch extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update
                    'COMMISSION_BATCH_ID:SPK:COMMISSION_BATCH_ID:Y',
                    'DOCUMENT_NO:S:DOCUMENT_NO:Y',
                    'RUN_DATE:S:RUN_DATE:N',
                    'DUE_DATE:S:DUE_DATE:N',
                    'CYCLE_TYPE:N:CYCLE_TYPE:N',
                    'CYCLE_ID:REFID:CYCLE_ID:N',
                    'BATCH_DESC:S:BATCH_DESC:N',
                    'BATCH_STATUS:N:BATCH_STATUS:N',
                    'APPROVED_DATE:S:APPROVED_DATE:N',
                    'APPROVED_SEQ:NZ:APPROVED_SEQ:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],

                  [ # 1 For query
                  'CB.COMMISSION_BATCH_ID:SPK:COMMISSION_BATCH_ID:Y',
                  'CB.DOCUMENT_NO:S:DOCUMENT_NO:Y',
                  'CB.RUN_DATE:S:RUN_DATE:N',
                  'CB.DUE_DATE:S:DUE_DATE:N',
                  'CB.CYCLE_TYPE:N:CYCLE_TYPE:Y',
                  'CB.CYCLE_ID:REFID:CYCLE_ID:Y',
                  'CB.BATCH_DESC:S:BATCH_DESC:Y',
                  'CB.BATCH_STATUS:N:BATCH_STATUS:Y',
                  'CB.APPROVED_DATE:S:APPROVED_DATE:N',
                  'CB.APPROVED_SEQ:N:APPROVED_SEQ:N',

                  'CB.CREATE_DATE:CD:CREATE_DATE:N',
                  'CB.MODIFY_DATE:MD:MODIFY_DATE:N',

                  'CB.RUN_DATE:FD:FROM_RUN_DATE:Y',
                  'CB.RUN_DATE:TD:TO_RUN_DATE:Y',
                  'CB.DUE_DATE:FD:FROM_DUE_DATE:Y',
                  'CB.DUE_DATE:TD:TO_DUE_DATE:Y',
                ],

                [ # 2 For approve
                  'COMMISSION_BATCH_ID:SPK:COMMISSION_BATCH_ID:Y',
                  'BATCH_STATUS:N:BATCH_STATUS:N',
                  'APPROVED_DATE:S:APPROVED_DATE:N',
                  'APPROVED_SEQ:NZ:APPROVED_SEQ:N',
                ],

    );

    private $froms = array(

                   'FROM COMMISSION_BATCH ',

                   'FROM COMMISSION_BATCH CB ' .
                       'LEFT OUTER JOIN CYCLE CC ON (CB.CYCLE_ID = CC.CYCLE_ID) ',

    );

    private $orderby = array(

                   'ORDER BY COMMISSION_BATCH_ID DESC ',

                   'ORDER BY CB.COMMISSION_BATCH_ID DESC ',

    );

    function __construct($db)
    {
        parent::__construct($db, 'COMMISSION_BATCH', 'COMMISSION_BATCH_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>