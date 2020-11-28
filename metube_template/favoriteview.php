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
	  <li><a id="floatleft" href="./media.php">Browse Media</a></li>
      <li><a id="floatleft" href='media_upload.php'>Upload Media</a></li>
	  <li><a id="floatleft" class="active" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
	  <li><a id="floatleft" href='./inbox.php'>Inbox</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>

	<?php 
		$check = mysql_query("SELECT playlist_id FROM favorite_table WHERE username=".$_SESSION['username']."");
        $result = mysql_query($check) or die("Selected from favorite_table" .mysql_error());
        $data = mysql_fetch_array($result, MYSQL_NUM);
        if($data[0] < 1){
            echo "No playlist favorited";
        }
        else
        {
	?>
	<center style="padding-top:20px;">
        <?php $playlistinfo = mysql_fetch_row(mysql_query("SELECT * FROM playlist WHERE playlistid = '$result'")); ?>
		<p>Playlist Name: <?php echo $playlistinfo[1]?></p>
	</center>


	<!-- Playlist View Code -->
	<center>
			<table style="width:70%">
			<tr>
			<?php
		    
			$rowSize=3;
			$playlistquery = mysql_query("SELECT * FROM playlist_to_media WHERE playlistid='$result'");
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

				if($result_row = mysql_fetch_row($result)){
					?>
					<?php
					 if($i % $rowSize == 0){ 
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
						</center>
						</td>
					<?php
				}else{
					break;
				}
			}
			?>
		    </tr>
		    </table>
		</center>
        <?php        
        }
        ?>
</body>
