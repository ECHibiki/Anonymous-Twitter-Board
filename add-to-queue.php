<?php
error_reporting (0);
	require("class/board-level-database-connection.php");
	$connection = new BoardLevelDatabaseConnection();

	if ($_POST["ticket"] == "-1"){
		echo "All=-1";
			$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
		die;
	} 
	
	$session_ip = $_SERVER["HTTP_X_REAL_IP"];
	$ticket_stored_parts = $connection->getPostDetails("SubmissionTicket", "IPAddress", $session_ip)[0];	
	$recieved_ticket  = base64_decode($_POST["ticket"]);
	
	$uncompressed_ticket = gzuncompress ($recieved_ticket);
	$ticket_parts = explode(",", $uncompressed_ticket);

	if($ticket_stored_parts["TicketValue"] != $ticket_parts[0]){//ticket id check against DB and token == Was this token registered
		echo "All=-2";	
			$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
		die;
	} 
	if($ticket_stored_parts["PostText"] != $ticket_parts[1]){//comment check against DB and token == Was this comment checked?
		echo "All=-3";
			$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
		die;
	} 
	if($_SERVER["HTTP_X_REAL_IP"] != $ticket_parts[2]){ // IP check against poster and token == Are you the person who made the token? 
		echo "All=-4";
			$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
		die;
	}
	
	require_once("class/board-functions.php");
	require_once("class/additional-functions.php");
	
	$comment = $ticket_parts[1];
	$file_string = BoardFunctions::uploadAndVerify(array($_POST["file1"], $_POST["file2"], $_POST["file3"], $_POST["file4"]));
	if($file_string != "" && $ticket_parts[3] == "0"){//Unpermitted image
		$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
		$file_string_arr = explode(",", urldecode($file_string));
		foreach($file_string_arr as $upload_location){
			if($upload_location !== "")
				unlink($upload_location);
		}
		echo "All=-5";
		die;
	}
	
	
	$do_not_submit = false;
	//Duplicate code = 6
	for($file = 0 ; $file < 4 ; $file++) if(BoardFunctions::$die_state[$file] != 0 && BoardFunctions::$die_state[$file] != 5){
		$do_not_submit = true;
	} 
	
	if($do_not_submit) {
		$file_string_arr = explode(",", urldecode($file_string));
		$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
		foreach($file_string_arr as $upload_location){
			if($upload_location !== "")
				unlink($upload_location);
		}
	}
	else{
		$id = ceil((microtime(true) *10)) . "" . (rand(0,9));
		$unverified_state = 0; // 0=false. Is verifed
		if(strpos($comment, "VERIFY: ") !== false){
			$unverified_state = 1;// 1=true. Is not verifed
			$comment = substr($comment, 8);
		}
		
		$post_properties = parse_ini_file("settings/postproperties.ini");
		$post_properties["TotalPosts"] = intval($post_properties["TotalPosts"]) + 1;
		StandardFunctions::write_php_ini($post_properties, "settings/postproperties.ini");
		
		$connection->addToTable("Tweet", ["PostID"=> $id, "ImageURL" => $file_string, "PostText"=>$comment]);
		$connection->addToTable("Unsubmitted", ["PostID"=> $id, "IPAddress"=>$_SERVER["HTTP_X_REAL_IP"], "Unverified"=>$unverified_state]);	
	} 
	echo "f1=".BoardFunctions::$die_state[0] ."&f2=". BoardFunctions::$die_state[1]
		."&f3=". BoardFunctions::$die_state[2] ."&f4=".BoardFunctions::$die_state[3];
	die;

?>