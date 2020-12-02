<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
	include_once "logincheck.php";
	if(isset($_POST['logout'])) {
		$_SESSION['username'] = "";
		header('Location: index.php');
	}
	if(isset($_POST['changeTitle'])) {

		if($_POST['newTile'] != ""){
			mysql_query("UPDATE media SET title='".$_POST['newTile']."' WHERE mediaid=".$_GET['id'].";");
			
		}else{
			$update_error = "Title cannot be blank";
		}
		unset($_POST['changefirstname']);
	}
	if(isset($_POST['changeDescription'])) {
		if($_POST['newDescription'] != ""){
			mysql_query("UPDATE media SET description='".$_POST['newDescription']."' WHERE mediaid=".$_GET['id'].";");
			
		}else{
			$update_error = "Description cannot be blank";
		}
		unset($_POST['changefirstname']);
	}
	if(isset($_POST['deleteVideo'])) {
		
		unset($_POST['deleteVideo']);
	}
	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
</ul>
<?php
if(isset($_GET['descERR']))
	$update_error = "Description cannot be blank";
if(isset($_GET['titleERR']))
	$update_error = "Title cannot be blank";
  if(isset($update_error))
   {  echo "<div id='passwd_result'>".$update_error."</div><br>";}
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
<center>
<a href="<?php echo $result_row[2].$result_row[1];?>" download> Download </a><br>

<form method="post" action="<?php echo "editmediabackend.php?id=".$_GET['id']."";?>">

	<b>Edit Title</b>: 
	<input type="text" name="newTile" value="<?php echo $vid_title['title'];?>">
	<input type="submit" value="Update Title" name="changeTitle"/>
</form>

<br>

<?php 
$playlistResult = mysql_query("SELECT * FROM playlist WHERE username='".$_SESSION['username']."';");
if(mysql_num_rows($playlistResult) != 0){
?>

<?php 
}else{
	?> 
	<p>You must create a playlist in My Channel to add this video to your playlist.</p>
	<?php
}
?>

</p>

<?php
$description = "SELECT `description` FROM `media` WHERE filename ='" .$result_row[1]. "'";
$result = mysql_query($description);
$vid_desc = mysql_fetch_assoc($result);
?>

<form method="post" action="<?php echo "editmediabackend.php?id=".$_GET['id']."";?>">
<p><b>Edit Description:</b><br> <textarea type="text" name="newDescription" rows="4" cols="30"><?php echo $vid_desc['description'];?></textarea></p>

<input type="submit" value="Update Description" name="changeDescription"/>
</form>

<br>
<form method="post" action="<?php echo "editmediabackend.php?id=".$_GET['id']."";?>">
<input type="submit" value="Delete Video" name="deleteVideo"/>
</form>
</center>

          
<?php
	
}
else
{
?>

<?php
}
?>

</body>

</html>
