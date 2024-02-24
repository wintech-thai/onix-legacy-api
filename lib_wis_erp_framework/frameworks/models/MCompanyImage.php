<?php
/* 
    Purpose : Model for COMPANY_IMAGE
    Created By : Seubpong Monsar
    Created Date : 11/18/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCompanyImage extends MBaseModel
{
    private $cols = array(
        [ # 0 For insert, update, delete
            'COMPANY_IMAGE_ID:SPK:COMPANY_IMAGE_ID:Y',
            'COMPANY_ID:REFID:COMPANY_ID:Y',
            'IMAGE_TYPE:REFID:IMAGE_TYPE:N',
            'IMAGE_NAME:S:IMAGE_NAME:N',

            'CREATE_DATE:CD:CREATE_DATE:N',
            'MODIFY_DATE:MD:MODIFY_DATE:N'
        ],

        [ # 1 Query in the item
            'COMPANY_IMAGE_ID:SPK:COMPANY_IMAGE_ID:Y',
            'COMPANY_ID:REFID:COMPANY_ID:Y',
            'IMAGE_TYPE:REFID:IMAGE_TYPE:N',
            'IMAGE_NAME:S:IMAGE_NAME:N',
            'IMAGE_NAME:S:IMAGE_NAME_WIP:N',
            
            'CREATE_DATE:CD:CREATE_DATE:N',
            'MODIFY_DATE:MD:MODIFY_DATE:N'
        ],        
    );

    private $froms = array(
        'FROM COMPANY_IMAGE ',
        'FROM COMPANY_IMAGE ',
    );

    private $orderby = array(
        'ORDER BY COMPANY_IMAGE_ID ASC ',
        'ORDER BY COMPANY_IMAGE_ID ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'COMPANY_IMAGE', 'COMPANY_IMAGE_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>