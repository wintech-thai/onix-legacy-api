<?php
/* 
    Purpose : Model for Package Period
    Created By : Seubpong Monsar
    Created Date : 09/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPackageBranch extends MBaseModel
{
    private $cols = array(
        [ # 0 For Insert, Update, Delete
        'PACKAGE_BRANCH_ID:SPK:PACKAGE_BRANCH_ID:Y', 
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',                    
        'BRANCH_ID:REFID:BRANCH_ID:Y',                                       
        'ENABLE_FLAG:S:ENABLE_FLAG:N',     
                           
        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N'
       ],    
       
       [ # 1 For delete by parent
        'PACKAGE_ID:SPK:PACKAGE_ID:Y' 
       ],  
       
       [ # 2 For select items by parent
        'PB.PACKAGE_BRANCH_ID:SPK:PACKAGE_BRANCH_ID:Y', 
        'PB.PACKAGE_ID:REFID:PACKAGE_ID:Y',                    
        'PB.BRANCH_ID:REFID:BRANCH_ID:N',                    
        'PB.ENABLE_FLAG:S:ENABLE_FLAG:N',                                                         
        'MR.CODE:S:BRANCH_CODE:N', 
        'MR.DESCRIPTION:S:BRANCH_NAME:N',                                                                      
       ],
);

    private $froms = array(
        'FROM PACKAGE_BRANCH ',            
        
        'FROM PACKAGE_BRANCH ',       

        'FROM PACKAGE_BRANCH PB  ' .   
            'LEFT OUTER JOIN MASTER_REF MR ON (PB.BRANCH_ID = MR.MASTER_ID) ',
    );

    private $orderby = array(
        'ORDER BY PACKAGE_BRANCH_ID ASC ',   
        
        'ORDER BY PACKAGE_BRANCH_ID ASC ',  
        
        'ORDER BY MR.DESCRIPTION ASC ',
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PACKAGE_BRANCH', 'PACKAGE_BRANCH_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>