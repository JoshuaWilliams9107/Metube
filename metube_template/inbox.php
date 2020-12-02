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
if(isset($_POST['deleteMessage'])){
	mysql_query("DELETE FROM message WHERE messageid=".$_POST['messageid'].";");
	unset($_POST['deleteMessage']);
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

		<div style="margin-left:200px;margin-right:200px;padding-top:20px;background-color:#E1E1E1;">

				<form action="./sendmessage.php">
   					<button id="pageControl" type="submit" class="btn-link">Compose New Message</button>
				</form>

				<table style="width:100%">
					<tr>
						<th style="border: 1px solid black;">Sender</th>
						<th style="border: 1px solid black;">Recipient</th>
						<th style="border: 1px solid black;">Subject</th>
						<th style="border: 1px solid black;">Time Sent</th>
						<th style="border: 1px solid black;">Delete</th>
					</tr>
				<?php
				$messageResult = mysql_query("SELECT * FROM message WHERE sender='".$_SESSION['username']."' OR recipient='".$_SESSION['username']."'");
				for($i=0;$i<mysql_num_rows($messageResult);$i++){
					$messageRow = mysql_fetch_row($messageResult); ?>
					
					<tr>
						<td style="border: 1px solid black;"><?php echo $messageRow[1] ?></td>
						<td style="border: 1px solid black;"><?php echo $messageRow[2] ?></td>
						<td style="border: 1px solid black;"><a href="/viewmessage.php?messageid=<?php echo $messageRow[0]?>"><?php echo $messageRow[4] ?></a></td>
						<td style="border: 1px solid black;"><?php echo $messageRow[5] ?></td>
						<td style="border: 1px solid black;">
							<form style="padding:0px; margin:0px;" method="POST" action="./inbox.php">
								<input type="hidden" name="messageid" value="<?php echo $messageRow[0]?>"/>
								<input type="submit" value="Delete Message" name="deleteMessage"/>
							</form>


						</td>
					</tr>

				<?php }?>
				</table>
				

		</div>

</body>
