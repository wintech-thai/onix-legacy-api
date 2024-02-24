<?php
/* 
    Purpose : Controller for Document number
    Created By : Seubpong Monsar
    Created Date : 10/29/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class NumberGenerator extends CBaseController
{
  private static $cfg = NULL;

  private static function initSqlConfig($db)
  {
      $config = [
          //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
          ['DOCUMENT_NUMBER_LIST', new MFrwDocumentNumber($db), 1, 0, 0],
      ];

      self::$cfg = $config;

      return($config);
  }

  private static function createObject($db)
  {
      $u = new MFrwDocumentNumber($db);
      return($u);
  }

  public static function CreateDocumentNumber($db, $param, $data)
  {
      $u = self::createObject($db);
      
      $childs = self::initSqlConfig($db);
      self::CreateData($db, $data, $u, 0, $childs);

      return(array($param, $data));        
  }    

  public static function UpdateDocumentNumber($db, $param, $data)
  {      
      $u = new MVirtualModel('');

      $childs = self::initSqlConfig($db);
      self::UpdateData($db, $data, $u, 0, $childs);

      return(array($param, $data));        
  }      

  public static function DeleteDocumentNumber($db, $param, $data)
  {
      $u = self::createObject($db);
      
      $childs = self::initSqlConfig($db);
      self::DeleteData($db, $data, $u, 0, $childs);
      
      return(array($param, $data));
  }

  public static function GetDocumentNumberList($db, $param, $data)
  {
      $u = self::createObject($db);
      list($cnt, $item_cnt, $chunk_cnt, $rows) = $u->QueryChunk(1, $data);

      $pkg = new CTable($u->GetTableName());
      self::PopulateRow($pkg, $item_cnt, $chunk_cnt, 'DOCUMENT_NUMBER_LIST', $rows);
      
      return(array($param, $pkg));
  }

  public static function GetDocumentNumberInfo($db, $param, $data)
  {
      //self::SetDumpSQL(true);
      $obj = self::GetDocumentNumberList($db, $param, $data);

      return($obj);        
  }
  
  private static function definitionToHash($definition)
  {
      $hash = [];
      $arr = explode("|", $definition);

      foreach ($arr as $token)
      {
          if ($token == '')
          {
              continue;
          }
          
          list($key, $value) = explode("=", $token);
          $hash[$key] = $value;
      }

      return($hash);
  }

  private static function hashToDefinition($hash)
  {
      $arr = [];
      foreach ($hash as $key => $value)
      {
          $token = "$key=$value";
          array_push($arr, $token);
      }

      $definition = implode("|", $arr);
      return($definition);
  }
  
  private static function getCustomSequence($customSeqDef, $customSeqKey)
  {
      $seq = 0;
      $definition = '';

      $hash = self::definitionToHash($customSeqDef);
      if (array_key_exists($customSeqKey, $hash))
      {
          $seq = $hash[$customSeqKey];        
      }

      $seq++;
      $hash[$customSeqKey] = $seq;

      $definition = self::hashToDefinition($hash);

      return([$seq, $definition]);
  }

  public static function newDocumentNumber($db, $data)
  {
      $months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

      $formula = $data->getFieldValue("FORMULA");
      $offset = $data->getFieldValue("YEAR_OFFSET");
      $currentSeq = $data->getFieldValue("CURRENT_SEQ");
      $lastRunYear = $data->getFieldValue("LAST_RUN_YEAR");
      $lastRunMonth = $data->getFieldValue("LAST_RUN_MONTH");
      $resetCriteria = $data->getFieldValue("RESET_CRITERIA");
      $startSeq = $data->getFieldValue("START_SEQ");
      $seqLength = $data->getFieldValue("SEQ_LENGTH");
      $customSeq = $data->getFieldValue("USE_CUSTOM_SEQ");
      $customSeqVar = $data->getFieldValue("CUSTOM_SEQ_VAR");
      $customSeqDef = $data->getFieldValue("SEQUENCE_DEFINITION");

      list($sec, $min, $hour, $mday, $mon, $year, $wday, $yday, $isdst) = localtime();

      $centuryYear = $year + 1900;
      $monthNo = $mon + 1;

      $monthKey = sprintf("%d:%02d", $centuryYear, $monthNo);
      $lastRunKey = sprintf("%d:%02d", $lastRunYear, $lastRunMonth);

      $variables = ['${seq}', '${mmm}', '${mm}', '${yyyy}', '${yy}'];
      $hash = [];
      
      $seq = $currentSeq + 1;


      $customSeqKey = '';
      $custom_vars = $data->getChildArray('CUSTOM_VARIABLES');
      foreach ($custom_vars as $var)
      {
          $ptrn = $var->getFieldValue('VARIABLE_NAME');
          $value = $var->getFieldValue('VARIABLE_VALUE');

          $hash[$ptrn] = $value;

          if ($ptrn == $customSeqVar)
          {
              $customSeqKey = $value;
          }
      }

      $newCustomSeqDef = $customSeqDef;

      foreach ($variables as $ptrn)
      {
          $value = "";
          
          if (preg_match('/seq/',$ptrn))
          { 
              if ($customSeq == 'Y')
              {
                  if ($customSeqKey != '')
                  {
                      //User custom sequence
                      list($seq, $newCustomSeqDef) = self::getCustomSequence($customSeqDef, $customSeqKey);
                  }
              }
              else if ($resetCriteria == 1)
              {
                  #Monthly
                  if ($monthKey != $lastRunKey)
                  {
                      #Reset value
                      $seq = $startSeq;
                  }
              }
              else
              {
                  #Yearly
                  if ($centuryYear != $lastRunYear)
                  {
                      #Reset value
                      $seq = $startSeq;
                  }                
              } 
              
              $fmt = "%0$seqLength" . 'd';
              $value = sprintf($fmt, $seq);
          }
          elseif (preg_match('/mmm/',$ptrn))
          {
              $value = $months[$mon];
          }
          elseif (preg_match('/mm/',$ptrn))
          {
              $value = sprintf("%02d", $monthNo);
          }        
          elseif (preg_match('/yyyy/',$ptrn))
          {
              $value = $centuryYear + $offset;
          }
          elseif (preg_match('/yy/',$ptrn))
          {
              $yearStr = $centuryYear + $offset;
              $value = substr($yearStr . '', 2, 2);
          }

          $hash[$ptrn] = $value;
    }
         
//$tmp = new CTable('');
//$tmp->SetFieldValue('VARIABLE_NAME', '${ud_mmyy}');    
//$tmp->SetFieldValue('VARIABLE_VALUE', '0418');
//$custom_vars = [$tmp];

     $subValue = $formula;
      
      foreach ($hash as $key => $value)
      {
          $subValue = str_replace($key, $value ,$subValue);
      }
      
      #Update value back 
      $data->setFieldValue("CURRENT_SEQ", $seq);
      $data->setFieldValue("LAST_RUN_YEAR", $centuryYear);
      $data->setFieldValue("LAST_RUN_MONTH", $monthNo);
      $data->setFieldValue("LAST_DOCUMENT_NO", $subValue);
      $data->setFieldValue("SEQUENCE_DEFINITION", $newCustomSeqDef);
      
      return($data);
  }

  public static function GenerateDocumentNumber($db, $param, $data)
  {
    $u = self::createObject($db);
    
    $childs = self::initSqlConfig($db);

    $obj = self::GetFirstRow($data, $u, 0, 'DOC_TYPE');
    $arr = $data->getChildArray('CUSTOM_VARIABLES');
    $obj->addChildArray('CUSTOM_VARIABLES', $arr);

    if (!isset($obj))
    {
        throw new Exception("No this doc type in database!!!");
    }

    $documentNumber = self::newDocumentNumber($db, $obj);
    self::UpdateData($db, $documentNumber, $u, 0, $childs);

    return([$param, $documentNumber]);
  }

  public static function TestGenerateDocumentNumber($db, $param, $data)
  {
    $u = self::createObject($db);
    
    $childs = self::initSqlConfig($db);

    $obj = self::GetFirstRow($data, $u, 0, 'DOC_TYPE');

    if (!isset($obj))
    {
        throw new Exception("No this doc type in database!!!");
    }

    $documentNumber = self::newDocumentNumber($db, $obj);
    return([$param, $documentNumber]);
  }

}