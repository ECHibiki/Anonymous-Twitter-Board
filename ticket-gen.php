<?php
	$pub_key="-----BEGIN PUBLIC KEY-----

-----END PUBLIC KEY-----";

$ticket =  rand(0,1000000000);
$ip = $_SERVER["HTTP_X_REAL_IP"];
$comment = $_GET["comment"];

require("class/queue-database-construction.php");
$construction = new QueueDatabaseConstruction();
$storage_ip = $construction->getPostDetails("SubmissionTicket", "IPAddress", $ip)[0]["IPAddress"];

if(preg_match("/VERIFY: /", $comment) == 0){
	if(preg_match("/http/", $comment) == 1) {
		echo "-2 http";
		die;
	}
}
$comment = $construction->checkCommentValid($comment);//use the ticket's comment for submission
if($construction->comment_error == 0){
	echo "-3 Comment-too-long";
	die;
}

if($storage_ip != $ip){
	$send_ticket = "$ticket,$comment,$ip";  // 10+45=55
	$encrypted_ticket="";
	$success_code = openssl_public_encrypt($send_ticket, $encrypted_ticket, $pub_key);
	
	if (!$success_code) throw new Exception('Err');
	else echo base64_encode($encrypted_ticket);
	//Not actually secure, but done for fun. https://paragonie.com/blog/2016/12/everything-you-know-about-public-key-encryption-in-php-is-wrong
	//File is just to prevent multiple submissions

	$construction->addToTable("SubmissionTicket", array("TicketValue"=>$ticket, "PostText"=>$comment, "IPAddress"=>$ip));	
}
else {
	echo -1;
	die;
}

?>