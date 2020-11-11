<link rel="stylesheet" type="text/css" href="css/default.css" />
<?php
session_start();

include_once "function.php";

if(isset($_POST['submit'])) {
		if($_POST['username'] == "" || $_POST['password'] == "") {
			$login_error = "One or more fields are missing.";
		}
		else {
			$check = user_pass_check($_POST['username'],$_POST['password']); // Call functions from function.php
			if($check == 1) {
				$login_error = "User ".$_POST['username']." not found.";
			}
			elseif($check==2) {
				$login_error = "Incorrect password.";
			}
			else if($check==0){
				$_SESSION['username']=$_POST['username']; //Set the $_SESSION['username']
				header('Location: home.php');
			}		
		}
}


 
?>
	<center style="padding-top: 150px">
	<p>Welcome to Metube, Plase login</p>
	<p>Don't have an account? <a href="/signup.php">Signup</a></p>
	<form method="post" action="<?php echo "index.php"; ?>">
		<input class="text"  type="text" name="username" placeholder="Username"><br>
		<input class="text"  type="password" name="password" placeHolder="Password"><br>
		<input name="submit" type="submit" value="Login"><br>
		<input name="reset" type="reset" value="Reset"><br>
	</form>
	</center>

<?php
  if(isset($login_error))
   {  echo "<div id='passwd_result'>".$login_error."</div>";}
?>
