<?php
/* 
    Purpose : Model for GLOBAL_VARIABLE
    Created By : Seubpong Monsar
    Created Date : 11/14/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MReportConfig extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update, delete
            'REPORT_CONFIG_ID:SPK:REPORT_CONFIG_ID:Y',
            'REPORT_NAME:S:REPORT_NAME:Y',
            'CONFIG_VALUE:S:CONFIG_VALUE:N',
            'USER_ID:REFID:USER_ID:Y',

            'CREATE_DATE:CD:CREATE_DATE:N',
            'MODIFY_DATE:MD:MODIFY_DATE:N'
        ],

        [ # 1 For Query - Suppress CONFIG_VALUE field
            'REPORT_CONFIG_ID:SPK:REPORT_CONFIG_ID:Y',
            'REPORT_NAME:S:REPORT_NAME:Y',
            'USER_ID:REFID:USER_ID:Y',
        ],        
    );

    private $froms = array(
        'FROM REPORT_CONFIG ',
        'FROM REPORT_CONFIG ',
    );

    private $orderby = array(
        'ORDER BY REPORT_CONFIG_ID ASC ',
        'ORDER BY REPORT_CONFIG_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'REPORT_CONFIG', 'REPORT_CONFIG_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>