<?php
/* 
    Created By : Seubpong Monsar
    Created Date : 09/02/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class CUtils
{
    /* Start Private methods here */

    private static function populateTableObject($obj_node, $tabName)
    {
        $children = $obj_node->childNodes;
        $table = new CTable($tabName);
    
        $idx = 0;
        foreach ($children as $node)
        {
            $tagName = $node->nodeName;
    
            if ($tagName == 'FIELD')
            {
                $value = $node->textContent;
                $fldName = $node->getAttribute('name');

                $table->setFieldValue($fldName, $value);
            }
            elseif ($tagName == 'ITEMS')
            {
                $itemName = $node->getAttribute('name');
                $items = $node->childNodes;
                $arr = array();
#printf("DEBUG0 [$itemName] \n");
                foreach ($items as $node2)
                {
                    $tagName = $node2->nodeName;
                    if ($tagName == 'OBJECT')
                    {
                        $tbName = $node2->getAttribute('name');
                        //Recursive
                        $obj = self::populateTableObject($node2, $tbName);
                        array_push($arr, $obj);
                    }
                }
    
                $table->addChildArray($itemName, $arr);
            }
        }
    
        return($table);    
    }

    private static function createObjElement($obj, $doc)
    {
        $sorted_flds = $obj->GetNameArray();
        sort($sorted_flds);

        $elm = $doc->createElement("OBJECT");
        $elm->setAttribute("name", $obj->getTableName());

        foreach ($sorted_flds as $fname)
        {
            $value = $obj->GetFieldValue($fname);
    
            $field_elm = $doc->createElement("FIELD");
            $field_elm->setAttribute("name", $fname);
       
            $txt_node = $doc->createTextNode("$value");
            $field_elm->appendChild($txt_node);
    
            $elm->appendChild($field_elm);
        }     
        
        $map = $obj->GetChildHash();
        ksort($map);
        
        foreach ($map as $item_name => $arr)
        {
            $item_elm = $doc->createElement("ITEMS");
            $item_elm->setAttribute("name", $item_name);
    
            foreach ($arr as $o)
            {
                $tb = $o->getTableName();
    
                #Recursive
                $obj_elm = self::createObjElement($o, $doc);
                $item_elm->appendChild($obj_elm);
            }
    
            $elm->appendChild($item_elm);            
        }

        return($elm);
    }

    /* End Private methods */
    public static function RowToHash($rows, $keyField)
    {
        $arr = array();
        foreach ($rows as $r)
        {
            $key = $r->GetFieldValue($keyField);
            $arr[$key] = $r;
        }

        return($arr);
    }

    public static function LockEntity($entity_name)
    {
        $name = $_ENV['LOCK_DIR'] . "/$entity_name.lock";
    
        $fh = fopen($name, "w");
        if (!$fh)
        {
            throw new Exception("Unable to open file [$name] !!!!");
        }

        $result = flock($fh, LOCK_EX);
        if (!$result)
        {
            throw new Exception("Unable to lock file!!!!");
        }

    #print("IN LOCKING START\n");
    #sleep(10);
    #print("IN LOCKING END\n");
    
        return($fh);
    }
    
    public static function UnlockEntity($fh)
    {
        $result = flock($fh, LOCK_UN);
        if (!$result)
        {
            throw new Exception("");
        }

    #print("IN UNLOCKING\n");
    
        return;
    }

    public static function Decrypt($ctext)
    {
        if (array_key_exists('SYMKEY', $_ENV))
        {
            $key = $_ENV['SYMKEY'];
            $iv = $_ENV['INIT_VECTOR'];
        }
        else
        {
            $key = getenv('SYMKEY');
            $iv = getenv('INIT_VECTOR');            
        }

        $text = openssl_decrypt(base64_decode($ctext), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return($text);
    }

    public static function DecryptUnzip($ctext)
    {
        if (array_key_exists('SYMKEY', $_ENV))
        {
            $key = $_ENV['SYMKEY'];
            $iv = $_ENV['INIT_VECTOR'];
        }
        else
        {
            $key = getenv('SYMKEY');
            $iv = getenv('INIT_VECTOR');            
        }
        
        $unzipText = gzdecode(base64_decode($ctext));

        $text = openssl_decrypt($unzipText, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return($text);
    }

    public static function Encrypt($ptext)
    {
        if (array_key_exists('SYMKEY', $_ENV))
        {
            $key = $_ENV['SYMKEY'];
            $iv = $_ENV['INIT_VECTOR'];          
        }
        else
        {
            $key = getenv('SYMKEY');
            $iv = getenv('INIT_VECTOR');                                  
        }
                      
        $text = openssl_encrypt($ptext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);

        $_ENV['SEND_OUT_ENCRYPTED_SIZE'] = sprintf("%s", strlen($text));
        $ziped = gzencode($text, 9);

        return(base64_encode($ziped));
    }

    public static function EncryptNoZip($ptext)
    {
        if (array_key_exists('SYMKEY', $_ENV))
        {
            $key = $_ENV['SYMKEY'];
            $iv = $_ENV['INIT_VECTOR'];          
        }
        else
        {
            $key = getenv('SYMKEY');
            $iv = getenv('INIT_VECTOR');                                  
        }
                      
        $text = openssl_encrypt($ptext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);

        $_ENV['SEND_OUT_ENCRYPTED_SIZE'] = sprintf("%s", strlen($text));

        return(base64_encode($text));
    }    

    public static function OverrideEnv($rc_file)
    {
        if (!file_exists($rc_file))
        {
            return;            
        }          
        
        $params = self::LoadConfigFile($rc_file);
        $prm = $params[""];

        foreach ($prm as $key => $value)
        {
            $value = trim($value);

            if ($value == 'true')
            {
                $_ENV[$key] = true;
            }
            elseif ($value == 'false')
            {
                $_ENV[$key] = false;
            }
            else            
            {
                $_ENV[$key] = $value;         
            }
        }
    }
        
    public static function LoadConfigFile($file_name)
    {
        $section = "";        
        $param = array();
        
        $fh = fopen($file_name, 'r');

        while ($line = fgets($fh)) 
        { 
            if (preg_match('/^\#.*$/', $line))                        
            {
                continue;
            }
            elseif (preg_match('/^$/', $line))
            {
                continue;
            }
            elseif (preg_match('/^\[(.+)\](\r\n|\r|\n)*$/', $line, $matches))
            {
                $section = $matches[1];
    
                $dummy = array();
                $param[$section] = $dummy;
            }
            elseif (preg_match('/^(.+?)=(.+)$/', $line, $matches))
            {
                $var = $matches[1];
                $value = $matches[2];

                $value = trim(str_replace(array("\n", "\r"), '', $value));

                $param[$section][$var] = $value;
//printf("SECTION=[$section] NAME=[$var] VALUE=[$value]\n");                
            }            
        }
        
        fclose($fh);
        
        return($param);
    }

    public static function CreateResultXML($param, $table)
    {
        $doc = new DomDocument("1.0", "UTF-8");
        $api = $doc->createElement("API");
        //$doc->setDocumentElement($api);
        $doc->appendChild($api);

        $param_elm = self::createObjElement($param, $doc);
        $api->appendChild($param_elm);
        
        $table_elm = self::createObjElement($table, $doc);
        $api->appendChild($table_elm);
        
        $xml = $doc->saveXML();
        return($xml);
    }

    public static function GetCurrentDateTimeInternal()
    {
        return(date('Y/m/d H:i:s'));
    }    

    public static function GetDateStart($dt)
    {
        $date = substr($dt, 0, 10);
        $str = sprintf("%s 00:00:00", $date);
    
        return($str);
    }
    
    public static function GetDateEnd($dt)
    {
        $date = substr($dt, 0, 10);
        $str = sprintf("%s 23:59:59", $date);
    
        return($str);
    }

    public static function DateAdd($dtm, $offset)
    {
        $pattern = '/(.*)\/(.*)\/(.*)\s.+/s';        
        $match = preg_match_all($pattern, $dtm, $matches);

        $yyyy = $matches[1][0];
        $mm = $matches[2][0];
        $dd = $matches[3][0];
    
        $dt = new DateTime("$yyyy/$mm/$dd");
        $new_dt;
    
        $interval = new DateInterval('P' . abs($offset) . 'D');

        if ($offset < 0)
        {
            $new_dt = $dt->sub($interval);
        }
        else
        {
            $new_dt = $dt->add($interval);
        }
    
        $fmt = $dt->format('Y/m/d');
        return($fmt);
    }

    public static function DateStartEOM($dtm)
    {
        $pattern = '/(.*)\/(.*)\/(.*)\s.+/s';        
        $match = preg_match_all($pattern, $dtm, $matches);

        $yyyy = $matches[1][0];
        $mm = $matches[2][0];
        $dd = $matches[3][0];
        
        $st = new DateTime("$yyyy/$mm/01");
        $st_fmt = $st->format('Y/m/d');

        $ed = new DateTime("$yyyy/$mm/01");        
        $ed->modify('last day of this month');
        $ed_fmt = $ed->format('Y/m/d');

        $start = self::GetDateStart($st_fmt);
        $end = self::GetDateEnd($ed_fmt);

        return([$start, $end]);
    }

    public static function GetSessionObject($data)
    {
        $session = $data->GetFieldValue("SESSION");
        
        $session_file = $_ENV['SESSION_DIR'] . '/' . basename($session);
        //Hacker might populate "SESSION" field with blank
        if (!file_exists($session_file) || (trim($session) == ''))
        {
            return(NULL);
        }
        
        $content = CUtils::ReadXMLFromFile($session_file);
        list($o1, $o2) = CUtils::ProcessRequest($content);  
        
        return($o2);              
    }
    
    public static function ProcessRequest($xml)
    {
        //Prevent XXE - XML External Entity 
        libxml_disable_entity_loader(true);

        $doc = new DOMDocument();
        $doc->loadXML($xml);

        $apiNode = ($doc->childNodes)[0];
        $children = $apiNode->childNodes;

        $idx = 0;

        foreach ($children as $node)
        {
            if ($node->nodeType != XML_ELEMENT_NODE)
            { 
                continue;
            }
          
            $tagName = $node->nodeName;
            $tbName = $node->getAttribute('name'); 

            if ($idx == 0)
            {
                $param = self::populateTableObject($node, $tbName);
            }
            elseif ($idx == 1)
            {
                $table = self::populateTableObject($node, $tbName);
            }
            else
            {
                #Ignore this for now
            }

            $idx++;            

            //printf("tag=[$tagName] table=[$tbName]\n");        
        } 
        
        return(array($param, $table));
    }

    public static function ReadXMLFromFile($fname)
    {
        $fh = fopen($fname,'r');
        $xml = '';

        while ($line = fgets($fh)) 
        {
            $xml = $xml . "$line";
        }

        fclose($fh);

        return($xml);
    }

    public static function ReadMetaFile($fname)
    {
        $fh = fopen($fname,'r');
        $hash = [];

        while ($line = fgets($fh)) 
        {
            $normalLine = preg_replace("/\r|\n/", "", $line);
            list($key, $value) = explode("=", $normalLine);

            $hash[$key] = $value;
        }

        fclose($fh);

        return($hash);
    }

    public static function ReadXMLFromStdIn()
    {
        $xml = '';
        while ($line = fgets(STDIN))
        {
            $xml = $xml . "$line";
        }

        return($xml);
    }

    public static function DeriveConfigFileName()
    {
        $dockerMode = false;

        if (array_key_exists('ONIX_DOCKER_MODE', $_ENV))
        {
            $dockerMode = ($_ENV['ONIX_DOCKER_MODE'] == "true");
        }

        #For testing purpose
        $url = "";

        if (array_key_exists('REQUEST_URI', $_ENV))
        {
            $url = $_ENV['REQUEST_URI'];
        }

        if (array_key_exists('REQUEST_URI', $_SERVER))
        {
            $url = $_SERVER['REQUEST_URI'];
        }

        $bin_dir = $_ENV['BIN_DIR'];
        $match1 = false;
        $match2 = false;

        $stage = "error";
        $system = "error";
        $client = "error";
        $product = "error";
        $base = "error";
                
        if ($dockerMode)
        {
            #Docker - take hightest priority
            $pattern2 = '/^\/(.+?)\/(.+?)\/(.+?)\/(.+?)\/cgi-bin\/dispatcher\.php$/';
            $match2 = preg_match_all($pattern2, $url, $matches2);

            if (!$match2)
            {
                $pattern2 = '/^\/(.+?)\/(.+?)\/(.+?)\/(.+?)\/dispatcher\.php$/';
                $match2 = preg_match_all($pattern2, $url, $matches2);
            }
        }
        else
        {
            $pattern1 = '/(.+)\/(.+)\/(.+)\/(.+)\/(.+)\/system\/bin/';
            $match1 = preg_match_all($pattern1, $bin_dir, $matches1);
        }
        
        if ($match2)
        {
            #Docker - take hightest priority

            $base = '/onix';
            $product = $matches2[1][0];
            $stage = $matches2[2][0];
            $client = $matches2[3][0];
            $system = $matches2[4][0];

            $_ENV['BASEPATH'] = "$base";
            $cfg_dir = "$base/system/config";
            $storage_dir = "$base/storage";
        }        
        else if ($match1)
        {
            #Legacy structure

            $base = $matches1[1][0];
            $product = $matches1[2][0];
            $stage = $matches1[3][0];
            $client = $matches1[4][0];
            $system = $matches1[5][0];     
            
            $_ENV['BASEPATH'] = "$base/$product/$stage/$client/$system";
            $cfg_dir = "$base/$product/$stage/$client/$system/system/config";  
            $storage_dir = "$base/$product/$stage/$client/$system/storage";                    
        }    
        
        $cfg = "$cfg_dir/$product.$stage.$client.$system.cfg";   
        $basepath = $_ENV['BASEPATH'];

        $_ENV['CONFIG_DIR'] = "$basepath/system/config";
        $_ENV['LOG_DIR'] = "$basepath/log";
        $_ENV['WIP_DIR'] = "$basepath/wip";
        $_ENV['SESSION_DIR'] = "$basepath/session";
        $_ENV['LOCK_DIR'] = "$basepath/lock";
        $_ENV['DOWNLOAD_DIR'] = "$basepath/download";
        $_ENV['STAGE'] = $stage;
        $_ENV['CLIENT'] = $client;
        $_ENV['SYSTEM'] = $system;
        $_ENV['PRODUCT'] = $product;
        $_ENV['STORAGE_DIR'] = $storage_dir;

        return($cfg);
    }

    public static function ParseArguments($param)
    {
        $ret_param = array();
        $last_key = "";

        foreach ($param as $val) 
        {
            if (preg_match("/\-.*/i", $val))
            {
                $ret_param[$val] = "";
                $last_key = $val;
            }
            else
            {
                $ret_param[$last_key] = $val;              
            }            
        }

        return($ret_param);
    }

    public static function ValidateArguments($params)
    {
        foreach ($params as $key => $value)
        {
            $valid = false;
            $desc = "";

            if (($key == '-if') || ($key == '-cfg') || ($key == ''))
            {
                $valid = true;
            }
            else
            {
                $desc = "Unknown argument [$key]";
            }    
            
            if (!$valid)
            {
                printf("ERROR!!! : $desc\n");
                exit(1);
            }            
        }
        
        $filename = '';
        if (array_key_exists('-if', $params))        
        {
            $filename = $params['-if'];

            if (!(file_exists($filename)))
            {
                printf("ERROR!!! : File [$filename] does not exist\n");
                exit(1);
            }            
        }
        
        if (array_key_exists('-cfg', $params))
        {
            $cfg_name = $params['-cfg'];

            if (!file_exists($cfg_name))
            {
                printf("ERROR!!! : File [$cfg_name] does not exist\n");
                exit(1);
            }
        }    
    }

    public static function SSESendHeader()
    {
        printf("Content-Type: text/event-stream\n\n");
        flush();
    }

    public static function SSESendStart()
    {
        $param = new CTable("");
        $data = new CTable("");
            
        $param->SetFieldValue("STAGE", "START");        
        $result = CUtils::CreateResultXML($param, $data);
        $result = CUtils::Encrypt($result);
        
        printf("BEGIN\n"); 
        flush();   
        printf("$result\n");
        flush();  
        printf("END\n"); 
        flush();     
    }

    public static function SSESendEnd($status, $data)
    {
        $param = new CTable("");
            
        $param->SetFieldValue("STAGE", "END"); 
        $param->setFieldValue("STATUS", "$status");               
        $result = CUtils::CreateResultXML($param, $data);
        $result = CUtils::Encrypt($result);
        
        printf("BEGIN\n");  
        flush(); 
        printf("$result\n");
        flush(); 
        printf("END\n");      
        flush();          
    }

    public static function SSESendData($data)
    {
        $param = new CTable("");        
        $param->SetFieldValue("STAGE", "RUNNING");
        
        $result = CUtils::CreateResultXML($param, $data);    
        $result = CUtils::Encrypt($result);
        
        printf("BEGIN\n");  
        printf("$result");
        printf("END\n");  
        flush();  
    }    
    
    private static function constructObject($hash, $objName)
    {
        $table = new CTable($objName);
        foreach ($hash as $key => $value)
        {
            $type = gettype($value);
            if ($type != 'string')
            {
                continue;
            }
            
            $table->setFieldValue($key, $value);
        }

        return($table);
    }

    public static function NotifyManager($xmlIn, $xmlOut)
    {
        $bin_dir = $_ENV['BIN_DIR'];
        $wip_dir = $_ENV['WIP_DIR'];

        #Not so strong but just used it for create a temp uniq file name, for short period
        $loginDtm = date('Y-m-d H:i:s');
        $msTime = round(microtime(true) * 1000);
        $pid = getmypid();
        $md5 = sprintf('X%sX', md5("$loginDtm$msTime$pid"));

        $tempFile = "$wip_dir/$md5";
        $cmd = "/bin/php $bin_dir/onix_core_framework.phar event_updater $tempFile ";


        list($paramIn, $tableIn) = CUtils::ProcessRequest($xmlIn); 
        list($paramOut, $tableOut) = CUtils::ProcessRequest($xmlOut); 

        $evt = new CTable("EVENT");
    
        $e = self::constructObject($_ENV, 'ENV_ARRAY');
        $p = self::constructObject($_POST, 'POST_ARRAY');
        $g = self::constructObject($_GET, 'GET_ARRAY');
        $s = self::constructObject($_SERVER, 'SERVER_ARRAY');

        $evt->addChildArray('EVENT_VAR_ARR', [$e, $p, $g, $s]);
        $evt->addChildArray('EVENT_IN_ARR', [$paramIn, $tableIn]);
        $evt->addChildArray('EVENT_OUT_ARR', [$paramOut, $tableOut]);

        $xml = CUtils::CreateResultXML(new CTable('PARAM'), $evt);  
        file_put_contents($tempFile, $xml);

        //passthru($cmd);
    }  
}

?>