<html>
<head></head>
<body>
<?php
	require("class/twitter-connection.php");
	$connection = new TwitterConnection();
	$connection->retrieveTimeline();
?>
</body>
</html>