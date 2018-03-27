<?php
	if(isset($_POST["page-style"])){
		setcookie("page-style", $_POST["page-style"]);
		header("Location: http://boards.verniy.xyz/?thread=" . $_GET["thread"]);
	}
?>
<html>
<head>
<base href="boards.verniy.xyz">
<link  rel="stylesheet" type="text/css" href="/board-style.css?4" />
</head>
<body class="page-properties">
<div id="top-settings">
	<ul class="header-list">
		<li><h1>Anonymous Twitter Project</h1></li>
		<li><h2><a href="https://twitter.com/Qazoku">Connecting Twitter Page: @Qazoku</a></h2></li>
	</ul>
	
	
	
	<div id='timer-container'><strong>Time Until Next Update: </strong>
		<span id='time'></span>
	</div>
	<!--<a href="/?display=catalog">Catalog View</a> <a href="/?display=list">List View</a>-->
	<a href="javascript:void(0);" id="catalog-link">Catalog View</a> <a href="javascript:void(0);" id="list-link">List View</a>
	
	<script src="/board-script.js?1"></script>	
	
	<?php require("class/queue-database-construction.php");
		echo "	<div id='style-settings'>
		<form action='' method='post'>
			<label>Embeded View: </label><input type='radio' name='page-style' value='embeded' checked=1>
			<label>Native View: </label><input type='radio' name='page-style' value='native'" . ($_COOKIE["page-style"] == "native" ? "checked=1" : "") . ">

			<input type='submit'>
		</form>
		</div>";
		$construction = new QueueDatabaseConstruction();
		if(isset($_GET["thread"]) && $_GET["thread"] !== ""){	
			echo "</div><div id='posts-container'>";
				$construction->buildThread($_COOKIE["page-style"], "list", $_GET["thread"]);
			echo "</div>";
		}
		else{
			echo "<div id='queue-form-container'>";
				$construction->buildQueueForm($_COOKIE["page-style"], $_COOKIE["display"]);
			echo "</div></div>";//top settings end
			
			echo "<div id='posts-container'>";
				//function builds all posts inside container
				$construction->buildAllThreads($_COOKIE["page-style"], $_COOKIE["display"]);
			echo "</div>";
		}
	?>
		<script src="/form-script.js?53442544r65"></script>
</body>
</html>