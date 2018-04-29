<html>
<head></head>
<body>
<?php
	echo "<pre>";
	require_once("../class/twitter-connection.php");
	require_once("../class/board-functions.php");
	require_once("../class/board-level-database-connection.php");
		
	$connection_tw = new TwitterConnection("../");
	$connection_db = new BoardLevelDatabaseConnection("../");
	
	BoardFunctions::retrieveTwitterTimeline($connection_tw, $connection_db);
	$connection_db->deleteExpiredEntries();
	echo "</pre>";
?>
</body>
</html>