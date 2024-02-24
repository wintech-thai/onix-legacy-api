<?php

declare(strict_types=1);

require_once 'CTable.php';

$t1 = new CTable("DUMMY");

$t1->SetFieldValue("ID", "1");
$t1->SetFieldValue("NAME", "SEUB");
$t1->SetFieldValue("PASSWORD", "HelloWorld");
$t1->DumpFields();

printf("SHOW1 [%s]\n", $t1->GetFieldValue("PASSWORD"));
printf("SHOW2 [%s]\n", $t1->GetFieldValue("NOTFOUND"));

$c1 = new CTable("CHILD");
$c1->SetFieldValue("ID", "1");
$c1->SetFieldValue("NAME", "SEUB");

$c2 = new CTable("CHILD");
$c2->SetFieldValue("ID", "2");
$c2->SetFieldValue("NAME", "AEY");

$child1 = array($c1, $c2);
$t1->AddChildArray("PARENTS", $child1);

$arr = $t1->GetChildArray("PARENTS");
$len = count($arr);
for ($x = 0; $x < $len; $x++) 
{
    $o = $arr[$x];
    $o->DumpFields();
}  

?>