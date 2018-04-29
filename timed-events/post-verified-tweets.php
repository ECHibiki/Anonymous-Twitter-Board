<?php //When called, make a request to pull a tweet from an SQL table 
	require_once("../class/board-level-database-connection.php");
	$db_connection = new BoardLevelDatabaseConnection("../");
	//row array
	$unposted_tweets = $db_connection->getAllUnposted();
	echo "<pre>";
		echo "<br/>" . var_dump($unposted_tweets);
	echo "</pre><hr/>";

	require_once("../class/twitter-connection.php");

	$tw_connection = new TwitterConnection("../");
	foreach($unposted_tweets as $tweet){
		$response = $tw_connection->makeTweet($tweet["PostText"], explode(",", $tweet["ImageURL"]));
		echo "<pre>";
		var_dump($tweet);
		echo "<pre><hr/>";
		if($response["created_at"] === null){
			echo "post unsuccessful ";
			echo($response["errors"][0]["code"]);
			$db_connection->updatePost("Unsubmitted", "PostID", $tweet["PostID"], 
					array('Unverified'=>$response["errors"][0]["code"]));
		} 
		else {
			$db_connection->deleteFromTable("Tweet", "PostID", $tweet["PostID"]); 	//might be 0
			echo "Found, Added and Deleted<br/>";
		}
	}
?>