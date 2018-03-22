<html>
<head></head>
<body>
<?php
	require("class/twitter-connection.php");
	$connection = new TwitterConnection();
	echo $connection->getUserTimeline();
?>
</body>
</html>