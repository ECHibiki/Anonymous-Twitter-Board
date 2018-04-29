<?php

error_reporting (0);
	require("class/board-level-database-connection.php");
	require("class/board-functions.php");
?>

<html>
<head>
<base href="boards.verniy.xyz">
<?php //echo'<link  rel="stylesheet" type="text/css" href="rsc/board-style.css?'.time().'2" />' ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body class="">

<?php BoardFunctions::buildNavBar(); ?>


<div class="">
<div id="top-settings" class="jumbotron">
<h1 class="display-4">Next Posts</h1>
	<div id='timer-container'><strong>Time Until Next Update: </strong>
		<span id='time'></span>
	</div>
</div>
<script src="rsc/board-script.js?16"></script>		
	
<div style="margin:3% 10%" id="queue-form-container">

<?php

	echo '<p>The next tweets to be posted are as follows. 
	"Withheld" implies it did not pass the spam filter,
	"Passed" means it will be posted on the regular 15 minute update timer.</p>';
	

	$connection = new BoardLevelDatabaseConnection();

	$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
	BoardFunctions::displayTabularJoin("Tweet", "Unsubmitted", "PostID", 3, true, $connection);
?>
</div>
</div>
</div>
</body>
</html>
