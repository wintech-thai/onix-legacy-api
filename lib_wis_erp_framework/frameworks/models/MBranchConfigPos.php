<?php
/* 
Purpose : Model for BRANCH_CONFIG_POS
Created By : Seubpong Monsar
Created Date : 11/03/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MBranchConfigPos extends MBaseModel
{
    private $cols = array(

                [ # 0 For Insert, Update, Delete
                    'BRANCH_POS_ID:SPK:BRANCH_POS_ID:Y',
                    'BRANCH_CONFIG_ID:REFID:BRANCH_CONFIG_ID:Y',
                    'POS_SERIAL_NO:S:POS_SERIAL_NO:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                ], 

                [ # 1 Delete by parent
                    'BRANCH_CONFIG_ID:SPK:BRANCH_CONFIG_ID:Y',
                ],                                     
    );

    private $froms = array(

                'FROM BRANCH_CONFIG_POS ',
                'FROM BRANCH_CONFIG_POS ',                           
    );

    private $orderby = array(

                'ORDER BY BRANCH_POS_ID DESC ',
                'ORDER BY BRANCH_POS_ID DESC ',                
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'BRANCH_CONFIG_POS', 'BRANCH_POS_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>