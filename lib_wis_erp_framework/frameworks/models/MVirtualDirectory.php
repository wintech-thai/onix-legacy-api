<?php
/* 
Purpose : Model for VIRTUAL_DIRECTORY
Created By : Seubpong Monsar
Created Date : 12/23/2018 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MVirtualDirectory extends MBaseModel
{
    private $cols = array(

                [ # 0 For insert, update, delete
                   'DIRECTORY_ID:SPK:DIRECTORY_ID:Y', 
                   'DIRECTORY_NAME:S:DIRECTORY_NAME:Y', 
                   'DIRECTORY_DESCRIPTION:S:DIRECTORY_DESCRIPTION:Y', 
                   'PARENT_DIRECTORY_ID:REFID:PARENT_DIRECTORY_ID:Y', 
                   'CATEGORY:N:CATEGORY:Y', 

                   'CREATE_DATE:CD:CREATE_DATE:N',
                   'MODIFY_DATE:MD:MODIFY_DATE:N'
                ],
    
                [ # 1 Query
                    'VD.DIRECTORY_ID:SPK:DIRECTORY_ID:Y', 
                    'VD.DIRECTORY_NAME:S:DIRECTORY_NAME:Y', 
                    'VD.DIRECTORY_DESCRIPTION:S:DIRECTORY_DESCRIPTION:Y', 
                    'VD.PARENT_DIRECTORY_ID:REFID:PARENT_DIRECTORY_ID:Y', 
                    'VD.CATEGORY:N:CATEGORY:Y', 

                    'VD.PARENT_DIRECTORY_ID:IS_NULL:IS_NULL_PARENT:Y', 
                ],                                                
    );

    private $froms = array(
                               
                'FROM VIRTUAL_DIRECTORY ',

                'FROM VIRTUAL_DIRECTORY VD ',            
    );

    private $orderby = array(

                'ORDER BY DIRECTORY_ID ASC, DIRECTORY_NAME ASC ',

                'ORDER BY DIRECTORY_ID DESC, DIRECTORY_NAME ASC ',
            
    );

    function __construct($db) 
    {
        parent::__construct($db, 'VIRTUAL_DIRECTORY', 'DIRECTORY_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>