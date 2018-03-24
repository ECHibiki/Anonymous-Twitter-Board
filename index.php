<html>
<head></head>
<body>
<?php
	require("class/twitter-connection.php");
	$connection = new TwitterConnection();
	$connection->retrieveTimeline();
	
	echo "<pre>";
	$connection->deleteExpiredEntries();
	echo "</pre>";
?>
</body>
</html>