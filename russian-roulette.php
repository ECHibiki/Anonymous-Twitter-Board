<?php
error_reporting (0);
//https://stackoverflow.com/questions/12801370/count-how-many-files-in-directory-php
$fi = new FilesystemIterator(__DIR__ . "/audio/", FilesystemIterator::SKIP_DOTS);
//printf("There were %d Files", iterator_count($fi));

$song = rand(0,iterator_count($fi));
$transition_message = "";

if($song == 2)
	$transition_message = "Turn down your volume";
else 
	$transition_message = "Post confirmed. You're safe for now...";


$get_fields = "";
$first_entry = true;
$_GET["song"] = $song;
foreach($_GET as $key=>$value){
	if($first_entry){
		$first_entry = false;
		$get_fields = "$key=$value";
	}
	else{
		$get_fields .= "&$key=$value";
	}
}

echo"<script>
setTimeout(function(){
window.location.href = ('/confirmation.php?$get_fields');
}, 1000);
</script>";
echo $transition_message;
?>