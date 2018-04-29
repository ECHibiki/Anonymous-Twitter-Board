<?php
error_reporting (0);
//also verifies comment
$ticket =  rand(0,1000000000);
$ip = $_SERVER["HTTP_X_REAL_IP"];
$comment = $_GET["comment"];
$file_string = $_GET["files"];
//include image path names

require("class/board-level-database-connection.php");
require("class/board-functions.php");
$connection = new BoardLevelDatabaseConnection();
$storage_ip = $connection->getPostDetails("SubmissionTicket", "IPAddress", $ip)[0]["IPAddress"];

$comment = BoardFunctions::checkSubmissionValid($comment, $file_string, $connection);//use the ticket's comment for submission
if(BoardFunctions::$comment_error != 0){
	echo BoardFunctions::$comment_error;
	die;
}

$file_ticket = 0;
if(!is_null($file_string)) $file_ticket = 1; 

if($storage_ip != $ip){
	$send_ticket = "$ticket,$comment,$ip,$file_ticket";  // 10+45+280+2=336
	$compressed_ticket= base64_encode(gzcompress($send_ticket, 9));
	if ($compressed_ticket == "") throw new Exception('Err');
	echo $compressed_ticket;
	$connection->addToTable("SubmissionTicket", array("TicketValue"=>$ticket, "PostText"=>$comment, "IPAddress"=>$ip));	
}
else {
	echo -1;
	die;
}

?>