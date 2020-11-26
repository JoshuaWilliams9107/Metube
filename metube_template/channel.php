<link rel="stylesheet" type="text/css" href="css/default.css" />
<?php
session_start();

include_once "function.php";
include_once "logincheck.php";

if(isset($_POST['logout'])) {
	$_SESSION['username'] = "";
	header('Location: index.php');
}


//Query String Parsing
parse_str($_SERVER['QUERY_STRING'], $query_string);
?>
<body style="padding:0;margin:0;">
	<ul>
	  <li><a id="floatleft" href="./home.php">Home</a></li>
	  <li><a id="floatleft" href="./media.php">Browse Media</a></li>
      <li><a id="floatleft" href='media_upload.php'>Upload Media</a>
	  <li><a id="floatleft" class="active" href="./channel.php">My Channel</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>

		<div style="margin-left:200px;margin-right:200px;padding-top:50px;background-color:#E1E1E1;">
			<img style="float:left;margin-left:100px;border: 5px solid black;"src="uploads/metube/blank.png" alt="blank user image" width=200px height=200px/> 
			<div style="display:inline-block;margin-left:20px;">
				<?php
				$user = mysql_query("SELECT * FROM account WHERE username='".$query_string['username']."'");
				$userRow = mysql_fetch_row($user);
				echo "<p style='font-size:40px;font-weight:bold;float:left;vertical-align:top;'>$userRow[0]</p>";
				echo "<p style=''>$userRow[4] $userRow[5]</p><br>";
				?>
			</div>
		</div>

		<div style="margin-left:200px;margin-right:200px;padding-top:70px;background-color:#E1E1E1;">
			<?php if($_SESSION['username'] == $query_string['username']){?>
				<ul>
				  <li><a id="floatleft" class="active" href="./channel.php">My Uploads</a></li>
				  <li><a id="floatleft" href="./contacts.php">Contacts</a></li>
				  <li><a id="floatleft" href="./profilesettings.php">Profile Settings</a></li>
				</ul>
			<?php }?>
			<!--Video Code-->
			<center>
			<table style="width:70%">
			<tr>
			<?php
		    
			$rowSize=3;
			
			$uploaderquery = mysql_query("SELECT * FROM upload WHERE username='".$query_string['username']."';");
			if(mysql_num_rows($uploaderquery) == 0){
				echo "<p style='font-size:20px;'>This channel has no uploaded videos</p>";
			}
			for($i=0; $i<mysql_num_rows($uploaderquery);$i++){
				$uploaderinformation = mysql_fetch_row($uploaderquery);
				$query = "SELECT * from media WHERE mediaid=".$uploaderinformation[2].";"; 
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
		</div>

</body>
