<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();

include_once "function.php";
include_once "logincheck.php";

if(isset($_POST['logout'])) {
	$_SESSION['username'] = "";
	header('Location: index.php');
}
if(isset($_POST['createPlaylist'])) {
	if($_POST['playlistname'] == ""){
		$error_message="Playlist Name cannot be blank";
	}else{
		$check_result = mysql_query("SELECT * FROM  playlist WHERE playlistname='".$_POST['playlistname']."' AND username='".$_SESSION['username']."';");
		if(mysql_num_rows($check_result) != 0){
			$error_message="You already have a playlist by that name.";
		}else{
			//make the playlist
			$result = mysql_query("INSERT INTO playlist (playlistname,username) VALUES ('".$_POST['playlistname']."','".$_SESSION['username']."')");
			if (!$result)
			{
	  			die ("createPlaylsit failed. Could not query the database: <br />". mysql_error());
			}
			$error_message="Playlist created.";
		}
	}	
}

if(isset($_POST['removeMedia'])) {
	mysql_query("DELETE FROM playlist_to_media WHERE playlistid=".$_GET['playlistid']." AND mediaid=".$_POST['videoid']."");
	unset($_POST['removeMedia']);
	header("Location: playlistview.php?playlistid=".$_GET['playlistid']."");
}

?>