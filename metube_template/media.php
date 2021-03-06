<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
	

	if(isset($_POST['addtoplaylist'])) {
		//add media to database
		$checkPlaylist = mysql_query("SELECT * FROM playlist_to_media WHERE playlistid='".$_POST['playlistname']."' AND mediaid='".$_GET['id']."';");
		if(mysql_num_rows($checkPlaylist) != 0){
			$error_message = "This media is already in that playlist";
		}else{
			$result = mysql_query("INSERT INTO playlist_to_media (playlistid,mediaid) VALUES ('".$_POST['playlistname']."','".$_GET['id']."');");
			if (!$result)
			{
		  		die ("addToPlaylist failed. Could not query the database: <br />". mysql_error());
			}
			$error_message = "Media added to playlist";
		}
	}
	if(isset($_POST['submitcomment'])) {
		if($_POST['comment'] ==""){
			$error_message = "Comment cannot be blank";
		}else{
			$date = date('Y-m-d H:i:s');
			$insertcomment = "INSERT INTO video_comment (username,media_id,comment,Time_stamp) VALUES ('".$_SESSION['username']."',".$_GET['id'].",'".santitize($_POST['comment'])."','".$date."');";
			$result = mysql_query($insertcomment);
			if (!$result)
			{
	  			die ("Comment failed. Could not query the database: <br />". mysql_error());
			}
			$error_message = "Comment Created";
		}
	}
	if(isset($_POST['download'])){
		$date = date('Y-m-d H:i:s');

		$result = mysql_query("INSERT INTO download (username,mediaid,downloadtime) VALUES ('".$_SESSION['username']."',".$_GET['id'].",'".$date."')");

		$selectresultID = mysql_fetch_row(mysql_query("SELECT * FROM download WHERE username='".$_SESSION['username']."' AND mediaid=".$_GET['id']." AND downloadtime='".$date."';"));
		
		$resultrelational = mysql_query("INSERT INTO download_to_media (downloadid,media_id) VALUES (".$selectresultID[0].",".$_GET['id'].")");
		if (!$result || !$resultrelational)
			{
	  			die ("Download failed. Could not query the database: <br />". mysql_error());
			}
		header("Content-type: ".$_POST['fileType']."");
		header('Content-Disposition: attachment; filename= '.$_POST['fileName'].'');
		//clearing the output buffer to prevent file corruption
		while (ob_get_level()) {
    		ob_end_clean();
		}
		readfile($_POST['fileURL']);
	}

?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<center>
<title>Media</title>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
<ul>
	  <li><a id="floatleft" href="./home.php">Home</a></li>
	  
	  <?php if($_SESSION['username'] != ""){?>
      <li><a id="floatleft" href='media_upload.php'>Upload Media</a></li>
      <li><a id="floatleft" href='./favoriteview.php'>Favorite playlist</a></li>
	  <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
	  <li><a id="floatleft" href='./inbox.php'>Inbox</a></li>
	  <?php }?>
	  <form action="<?php echo "home.php";?>" method="post">
   			<?php if($_SESSION['username'] != ""){?>
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
   		<?php }else{?>
			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Login</button>
   <?php 	}?>
	  </form>
</ul>
<?php
	if(isset($error_message))
	{  echo "<div id='passwd_result'>".$error_message."</div>";}
	?>
<?php
if(isset($_GET['id'])) {
	$query = "SELECT * FROM media WHERE mediaid='".$_GET['id']."'";
	$result = mysql_query( $query );
	$result_row = mysql_fetch_row($result);
	
	updateMediaTime($_GET['id']);
	
	$filename=$result_row[1];
	$filepath=$result_row[2];
	$type=$result_row[3];
	if(substr($type,0,5)=="image") //view image
	{
		echo "<center><br><img src='".$filepath.$filename."'/></center>";
	}else if(substr($type,0,5)=="audio") //view image
	{
		?>
		<audio controls>
			<source src="<?php echo $filepath.$filename?>" type="audio/mpeg">
		Your browser does not support the audio element.
		</audio>
		<?php
	}
	else //view movie
	{	
?>


    <!--

     <video width="440" height="360" controls>
     <source src="<?php echo $result_row[2].$result_row[1]; ?>" type="video/mp4">
     <source src="<?php echo $result_row[2].$result_row[1]; ?>" type="video/webm">
Your browser does not support the video tag.
</video> 
        -->
<?php
$movie1 = $result_row[2].$result_row[1];
echo '<div align="center">';
echo '<video width="960" height="480" controls>';
echo '<source src= "'.$movie1.'" type="video/mp4">';
echo "Your browser does not support the video tag.";
echo '</video>';
echo '</div>';
?>

<?php }?>
<br>

<?php 
$title = "SELECT `title` FROM `media` WHERE filename = '" .$result_row[1]. "'";
$result = mysql_query($title);
$vid_title = mysql_fetch_assoc($result);
?>
<p>
<b>Title</b>: <?php echo $vid_title['title'];  ?>  
<br>
<form method="post" action="<?php echo "media.php?id=".$_GET['id'].""; ?>">
	<input type="hidden" value="<?php echo $result_row[3];?>" name="fileType"/>
	<input type="hidden" value="<?php echo $result_row[2].$result_row[1];?>" name="fileURL"/>
	<input type="hidden" value="<?php echo $result_row[1];?>" name="fileName"/>
	<input type="submit" name="download" value="Download"/>
</form>
<?php 
$timesdownload = mysql_query("SELECT * FROM download WHERE mediaid=".$_GET['id'].";");

$timesdownloadusername = mysql_query("SELECT * FROM download WHERE mediaid=".$_GET['id']." AND username='".$_SESSION['username']."';");
?>
<p>Total Times Downloaded: <?php echo mysql_num_rows($timesdownload)?></p>
<?php if($_SESSION['username'] != ""){ ?>
<p>Total Times <?php echo $_SESSION['username']?> Downloaded: <?php echo mysql_num_rows($timesdownloadusername)?></p>
<?php }else{ ?>

<p>Total Times Not Logged in Users Downloaded: <?php echo mysql_num_rows($timesdownloadusername)?></p>
<?php } ?>
<?php 
$playlistResult = mysql_query("SELECT * FROM playlist WHERE username='".$_SESSION['username']."';");
if(mysql_num_rows($playlistResult) != 0){
?>
<form method="post" action="<?php echo "media.php?id=".$_GET['id'].""; ?>">
	<label for="playlistname">Playlist Name: </label>
	<select name="playlistname">
		<?php 
			$playlistResult = mysql_query("SELECT * FROM playlist WHERE username='".$_SESSION['username']."';");
			if(mysql_num_rows($playlistResult) != 0){
			for($i = 0; $i < mysql_num_rows($playlistResult); $i++){
				$playlistRow = mysql_fetch_row($playlistResult);
		?>
				<option value="<?php echo $playlistRow[0]?>"><?php echo $playlistRow[1]?></option>
		<?php }
		} ?>
	</select>
	
	<input type="submit" value="Add to Playlist" name="addtoplaylist"/>
</form>
<?php 
}else{
	?> 
	<p>You must create a playlist in My Channel to add this video to your playlist.</p>
	<?php
}
?>
<!--
<form action="favorites.php" method="get" id="favorite">
    <input type="hidden" id="filename" name="filename" value ="<?php echo $result_row[1]; ?>">
    <input type="submit" value="Favorite">
</form>
-->
</p>

<?php
$description = "SELECT `description` FROM `media` WHERE filename ='" .$result_row[1]. "'";
$result = mysql_query($description);
$vid_desc = mysql_fetch_assoc($result);
?>
<p><b>Description</b>: <?php echo $vid_desc['description']; ?></p>    
<?php if($_SESSION['username'] != ""){ ?>
<form method="post" action="media.php?id=<?php echo $_GET['id'];?>">
    <label for="comment">Comment:</label><br>
    <textarea type="text" rows="6" cols="50" id="comment" name="comment" placeholder="Enter comment here..."></textarea>
    <input value="submit" name="submitcomment" type="submit"/>
</form>
<?php } ?>
<p>All Comments</p>
<?php 
$commentSelect = mysql_query("SELECT * FROM video_comment WHERE media_id=".$_GET['id']."");
for($i = 0; $i < mysql_num_rows($commentSelect); $i++){
	$commentRow = mysql_fetch_row($commentSelect);
?>
	<span style="background-color: white;display:inline-block;padding: 3px;margin:3px; border: 1px solid black;"><?php echo $commentRow[1] ?></span>
	<span style="background-color: white;display:inline-block;padding: 3px;margin:3px; border: 1px solid black;"><?php echo $commentRow[4] ?></span>
	<pre style="margin:0px;border: 1px solid black; background-color: white;"><span style="font-size:16px;font-family:Arial;"><?php echo $commentRow[3] ?></span></pre>
   	<br>
<?php } ?>
</center>
<!--
    <object id="MediaPlayer" width=320 height=286 classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components…" type="application/x-oleobject" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112">

<param name="filename" value="<?php echo $result_row[2].$result_row[1];  ?>">
<param name="Showcontrols" value="True">
<param name="autoStart" value="True">

<embed type="application/x-mplayer2" src="<?php echo $result_row[2].$result_row[1];  ?>" name="MediaPlayer" width=320 height=240></embed>

</object>
-->
          
<?php
	
}
else
{
?>
<meta http-equiv="refresh" content="0;url=browse.php">
<?php
}
?>

</body>

</html>
