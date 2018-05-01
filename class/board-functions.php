<?php
	include_once("class/database-connection.php");
	include_once("../class/database-connection.php");
		include_once("class/twitter-connection.php");
	include_once("../class/twitter-connection.php");
class BoardFunctions{

	public static $die_state = array();
	public static $comment_error = false;
	
	function __construct(){}
	
	public static function buildAudioGimick($song = 0){
		$track_list = scandir (__DIR__ . "/../audio", FilesystemIterator::SKIP_DOTS);
		asort($track_list);
		$track_list = array_slice($track_list, 4);//remove non-audio and dots
		$track = 'audio/' . $track_list[$song];

		echo '
			<audio id="blaring-music" controls autoplay preload="none" class="top-widget">
			  <source src="' . $track  . '" type="audio/mpeg">
			  Your browser does not support the audio tag.
			</audio>
			<script>
			  var audio = document.getElementById("blaring-music");
			  audio.volume = 0.1;
			</script>
			';

			$post_properties = parse_ini_file("settings/postproperties.ini");
			$id = "";
			if($song == 0){
			$id = "kind-of-a-huge-deal-komrad";
		echo "
		<style>#kind-of-a-huge-deal-komrad{
			font-size: 120px;
			text-align:center;
			margin:0% 7%;
			color:BLUE;
			position:absolute;}</style>

		";
			echo '<h1 id="' . $id  . '">ХОРОШО <br/>POSTER NUMBER: ' . $post_properties["TotalPosts"] .'<br/>
		<br/>Спасибо!</h1>';
			}
			else if ($song == 1){
		echo"
		<style>#a-huge-deal-komrad{
			font-size: 130px;
			text-align:center;
			margin:0% 7%;
			color:purple;
			position:absolute;
		}</style>
		";
				$id = "a-huge-deal-komrad";
				echo '<h1 id="' . $id  . '">ХОРОШО<br/> POSTER NUMBER: ' . $post_properties["TotalPosts"] .'<br/><br/>HIBIKI LIVED, SO WILL YOU!</h1>';
			}
			else if($song == 2){
			$id = "fucking-huge-deal-komrad";
		echo"
		<style>#fucking-huge-deal-komrad{
			font-size: 200px;
			text-align:center;
			margin:0% -5%;
			color:red;
			position:absolute;
		}</style>
		";
			echo '<h1 id="' . $id . '">CONGRAGULATIONS KOMRAD NUMBER:' . $post_properties["TotalPosts"] .' <br/> KOMRAD. 
					YOU MADE A VERY GREAT POST ON THIS VERY GOOD SITE UNFORTUNATLY WE HAVE NO FOOD TO GIVE SINCE WE KILLED ALL THE UKRAINIAN FARMERS. 
					MANY THANKS AND HAVE A NICE DAY SORRY FOR THE EAR RAPE!!!!!!!!!!!!!</h1>';
			}
			else{
			$r = rand(0,255);
			$g = rand(0,255);
			$b = rand(0,255);
			$added_properties = "color:rgb($r,g,$b)";
				$id = "its-a-deal-komrad";
			echo "<style>#its-a-deal-komrad{
				font-size: 100px;
				text-align:center;
				margin:0% 14%;
				color:rgb($r,$g,$b);	
				position:absolute;
			}</style>";
			echo "<h1 id='$id' style='color:rgb($r,g,$b)'>ХОРОШО<br/> POSTER NUMBER:". $post_properties["TotalPosts"] . " </h1>";
		}

			echo'
			<script>
			//https://html.com/tags/blink/
			var blink_speed = 700; var t = setInterval(function () { var ele = document.getElementById("'. $id .'"); ele.style.visibility = (ele.style.visibility == "hidden" ? "" : "hidden"); }, blink_speed);
			</script>
			';

	}
	
	public static function buildNavBar(){
		echo'		<nav class="navbar navbar-expand-sm bg-secondary">
			  <ul class="navbar-nav">
				<li class="nav-item">
				  <a class="nav-link text-success" href="/">Home</a>
				</li>
				<li class="nav-item">
				  <a class="nav-link text-success" href="/view-queue">To Be Posted</a>
				</li>
				<li class="nav-item">
				  <a class="nav-link text-success" href="https://twitter.com/Qazoku">@Qazoku</a>
				</li>
			  </ul>
		</nav>';
	}


	public static function buildQueueForm(){
			
		
		echo'<div class="card w-75 mx-5 my-0">
				<div class="card-header">
					<button class="btn btn-link" data-toggle="collapse" data-target="#comment-fields" aria-expanded="true" aria-controls="comment-fields">Comment</button>
				</div>
				<div id="comment-fields" class="collapse px-5 pt-2 pb-5">
					<form action="add-to-queue.php" enctype="multipart/form-data" method="POST" target="_self" id="submit-form">
					<br />
					<textarea id="Comment" name="comment" rows="10" cols="60" placeholder = "Comment Text Here"></textarea>
					<p id="CharacterCount" class="lead"></p>

					<input name="MAX_FILE_SIZE" type="hidden" value="5242880" />
					<div class="dropdown">
						<button class="btn dropdown-toggle" type="button" id="file-container" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Add Image</button>
						<div class="dropdown-menu pt-3" aria-labelledby="file-container">
							<input name="file1" type="file" id="f1" class="dropdown-item form-control-file"/>
							<input name="file2" type="file" id="f2" class="dropdown-item form-control-file"/>
							<input name="file3" type="file" id="f3" class="dropdown-item form-control-file"/>
							<input name="file4" type="file" id="f4" class="dropdown-item form-control-file"/>
						</div>
					</div>
					<hr />
					<div id="errorMsg" class="alert alert-warning" role="alert">Input a comment and/or file</div>
					<input id="submit-button" class="btn btn-secondary form-control" type="submit" disabled="1"/></form>					
				</div>
			</div>
		';
	}
	
	public static function buildPassForm(){
		echo"<form action='' method='POST'>
		<input name='name'><br/>
		<input name='pass' type='password'><br/>
		<input type='submit' id='authorization-input' value='Authorize'><br/>
		<label>Stay Logged In: <input type='checkbox' name='persistent-login'></label><br/></form>";
	}

	
	
	public static function retrieveTwitterTimeline($connection_tw, $connection_db){
		$timeline_tweets = $connection_tw->retrieveTimeline();
		StandardFunctions::recursiveEchoJson($combined_database_arr,0);
		
		echo sizeof($timeline_tweets)  . "<pre>";
		if(sizeof($timeline_tweets) != 0){
			$connection_db->addTimelineTweetsToDatabase($timeline_tweets);
		} 
		echo "Done</pre>";
	}
	
	
	public static function checkSubmissionValid($tweet_comment="", $file_string="",$database_con){
		$COMMENT_MAX = 280;
		BoardFunctions::$comment_error = 0;
		$tweet_comment = trim($tweet_comment);
		$banned = $database_con->getPostDetails("Banned", "IPAddress", $_SERVER['HTTP_X_REAL_IP'])[0]["BanComment"];
		$banned = str_replace(" ", "_", $banned);
		if($banned != null){
			BoardFunctions::$comment_error = "-5 $banned";
		}
		else if(mb_strlen($tweet_comment) > $COMMENT_MAX){
			BoardFunctions::$comment_error = "-3 Comment-too-long";
		}
		else if(mb_strlen($tweet_comment) == 0){
			BoardFunctions::$comment_error = "-4 No Comment";
		}
		else if(preg_match("/VERIFY: /", $tweet_comment) == 0){
			$filters = JSON_Decode(file_get_contents("settings/verify-levels.json"), true);
			if($filters["Image-Block"] == 1 && !($file_string == "" || $file_string == NULL))
				BoardFunctions::$comment_error = "-2 Images_disabled";
			else if($filters["URL-Block"] == 1 && (preg_match("/http/", $tweet_comment) == 1 || preg_match("/\.com/", $tweet_comment) == 1))
				BoardFunctions::$comment_error = "-2 URLs_disabled";
			else if($filters["At-Block"] == 1 && preg_match("/@/", $tweet_comment) == 1) 
				BoardFunctions::$comment_error = "-2 @_links_disabled";
			else if($filters["Filter-Text-Active"] == 1){
				foreach($filters["Filter-Text"] as $filter){
					if(preg_match("/$filter/", $tweet_comment) == 1) {
						BoardFunctions::$comment_error = "-2 Remove_$filter";
					}
				}
			}
		}
		return $tweet_comment;
	}
	
	public static function uploadAndVerify($files){			
		$FILE_MAX = 5242880;
		$file_arr = array();
		$file_string = "";
		$first = true;
		for($file = 0; $file < 4; $file++){
			//empty check
			if($files[$file] == "") {
				//echo "file " . (string)$file .", Empty<br/>";
				BoardFunctions::$die_state[$file] = 5;
				continue;
			}
			
			$file_components = explode("=", ($files[$file]));
			$file_name = urldecode($file_components[0]);
			$file_data = $file_components[1];
			$upload_location = "images/" . $file_name;
			
			//duplicate check
			if(file_exists($upload_location)) {
				//echo "file " . (string)$file .", Duplicate ($upload_location)<br/>";
				BoardFunctions::$die_state[$file] = 6;
				continue;
			}
			
			$dir_file = fopen($upload_location, "w");
			fwrite($dir_file, base64_decode($file_data));
			fclose($dir_file);
			$file_size = filesize ($upload_location);//https://softwareengineering.stackexchange.com/questions/288670/know-file-size-with-a-base64-string
			
			//over filesize check
			if($file_size >= $FILE_MAX){
				//echo "file" . (string)$file ." Over filesize limit-Server $file_size<br/>";
				BoardFunctions::$die_state[$file] = 1;
				unlink($upload_location);
				continue;
			}

			if($first){
				$file_string .= rawurlencode($upload_location);
				$first = false;
			}
			else{
				$file_string .=  "," . rawurlencode($upload_location);
			}

			BoardFunctions::$die_state[$file] = 0;	
		}
		return $file_string;
	}
	
	public static function displayTabularDatabase($table_name, $ordering_param, $display_images = false){
		echo "<br/>Displaying All entries(lower number means posted sooner): <br/>";
		$statement = $this->connection->prepare("Select * from $table_name ORDER BY :param DESC;");
		$statement->bindParam(":param", $ordering_param);
		$statement->execute();
		$result_arr = $statement->fetchAll();
		if(sizeof($result_arr) !== 0){
			foreach($result_arr[0] as $key=>$head){
				if(is_numeric ($key)) unset($result_arr[0][$key]);
			}
			
			echo "<table border='1' class='table table-striped'><tr> <thead class='table-dark'><tr>";
			foreach($result_arr[0] as $key=>$head_item)
				echo "<th>$key</th>";
			echo "</thead></tr><tbody>";	
					
			for($row = sizeof($result_arr) - 1; $row >= 0 ; $row--){
				echo"<tr>";
				$tupple = $result_arr[$row];
				$column = 0;
				foreach($tupple as $key=>$col){
					if(is_numeric ($key)) unset($result_arr[0][$key]);
					else {
						if($column == 2 && $display_images){
							$img_arr = explode(",", $col);
							foreach($img_arr as $img){
								$img = urldecode($img);
								$img_ext = pathinfo($img, PATHINFO_EXTENSION);
								if(strcmp($img_ext, "png") == 0 || strcmp($img_ext, "jpg")  == 0|| strcmp($img_ext, "gif") == 0) 
									echo "<td>" . $this->createImageNode($img) . "</td>";
								else
									echo "<td>" . $this->createVideoNode($img) . "</td>";
								
							}
						}
						else{
							if($key == "PostNo") echo "<td>$col - $row</td>";
							else echo "<td>$col</td>";
						}
						$column++;
					}
				}
				echo"</tr>";
			}
			echo "</tbody></table><hr/>";
		}
		else echo '<table  border="1"><hr/><th>No Entries</th><tr></table>';
		
	}
	
	public static function displayTabularJoin($table_name, $join_with, $ordering_param, $skip_col, $display_images = false,  $database_con, $ordering = "ASC"){
		echo "<h3>Displaying All Combined Entries(lower number means posted sooner): </h3>";			
		$result_arr = $database_con->getAllSubmissionDetails($table_name, $join_with, $ordering_param,$ordering);

		if(sizeof($result_arr) !== 0){
			foreach($result_arr[0] as $key=>$head){
				if(is_numeric ($key)) unset($result_arr[0][$key]);
			}
			
			echo "<table border='1' class='table table-striped'><tr> <thead class='table-dark'>";
			foreach($result_arr[0] as $key=>$head_item){
				if($column++ == $skip_col) continue;
				echo "<th>$key</th>";	
			}
					echo "</tr>  </thead><tbody>";
			
			
			
			for($row = sizeof($result_arr) - 1; $row >= 0 ; $row--){
				echo"<tr>";
				$tupple = $result_arr[$row];
				$column = 0;
				foreach($tupple as $key=>$col){
				//fail checks
					if(is_numeric ($key)) {
						unset($result_arr[0][$key]);
						continue;
					}
					else if($column == $skip_col){
						$column++;
						continue;
					}
					//pass chekcs
					echo "<td>";
					if($key=="Unverified"){
						echo "<strong>";
						if($col == "1") echo "Withheld"; //is unverified
						else if ($col == "-1") echo "Banned"; //banned
						else if($col == "187") echo "Duplicate"; //is not unverified
						else if($col == "170") echo "No Contents"; //is not unverified
						else if($col == "0") echo "Verified"; //is not unverified
						else echo $col;
						echo "</strong>";
					}
							else if($key == "PostNo") echo "$col - $row";
					else if($key == "ImageURL" && $display_images){
							$img_arr = explode(",", $col);
							foreach($img_arr as $img){
								$img = urldecode($img);
								$img_ext = pathinfo($img, PATHINFO_EXTENSION);
								if(strcmp($img_ext, "png") == 0 || strcmp($img_ext, "jpg")  == 0|| strcmp($img_ext, "gif") == 0) 
									echo BoardFunctions::createImageNode($img);
								else if(strcmp($img_ext, "mp4") == 0)
									echo  BoardFunctions::createVideoNode($img);
								else {};
							}	
					}
					else echo "$col";
					$column++;
					echo "</td>";
				}
				echo"</tr>";
			}
			echo "</tbody></table><hr/>";
		}
		else echo '<table  border="1"><hr/><th>No Entries</th><tr></table>';
		
	}
	
	public static function displayVerificationForm($display_images = false, $database_con, $ordering = "DESC"){
		echo "<h3>Displaying All Data(lower number means posted sooner): </h3>";			
		$result_arr = $database_con->getVerificationDetails($ordering);
		if(sizeof($result_arr) !== 0){	
			echo "<form method='POST' action='proccess-mod.php'><table border='1' class='table table-striped'><thead class='table-dark'><tr>";
			echo "<th>Item Selected</th>";	
			foreach($result_arr[0] as $key=>$head_item){
				if(!is_numeric ($key)) echo "<th>$key</th>";	
			}
			echo "</thead></tr></tbody>";
			for($row = 0; $row <= sizeof($result_arr) - 1 ; $row++){
				if($result_arr[$row][0] != null){
					$result_arr[$row]["PostID"] = $result_arr[$row][0];
				}
				if($result_arr[$row][6] != null){
					$result_arr[$row]["IPAddress"] = $result_arr[$row][6];
				}
				echo"<tr>";
				echo "<td><input type='checkbox' name='chk". $result_arr[$row]["PostID"] ."' /></td>";
				
				$tupple = $result_arr[$row];
				$column = 0;
				foreach($tupple as $key=>$col){
				//fail checks
					if(is_numeric ($key)) {
						unset($result_arr[0][$key]);
						continue;
					}
					//pass chekcs
					echo "<td>";
					if($key=="Unverified"){
						echo "<strong>";
						if($col == "1") echo "Withheld"; 			//is unverified
						else if($col == "187") echo "Duplicate"; 	//is not unverified
						else if($col == "170") echo "No Contents"; 	//is not unverified
						else if($col == "0") echo "Verified"; 		//is not unverified
						else if($result_arr[$row]["RepliesTo"] == "") echo "Posted";
						else if($result_arr[$row]["RepliesTo"] != "") echo "Replied";
						else echo $col;
						if ($col == "") $col = "-";
						echo "</strong>";
						if($col == "1" || $col == "0") echo ": <input placeholder='$col' name='unv". $result_arr[$row]["PostID"] ."' class=''/>";
					}
					else if($key == "PostID") echo ($row+1) . " - $col";
					else if($key == "ImageURL" && $display_images){
							$img_arr = explode(",", $col);
							if($img_arr[0] == "") echo "<strong>None</strong>";
							foreach($img_arr as $img){
								$img = urldecode($img);
								$img_ext = pathinfo($img, PATHINFO_EXTENSION);
								if(strcmp($img_ext, "png") == 0 || strcmp($img_ext, "jpg")  == 0|| strcmp($img_ext, "gif") == 0) 
									echo BoardFunctions::createImageNode($img);
								else if(strcmp($img_ext, "mp4") == 0)
									echo  BoardFunctions::createVideoNode($img);
								else {};
							}
		
					}
					else if($key == "BanComment"){
						if( $result_arr[$row]["IPAddress"] != "")
							echo "<textarea name='ban" . $result_arr[$row]["PostID"] . "' class=''>$col</textarea> ";
						else echo "-";
					} 
					else if($key == "IPAddress"){
						if($col != "") echo "<input hidden name='ipd" . $result_arr[$row]["PostID"]. "' value='$col' />$col";
						else echo "-";
					} 
					else if($key == "RepliesTo" && $col == "") echo "-";
					else if($key == "BanComment" && $col == "") echo "-";
					else echo "$col";
					$column++;
					echo "</td>";
				}
				echo"</tr>";
			}
			echo "</table><br/>";
			echo "<input type='submit' value='Delete Entries' name='delete-button'>";
			echo "<input type='submit' value='Toggle IP Ban' name='ban-button'>";
			echo "<input type='submit' value='Set Unverifed' name='verify-button'>";
			echo "<input type='submit' value='Send Entries' name='send-button'>";
			echo "</form>";
			echo "<hr/>";
			echo "<h2>Verification Blocks</h2>";
			echo "<form method='POST' action='proccess-mod.php'>";
			//read from text file settings and place into inputs
			$settings_file = JSON_Decode(file_get_contents($database_con->path_prefix . "settings/verify-levels.json"), true);
			if($settings_file["Image-Block"] == 0){
				echo "<label><input type='checkbox' name='blockimg'>Block Images</label><br/>";
			}
			else{
				echo "<label><input type='checkbox' name='blockimg' checked>Block Images</label><br/>";
			}
			if($settings_file["URL-Block"] == 0){
				echo "<label><input type='checkbox' name='blockurl'>Block URLS</label><br/>";
			}
			else{
				echo "<label><input type='checkbox' name='blockurl' checked>Block URLS</label><br/>";
			}
			if($settings_file["At-Block"] == 0){
				echo "<label><input type='checkbox' name='blockat'>Block @ Links</label><br/>";
			}
			else{
				echo "<label><input type='checkbox' name='blockat' checked>Block @ Links</label><br/>";
			}
			if($settings_file["Filter-Text-Active"] == 0){
				echo "<label><input type='checkbox' name='blocktext' id='blocktext'>Block Filtered Text</label><br/>";
				if(sizeof($settings_file["Filter-Text"]) == 0){
					echo "<span style='display:none' class='filters'>Item 1: <input type='text' name='filter1' '>
						<a style='text-decoration:none' id='plus' class='fa fa-plus-circle' href='javascript:void(0)'></a ></span><br/>";
				}
				else{
					echo "<span style='display:none;margin:10px;' class='filters'>";
					foreach($settings_file["Filter-Text"] as $index=>$filter){
						$filter_number = $index + 1;
						echo "Item $filter_number: <input type='text' name='filter$filter_number' value='$filter'>";
						if($index == 0){
							echo "<a style='text-decoration:none' id='plus' class='fa fa-plus-circle' href='javascript:void(0)'></a >";	
						}
						echo "<br/>";
					}
					echo"</span>";
				}
			}		
			else{
				echo "<label><input type='checkbox' name='blocktext' id='blocktext' checked>Block Filtered Text</label><br/>";
				echo "<span style='display:block;margin:10px;' class='filters'>";
				foreach($settings_file["Filter-Text"] as $index=>$filter){
					$filter_number = $index + 1;
					echo "Item $filter_number: <input type='text' name='filter$filter_number' value='$filter'>";
					if($index == 0){
						echo "<a style='text-decoration:none' id='plus' class='fa fa-plus-circle' href='javascript:void(0)'></a >";	
					}
					echo "<br/>";
				}
				echo"</span>";
			}
			//script to handel new filters
			echo "		
			<script>
				var blocktext_node=document.getElementById('blocktext');
				blocktext_node.addEventListener('click', function(){
					var filters = document.getElementsByClassName('filters');
					if(blocktext_node.checked == false){
						var len = filters.length;
						console.log(filters);
						for(var filter = 0; filter < len ; filter++){
							filters[filter].style.display = 'none';
						}
					}
					else{
						var len = filters.length;
						console.log(filters);
						for(var filter = 0; filter < len ; filter++){
							filters[filter].style.display = 'block';
						}
					}
				});
				var plus = document.getElementById('plus');
				plus.addEventListener('click',function(){
					var filters = document.getElementsByClassName('filters');
					var new_span = document.createElement('SPAN');
					new_span.innerHTML='Item ' + (filters[filters.length-1].getElementsByTagName('INPUT').length + 1) + ': <input type=\'text\' name=\'filter' + (filters[filters.length-1].getElementsByTagName('INPUT').length + 1) + '\'></span><br/>';
					filters[filters.length-1].appendChild(new_span);
				});
			</script>
			";
			echo "<br/><input type='submit' value='Set Verification Levels' name='levels-button'>";
			echo "</form>";
		}
		else echo '</tbody><table  border="1"><hr/><th>No Entries</th><tr></table>';
		
	}
	
	public static function logIntoAuthorizedSpace($post_fields, $client_ip, &$session_fields, $database_con){
		if($post_fields["logout"] !== null){ 
			$database_con->updatePost("Authorized", "LoggedInIP", $client_ip, 
												array("LoggedInIP" => null));
			$session_fields["mod"] = "Good Samaritan";
			return true;
		}
		else{
			$name_fail = false;
			$pass_fail = false;
			
			$persistent_lookup = $database_con->getPostDetails("Authorized", 
													"LoggedInIP", $client_ip);
			if(sizeof($persistent_lookup) > 0 ){
				 $session_fields["mod"] = "pervert";
				 header("Location: ");
			}
			else{
				$user_lookup = $database_con->getPostDetails("Authorized", "ModName", $post_fields["name"]);
				if(sizeof($user_lookup) == 0) $name_false = true;
				else{
					$password_hashed = hash("SHA512", $post_fields["pass"]);
					if(strcasecmp($password_hashed, $user_lookup[0]["ModSha512"]) == 0){
						if($post_fields["persistent-login"])
							$database_con->updatePost("Authorized", "ModName", $post_fields["name"], 
													array("LoggedInIP" => $client_ip));
						else			
							$database_con->updatePost("Authorized", "ModName", $post_fields["name"], 
													array("LoggedInIP" => null));
							
						 $session_fields["mod"] = "pervert";
						 header("Location: ");
					} 
					else $pass_fail = true;
				}
			}
			return false;
		}
	}

	public static function createMediaNodeFromRaw($img_path_unprocessed){
		$img_arr = explode(",", $img_path_unprocessed);
		foreach($img_arr as $img){
			$img = urldecode($img);
			$img_ext = pathinfo($img, PATHINFO_EXTENSION);
			if(strcmp($img_ext, "png") == 0 || strcmp($img_ext, "jpg")  == 0|| strcmp($img_ext, "gif") == 0) 
				echo "<td>" . BoardFunctions::createImageNode($img) . "</td>";
			else
				echo "<td>" . BoardFunctions::createVideoNode($img) . "</td>";
			
		}
	}
	
	public static function createImageNode($img_path){
		//return "<img src='$img_path' width='50%'/>";
		return "<a href='$img_path'><img src='$img_path' class='img-fluid' style=''/></a>";
	}
	public static function createVideoNode($vid_path){
		//return "<video src='$vid_path' autoplay='true' loop='true' width='50%'/>"
		return "<a href='$vid_path'><video src='$vid_path' autoplay='true' loop='true' class='img-fluid' /></a>";
	}
	public static function buildAllThreads($build_type, $display_type, $database_con){
		$threads = $database_con->getThreads();
		$thread_counter = 0;
		$row_size = 4;
		$list_add = "";
		if($build_type == "native"){
			if($display_type == "list"){
				foreach($threads as $thread){
					$post_id = $thread[0];
					echo "<div class='container px-0 my-4 border' PostNo='" . $post_id ."'>";	
					//details
						echo "<div class='border p-2 bg-light'>
								<div class='col'>
								<div class='row'>
									<span>PostNo: " . $post_id ."</span>
									<span  class=''>
								</div>
								<div class='row'>
									<a href='/?thread=" . $post_id . "' class='px-4 py-0'>
										Open
									</a>
									</span>
									<span  class=''>
									<a href='https://twitter.com/Qazoku/status/". $post_id ."'>
										Twitter
									</a>
									</span>
								</div>
								</div>
						</div>";
						//contents;
						echo "
						<div class='row px-1 py-0'>
						<div class='col-8 p-4'><blockquote>" . $thread["PostText"] ."</blockquote></div>
						<div class='col-4'>";
						if($thread["ImageURL"] !== null)
							BoardFunctions::createMediaNodeFromRaw($thread["ImageURL"]);
						else echo "<img/>";
						echo "</div>
						</div>";
					echo "</div>";
					//if($thread_counter % $row_size == $row_size - 1) echo "</ul>";
				}
			}
			else{
				foreach($threads as $thread){
					$post_id = $thread[0];
					echo "<div class='d-flex flex-wrap border w-25 m-4' PostNo='" . $post_id ."'>";	
					//details
						echo "<div class='border px-0 py-2  col-12  bg-light' style='height:5rem'>
								<div class='col'>
									<div class='row'>
									<div class='col-1'></div>
										<span class='col-10'>PostNo: " . $post_id ."</span>
									
									<div class='col-1'></div>
									</div>
									<div class='row'>
										<div class='col-1'></div>
										<span class='col-3'>
										<a href='/?thread=" . $post_id . "' class='px-4 py-0'>
											Open
										</a>
										</span>
											<div class='col-1'></div>
										<span class='col-3'>
										<a href='https://twitter.com/Qazoku/status/". $post_id ."'>
											Twitter
										</a>
										</span>
									</div>
								</div>
						</div>";
						//contents;
						echo "
						<div class='px-1 py-0'>
						<div class='row p-4'><blockquote>" . $thread["PostText"] ."</blockquote></div>
						<div class='row col-12' style=''>";
						if($thread["ImageURL"] !== null)
							BoardFunctions::createMediaNodeFromRaw($thread["ImageURL"]);
						else echo "<img/>";
						echo "</div>
						</div>";
					echo "</div>";
					//if($thread_counter % $row_size == $row_size - 1) echo "</ul>";
				}
			}
			
			
		}
		else{
			require_once("class/twitter-connection.php");
			
			$twitter_connection = new TwitterConnection();
			
			if($display_type == "list"){
				foreach($threads as $thread){
					$post_id = $thread[0];
					
										//if($thread_counter % $row_size == 0) echo"<ul class='row-container" . $list_add ."'>";
					echo "<div class='container w-50 border p-0 m-4' PostNo='" . $post_id ."'>";	
					//details
						echo "<div class='border m-0 bg-light'>
								<div class='col'>
								<div class='row'>
									<span>PostNo: " . $post_id ."</span>
									<span  class=''>
								</div>
								<div class='row'>
									<a href='/?thread=" . $post_id . "' class='px-4 py-0'>
										Open
									</a>
									</span>
									<span  class=''>
									<a href='https://twitter.com/Qazoku/status/". $post_id ."'>
										Twitter
									</a>
									</span>
								</div>
								</div>
						</div>";
						//contents;
						echo "<div class='row'>
						<div class='col-1'></div>
							<div class='col-10 px-0 py-0'>";
								echo $twitter_connection->getEmbededTweet($post_id)["html"];
							echo "</div>
							<div class='col-1'></div>
						</div></div>
						
						</div>";
					//if($thread_counter % $row_size == $row_size - 1) echo "</ul>";			
				}
			}
			else{
				foreach($threads as $thread){
					$post_id = $thread[0];
					echo "<div class='d-flex flex-wrap border m-4' style='width:550px' PostNo='" . $post_id ."'>";	
					//details
						echo "<div class='border px-0 py-2  col-12  bg-light' style='height:5rem'>
								<div class='col'>
									<div class='row'>
									<div class='col-1'></div>
										<span class='col-10'>PostNo: " . $post_id ."</span>
									
									<div class='col-1'></div>
									</div>
									<div class='row'>
										<div class='col-1'></div>
										<span class='col-3'>
										<a href='/?thread=" . $post_id . "' class='px-4 py-0'>
											Open
										</a>
										</span>
											<div class='col-1'></div>
										<span class='col-3'>
										<a href='https://twitter.com/Qazoku/status/". $post_id ."'>
											Twitter
										</a>
										</span>
									</div>
								</div>
						</div>";
						//contents;
						echo "
						<div class='px-1 py-0'>
						<div class='p-2'>
						<div class=''>" . $twitter_connection->getEmbededTweet($post_id)["html"] ."
						</div>
						</div></div>";
					echo "</div>";
					//if($thread_counter % $row_size == $row_size - 1) echo "</ul>";
				}
			}
		}
	}
	
	public static function buildThread($build_type, $display_type, $thread_id, $database_con){
		//echo "<hr/>";
		$replies = $database_con->getReplies($thread_id);
		//var_dump ($replies);
		$list_add = "";
		$reply_counter = 0;
		$row_size = 4;
		if($build_type == "native"){
			foreach($replies as $reply){
				$post_id = $reply[0];
					echo "<div class='container px-0 my-4 border' PostNo='" . $post_id ."'>";	
					//details
						echo "<div class='border p-2 bg-light'>
								<div class='col'>
								<div class='row'>
									<span>PostNo: " . $post_id ."</span>
									<span  class=''>
								</div>
								<div class='row'>
									<a href='/?thread=" . $post_id . "' class='px-4 py-0'>
										Open
									</a>
									</span>
									<span  class=''>
									<a href='https://twitter.com/Qazoku/status/". $post_id ."'>
										Twitter
									</a>
									</span>
								</div>
								</div>
						</div>";
						//contents;
						echo "
						<div class='row px-1 py-0'>
						<div class='col-8 p-4'><blockquote>" . $reply["PostText"] ."</blockquote></div>
						<div class='col-4'>";
						if($reply["ImageURL"] !== null)
							BoardFunctions::createMediaNodeFromRaw($reply["ImageURL"]);
						else echo "<img/>";
						echo "</div>
						</div>";
					echo "</div>";
				
			}
		}
		else{
			ob_start();
			require_once("class/twitter-connection.php");
			ob_clean();
			
			$twitter_connection = new TwitterConnection($connection->path_prefix);
			
			foreach($replies as $reply){
				$post_id = $reply[0];
					
										//if($thread_counter % $row_size == 0) echo"<ul class='row-container" . $list_add ."'>";
					echo "<div class='container w-50 border p-0 m-4' PostNo='" . $post_id ."'>";	
					//details
						echo "<div class='border m-0 bg-light'>
								<div class='col'>
								<div class='row'>
									<span>PostNo: " . $post_id ."</span>
									<span  class=''>
								</div>
								<div class='row'>
									<a href='/?thread=" . $post_id . "' class='px-4 py-0'>
										Open
									</a>
									</span>
									<span  class=''>
									<a href='https://twitter.com/Qazoku/status/". $post_id ."'>
										Twitter
									</a>
									</span>
								</div>
								</div>
						</div>";
						//contents;
						echo "<div class='row'>
						<div class='col-1'></div>
							<div class='col-10 px-0 py-0'>";
								echo $twitter_connection->getEmbededTweet($post_id)["html"];
							echo "</div>
							<div class='col-1'></div>
						</div></div>
						
						</div>";
					//if($thread_counter % $row_size == $row_size - 1) echo "</ul>";
					
			}
		}
	}
	
	public static function uploadMedia($filename,$url){
		echo("<br/>" . $filename . " " . $url . "<br/>");
		 $file_binary = file_get_contents($url);
		 fopen($filename, "w");
		file_put_contents($filename, $file_binary);
	}
	
}
	
?>