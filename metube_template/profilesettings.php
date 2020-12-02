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
if(isset($_POST['changeusername'])) {
	if($_POST['username'] != ""){
		$query = "select * from account where username='".$_POST['username']."'";
		$result = mysql_query( $query );
		if(mysql_num_rows($result) != 0){
			$signup_error = "Username is already taken";
			unset($_POST['changeusername']);
		}else{
			mysql_query("UPDATE account SET username='".$_POST['username']."' WHERE username='".$_SESSION['username']."';");
			$_SESSION['username'] = $_POST['username'];
			unset($_POST['changeusername']);
			header('Location: profilesettings.php');
		}
	}else{
		$signup_error = "Username Cannot be blank";
		unset($_POST['changeusername']);
	}
}
if(isset($_POST['changepassword'])) {
	if($_POST['password'] != $_POST['passwordC']){
		$signup_error = "Password and confirm password do not match.";
		unset($_POST['changepassword']);
	}else if($_POST['password'] == ""){
		$signup_error = "Password cannot be blank.";
	}else{
		mysql_query("UPDATE account SET password='".$_POST['password']."' WHERE username='".$_SESSION['username']."';");
		unset($_POST['changepassword']);
		header('Location: profilesettings.php');
	}
}
if(isset($_POST['changefirstname'])) {
	if($_POST['firstname'] != ""){
		mysql_query("UPDATE account SET firstName='".$_POST['firstname']."' WHERE username='".$_SESSION['username']."';");
	}else{
		$signup_error = "First name cannot be blank";
	}
	unset($_POST['changefirstname']);
	header('Location: profilesettings.php');
}
if(isset($_POST['changelastname'])) {
	if($_POST['lastname'] != ""){
		mysql_query("UPDATE account SET lastName='".$_POST['lastname']."' WHERE username='".$_SESSION['username']."';");
	}else{
		$signup_error = "Last name cannot be blank";
	}
	unset($_POST['changelastname']);
	header('Location: profilesettings.php');
}
if(isset($_POST['changemail'])) {
	if($_POST['email'] != ""){
		mysql_query("UPDATE account SET email='".$_POST['email']."' WHERE username='".$_SESSION['username']."';");
	}else{
		$signup_error = "Email cannot be blank";
	}
	unset($_POST['changemail']);
	header('Location: profilesettings.php');
}
$accountResult = mysql_query("SELECT * FROM account WHERE username='".$_SESSION['username']."';");
$accountInfo = mysql_fetch_row($accountResult);
$firstName = $accountInfo[4];
$lastName = $accountInfo[5];
$email = $accountInfo[2];
$username = $accountInfo[0];
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
			  <li><a id="floatleft" href="./playlists.php?username=<?php echo $_SESSION['username']?>">Playlists</a></li>
			  <li><a id="floatleft" class="active" href="./profilesettings.php">Profile Settings</a></li>
			</ul>
			<!--Contacts Code-->
			<center style="padding-top:100px;">
				<form method="post" action="<?php echo "profilesettings.php"; ?>">
					<label for="username">Username: </label>
					<input type="text" name="username" placeholder="username" value="<?php echo $username ?>"/>
					<input type="submit" value="Update Username" name="changeusername"/>
				</form>
				<form method="post" action="<?php echo "profilesettings.php"; ?>">
					<label for="username">First Name: </label>
					<input type="text" name="firstname" placeholder="firstname" value="<?php echo $firstName ?>"/>
					<input type="submit" value="Update First Name" name="changefirstname"/>
				</form>
				<form method="post" action="<?php echo "profilesettings.php"; ?>">
					<label for="username">Last Name: </label>
					<input type="text" name="lastname" placeholder="lastname" value="<?php echo $lastName ?>"/>
					<input type="submit" value="Update Last Name" name="changelastname"/>
				</form>
				<form method="post" action="<?php echo "profilesettings.php"; ?>">
					<label for="username">Email: </label>
					<input type="email" name="email" placeholder="email" value="<?php echo $email ?>"/>
					<input type="submit" value="Update Email" name="changemail"/>
				</form>
				<form method="post" action="<?php echo "profilesettings.php"; ?>">
					<input class="text"  type="password" name="password" placeHolder="Password"><br>
					<input class="text"  type="password" name="passwordC" placeHolder="Confirm Password"><br>
					<input type="submit" value="Update Password" name="changepassword"/>
				</form>
			</center>
		</div>

</body>
<?php
  if(isset($signup_error))
   {  echo "<div id='passwd_result'>".$signup_error."</div>";}
?>