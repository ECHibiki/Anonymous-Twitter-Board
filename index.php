<?php
	if(isset($_POST["page-style"])){
		setcookie("page-style", $_POST["page-style"]);
		header("Location: http://boards.verniy.xyz/?display=" . $_GET["display"]);
	}
?>
<html>
<head>
<base href="boards.verniy.xyz">
<link  rel="stylesheet" type="text/css" href="/board-style.css?1" />
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
		<script src="/board-script.js?3"></script>
	
	<?php require("class/queue-database-construction.php");
		echo "	<div id='style-settings'>
		<form action='' method='post'>
			<label>Native View: </label><input type='radio' name='page-style' value='native' checked=1>
			<label>Embeded View: </label><input type='radio' name='page-style' value='embeded'". ($_COOKIE["page-style"] == "embeded" ? "checked=1" : "") . ">
			<input type='submit'>
		</form>
		</div>";
		echo "<div id='queue-form-container'>";
			$construction = new QueueDatabaseConstruction();
			$construction->buildQueueForm();
		echo "</div></div>";//top settings end
		
		echo "<div id='posts-container'>";
			//function builds all posts inside container
			$construction->buildThreadPosts($_COOKIE["page-style"], $_COOKIE["display"]);
		echo "</div>";
	?>
	
</body>
</html>