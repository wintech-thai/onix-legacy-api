<?php

declare(strict_types=1);

require_once 'CUtils.php';

$_ENV['BIN_DIR'] = dirname(__FILE__);
$_ENV['INIT_VECTOR'] =  'pemgail9uzpgzl88';
$_ENV['SYMKEY'] = '1ebb7ac384bcb801';

$result = "PTqS9+A9ZNkJ9J4XyYt4dycFduUB41wTvvMRI0vGuI+oj9tqfDh5byXeBuq8WKUBZY6TPdZ9qUjPF16WxJ8HGtgCxpxbarq8QMjTed0x21UYrtOdI0mW/GPyxToct2uTTaYhJLit5wdSiuk16pXABXDhcBqwWWyezuW4MARTmVxtDpYCQrXCtFaPWnSjs3ghfMJkgvMJ/FgaxUIHc7qx1TdxRL9jCZPBUgLzkIFg/GnjB7lAHV2UnFNkl6M82O2bDlqhlF640dvIJP65VHBI+BO1O0rRkDtIT8pKn4KxeLKULNzEZrmdgbfgkDGo89weJnVhgJwBPBpX6x0+i+cZbO7XWb7qFX3owlt6Z517qXDagXbehh7oy2Dl9Q/hyAag";
$xml = CUtils::Decrypt($result); 

$result = CUtils::Encrypt($xml); 

printf("$xml\n");
printf("$result\n");

?>