<?php
/* 
Purpose : Model for VOUCHER_TEMPLATE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MVoucherTemplate extends MBaseModel
{
    private $cols = array(

                [ # 0 insert update delete
                    'VOUCHER_TEMPLATE_ID:SPK:VOUCHER_TEMPLATE_ID:Y',
                    'VC_TEMPLATE_NO:S:VC_TEMPLATE_NO:Y',
                    'VC_TEMPLATE_NNAME:S:VC_TEMPLATE_NNAME:N',
                    'ENABLE_FLAG:S:ENABLE_FLAG:N',
                    'AMOUNT:N:AMOUNT:N',
                    'QUANTITY:N:QUANTITY:N',
                    'EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
                    'EXPIRE_DATE:S:EXPIRE_DATE:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                ],
                [ # 1 get query
                    'VOUCHER_TEMPLATE_ID:SPK:VOUCHER_TEMPLATE_ID:Y',
                    'VC_TEMPLATE_NO:S:VC_TEMPLATE_NO:Y',
                    'VC_TEMPLATE_NNAME:S:VC_TEMPLATE_NNAME:Y',
                    'ENABLE_FLAG:S:ENABLE_FLAG:N',
                    'AMOUNT:N:AMOUNT:N',
                    'QUANTITY:N:QUANTITY:N',
                    'EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
                    'EXPIRE_DATE:S:EXPIRE_DATE:N',

                    'EFFECTIVE_DATE:FD:FROM_EFFECTIVE_DATE:Y',
                    'EFFECTIVE_DATE:TD:TO_EFFECTIVE_DATE:Y',
                    'EXPIRE_DATE:FD:FROM_EXPIRE_DATE:Y',
                    'EXPIRE_DATE:TD:TO_EXPIRE_DATE:Y',
                ]

            
    );

    private $froms = array(

                'FROM VOUCHER_TEMPLATE ',
                'FROM VOUCHER_TEMPLATE '
            
    );

    private $orderby = array(

                   'ORDER BY VOUCHER_TEMPLATE_ID DESC ',
                   'ORDER BY VOUCHER_TEMPLATE_ID DESC '
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'VOUCHER_TEMPLATE', 'VOUCHER_TEMPLATE_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>