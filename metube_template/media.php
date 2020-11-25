<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media</title>
<script src="Scripts/AC_ActiveX.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
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
		echo "Viewing Picture:";
		echo $result_row[2].$result_row[1];
		echo "<img src='".$filepath.$filename."'/>";
	}
	else //view movie
	{	
?>
	<p>Viewing Video:<?php echo $result_row[2].$result_row[1];?></p>

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
echo '<video width="854" height="480" controls>';
echo '<source src= "'.$movie1.'" type="video/mp4">';
echo "Your browser does not support the video tag.";
echo '</video>';
echo '</div>';
?>


<br>

<?php 
$title = "SELECT `title` FROM `media` WHERE filename = '" .$result_row[1]. "'";
$result = mysql_query($title);
$vid_title = mysql_fetch_assoc($result);
?>
<h4>Title:<?php echo " " $vid_title['title'];  ?></h4>

<?php
$description = "SELECT `description` FROM `media` WHERE filename ='" .$result_row[1]. "'";
$result = mysql_query($description);
$vid_desc = mysql_fetch_assoc($result);
?>
<p><b>Description</b>:<?php echo $vid_desc['description']; ?></p>    

<form method="post" action="comment.php">
    <label for="comment">Comment:</label><br>
    <textarea type="text" rows="6" cols="50" id="comment" name="comment" placeholder="Enter comment here..."></textarea>
    <input value="comment" name="submit" type="submit"/>
</form>

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
