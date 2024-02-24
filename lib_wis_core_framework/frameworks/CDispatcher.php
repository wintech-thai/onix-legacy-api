<?php
/* 
    Purpose : API dispatcher class
    Created By : Seubpong Monsar
    Created Date : 09/04/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class CDispatcher
{
    private static $frameworkServices = [
        /* Automatically retrieve method names from ClassName, possible? */
        'Echo' => ['phar://onix_core_framework.phar/CFrameworkServices.php', 'Echo', false],
        'Patch' => ['phar://onix_core_framework.phar/CFrameworkServices.php', 'Patch', false],
        'TestLock' => ['phar://onix_core_framework.phar/CFrameworkServices.php', 'TestLock', false], 
    ];

    private static $paramVariables = [];
    private static $customServices = []; 
    private static $getUserInfoFuncName = '';

    public static function RegisterGetUserInfoFuncName($funcName)
    {
        self::$getUserInfoFuncName = $funcName;
    }

    public static function RegisterServices($servicesArray)
    {
        //self::$customServices = $servicesArray;

        foreach ($servicesArray as $svc => $arr)
        {
            /* Override if exist */
            self::$customServices[$svc] = $arr;
        }
    }

    public static function AddParamVariable($name, $value)
    {
        self::$paramVariables[$name] = $value;
    }

    /* Return : XML string */
    public static function InvokeService($db, $param, $data, $mode)
    {
        $startTime = round(microtime(true) * 1000);

        $bindir = $_ENV['BIN_DIR'];
        $funcName = $param->getFieldValue("FUNCTION_NAME");
        $dumpSql = $param->getFieldValue("DEBUG_FLAG");

        CSql::SetDumpSQL($dumpSql == 'Y');

        if (array_key_exists($funcName, self::$frameworkServices))    
        {
            $arr = self::$frameworkServices[$funcName];
        }
        elseif (array_key_exists($funcName, self::$customServices))    
        {
            $arr = self::$customServices[$funcName];
        }
        else
        {
            throw new Exception("Service [$funcName] not found!!!");
        }

        list($file, $method, $onlyAdmin) = $arr;
        $className = str_replace('.php', '', $file);
        $className = basename($className);

        $absoluteFile = "$file";

        if (!is_file($absoluteFile))
        {
            throw new Exception("File [$absoluteFile] does not exist!!!");           
        }   

        require_once($absoluteFile);

        if (!class_exists($className))
        {
            throw new Exception("Class [$className] does not exist!!!");   
        }

        if (!method_exists("$className", "$method"))
        {
            throw new Exception("Method [$className::$method] does not exist!!!");   
        }

        self::ValidateSession($param, $data, true, $onlyAdmin, $mode);

        list($p, $d) = $className::$method($db, $param, $data);
        $endTime = round(microtime(true) * 1000);
        $duration = sprintf('%d millisecond', $endTime - $startTime);
        
        $p->SetFieldValue("ONIX_DOCKER_MODE", 'false');
        if (array_key_exists('ONIX_DOCKER_MODE', $_ENV))
        {
            $p->SetFieldValue("ONIX_DOCKER_MODE", $_ENV['ONIX_DOCKER_MODE']);
        }

        $p->SetFieldValue("ERROR_CODE", "0");
        $p->SetFieldValue("ERROR_DESC", "SUCCESS");
        $p->SetFieldValue("FUNCTION_NAME", $funcName);
        $p->SetFieldValue("ENGINE", 'PHP');
        $p->SetFieldValue("ONIX_CORE_FRAMEWORK_VERSION", $_ENV['ONIX_CORE_FRAMEWORK_VERSION']); 
              
        $p->SetFieldValue("DEBUG_TIME_TO_EXECUTE", $duration);
        $p->SetFieldValue("DEBUG_TIME_TO_CONNECT_DB", $_ENV['DEBUG_TIME_TO_CONNECT_DB']);
        $p->SetFieldValue("LOG_FILE", CLog::GetLogName());
        self::populateParamVariables($p);

        $xml = CUtils::CreateResultXML($p, $d);

        return($xml);
    }

    private static function populateParamVariables($param)
    {
        foreach(self::$paramVariables as $key => $value)
        {
            $param->SetFieldValue($key, $value);
        }
    }

    public static function ValidateSession($param, $data, $checkAdmin, $config, $mode)
    {
        $funcName = $param->getFieldValue("FUNCTION_NAME");
       
        //Hash/Assoc Array            
        $whoCanRun = $config['WhoCanRun'];
        $channel = $config['Channel'];            

        $forAdmin = false;
        if ($whoCanRun == 'admin')
        {
            $forAdmin = true;
        }

        //Must run as CGI mode
        if (($_ENV['STAGE'] != 'dev') && ($channel == 'web') && ($mode == 'CMDLINE'))
        {
            throw new Exception("Not allow to run in CMDLINE mode [$funcName] !!!");
        }

        if (($channel == 'cli') && ($mode != 'CMDLINE'))
        {
            throw new Exception("Allow to run only in CMDLINE mode [$funcName] !!!");
        }

        //Only Patch() or Echo() or Login() or running in command line mode will not need to validate the session 
        $except = ($funcName == 'Patch') || ($funcName == 'Echo') ||
                  ($funcName == 'Login') || ($mode == 'CMDLINE') || 
                  ($whoCanRun == 'nologin');

        $session = $param->GetFieldValue('SESSION');
        $session_file = $_ENV['SESSION_DIR'] . '/' . basename($session);
        //Hacker might populate "SESSION" field with blank
        if (!file_exists($session_file) || (trim($session) == ''))
        {
            if (!$except)
            {
                throw new Exception("Please login first [$funcName] !!!");
            }
        }

        //Get ssession content
        
        if (!$except)
        {
            $content = CUtils::ReadXMLFromFile($session_file);
            list($o1, $o2) = CUtils::ProcessRequest($content);

            $_ENV['LOGIN_USER_NAME'] = $o2->GetFieldValue('USER_NAME');

            //Check for time out
            $loginDtm = new DateTime($o2->GetFieldValue('LOGIN_DATE'));
            $currentDtm = new DateTime(date('Y-m-d H:i:s'));

            $diff = $currentDtm->diff($loginDtm);

            $hours = $diff->h;
            $hours = $hours + ($diff->days*24);

            if ($hours > 8)
            {
                throw new Exception("Session expired !!!");
            }
        }

        //Check if function only eligible for "administrator"
        if ($forAdmin && $checkAdmin && !$except)
        {
            $isadmin = $o2->GetFieldValue("IS_ADMIN");
            if ($isadmin != 'Y')
            {
                throw new Exception("Only 'administrator' can perform this API [$funcName] !!!");
            } 
        }
    }

    /* Return : Boolean */
    public static function IsServiceAvailable($param, $data)
    {
        $funcName = $param->getFieldValue("FUNCTION_NAME");
       
        if (!array_key_exists($funcName, self::$frameworkServices) &&
            !array_key_exists($funcName, self::$customServices))      
        {
            return(false);
        } 
        
        return(true);
    }    
}

?>