<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();

include_once "function.php";
include_once "logincheck.php";
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
	  <?php if($_SESSION['username'] != ""){?>
      <li><a id="floatleft" href='media_upload.php'>Upload Media</a></li>
	  <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
	  <li><a id="floatleft" href='./inbox.php'>Inbox</a></li>
	  <?php }?>
	  <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	  </form>
	</ul>
	<center>
    <div>
	<form action="search.php" method="get">
		<label>
			Search
			<input type="text" name="keywords" autocomplete="off">
		</label>
		<input type="submit" value="Search">
	</form>
    <style>
    select:required:invalid {color: grey;}
    option[value=""][disabled]{ display: none;}
    option{ color: black; }
    </style>
    <form action "category.php" method="get">
        <select required id="category" name="category">
            <option value="" disabled selected hidden>Categories: </option>
            <option value="Sports" selected>Sports</option>
            <option value="Education">Education</option>
            <option value="Video Games">Video Games</option>
            <option value="Vlogs">Vlogs</option>
            <option value="Podcasts">Podcasts</option>
            <option value="Entertainment">Entertainment</option>
            <option value="Others">Others</option>
        </select>
    <input value="Go" name="submit" type="submit"/>
    </form>
    </div>
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
				<a href="./media.php?id=<?php echo $result_row[0];?>">

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
				 echo "<p>".$result_row[4]."</p>";?>
				 </a>
				 <!--Get Uploader Information here-->
				 <?php
				 $uploaderquery = mysql_query("SELECT * FROM upload WHERE mediaid=".$result_row[0].";");
				 $uploaderinformation = mysql_fetch_row($uploaderquery);
				 echo "<a style='font-size:10px;' href='/channel.php?username=".$uploaderinformation[1]."'>".$uploaderinformation[1]."</p>";
				 ?>
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
