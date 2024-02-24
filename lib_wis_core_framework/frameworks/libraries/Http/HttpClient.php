<?php
/* 
    Purpose : Controller for Http Client
    Created By : Seubpong Monsar
    Created Date : 06/17/2018 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class HttpClient extends CBaseController
{
    private static $keyFilterOut = ['KEY' => '', 'URL' => '', 'OBJ_NAME' => '', 'INIT_VECTOR' => ''];

    private static function constructContent($param, $data)
    {
        $newParam = new CTable('PARAM');

        $arr = $param->getNameArray();
        foreach ($arr as $fld)
        {
            if (array_key_exists($fld, self::$keyFilterOut))
            {
                continue;
            }

            $value = $param->getFieldValue($fld);
            $newParam->setFieldValue($fld, $value);
        }

        $xml = CUtils::CreateResultXML($newParam, $data);

//printf("XML = [%s]\n", $xml);        
        $encrypted = CUtils::EncryptNoZip($xml);   
//printf("ENCRYPTED = [%s]\n", $encrypted);        
        return($encrypted);
    }

    private static function sendCommand($param, $content)
    {
        $url = $param->getFieldValue('URL');
        $objName = $param->getFieldValue('OBJ_NAME');
        $post_arr = [$objName => "$content"];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arr);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);

        $server_output = curl_exec($ch);
        $err_msg = curl_error($ch);
    
        if ($err_msg != '')
        {
            throw new Exception("Unable to send command [$err_msg] !!!\n");
        }
//printf("RETURNED = [%s]\n", $server_output);    
        $decrypted = CUtils::DecryptUnzip($server_output);  
//printf("DECRYPTED = [%s]\n", $decrypted);  

        return($decrypted);
    }

    public static function SubmitCommand($param, $data)
    {
        $_ENV['SYMKEY'] = $param->getFieldValue('KEY');
        $_ENV['INIT_VECTOR'] = $param->getFieldValue('INIT_VECTOR');

        $content = self::constructContent($param, $data);
        $result = self::sendCommand($param, $content);

        list($p, $d) = CUtils::ProcessRequest($result);

        $errCd = $p->getFieldValue('ERROR_CODE');
        $errDesc = $p->getFieldValue('ERROR_DESC');

        if ($errCd != '0')
        {
            throw new Exception("Command error [$errDesc] !!!\n");
        }

        return([$p, $d]);
    }

    public static function PopulateClientParam($cmd, $key, $url, $objName, $vector)
    {
        $data = new CTable('PARAM');

        $data->setFieldValue('FUNCTION_NAME', $cmd);
        $data->setFieldValue('KEY', $key);
        $data->setFieldValue('URL', $url);
        $data->setFieldValue('OBJ_NAME', $objName);
        $data->setFieldValue('INIT_VECTOR', $vector);
        
        return($data);
    }

    public static function PopulateCommand($cmd, $prm)
    {
        $prm->setFieldValue('FUNCTION_NAME', $cmd);
    }    
}