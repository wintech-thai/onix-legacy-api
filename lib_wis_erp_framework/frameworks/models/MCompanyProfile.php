<?php
/* 
Purpose : Model for COMPANY_PROFILE
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MCompanyProfile extends MBaseModel
{
    private $cols = array(

                [ # 0 For insert, update, delete 
                    'COMPANY_ID:SPK:COMPANY_ID:Y', 
                    'CODE:S:CODE:Y', 
                    'TAX_ID:S:TAX_ID:Y', 
                    'COMPANY_NAME_THAI:S:COMPANY_NAME_THAI:N', 
                    'COMPANY_NAME_ENG:S:COMPANY_NAME_ENG:Y', 
                    'OPERATOR_NAME_THAI:S:OPERATOR_NAME_THAI:Y', 
                    'OPERATOR_NAME_ENG:S:OPERATOR_NAME_ENG:Y',  
                    'ADDRESS:S:ADDRESS:Y', 
                    'ADDRESS_ENG:S:ADDRESS_ENG:Y', 
                    'TELEPHONE:S:TELEPHONE:Y',
                    'FAX:S:FAX:Y', 
                    'EMAIL:S:EMAIL:Y', 
                    'WEBSITE:S:WEBSITE:Y', 
                    'LOGO:S:LOGO:Y',
                    'PREFIX_ID:REFID:PREFIX_ID:Y',

                    'REGISTRATION_NAME:S:REGISTRATION_NAME:N',
                    'REGISTRATION_ADDRESS:S:REGISTRATION_ADDRESS:N',
                    'BUILDING_NAME:S:BUILDING_NAME:N',
                    'ROOM_NO:S:ROOM_NO:N',
                    'FLOOR_NO:S:FLOOR_NO:N',
                    'VILLAGE_NAME:S:VILLAGE_NAME:N',
                    'HOME_NO:S:HOME_NO:N',
                    'MOO:S:MOO:N',
                    'SOI:S:SOI:N',
                    'ROAD:S:ROAD:N',
                    'DISTRICT:S:DISTRICT:N',
                    'TOWN:S:TOWN:N',
                    'PROVINCE:S:PROVINCE:N',
                    'ZIP:S:ZIP:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N'
                ],

                [ # 1 Get Info
                    'CP.COMPANY_ID:SPK:COMPANY_ID:Y', 
                    'CP.CODE:S:CODE:Y', 
                    'CP.TAX_ID:S:TAX_ID:Y', 
                    'CP.COMPANY_NAME_THAI:S:COMPANY_NAME_THAI:N', 
                    'CP.COMPANY_NAME_ENG:S:COMPANY_NAME_ENG:Y', 
                    'CP.OPERATOR_NAME_THAI:S:OPERATOR_NAME_THAI:Y', 
                    'CP.OPERATOR_NAME_ENG:S:OPERATOR_NAME_ENG:Y',  
                    'CP.ADDRESS:S:ADDRESS:Y', 
                    'CP.ADDRESS_ENG:S:ADDRESS_ENG:Y', 
                    'CP.TELEPHONE:S:TELEPHONE:Y',
                    'CP.FAX:S:FAX:Y', 
                    'CP.EMAIL:S:EMAIL:Y', 
                    'CP.WEBSITE:S:WEBSITE:Y', 
                    'CP.LOGO:S:LOGO:Y',
                    'CP.PREFIX_ID:REFID:PREFIX_ID:Y',

                    'CP.REGISTRATION_NAME:S:REGISTRATION_NAME:N',
                    'CP.REGISTRATION_ADDRESS:S:REGISTRATION_ADDRESS:N',
                    'CP.BUILDING_NAME:S:BUILDING_NAME:N',
                    'CP.ROOM_NO:S:ROOM_NO:N',
                    'CP.FLOOR_NO:S:FLOOR_NO:N',
                    'CP.VILLAGE_NAME:S:VILLAGE_NAME:N',
                    'CP.HOME_NO:S:HOME_NO:N',
                    'CP.MOO:S:MOO:N',
                    'CP.SOI:S:SOI:N',
                    'CP.ROAD:S:ROAD:N',
                    'CP.DISTRICT:S:DISTRICT:N',
                    'CP.TOWN:S:TOWN:N',
                    'CP.PROVINCE:S:PROVINCE:N',
                    'CP.ZIP:S:ZIP:N',

                    'MR1.DESCRIPTION:S:PREFIX_NAME:Y',
                    'MR1.DESCRIPTION_ENG:S:PREFIX_NAME_ENG:Y',                    
                ]
    );

    private $froms = array(

                'FROM COMPANY_PROFILE ',

                'FROM COMPANY_PROFILE CP ' .
                    'LEFT OUTER JOIN MASTER_REF MR1 ON (MR1.MASTER_ID = CP.PREFIX_ID) ',            
    );

    private $orderby = array(

                'ORDER BY COMPANY_ID ASC ',
                'ORDER BY COMPANY_ID ASC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'COMPANY_PROFILE', 'COMPANY_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>