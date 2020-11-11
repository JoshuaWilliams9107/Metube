<link rel="stylesheet" type="text/css" href="css/default.css" />
<?php
session_start();

include_once "function.php";

if(isset($_POST['submit'])) {

		 if($_POST['username'] == "" || $_POST['password'] == "" || $_POST['passwordC'] == "" || $_POST['email'] == "") {
		 	$signup_error = "One or more fields are missing.";
		 }else if($_POST['password'] != $_POST['passwordC']){
		 	$signup_error = "Password and confirm password do not match.";
		 }else {
		 	$check = addUserToDatabase($_POST['username'],$_POST['password'],$_POST['email']); // Call functions from function.php
		 	if($check == 1) {
		 		$signup_error = "Username already exists in database";
		 	}
		 	elseif($check==0) {
		 		header('Location: index.php');
		 	}	
		 }
}


 
?>
	<center style="padding-top: 150px">
	<p>Please create an account</p>
	<p>Already have an account? <a href="/">Sign in</a></p>
	<form method="post" action="<?php echo "signup.php"; ?>">
		<input class="text"  type="text" name="username" placeholder="Username"><br>
		<input class="text"  type="text" name="email" placeholder="Email Address"><br>
		<input class="text"  type="password" name="password" placeHolder="Password"><br>
		<input class="text"  type="password" name="passwordC" placeHolder="Confirm Password"><br>
		<input name="submit" type="submit" value="Sign Up"><br>
		<input name="reset" type="reset" value="Reset"><br>
	</form>
	</center>

<?php
  if(isset($signup_error))
   {  echo "<div id='passwd_result'>".$signup_error."</div>";}
?>
