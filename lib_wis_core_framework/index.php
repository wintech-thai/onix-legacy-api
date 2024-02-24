<?php

$command = $argv[1];
$param1 = $argv[2];

if ($command == 'event_updater')
{
    $pid = pcntl_fork();
    if ($pid == -1) 
    {
        throw new Exception("Fork error !!!");
    } 

    if ($pid) 
    {
        //Do nothing
        return;
    } 

    //Declare the independence from parrent
    //Uncomment line below if testing in command mode to print the debug message
    //Close standard output then parrent will not wait for child
    
    fclose(STDOUT);

    $content = file_get_contents($param1);

    //$url = "https://wtt-wtt-scm.wintech-thai.com/scm/prod/wtt/wintechthai/cgi-bin/events_entry.php";
    $url = "https://development.wintech-thai.com/scm/dev/wtt/development/cgi-bin/events_entry.php";
    $ch = curl_init();
    $post_arr = ['EVENT' => "$content"];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arr);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    $server_output = curl_exec($ch);
    $err_msg = curl_error($ch);

    if ($err_msg == '')
    {
        unlink($param1);
    }

//printf($err_msg);
//printf($server_output);

    curl_close($ch);
}

exit(0);

?>