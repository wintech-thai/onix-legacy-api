<?php

   require_once 'COnixPDO.php';

   $host = "localhost";
   $dbname = "onix_dev_wis_development";
   $dbuser = "onix_dev_wis_development";
   $dbpass = "9636c50c74304a50873e12ef226602e4";
   $port = "5432";

   try
   {
       $dbh = new COnixPDO("pgsql:dbname=$dbname;host=$host;port=$port", $dbuser, $dbpass);
       
       printf("Connected!\n");
   }
   catch (Exception $e)
   {
       printf("Error! : [%s]\n" ,$e->getMessage());
   }

   $dbh = NULL;

?>