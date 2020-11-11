<link rel="stylesheet" type="text/css" href="css/default.css" />
<?php
session_start();

include_once "function.php";

if(isset($_POST['logout'])) {
	$_SESSION['username'] = "";
	header('Location: index.php');
}


 
?>
<body style="padding:0;margin:0;">
	<ul>
	  <li><a id="floatleft" class="active" href="/home.php">Home</a></li>
	  <li><a id="floatleft" href="/media.php">Upload Media</a></li>
	  <li><a id="floatleft" href="/profile.php">Profile</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>
</body>