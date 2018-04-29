<?php
error_reporting (0);
echo'
<html>
<head>
<base href="boards.verniy.xyz">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body class="page-properties">
';
	require("class/board-level-database-connection.php");
	$connection = new BoardLevelDatabaseConnection();
	$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
	
	require("class/board-functions.php");
BoardFunctions::buildNavBar();
	BoardFunctions::buildAudioGimick(intval($_GET["song"]));



echo '

<div class="">
<div class="jumbotron">
<h1 class="display-4">Post Succeeded</h1>
	<div id="timer-container"><strong>Time Until Next Update: </strong>
		<span id="time"></span>
	</div>
</div>
';
	echo '<script src="rsc/board-script.js?'. time() .'"></script>'	;	


echo '
<div style="margin:3% 10%" id="queue-form-container">

';

	$error = false;
	for($file = 1 ; $file <= 4 ; $file++){
		if($_GET["f" . (string)$file] == 0){
			echo "File: $file  was valid.<br/>";
			continue;
		}
		else if($_GET["f" . (string)$file] == 1){
			echo "file $file, Over size limit-Client<br/>";
		}
		else if($_GET["f" . (string)$file] == 5){
			echo "file $file, Empty<br/>";
			continue;
		}
		else if($_GET["f" . (string)$file] == 6) {
			echo "file " . (string)$file .", Duplicate<br/>";
		}
		else{
			echo "file $file, Unkown Upload Error " . $files["file" . (string)$file]["error"] . "<br/>";	
		}
		$error = true;
	}
	
	echo "<hr/>";			
	
	if($error) echo "Error in Tweet.<br/>";
		
	BoardFunctions::displayTabularJoin("Tweet", "Unsubmitted", "PostID", 3, true, $connection);
?>

</div><hr/>
<a href="http://boards.verniy.xyz/">Back to Form</a>
</div>

</body>
</html>
