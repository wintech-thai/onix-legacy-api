<?php

declare(strict_types=1);

require_once 'CUtils.php';

$arguments = CUtils::ParseArguments($argv);
$method = $arguments['-method'];

$text = read_from_stdin();

if ($method == 'encrypt')
{
    $new_text = CUtils::Encrypt($text);
}
else
{
    //Decrypt
    $new_text = CUtils::Decrypt($text);
}

printf($new_text);
exit(0);

function read_from_stdin()
{
    $txt = "";

    while($f = fgets(STDIN))
    {
        $txt = $txt . $f;
    }

    return($txt);
}

?>