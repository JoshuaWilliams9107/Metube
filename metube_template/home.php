<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();

include_once "function.php";

if(isset($_POST['logout'])) {
	$_SESSION['username'] = "";
	header('Location: index.php');
}


 
?>
<body style="padding:0;margin:0;">
	<form action="search.php" method="get">
		<label>
			Search
			<input type="text" name="keywords" autocomplete="off">
		</label>
		<input type="submit" value="Search">
	</form>
	<ul>
	  <li><a id="floatleft" class="active" href="./home.php">Home</a></li>
	  <li><a id="floatleft" href="./media.php">Upload Media</a></li>
	  <li><a id="floatleft" href="./profile.php">My Channel</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>
</body>
