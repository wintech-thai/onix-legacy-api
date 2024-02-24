<?php
/* 
Purpose : Model for ACCOUNT_DOC_REMARK
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";

class MAuxilaryDocRemark extends MBaseModel
{
    private $cols = array(

                  [ # 0 For insert, update, delete
                    'AUXILARY_DOC_REMARK_ID:SPK:AUXILARY_DOC_REMARK_ID:Y',
                    'AUXILARY_DOC_ID:REFID:AUXILARY_DOC_ID:Y',
                    'CODE_REFERENCE:S:CODE_REFERENCE:Y',
                    'NOTE:S:NOTE:Y',

                    'CREATE_DATE:CD:CREATE_DATE:N',
                    'MODIFY_DATE:MD:MODIFY_DATE:N',
                  ],
                  
                  [ # 1 For Get Account Aux Item List
                    'AR.AUXILARY_DOC_REMARK_ID:SPK:AUXILARY_DOC_REMARK_ID:Y',
                    'AR.AUXILARY_DOC_ID:REFID:AUXILARY_DOC_ID:Y',
                    'AR.CODE_REFERENCE:S:CODE_REFERENCE:N',
                    'AR.NOTE:S:NOTE:N',               
                  ],

                  [ # 2 For Delete by parent
                      'AUXILARY_DOC_ID:SPK:AUXILARY_DOC_ID:Y',
                  ],
    );

    private $froms = array(

                'FROM AUXILARY_DOC_REMARK ',

                'FROM AUXILARY_DOC_REMARK AR '.
                    'LEFT OUTER JOIN AUXILARY_DOC AD ON (AD.AUXILARY_DOC_ID = AR.AUXILARY_DOC_ID) ',

                'FROM AUXILARY_DOC_REMARK ',
    );

    private $orderby = array(

                'ORDER BY AUXILARY_DOC_REMARK_ID ASC ',
                
                'ORDER BY AR.AUXILARY_DOC_REMARK_ID ASC ',
                
                'ORDER BY AUXILARY_DOC_REMARK_ID ASC ',         
    );

    function __construct($db) 
    {
        parent::__construct($db, 'AUXILARY_DOC_REMARK', 'AUXILARY_DOC_REMARK_ID', $this->cols, $this->froms, $this->orderby);
    }
}
?>