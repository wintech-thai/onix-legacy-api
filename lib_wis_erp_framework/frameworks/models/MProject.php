<?php
/* 
Purpose : Model for PROJECT
Created By : Seubpong Monsar
Created Date : 01/12/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MProject extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'PROJECT_ID:SPK:PROJECT_ID:Y',
                    'PROJECT_CODE:S:PROJECT_CODE:Y',
                    'PROJECT_NAME:S:PROJECT_NAME:Y',
                    'PROJECT_DESC:S:PROJECT_DESC:N',
                    'PROJECT_GROUP:REFID:PROJECT_GROUP:Y',
                    'PRODUCT:S:PRODUCT:N',
                    'CBU:S:CBU:N',
                    'SYSTEM:S:SYSTEM:N',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get List
                    'PJ.PROJECT_ID:SPK:PROJECT_ID:Y',
                    'PJ.PROJECT_CODE:S:PROJECT_CODE:Y',
                    'PJ.PROJECT_NAME:S:PROJECT_NAME:Y',
                    'PJ.PROJECT_DESC:S:PROJECT_DESC:N',
                    'PJ.PROJECT_GROUP:REFID:PROJECT_GROUP:Y',
                    'PJ.PRODUCT:S:PRODUCT:N',
                    'PJ.CBU:S:CBU:N',
                    'PJ.SYSTEM:S:SYSTEM:N',

                    'MR1.DESCRIPTION:S:PROJECT_GROUP_NAME:N', 
                    'MR1.CODE:S:PROJECT_GROUP_CODE:Y', 

                  ],
    );

    private $froms = array(

                'FROM PROJECT ',

                'FROM PROJECT PJ ' .
                    'LEFT OUTER JOIN MASTER_REF MR1 ON (PJ.PROJECT_GROUP = MR1.MASTER_ID) ',
    );

    private $orderby = array(

                'ORDER BY PROJECT_ID ASC ',
                
                'ORDER BY PJ.PROJECT_ID ASC ',     
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PROJECT', 'PROJECT_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>