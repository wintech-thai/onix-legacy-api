<?php
/* 
    Purpose : Model for Package Period
    Created By : Seubpong Monsar
    Created Date : 09/07/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MPackagePeriod extends MBaseModel
{
    private $cols = array(
        [ # 0 For query, insert, delete, update
        'PACKAGE_PERIOD_ID:SPK:PACKAGE_PERIOD_ID:N', 
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',                    
        'DAY_OF_WEEK:N:DAY_OF_WEEK:N',                    
        'PERIOD_TYPE:N:PERIOD_TYPE:N',                    
        'FROM_HOUR1:S:FROM_HOUR1:N', 
        'FROM_MINUTE1:S:FROM_MINUTE1:N', 
        'TO_HOUR1:S:TO_HOUR1:N', 
        'TO_MINUTE1:S:TO_MINUTE1:N', 
        'FROM_HOUR2:S:FROM_HOUR2:N', 
        'FROM_MINUTE2:S:FROM_MINUTE2:N', 
        'TO_HOUR2:S:TO_HOUR2:N', 
        'TO_MINUTE2:S:TO_MINUTE2:N', 
        'ENABLE_FLAG:S:ENABLE_FLAG:N',
                                                                                    
        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N'
       ],  
       
       [ # 1 For delete by parent
        'PACKAGE_ID:SPK:PACKAGE_ID:N'
       ],
     [ # 2 For For Get Company Package All
        'PACKAGE_PERIOD_ID:SPK:PACKAGE_PERIOD_ID:N', 
        'PACKAGE_ID:REFID:PACKAGE_ID:Y',                    
        'DAY_OF_WEEK:N:DAY_OF_WEEK:N',                    
        'PERIOD_TYPE:N:PERIOD_TYPE:N',                    
        'FROM_HOUR1:S:FROM_HOUR1:N', 
        'FROM_MINUTE1:S:FROM_MINUTE1:N', 
        'TO_HOUR1:S:TO_HOUR1:N', 
        'TO_MINUTE1:S:TO_MINUTE1:N', 
        'FROM_HOUR2:S:FROM_HOUR2:N', 
        'FROM_MINUTE2:S:FROM_MINUTE2:N', 
        'TO_HOUR2:S:TO_HOUR2:N', 
        'TO_MINUTE2:S:TO_MINUTE2:N', 
        'ENABLE_FLAG:S:ENABLE_FLAG:N',
                                                                                    
        'CREATE_DATE:CD:CREATE_DATE:N',
        'MODIFY_DATE:MD:MODIFY_DATE:N',

        'PACKAGE_ID:INC_SET:PACKAGE_ID_SET:Y'
       ]
    );

    private $froms = array(
        'FROM PACKAGE_PERIOD ',        
        
        'FROM PACKAGE_PERIOD ',

        'FROM PACKAGE_PERIOD '
    );

    private $orderby = array(
        'ORDER BY PACKAGE_PERIOD_ID ASC ',     
        
        'ORDER BY PACKAGE_PERIOD_ID ASC ',                                                                                       

        'ORDER BY PACKAGE_PERIOD_ID ASC '
    );

    function __construct($db) 
    {
        parent::__construct($db, 'PACKAGE_PERIOD', 'PACKAGE_PERIOD_ID', $this->cols, $this->froms, $this->orderby);
    }
}

?>