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
	  <li><a id="floatleft" href="./home.php">Home</a></li>
	  <li><a id="floatleft" href="./media.php">Upload Media</a></li>
	  <li><a id="floatleft" class="active" href="./profile.php">My Channel</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>
	<div style="padding-left:300px;padding-top:100px;display:inline-block;float:left;">
		<img src="uploads/metube/blank.png" alt="blank user image" width=200px height=200px/> 
		<div style="display:inline-block;margin-left:20px;float:right;">
			<?php
			$user = mysql_query("SELECT * FROM account WHERE username='".$_SESSION['username']."'");
			$userRow = mysql_fetch_row($user);
			echo "<p style='float:left;vertical-align:top;'>$userRow[4] $userRow[5]</p><br>";
			
			echo "<p style='float:left;vertical-align:top;'>username: $userRow[0]</p>";
			?>
			<form action="./friends.php">
				<input type="submit" value="View Contacts"/>
			</form>
		</div>
	</div>
</body>
