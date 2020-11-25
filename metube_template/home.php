<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();

include_once "function.php";
parse_str($_SERVER['QUERY_STRING'], $query_string);
if(!isset($query_string['page'])){
	header('Location: home.php?page=0');
}else{
	$pageNumber=$query_string['page'];
} 
$totalpageSize = 9;//Should be divisble by rowSize
$rowSize = 3;
if(isset($_POST['logout'])) {
	$_SESSION['username'] = "";
	header('Location: index.php');
}
if(isset($_POST['nextpage'])) {
	//Doesnt' go to next page if there are no results
	parse_str($_SERVER['QUERY_STRING'], $query_string);
	$pageNumber = $query_string['page']+1;
	$query = "SELECT * from media LIMIT ".$pageNumber*$totalpageSize.",".(($pageNumber*$totalpageSize)+$totalpageSize).";"; 
	$result = mysql_query( $query );
	if(mysql_num_rows($result) > 0){
		header("Location: home.php?page=".$pageNumber."");
	}else{
		unset($_POST['nextpage']);
	}
}
if(isset($_POST['lastpage'])) {
	
	parse_str($_SERVER['QUERY_STRING'], $query_string);
	if($query_string['page'] > 0){
		$pageNumber = $query_string['page']-1;
		header("Location: home.php?page=".$pageNumber."");
	}else{
		unset($_POST['lastpage']);
	}
}

?>
<body style="padding:0;margin:0;">
	<ul>
	  <li><a id="floatleft" class="active" href="./home.php">Home</a></li>
	  <li><a id="floatleft" href="./media.php">Browse Media</a></li>
      <li><a id="floatleft" href='media_upload.php'>Upload Media</a>
	  <li><a id="floatleft" href="./profile.php">My Channel</a></li>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>
	<center>
	<form action="search.php" method="get">
		<label>
			Search
			<input type="text" name="keywords" autocomplete="off">
		</label>
		<input type="submit" value="Search">
	</form>
	<table style="width:70%">
		<tr>
	<?php
    $query = "SELECT * from media LIMIT ".$pageNumber*$totalpageSize.",".(($pageNumber*$totalpageSize)+$totalpageSize).";"; 
	$result = mysql_query( $query );
	
	if (!$result){
	   die ("Could not query the media table in the database: <br />". mysql_error());
	}
	
	for($i=0; $i<$totalpageSize;$i++){
		?>
			
		<?php

		if($result_row = mysql_fetch_row($result)){
			?>
			<?php
			 if($i % $rowSize == 0){ 
				?>
				</tr>
				<tr>
			<?php } ?>
			<td>
				<center>
				<a href="/media.php?id=<?php echo $result_row[0];?>">

				<?php if(strpos($result_row[3],'image') !== false){?>
					<img src="<?php echo $result_row[2].$result_row[1];?>"
				 alt="thumbnail" width=250px height=150px/> <br>
				<?php }else if(!is_null($result_row[9])){?>
				<img src="<?php echo $result_row[2]."thumbnail/".$result_row[9]?>"
				 alt="thumbnail" width=250px height=150px/> <br>

				<?php }else{?>
				<img src="uploads/metube/BlankVideo.png"
				 alt="blank user image" width=250px height=150px/> <br>

				<?php
				}
				 echo $result_row[4];?>
			    </a>
				</center>
				</td>
			<?php
		}else{
			break;
		}
	}
	?>
    </tr>
    </table>
    <br>
    <div style="display:inline-block;">
    	<form action="<?php echo "home.php";?>" method="post">
   			<button id="pageControl" type="submit" name="lastpage" value="true" class="btn-link">Previous Page</button>
   			<label><?php echo $pageNumber+1; ?></label>
   			<button id="pageControl" type="submit" name="nextpage" value="true" class="btn-link">Next Page</button>
		</form>
   			
	</div>
	</center>
</body>
