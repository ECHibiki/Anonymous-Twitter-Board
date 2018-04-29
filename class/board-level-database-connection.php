<?php

	include_once("class/database-connection.php");
	include_once("../class/database-connection.php");

class BoardLevelDatabaseConnection extends DatabaseConnection{
	function __construct($path_prefix = ""){
		echo $path_prefix;
		parent::__construct($path_prefix);
	}

	function buildThread($build_type, $display_type, $thread_id){
		//echo "<hr/>";
		$replies = $this->getReplies($thread_id);
		//var_dump ($replies);
		$list_add = "";
		$reply_counter = 0;
		$row_size = 4;
		if($display_type == "list") $list_add = "-list";
		
		if($build_type == "native"){
			foreach($replies as $reply){
				//$this->testBlock( $reply);
				//echo"<hr/>";
				$post_id = $reply[0];
				if($reply_counter % $row_size == 0) echo"<ul class='row-reply" . $list_add ."'>";
				echo "<li class='reply-container" . $list_add ."' PostNo='" . $post_id ."'>";	
				//details
					echo "<div class='details" . $list_add ."'><ul class='embed-details'>
					<li>PostNo: " . $post_id .
					"	
					</li>
					<li  class='interaction-item" . $list_add ."'>
					<a href='/?thread=" . $post_id . "'>
						Open
					</a>
					</li>
					<li  class='interaction-item" . $list_add ."'>
					<a href='https://twitter.com/Qazoku/status/". $post_id ."'>
						Twitter
					</a>
					</li>
					</ul>
					</div>";
					//contents;
					echo "
					<div class='thread-contents" . $list_add ."'>
					<div class='thread-text" . $list_add ."'><blockquote>" . $reply["PostText"] ."</blockquote></div>
					<div class='thread-image" . $list_add ."'>";
					if($reply["ImageURL"] !== null)
						$this->createMediaNodeFromRaw($reply["ImageURL"]);
					else echo "<img/>";
					echo "</div></div>";
				echo "</li>";
				if($reply_counter % $row_size == $row_size - 1) echo "</ul>";
				$reply_counter++;
			}
		}
		else{
			ob_start();
			require_once("class/twitter-connection.php");
			ob_clean();
			
			$twitter_connection = new TwitterConnection($this->path_prefix);
			
			foreach($replies as $reply){
				$post_id = $reply[0];
				if($reply_counter % $row_size == 0) echo"<ul class='row-reply" . $list_add ."'>";

				echo "<li class='embeded reply-container" . ($display_type == "list" ? "-list max":"") . "' PostNo='" . $post_id ."'>";
				echo "<div class=''><ul class='embed-details'>
					<li class=''>PostNo: " . $post_id .
					"	
					</li>
					<li class='embed-detail-item'>
					<a href='/?thread=" . $post_id . "'>
						Open
					</a>
					</li>
					<li class='embed-detail-item'>
					<a href='https://twitter.com/Qazoku/status/". $post_id ."'>
						Twitter
					</a>
					</li>
					</ul>
					</div>";

				echo $twitter_connection->getEmbededTweet($post_id)["html"];

				echo "</li>";
								if($reply_counter % $row_size == $row_size - 1) echo"</ul>";
				$reply_counter++;
			}
		}
	}
	
	//Get the count of all items that are not Unsubmitted and not replies
	function getThreads(){
		$statement = $this->connection->prepare("SELECT * FROM `Tweet` 
										LEFT OUTER JOIN `Response` ON `Response`.`PostID` = `Tweet`.`PostID` 
										LEFT OUTER JOIN `Unsubmitted` ON `Unsubmitted`.`PostID` = `Tweet`.`PostID` 
										WHERE `Unsubmitted`.`PostID` IS NULL AND `Response`.`PostID` IS NULL
										ORDER BY Tweet.PostID DESC");
		try{	
			$statement->execute();
			$threads = $statement->fetchAll();
		}catch(Exception  $e){
		   echo "<strong>" . $e->getMessage() . "</strong><br/>";
		}				
		return $threads;
	}
	
	function getAllUnposted(){
		$statement = $this->connection->prepare("SELECT * FROM Tweet JOIN Unsubmitted ON `Tweet`.`PostID` = `Unsubmitted`.`PostID` WHERE `Unsubmitted`.`Unverified` = 0");
		try{
			$statement->execute();
			return  $statement->fetchAll();
		}
		catch(Exception $e){
			echo "<strong>" . $e->getMessage() . "</strong><br/>";
		}
	}
	
	function getReplies($post_id){
		$thread_replies= [];
		$this->getRepliesRecursive($post_id, $thread_replies);
		$thread_replies = array_reverse($thread_replies);
		$head = $this->getPostDetails("Tweet", "PostID", $post_id);
		array_unshift($thread_replies, $head[0]);
		return $thread_replies;
	}
	
	function getRepliesRecursive($post_id, &$thread_replies_store){
		//echo "grr_";
		$statement = $this->connection->prepare('
								SELECT * FROM `Tweet` 
								LEFT JOIN `Response` ON `Response`.`PostID` = `Tweet`.`PostID`
								WHERE `Response`.`RepliesTo` = :postID
								ORDER BY Tweet.PostID DESC');
		$statement->bindParam(":postID", $post_id);
		
		try{	
			$statement->execute();
			$threads = $statement->fetchAll();
		}catch(Exception  $e){
		   echo "<strong>" . $e->getMessage() . "</strong><br/>";
		   return;
		}				
		//$this->testBlock($threads);
		foreach($threads as $thread){
			$this->getRepliesRecursive($thread[0], $thread_replies_store);//0=postid
			array_push($thread_replies_store, $thread);
		}
	}
	
	function buildDetailList($array){
		$built_arr = array();
		foreach($array as $name=>$post){
			if(preg_match("/chk\d*/", $name)){
				$post_id = substr($name, 3);
				$built_arr[$post_id] = array("unv"=>$array["unv$post_id"],
											"ipd"=>$array["ipd$post_id"],
											"ban"=>$array["ban$post_id"]
											);
			}
		}
		return $built_arr;
	}
	
	function sendVerifiedPostID($id){
		echo " $id ";
		$data_arr = $this->getPostDetails("Tweet", "PostID", $id)[0];
		var_dump($data_arr);
		require_once("class/twitter-connection.php");
		$twitter_connection = new TwitterConnection();
		$twitter_connection->makeTweet($data_arr["PostText"], explode(",", $data_arr["ImageURL"]));
	}

	function toggleBanIPList($post_arr){
		$post_no_list = $this->buildDetailList($post_arr);
		foreach($post_no_list as $key=>$item){	
			$propper_entry = $this->getPostDetails("Banned", "IPAddress", $item["ipd"]);
			if(sizeof($propper_entry) > 0){
				$this->deleteFromTable("Banned", "IPAddress", $item["ipd"]);
			}
			else{
				$this->addToTable("Banned", array("IPAddress"=>$item["ipd"], "BanComment" => $item["ban"]));
			}
		}	
	}
	function verifyPosts($post_arr){
		$post_no_list = $this->buildDetailList($post_arr);
		foreach($post_no_list as $key=>$item){	
			$propper_entry = $this->getPostDetails("Unsubmitted", "PostID", $key);
			if(sizeof($propper_entry) > 0){
				$this->updatePost("Unsubmitted", "PostID", $key, array("Unverified" => $item["unv"]));
			}
			else throw "Post doesn't exist??";
		}	
	}
	
		
		
	function deleteExpiredEntries(){			
		$threads = $this->getThreads();
		$thread_count = 0;
		echo "A " . $this->path_prefix;
		$user_properties = new TwitterConnection($this->path_prefix);
		$delete_threshold = ($user_properties->getPostProperties()["Catalog-Size"]);
		//echo(var_dump($user_properties->getPostProperties()));
		foreach($threads as $thread){
			$thread_count++;
			echo $thread_count . " > $delete_threshold<br/>";
			if($thread_count > $delete_threshold){
				echo "DELETE-- ";
				var_dump ($thread);
				$this->deleteFromUnprocessedImageString($thread["ImageURL"]);
				$this->deleteChain($thread[0]);//0 is the most relevant PostID
			}
		}
		$database_connection = null;
	}
		
	function deleteChain($post_id){
		$delete_arr = array_reverse($this->getReplies($post_id));
		foreach($delete_arr as $delete_item){
			$this->deleteAllOfPost($delete_item["PostID"]);
		}
	}
	
	function deleteAllOfPost($post_id){
		$this->deleteFromTable("Response", "PostID", $post_id);//0=postid
		$this->deleteFromTable("Unsubmitted", "PostID", $post_id);//0=postid
		$this->deleteFromTable("Tweet", "PostID", $post_id);//0=postid
	}
	
	function deleteFromUnprocessedImageString($image_path_uprocessed){
		if($image_path_uprocessed === null) return;
		$image_path_uprocessed_arr = explode(",", $image_path_uprocessed);
		foreach($image_path_uprocessed_arr as $unprocessed_path){
			$path = rawurldecode($unprocessed_path);
			unlink ($path);
		}
	}
	
	
		function retrieveOldestEntry(){
		echo "<pre>";
		$retrieval_query = $this->connection->prepare("SELECT * FROM TweetQueue ORDER BY PostNo ASC LIMIT 1");

		$most_recent = $retrieval_query->execute();

		$data_arr = $retrieval_query->fetchAll();

		print_r($data_arr);

		$file_arr  = explode(",", ($data_arr[0]["ImageLocation"] ));
		return $data_arr;
	}
	
	function retrieveNewestEntry(){
		echo "<pre>";
		$retrieval_query = $this->connection->prepare("SELECT * FROM TweetQueue ORDER BY PostNo DESC LIMIT 1");

		$most_recent = $retrieval_query->execute();

		$data_arr = $retrieval_query->fetchAll();

		print_r($data_arr);

		$file_arr  = explode(",", ($data_arr[0]["ImageLocation"] ));
		return $data_arr;
	}
	
	function deleteOldestEntry($oldest){
		print_r($oldest);

		$this->deleteFromUnprocessedImageString($oldest[0]["ImageLocation"]);
		
		$delete_querry = $this->connection->prepare("DELETE FROM TweetQueue WHERE PostNo=:PostNo;");
		$delete_querry->bindParam(":PostNo", $oldest[0]["PostNo"]);
		$this->delete_status = $delete_querry->execute();
		
		if($this->delete_status !== 1){
			echo "<pre><hr/>Delete Err" . $delete_query->error;
		}
	}
		
	function addTimelineTweetsToDatabase($combined_database_arr){
		foreach($combined_database_arr as $key => $timeline_item){
			$timeline_item[1] = str_replace("<","&lt;",$timeline_item[1]);
			$timeline_item[1] = str_replace(">","&gt;",$timeline_item[1]);
			$timeline_item[1] = str_replace("\"","&quot;",$timeline_item[1]);
			
			$this->addToTable("Tweet",  array("PostID"=>$timeline_item[0],
						"PostText"=> $timeline_item[1], "ImageURL"=> $timeline_item[2]));
		}
		foreach($combined_database_arr as $key => $timeline_item){
			if($timeline_item[3] !== null)
				$this->addToTable("Response",  array("PostID"=>$timeline_item[0], "RepliesTo"=>$timeline_item[3]));
		}
	}
		
	function getAllSubmissionDetails($table_name, $join_with, $ordering_param,$ordering){
		$statement = $this->connection->prepare("SELECT * FROM $table_name JOIN $join_with ON `$table_name`.`PostID` = `$join_with`.`PostID` ORDER BY `$table_name` . `$ordering_param` $ordering");
		//$statement->bindParam(":ordering_param", $ordering_param);
		$statement->execute();
		return $statement->fetchAll();
	}
	
	function getVerificationDetails($ordering){
		$statement = $this->connection->prepare("
			SELECT DISTINCT * FROM `Tweet`
				LEFT JOIN `Response` ON `Tweet`.`PostID` = `Response`.`PostID`
				LEFT JOIN `Unsubmitted` ON `Tweet`.`PostID` = `Unsubmitted`.`PostID`
				LEFT JOIN `Banned` ON `Banned`.`IPAddress` = `Unsubmitted`.`IPAddress`
				ORDER BY `Tweet`.`PostID` $ordering
		");
																										
		$statement->execute();
		return $statement->fetchAll();
	}
	
}

?>