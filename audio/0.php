<html>
<body>
<?php
$track_list = scandir (__DIR__ , FilesystemIterator::SKIP_DOTS);
echo "<pre>";
	asort($track_list);
	$track_list = array_slice($track_list, 3);
var_dump( $track_list);
echo "<pre>";

	$track = $track_list[2];

echo '
	<audio controls autoplay class="top-widget">
	  <source src="' . $track  . '" type="audio/mpeg">
	  Your browser does not support the audio tag.
	</audio>
	';
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
	echo "<h1 id='$id' style='color:rgb($r,g,$b)'>lol</h1>";

	echo'
	<script>
	//https://html.com/tags/blink/
	var blink_speed = 700; var t = setInterval(function () { var ele = document.getElementById("'. $id .'"); ele.style.visibility = (ele.style.visibility == "hidden" ? "" : "hidden"); }, blink_speed);
	</script>
	';

?>
</body>
</html>