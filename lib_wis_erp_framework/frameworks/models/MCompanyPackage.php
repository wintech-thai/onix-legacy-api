<?php
/*
Purpose : Model for COMPANY_PACKAGE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCompanyPackage extends MBaseModel
{
    private $cols = array(

        [ # 0 For Insert, Update, Delete
            'COMPANY_PACKAGE_ID:SPK:COMPANY_PACKAGE_ID:Y',
            'COMPANY_ID:N:COMPANY_ID:N',
            'PACKAGE_ID:REFID:PACKAGE_ID:Y',
            'SEQUENCE_NO:N:SEQUENCE_NO:N',
            'ENABLE_FLAG:S:ENABLE_FLAG:N',
            'CREATE_DATE:CD:CREATE_DATE:N',
            'MODIFY_DATE:MD:MODIFY_DATE:N'
        ],
        [ # 1 For delete by parent
            'PACKAGE_ID:SPK:PACKAGE_ID:Y'
        ],
        [ # 2 For select items by parent
            'CP.COMPANY_PACKAGE_ID:SPK:COMPANY_PACKAGE_ID:Y',
            'CP.COMPANY_ID:REFID:COMPANY_ID:Y',
            'CP.PACKAGE_ID:REFID:PACKAGE_ID:Y',
            'CP.SEQUENCE_NO:N:SEQUENCE_NO:N',
            'CP.ENABLE_FLAG:S:ENABLE_FLAG:N',
            'PK.PACKAGE_TYPE:NZ:PACKAGE_TYPE:N',
            'PK.PACKAGE_NAME:S:PACKAGE_NAME:N',
            'PK.PACKAGE_CODE:S:PACKAGE_CODE:N',
            'PK.TIME_SPECIFIC_FLAG:S:TIME_SPECIFIC_FLAG:N',
            'PK.ENABLE_FLAG:S:ENABLE_FLAG:N',
            'PK.EFFECTIVE_DATE:S:EFFECTIVE_DATE:N',
            'PK.EXPIRE_DATE:S:EXPIRE_DATE:N',
            'PTM.PACKAGE_GROUP:N:PACKAGE_GROUP:N',
        ],


    );

    private $froms = array(

                'FROM COMPANY_PACKAGE ',

                'FROM COMPANY_PACKAGE ',

                'FROM COMPANY_PACKAGE CP '.
                    'LEFT OUTER JOIN PACKAGE PK ON (CP.PACKAGE_ID = PK.PACKAGE_ID) ' .
                    'LEFT OUTER JOIN PACKAGE_TYPE_MAP PTM ON (PK.PACKAGE_TYPE = PTM.PACKAGE_TYPE) '

    );

    private $orderby = array(

                   'ORDER BY SEQUENCE_NO ASC ',

                   'ORDER BY SEQUENCE_NO ASC ',

                   'ORDER BY CP.SEQUENCE_NO ASC '

    );

    function __construct($db)
    {
        parent::__construct($db, 'COMPANY_PACKAGE', 'COMPANY_PACKAGE_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>