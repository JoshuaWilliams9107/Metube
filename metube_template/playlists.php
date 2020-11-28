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
	  			die ("createPlaylist failed. Could not query the database: <br />". mysql_error());
			}
			$error_message="Playlist created.";
		}
	}	
}

if(isset($_POST['friendDecision'])) {
	if($_POST['friendDecision'] == "Accept"){
		$result = mysql_query("UPDATE contacts SET status=1 WHERE sender='".$_POST['sender']."' AND recipient='".$_SESSION['username']."'");
	} else {
		$result = mysql_query("DELETE FROM contacts WHERE sender='".$_POST['sender']."' AND recipient='".$_SESSION['username']."'");
	}
	
}

?>
<body style="padding:0;margin:0;">
	<ul>
	  <li><a id="floatleft" href="./home.php">Home</a></li>
	  <li><a id="floatleft" href="./media.php">Browse Media</a></li>
      <li><a id="floatleft" href='media_upload.php'>Upload Media</a></li>
	  <li><a id="floatleft" class="active" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
	  <li><a id="floatleft" href='./inbox.php'>Inbox</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>

		<div style="margin-left:200px;margin-right:200px;padding-top:50px;background-color:#E1E1E1;">
			<img style="float:left;margin-left:100px;border: 5px solid black;"src="uploads/metube/blank.png" alt="blank user image" width=200px height=200px/> 
			<div style="display:inline-block;margin-left:20px;">
				<?php
				$user = mysql_query("SELECT * FROM account WHERE username='".$_SESSION['username']."'");
				$userRow = mysql_fetch_row($user);
				echo "<p style='font-size:40px;font-weight:bold;float:left;vertical-align:top;'>$userRow[0]</p>";
				echo "<p style=''>$userRow[4] $userRow[5]</p><br>";
				?>
			</div>
		</div>
		<div style="margin-left:200px;margin-right:200px;padding-top:70px;background-color:#E1E1E1;">
			<ul>
				  <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Uploads</a></li>
				  <li><a id="floatleft" href="./contacts.php">Contacts</a></li>
				  <li><a id="floatleft" class="active" href="./playlists.php">Playlists</a></li>
				  <li><a id="floatleft" href="./profilesettings.php">Profile Settings</a></li>
				</ul>
			<!--Contacts Code-->
			<center style="padding-top:100px;">
		<form method="post" action="<?php echo "playlists.php"; ?>">
			<input type="text" name="playlistname" placeholder="Playlist Name"/>
			<input type="submit" value="Create Playlist" name="createPlaylist"/>
		</form>
	</center>


	<?php
	if(isset($error_message))
	{  echo "<div id='passwd_result'>".$error_message."</div>";}
	?>
	<center style="padding-top:10px;">
	<p>Playlists</p>
	<?php
	$playlists = getPlaylists();
	if($playlists){
		for($i = 0; $i < count($playlists); $i++){?>
			<a href="/playlistview.php?playlistid=<?php echo $playlists[$i][0];?>"><?php echo $playlists[$i][1] ?></a>
            <form action="favorites.php" method="get" id="favorite">
                <input type="hidden" id="playlist" name="playlist" value="<?php echo $playlists[$i][1]; ?>">
                <input type="submit" value="Favorite">
            </form>
            <br>
		<?php }
	}
	?>
	</center>
		</div>

</body>
