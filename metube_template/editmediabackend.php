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
			header("Location: editmedia.php?id=".$_GET['id']."");
		}else{
			header("Location: editmedia.php?id=".$_GET['id']."&titleERR=true");
		}
		unset($_POST['changefirstname']);
		
	}
	if(isset($_POST['changeDescription'])) {
		if($_POST['newDescription'] != ""){
			mysql_query("UPDATE media SET description='".$_POST['newDescription']."' WHERE mediaid=".$_GET['id'].";");
			header("Location: editmedia.php?id=".$_GET['id']."");
		}else{
			header("Location: editmedia.php?id=".$_GET['id']."&descERR=true");
		}
		unset($_POST['changefirstname']);
		
	}
	if(isset($_POST['deleteVideo'])) {
		unset($_POST['deleteVideo']);
		mysql_query("DELETE FROM media WHERE mediaid=".$_GET['id'].";");
		mysql_query("DELETE FROM upload WHERE mediaid=".$_GET['id'].";");
		mysql_query("DELETE FROM playlist_to_media WHERE mediaid=".$_GET['id'].";");
		mysql_query("DELETE FROM media_to_keywords WHERE media_id=".$_GET['id'].";");
		mysql_query("DELETE FROM video_comment WHERE media_id=".$_GET['id'].";");
		mysql_query("DELETE FROM download WHERE mediaid=".$_GET['id'].";");
		header("Location: channel.php?username=".$_SESSION['username']."&videoDelete=true");
	}
?>	