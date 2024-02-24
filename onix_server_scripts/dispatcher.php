<?php
/* 
    Purpose : Entry point of web service API
    Created By : Seubpong Monsar
    Created Date : 09/02/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once 'config.php';

require_once "build.php";

$AppRevision="1.0";
#This will be replace while building the package
$AppVersion = "$APP_VERSION_LABEL";

//=== Begin init app version

require_once "phar://onix_erp_framework.phar/onix_erp_include.php";
CDispatcher::AddParamVariable('APP_VERSION_LABEL', "$AppVersion-$AppRevision");
//=== End init app version

require_once "phar://onix_erp_framework.phar/services.php";
require_once "phar://onix_erp_framework.phar/patches.php";

require_once "phar://onix_core_framework.phar/onix_core_dispatcher.php";

exit(0);

?>