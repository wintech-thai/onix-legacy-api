<?php
/* 
    Purpose : Entry point of web service API
    Created By : Seubpong Monsar
    Created Date : 09/02/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

$startE2ETime = round(microtime(true) * 1000);

require_once "phar://onix_core_framework.phar/build.php";
require_once "phar://onix_core_framework.phar/onix_core_include.php";

$post_param_name = $_ENV['POST_PARAM_NAME'];

$_ENV['ONIX_CORE_FRAMEWORK_VERSION'] = "1.0.38 built on $ONIX_CORE_BUILT_DATE (MM/DD/YYYY)";
$_ENV['SESSION_DIR'] = getenv('ONIX_SESSION_DIR');
$_ENV['WIP_DIR'] = getenv('ONIX_WIP_DIR');
$_ENV['STAGE'] = getenv('ONIX_STAGE');
$_ENV['LOCK_DIR'] = getenv('ONIX_LOCK_DIR');
$_ENV['STORAGE_DIR'] = getenv('ONIX_STORAGE_DIR');

$xml = '';
if (array_key_exists($post_param_name, $_POST))
{
    $xml = $_POST[$post_param_name];
}

$config_file = "";
$mode = "CGI";

if ($xml != '')
{
    $_ENV['SYMKEY'] = getenv('ONIX_SYM_KEY');

    if (isCGIEncryptedMode())
    {
        $xml = CUtils::Decrypt($xml); 
    }
}

$result = "";
$conn = NULL;
$_ENV['CALLER_MODE'] = $mode;

try 
{
    list($param, $table) = CUtils::ProcessRequest($xml);    
    $_ENV['ONIX_CALLER_VERSION'] = $param->GetFieldValue('WisWsClientAPI_VERSION');

    CLog::Open($param, $table);   
    CLog::WriteLn('Start processing command');

    if ($_ENV['INPUT_XML_DUMP'])
    {
        CLog::WriteLn($xml);
    }

    list($dsn, $dbuser, $dbpass) = getDBConfig();

    $startTime = round(microtime(true) * 1000);

    $conn = new COnixPDO($dsn, $dbuser, $dbpass);  
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $endTime = round(microtime(true) * 1000);
    $duration = sprintf('%d millisecond', $endTime - $startTime);
    $_ENV['DEBUG_TIME_TO_CONNECT_DB'] = $duration;

    $result = processCommand($conn, $param, $table, $mode);
    //$conn = NULL; //Comment this line to preserve the connection (persistent mode)
} 
catch (Exception $e) 
{
    if (($conn != NULL) && $conn->inTransaction())
    {    
        $conn->rollBack();
    }

    $param = new CTable("PARAM");
    $param->setFieldValue("ERROR_CODE", 1);
    $param->setFieldValue("ERROR_DESC", $e->getMessage());

    $table = new CTable("EXCEPTION"); 
    $result = CUtils::CreateResultXML($param, $table);  
}

$plainTextResult = $result;

if (($mode != 'CMDLINE') && ($mode != 'STDIN'))
{
    if (isCGIEncryptedMode())
    {
        //Encrypt data back
        $result = CUtils::Encrypt($result);   
    }

    header('Content-Type: text/html');
    flush();
}

$endE2ETime = round(microtime(true) * 1000);
$duration = $endE2ETime - $startE2ETime;
$e2eDuration = sprintf('%d millisecond', $duration);

if (isManagerLogEnabled())
{
    $_ENV['PROCESSING_DURATION'] = "$duration"; //Must be string;
    $_ENV['SEND_OUT_COMPRESSED_SIZE'] = sprintf("%s", strlen($result));
    CUtils::NotifyManager($xml, $plainTextResult);
}

CLog::WriteLn("Time to execute E2E : [$e2eDuration]");
CLog::WriteLn('Done processing command');
CLog::Close(); 

print("$result");

exit(0);

function isManagerLogEnabled()
{
    if (!array_key_exists('WIS_CORE_MANAGER', $_ENV))
    {
        //Default to 'true' - send to manager
        return(false);
    }

    return($_ENV['WIS_CORE_MANAGER']);
}

function isCGIEncryptedMode()
{
    if (!array_key_exists('WIS_CORE_ENCRYPTED', $_ENV))
    {
        //Default to encrypted mode
        return(true);
    }

    return($_ENV['WIS_CORE_ENCRYPTED']);
}

function getDBConfig()
{    
    $dbstr = getenv('ONIX_DSN');
    $user = getenv('ONIX_DB_USERNAME');
    $password = getenv('ONIX_DB_PASSWORD');

    $pattern = '/dbname=(.*);host=(.*);port=(.*)/s';        
    $match = preg_match_all($pattern, $dbstr, $matches);
    if (!$match)
    {
        throw new Exception("Unable to connect database, please check you config file!!!");
    }

    $dbname = $matches[1][0];
    $host = $matches[2][0];
    $port = $matches[3][0];
    
    $dsn = "pgsql:dbname=$dbname;host=$host;port=$port";
    return(array($dsn, $user, $password));
}

function processCommand($db, $param, $table, $mode)
{
    $isexist = CDispatcher::IsServiceAvailable($param, $table);
    if ($isexist)
    {
        $buffer = CDispatcher::InvokeService($db, $param, $table, $mode);
        return($buffer);
    }

    throw new Exception('Unknown command !!!!');
}

?>