<?php
	$priv_key = "-----BEGIN RSA PRIVATE KEY-----

-----END RSA PRIVATE KEY-----";

	require("class/queue-database-construction.php");
	$construction = new QueueDatabaseConstruction();

	$session_ip = $_SERVER["HTTP_X_REAL_IP"];
	$ticket_stored_parts = $construction->getPostDetails("SubmissionTicket", "IPAddress", $session_ip)[0];	
	$construction->deletePost("SubmissionTicket", "IPAddress", $session_ip);

	$recieved_ticket = base64_decode($_POST["ticket"]);
	$decrypted_ticket = "";
	$success_code = openssl_private_decrypt($recieved_ticket , $decrypted_ticket, $priv_key);
	if (!$success_code){
		echo "-1 ";
		die;
	} 
	//Not actually secure, but done for fun. https://paragonie.com/blog/2016/12/everything-you-know-about-public-key-encryption-in-php-is-wrong
	$ticket_parts = explode(",", $decrypted_ticket);

	if($ticket_stored_parts["TicketValue"] != $ticket_parts[0]){//ticket id check against DB and token == Was this token registered
		echo "-2 A";	
		die;
	} 
	if($ticket_stored_parts["PostText"] != $ticket_parts[1]){//comment check against DB and token == Was this comment checked?
		echo "-2 B ";
		die;
	} 
	if($_SERVER["HTTP_X_REAL_IP"] != $ticket_parts[2]){ // IP check against poster and token == Are you the person who made the token? 
		echo "-2 C";
		die;
	}
	echo "1";
	
	$comment = $ticket_parts[1];
/*
	

	$file_string = $construction->uploadAndVerify($_FILES);

	$do_not_submit = false;
	//Duplicate code = 5
	for($file = 0 ; $file < 4 ; $file++) if($construction->die_state[$file] != -1 && $construction->die_state[$file] != 4 && $construction->die_state[$file] != 5){
		$do_not_submit = true;
		echo "File: $file<br/>";
	} 
	if( $construction->comment_error == 0) $do_not_submit = true;

	if($do_not_submit) {echo "Error in Tweet. Aborting addition to queue.<br/><a href='/twitter'>Back</a>"; die;}
	else $construction->addToTable("TweetQueue", ["ImageLocation" => $file_string, "Comment"=>$comment]);

	header("location: /twitter/confirmation?" . "comment=" . $construction->comment_error
								. "&f1=".$construction->die_state[0] 
								."&f2=". $construction->die_state[1]
								."&f3=". $construction->die_state[2]
								."&f4=".$construction->die_state[3]);*/
?>