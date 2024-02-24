#!/usr/local/bin/php

<?php

//printf("Starting to convert file...\n");

$fh = fopen("tables_list.txt", "r");
if ($fh) 
{
    while (($line = fgets($fh)) !== false) 
    {
        $line = preg_replace('~[\r\n]+~', '', $line);

        printf("Converting file [%s]\n", $line);
        ConvertFile($line, '.');
    }
}


//printf("Done converting file(s)\n");
exit(0);

function ExtractBlock($chunk)
{
    $result = preg_match_all('/\((.*?)\);/s', $chunk, $matches);
    if ($result)
    {
        return($matches[1][0]);
    }

    return("");
}

function ConvertFile($perlFile, $destPath)
{
    $chunks = array();
    $table = "";
    $pk = "";
    $classname = "";

    $fh = fopen($perlFile,'r');
    if (!isset($fh))
    {
        throw new Exception("Open file error [$perlFile]");
    }

    $content = '';
    $cnt = 0;

    while ($line = fgets($fh)) 
    {
        if (preg_match_all('/package CBaseTable::C(.*);/', $line, $matches))
        {
            $classname = 'M' . $matches[1][0];
        }
        elseif (preg_match_all('/my \$TABLE_NAME\s*=\s*"(.*)"/', $line, $matches))
        {
            $table = $matches[1][0];
        }     
        elseif (preg_match_all('/my \$PK_FIELD_NAME\s*=\s*"(.*)"/', $line, $matches))
        {
            $pk = $matches[1][0];
        }            
        elseif (preg_match('/^my @COLUMNS/', $line))
        {
            //printf("Start block 1\n");
            $content = "";
        }
        elseif (preg_match('/^my @FROMS/', $line))
        {
            $chunks[0] = $content;

            //printf("Start block 2\n");
            $content = "";
            $cnt++;
        }
        elseif (preg_match('/^my @ORDERS/', $line))
        {
            $chunks[1] = $content;

            //printf("Start block 3\n");
            $content = "";
            $cnt++;
        }
        elseif (preg_match('/^sub new/', $line))
        {
            $chunks[2] = $content;

            //printf("Start block 4\n");
            $content = "";
            $cnt++;
        }

        $content = $content . "$line";
    }
    fclose($fh);
    
    $c1 = ExtractBlock($chunks[0]);
    $c2 = ExtractBlock($chunks[1]);
    $c3 = ExtractBlock($chunks[2]);

    $fname = "$destPath/$classname.php";
    $oh = fopen($fname, "w") or die("Unable to open file [$fname]!");
    CreateContent($table, $pk, $classname, $c1, $c2, $c3, $oh);
    fclose($oh);
}

function CreateContent($tableName, $pkName, $className, $block1, $block2, $block3, $oh)
{
    $bintext = '$_ENV[BIN_DIR]';
    $cols = '$cols';
    $froms = '$froms';
    $orderby = '$orderby';
    $db = '$db';
    $colarr = '$this->cols';
    $fromarr = '$this->froms';
    $orderbys = '$this->orderby';

$stmt = <<<EOD
<?php
/* 
Purpose : Model for $tableName
Created By : Seubpong Monsar
Created Date : 09/04/2017 (MM/DD/YYYY)
IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://$bintext/onix_core_framework.phar/MBaseModel.php";

class $className extends MBaseModel
{
    private $cols = array(
$block1
    );

    private $froms = array(
$block2
    );

    private $orderby = array(
$block3
    );

    function __construct($db) 
    {
        parent::__construct($db, '$tableName', '$pkName', $colarr, $fromarr, $orderbys);
    }
}
?>
EOD;

fwrite($oh, $stmt);

}

?>
