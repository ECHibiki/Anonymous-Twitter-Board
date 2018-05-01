<?php
error_reporting(0);
	if(isset($_POST["page-style"]) && isset($_POST["display-style"])){
		setcookie("page-style", $_POST["page-style"]);
		setcookie("display-style", $_POST["display-style"]);
		header("Location: http://boards.verniy.xyz/?thread=" . $_GET["thread"]);
	}
	
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
<div id="top-settings" class="jumbotron pt-3">
		<h1 class='display-4'>Anonymous Twitter Project</h1></li>
		<h4 class=''><a href="https://twitter.com/Qazoku" class=''>Connecting Twitter Page: @Qazoku</a></h4>

	
	
<?php 
		echo"
		<div class='card  w-75 mt-5 ml-5'>
			<div class='card-header '>
				<button class='btn btn-link' data-toggle='collapse' data-target='#timerandsettings' aria-expanded='true' aria-controls='timerandsettings'>Settings</button>
			</div>
			<div id='timerandsettings'  class='collapse collapsed'>
				<div class='card-body'>
					<span><strong>Time Until Next Update: </strong>	<span id='time'></span><br/>
					<div id='style-settings'>
						<form action='' method='post'>
							<label>Catalog View: <input type='radio' name='display-style' value='catalog' checked=1>
							</label> <label>List View: <input type='radio' name='display-style' value='list' " . ($_COOKIE["display-style"] == "list" ? "checked=1" : "") . "></label><br/>
							<label>Embeded View: <input type='radio' name='page-style' value='embeded' checked=1></label>
							<label>Native View: <input type='radio' name='page-style' value='native'" . ($_COOKIE["page-style"] == "native" ? "checked=1" : "") . "></label><br/>
							<input type='submit'>
						</form>
						</div>
					</span>
				</div>
			</div>
		</div>
";
		
		$connection = new BoardLevelDatabaseConnection();
		$connection->deleteFromTable("SubmissionTicket", "IPAddress", $_SERVER["HTTP_X_REAL_IP"]);
		
	
		if(isset($_GET["thread"]) && $_GET["thread"] !== ""){	
			echo "</div><div id='posts-container' >";
				BoardFunctions::buildThread($_COOKIE["page-style"], $_COOKIE["display-style"], $_GET["thread"], $connection);
			echo "</div>";
		}
		else{
			echo "<div id='queue-form-container'>";
				BoardFunctions::buildQueueForm($_COOKIE["page-style"], $_COOKIE["display-style"]);
			echo "</div></div>";//top settings end
			
			echo '			
				<script src="rsc/board-script.js?'. time() .'"></script>	
				<script src="rsc/form-script.js?'. time() .'"></script>'; //Build scripts
				
				if($_COOKIE["display-style"] == "catalog"){
					echo "<div id='posts-container' class='d-flex flex-wrap'>";
				}
				else{
					echo "<div id='posts-container' class=''>";
				}
				//function builds all posts inside container
				BoardFunctions::buildAllThreads($_COOKIE["page-style"], $_COOKIE["display-style"], $connection);
			echo "</div>";
		}
	?>
</body>
</html>