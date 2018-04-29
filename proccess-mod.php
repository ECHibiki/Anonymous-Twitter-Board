<?php
error_reporting (0);
	require("class/board-level-database-connection.php");
	require("class/board-functions.php");
	$connection = new BoardLevelDatabaseConnection();
	session_start();
	if($_SESSION["mod"] != "pervert"){ echo "Not Verified";}
	else{
		$connection->addToTable("SubmissionHalt", array("IPAddress" => $_SERVER['HTTP_X_REAL_IP']));	//prevent multisubmissions

		//var_dump($_POST);
		
		if($_POST["delete-button"] != null){
			foreach($_POST as $key=>$postdata){
				//echo $key .  " " . preg_match("/chk\d+/", $key);
				if(preg_match("/chk\d+/", $key)){
					$id = substr($key, 3);
					//echo $id;
					$connection->DeleteChain($id);
				}
			}
		}
		else if($_POST["ban-button"] != null){
			$connection->toggleBanIPList($_POST);
		}
		else if($_POST["verify-button"] != null){
			$connection->verifyPosts($_POST);
		}
		else if($_POST["send-button"]){
			foreach($_POST as $key=>$postdata){
				//echo $key .  " " . preg_match("/chk\d+/", $key);
				if(preg_match("/chk\d+/", $key)){
					ob_start();
					$id = substr($key, 3);
					$connection->sendVerifiedPostID($id);
					$connection->deleteFromTable("Tweet", "PostID", $id); 
					ob_end_clean();
				}
			}
		}
		else if($_POST["levels-button"]){	
			$levels_json = JSON_Decode(file_get_contents("settings/verify-levels.json"), true);
			$levels_json["Image-Block"] = $_POST["blockimg"] == "on" ? 1 : 0;
			$levels_json["URL-Block"] = $_POST["blockurl"] == "on" ? 1 : 0;
			$levels_json["At-Block"] = $_POST["blockat"] == "on" ? 1 : 0;
			$levels_json["Filter-Text-Active"] = $_POST["blocktext"] == "on" ? 1 : 0;
			if($_POST["blocktext"] == "on"){
				$levels_json["Filter-Text"] = [];
				foreach($_POST as $key=>$postdata){
					if(preg_match("/^filter/", $key)){
						$postdata = trim($postdata);
						if($postdata !== ""){
							array_push($levels_json["Filter-Text"], $postdata);					
						}
					}
				}
			}
						

			$levels_file = fopen("settings/verify-levels.json", 'w');
			fwrite($levels_file, JSON_Encode($levels_json));
			
			$levels_json = JSON_Decode(file_get_contents("settings/verify-levels.json"), true);

		}
		header("Location: http://boards.verniy.xyz/verify");
	}
?>