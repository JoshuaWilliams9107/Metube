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
	<?php 
		$playlistinfo = mysql_fetch_row(mysql_query("SELECT * FROM playlist WHERE playlistid=".$_GET['playlistid'].""));
	?>
	<center style="padding-top:20px;">
		<p>Playlist Name: <?php echo $playlistinfo[1]?></p>
	</center>


	<?php
	if(isset($error_message))
	{  echo "<div id='passwd_result'>".$error_message."</div>";}
	?>

	<!-- Playlist View Code -->
	<center>
			<table style="width:70%">
			<tr>
			<?php
		    
			$rowSize=3;
			$playlistquery = mysql_query("SELECT * FROM playlist_to_media WHERE playlistid='".$_GET['playlistid']."';");
			if(mysql_num_rows($playlistquery) == 0){
				echo "<p style='font-size:20px;'>This playlist has no media</p>";
			}

			for($i=0; $i<mysql_num_rows($playlistquery);$i++){
				$playlistInfo = mysql_fetch_row($playlistquery);
				$query = "SELECT * from media WHERE mediaid=".$playlistInfo[2].";"; 
				$result = mysql_query( $query );
				if (!$result){
			   		die ("Could not query the media table in the database: <br />". mysql_error());
				}
				?>
					
				<?php
				$rowSize = 3;
				if($result_row = mysql_fetch_row($result)){
					?>
					<?php
					 if($i % $rowSize == 0 && $i != 0){ 
						?>
						</tr>
						<tr>
					<?php } ?>
					<td>
						<center>
						<a href="./media.php?id=<?php echo $result_row[0];?>">

						<?php if(strpos($result_row[3],'image') !== false){?>
							<img src="<?php echo $result_row[2].$result_row[1];?>"
						 alt="thumbnail" width=250px height=150px/> <br>
						<?php }else if(!is_null($result_row[9])){?>
						<img src="<?php echo $result_row[2]."thumbnail/".$result_row[9]?>"
						 alt="thumbnail" width=250px height=150px/> <br>

						<?php }else{?>
						<img src="uploads/metube/BlankVideo.png"
						 alt="blank user image" width=250px height=150px/> <br>

						<?php
						}
						 echo "<p>".$result_row[4]."</p>";?>
						 </a>
						<form method="post" action="<?php echo "playlistviewbackend.php?playlistid=".$_GET['playlistid']."";?>">
							<input type="hidden" name="videoid" value="<?php echo $result_row[0];?>">
							<input type="submit" value="Remove from playlist" name="removeMedia"/>
						</form>

						</form>
						</center>
						</td>
					<?php
				}
			}
			?>
		    </tr>
		    </table>
		</center>
		</div>

</body>