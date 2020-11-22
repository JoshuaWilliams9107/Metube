<link rel="stylesheet" type="text/css" href="css/default.css" />
<?php
session_start();

include_once "function.php";

if(isset($_POST['logout'])) {
	$_SESSION['username'] = "";
	header('Location: index.php');
}
if(isset($_POST['addFriend'])) {
	$check = sendFriendRequest($_POST['recipient']);

	if($check == 1){
		$login_error = "Username does not exist.";
		unset($_POST['addFriend']);
	} else if($check == 2){
		$login_error = "Friend Request Already Sent to that user";
		unset($_POST['addFriend']);
	}else if($check == 0){
		$login_error = "Friend Request Sent";
		unset($_POST['addFriend']);
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
	  <li><a id="floatleft" href="/home.php">Home</a></li>
	  <li><a id="floatleft" href="/media.php">Upload Media</a></li>
	  <li><a id="floatleft" class="active" href="/profile.php">My Channel</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>
	<center style="padding-top:100px;">
		<form method="post" action="<?php echo "friends.php"; ?>">
			<input type="text" name="recipient" placeholder="contact's username"/>
			<input type="submit" value="Add Contact" name="addFriend"/>
		</form>
	</center>


	<?php
	if(isset($login_error))
	{  echo "<div id='passwd_result'>".$login_error."</div>";}
	?>
	<center style="padding-top:10px;">
	<p>Contacts<p>
	<?php
	$contacts = getContacts();
	if($contacts){
		for($i = 0; $i < count($contacts); $i++){
			if($contacts[$i][1] == $_SESSION['username']){
				if($contacts[$i][3] == 0){
					echo "<p>".$contacts[$i][2]." Request Pending</p>";
				}else{
					echo "<p>".$contacts[$i][2]."</p>";
				}
				
			}else{
				if($contacts[$i][3] == 0){
					?>
					<p style="display: inline-block;"><?php echo $contacts[$i][1];?></p>
					<form method="post" action="<?php echo "friends.php";?>" style="display: inline-block;padding-left:20px;">
						<input type="hidden" value="<?php echo $contacts[$i][1]?>" name="sender"/>
						<input type="submit" value="Accept" name="friendDecision"/>
						<input type="submit" value="Refuse" name="friendDecision"/>
					</form>
					
					<?php
				}else{
					echo "<p>".$contacts[$i][2]."</p>";
				}
				
			}
		}
	}
	?>
	</center>
</body>