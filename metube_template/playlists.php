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
parse_str($_SERVER['QUERY_STRING'], $query_string);
$userID=$_SESSION['username'];
$favorited = false;
if(isset($_GET['playlist'])){
$playlist=$_GET['playlist'];
$query = "SELECT playlistid FROM  playlist WHERE playlistname = '".$playlist."'";
$play = mysql_query($query);
$play_A = mysql_fetch_assoc($play);
$play_id = $play_A['playlistid'];

if(isset($_GET['playlist']) && !empty($_GET['playlist'])){    
    $check = "SELECT * FROM favorite_table WHERE username = '$userID'";
    $result = mysql_query($check) or die("Selected from favorite_table" .mysql_error());
    $data = mysql_fetch_array($result, MYSQL_NUM);
    if($data[0] >= 1){
        $insertUFR = "UPDATE favorite_table SET playlist_id='$play_id' WHERE username='$userID'";
        $queryresult = mysql_query($insertUFR) 
            or die("Update into media_to_favorites in favorite.php" .mysql_error());
        }
    else{    
        $insertF = "INSERT into favorite_table(username, playlist_id) VALUES('$userID', '$play_id')";
        $queryresult = mysql_query($insertF)
            or die("Insert into favorite_table in favorite.php" .mysql_error());
        }
    $favorited = true;
    }
}

?>
<body style="padding:0;margin:0;">
	<ul>
	  <li><a id="floatleft" href="./home.php">Home</a></li>
	  
      <li><a id="floatleft" href='media_upload.php'>Upload Media</a></li>
      <li><a id="floatleft" href='./favoriteview.php'>Favorite playlist</a></li>
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
				$user = mysql_query("SELECT * FROM account WHERE username='".$_GET['username']."'");
				$userRow = mysql_fetch_row($user);
				echo "<p style='font-size:40px;font-weight:bold;float:left;vertical-align:top;'>$userRow[0]</p>";
				echo "<p style=''>$userRow[4] $userRow[5]</p><br>";
				?>
			</div>
		</div>
		<div style="margin-left:200px;margin-right:200px;padding-top:70px;background-color:#E1E1E1;">
			<ul>
			<?php if($_SESSION['username'] == $query_string['username']){?>
				<ul>
				  <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Uploads</a></li>
				  <li><a id="floatleft" href="./contacts.php">Contacts</a></li>
				  <li><a id="floatleft" class="active" href="./playlists.php?username=<?php echo $query_string['username']?>">Playlists</a></li>
				  <li><a id="floatleft" href="./profilesettings.php">Profile Settings</a></li>
				</ul>
			<?php }else{ ?>
				<ul>
					<li>
						<a id="floatleft" href="./channel.php?username=<?php echo $query_string['username']?>">
						Uploaded Media</a>
						<a id="floatleft"  class="active" href="./playlists.php?username=<?php echo $query_string['username']?>">
						Playlists</a>
					</li>
				</ul>
			<?php } ?>
			</ul>

			<center style="padding-top:100px;">
			<?php if($_SESSION['username'] == $query_string['username']){?>
				<form method="post" action="./playlists.php?username=<?php echo $query_string['username']?>">
					<input type="text" name="playlistname" placeholder="Playlist Name"/>
					<input type="submit" value="Create Playlist" name="createPlaylist"/>
				</form>
			<?php }?>
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
        if($favorited == true)
            echo "Updated favorite list!";
		for($i = 0; $i < count($playlists); $i++){?>
            <div style="overflow: hidden;">
			<a href="./playlistview.php?playlistid=<?php echo $playlists[$i][0];?>&username=<?php echo $_GET['username']?>"><?php echo $playlists[$i][1] ?></a>
			<?php if($_SESSION['username'] == $query_string['username']){?>
            <form action="./playlists.php" method="get" id="favorite">
                <input type="hidden" id="playlist" name="playlist" value="<?php echo $playlists[$i][1]; ?>">
                <input type="hidden" id="username" name="username" value="<?php echo $query_string['username'];?>">
                <input type="submit" value="Favorite">
            </form>
        	<?php }?>
            </div>
            <br>
		<?php }
	}
	?>
	</center>
		</div>

</body>
