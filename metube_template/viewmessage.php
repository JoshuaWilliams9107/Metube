<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();

include_once "function.php";
include_once "logincheck.php";
parse_str($_SERVER['QUERY_STRING'], $query_string);
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
	  <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
	  <li><a id="floatleft" class="active" href='./inbox.php'>Inbox</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>

		<div style="margin-left:200px;margin-right:200px;padding-top:20px;">
				<?php 
				$messageResult = mysql_query("SELECT * FROM message WHERE messageid=".$query_string['messageid']."");
				$messageRow = mysql_fetch_row($messageResult);
				?>
				
					<span style="display:inline-block;padding: 10px;margin:3px;">

					<span style="background-color: white;display:inline-block;padding: 3px;margin:3px; border: 1px solid black;">Sender: <?php echo $messageRow[1] ?></span>
					<br>
					<span style="background-color: white;display:inline-block;padding: 3px;margin:3px; border: 1px solid black;">Recipient: <?php echo $messageRow[2] ?></span>
					<br>

					<span style="background-color: white;display:inline-block;padding: 3px;margin:3px; border: 1px solid black;">Subject: <?php echo $messageRow[4] ?></span>
				</span>
   					<pre style="margin:0px;border: 1px solid black; background-color: white;"><span style="font-size:16px;font-family:Arial;"><?php echo $messageRow[3] ?></span></pre>
   					<br>
   					</span>
				
			
		</div>

</body>
<?php
  if(isset($error_message))
   {  echo "<div id='passwd_result'>".$error_message."</div>";}
?>