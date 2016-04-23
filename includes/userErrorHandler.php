<?php
// we will do our own error handling
error_reporting(0);

// user defined error handling function
function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
    // timestamp for the error entry
    $err = "";
    $err2="";
    $dt = date("m/d/Y H:i:s",strtotime("+8 hours"));

    // define an assoc array of error string
    // in reality the only entries we should
    // consider are 2,8,256,512 and 1024
    $errortype = array (
                1   =>  "Error",
                2   =>  "Warning",
                4   =>  "Parsing Error",
                8   =>  "Notice",
                16  =>  "Core Error",
                32  =>  "Core Warning",
                64  =>  "Compile Error",
                128 =>  "Compile Warning",
                256 =>  "User Error",
                512 =>  "User Warning",
                1024=>  "User Notice"
                );
                
    // set of errors for which a var trace will be saved
    $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
    
    //for log file
    $err .= "datetime       :".$dt."\n";
    $err .= "errornum       :".$errno."\n";
    $err .= "errortype      :".$errortype[$errno]."\n";
    $err .= "errormsg       :".$errmsg."\n";
    $err .= "scriptname     :".$filename."\n";
    $err .= "scriptlinenum  :".$linenum."\n";
    $err .= "backtrace      :".get_backtrace()."\n";
    $err .= str_repeat('-',150)."\n";
    
    //for browser
    $err2 .= "<br>datetime       :".$dt."\n<br>";
    $err2 .= "errornum       :".$errno."\n<br>";
    $err2 .= "errortype      :".$errortype[$errno]."\n<br>";
    $err2 .= "errormsg       :".$errmsg."\n<br>";
    $err2 .= "scriptname     :".$filename."\n<br>";
    $err2 .= "scriptlinenum  :".$linenum."\n<br>";
    $err2 .= "backtrace      :".get_backtrace()."\n<br>";
    $err2 .= str_repeat('-',150)."\n";    
    
    // for testing
    //echo $err2;

    error_log($err, 3, "C:\wamp\www\PG-HRIS-GEN\includes\PG-HRIS_error.log");
}

function get_backtrace(){
	
	ob_start();
	$backtrace = debug_backtrace();
	//print_r($backtrace);
	$tmpBackTrace = $backtrace[sizeof($backtrace)-1];
	echo 'filename  :   ' .$tmpBackTrace['file']."\n";
	echo '                line      :   ' .$tmpBackTrace['line']."\n";
	echo '                function  :   ' .$tmpBackTrace['function']."\n";
	$clean = ob_get_clean();
	return $clean;
}

$old_error_handler = set_error_handler("userErrorHandler");

?> 