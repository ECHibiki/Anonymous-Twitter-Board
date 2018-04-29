<?php
error_reporting (0);
	require("class/board-level-database-connection.php");
	require("class/board-functions.php");
	$connection = new BoardLevelDatabaseConnection();
	session_start();
	$connection->deleteFromTable("SubmissionHalt", "IPAddress", $_SERVER['HTTP_X_REAL_IP']);	//prevent mutlisubmisions
	$success = BoardFunctions::logIntoAuthorizedSpace($_POST, $_SERVER['HTTP_X_REAL_IP'], $_SESSION,$connection);	//contains header
	if($success) header("Location: ");
	
echo '
	<html>
	<head>
		<base href="http://boards.verniy.xyz/">
		<base href="boards.verniy.xyz">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body style="margin: 2% 5%;">
	<h1>Verify and Modify Posts</h1>
';

	if($_SESSION["mod"] != "pervert"){
		if($name_fail) echo "<p>Name Did Not Match</p>";
		if($pass_fail) echo "<p>Password wrong</p>";
		BoardFunctions::buildPassForm();
	}
	else{			
		echo "<form method='POST'><input hidden name='logout'/><input type='submit'/ value='Logout'></form>";
		BoardFunctions::displayVerificationForm(true, $connection);
	}
?>
</body>
</html>