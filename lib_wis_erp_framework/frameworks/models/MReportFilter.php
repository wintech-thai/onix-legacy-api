<?php
/* 
    Purpose : Model for REPORT_FILTER
    Created By : Seubpong Monsar
    Created Date : 12/25/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MReportFilter extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update, delete
            'REPORT_FILTER_ID:SPK:REPORT_FILTER_ID:Y',
            'REPORT_NAME:S:REPORT_NAME:Y',
            'REPORT_GROUP:S:REPORT_GROUP:Y',
            'REPORT_SEQ:N:REPORT_SEQ:Y',
            'IS_SELECTED:S:IS_SELECTED:Y',
            'REPORT_NS:NZ:REPORT_NS:Y',

            'CREATE_DATE:CD:CREATE_DATE:N',
            'MODIFY_DATE:MD:MODIFY_DATE:N'
        ],
    );

    private $froms = array(
        'FROM REPORT_FILTER ',
    );

    private $orderby = array(
        'ORDER BY REPORT_FILTER_ID ASC ',
        '',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'REPORT_FILTER', 'REPORT_FILTER_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>