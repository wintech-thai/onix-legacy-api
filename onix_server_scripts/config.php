<?php
/* 
    Purpose : Global config file
    Created By : Seubpong Monsar
    Created Date : 09/02/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

$_ENV['BIN_DIR'] = dirname(__FILE__);

$_ENV['API_VERSION'] = '$ONIX_BUILD_LABEL_VAR$-1.1';
$_ENV['INIT_VECTOR'] =  'pemgail9uzpgzl88';
$_ENV['POST_PARAM_NAME'] = 'DBOSOBJ';

//For debugging purpose
$_ENV['INPUT_XML_DUMP'] = false;

$_ENV['WIS_CORE_ENCRYPTED'] = true;
$_ENV['WIS_CORE_MANAGER'] = true;

?>