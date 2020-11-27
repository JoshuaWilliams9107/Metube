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
if(isset($_POST['message'])) {
	//do some verification
	$contactCheck = mysql_query("SELECT * FROM contacts WHERE (sender='".$_SESSION['username']."' AND recipient='".$_POST['recipient']."') OR (sender='".$_POST['recipient']."' AND recipient='".$_SESSION['username']."');");
	if($_POST['subject'] == ""){
		$error_message = "Message Subject cannot be blank.";
	}else if($_POST['message'] == ""){
		$error_message = "Message Text cannot be blank.";
	}else if(mysql_num_rows($contactCheck) == 0){
		$error_message = "You cannot send messages to those who are not your contact.";
	}else if(mysql_fetch_row($contactCheck)[3] == 0){
		$error_message = "The recipient has not yet accepted your friend request.";
	}else{
		//send the message
		mysql_query("INSERT INTO message (sender,recipient,message,subject) VALUES ('".$_SESSION['username']."','".$_POST['recipient']."','".$_POST['message']."','".$_POST['subject']."')");
		$error_message = "Message Sent.";
	}
}
?>
<body style="padding:0;margin:0;">
	<ul>
	  <li><a id="floatleft" href="./home.php">Home</a></li>
	  <li><a id="floatleft" href="./media.php">Browse Media</a></li>
      <li><a id="floatleft" href='media_upload.php'>Upload Media</a></li>
	  <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
	  <li><a id="floatleft" class="active" href='./inbox.php'>Inbox</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>

		<div style="margin-left:200px;margin-right:200px;padding-top:20px;background-color:#E1E1E1;">
			
				<center>
				<form action="<?php echo "sendmessage.php";?>" method="post">
					<input name="recipient" placeholder="recipient username"/>
					<br>
					<input name="subject" placeholder="message subject"/>
					<br>
   					<textarea name="message" rows="10" cols="60" placeholder="message content here"></textarea>
   					<br>
   					<button id="pageControl" type="submit" name="sendmessage" value="true" class="btn-link">Send Message</button>
				</form>
				</center>
			
		</div>

</body>
<?php
  if(isset($error_message))
   {  echo "<div id='passwd_result'>".$error_message."</div>";}
?>