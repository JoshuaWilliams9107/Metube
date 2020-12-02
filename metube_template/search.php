<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();
include_once "function.php";
include_once "logincheck.php";
if(isset($_GET['keywords'])){
    $keywords = mysql_escape_string($_GET['keywords']);
    $keywords = explode(' ', $keywords);
    $media_Arr = array();
   foreach($keywords as $word){ 
        $keyword_query = mysql_query("
            SELECT *
            FROM keyword_table
            WHERE keyword LIKE '{$word[0]}%'
        ");
        $max = -99999;
        $keywordIndex = -9;
        $key_id = -9;
        for($i = 0; $i < mysql_num_rows($keyword_query);$i++){
        	$keywordCompare = mysql_fetch_row($keyword_query);
        	similar_text($keywordCompare[1],$word,$percent);
        	if($percent > $max){
        		$max = $percent;
        		$keywordIndex = $i;
        		$key_id = $keywordCompare;
        	}
        }
        if($keywordIndex >= 0 && $max >= 80 && $key_id >= 0){
	        $key = $key_id;

	        //$check = mysql_fetch_array($key_id, MYSQL_NUM);
	        //$true_key_id = mysql_fetch_assoc($key_id);
	        
	        if($key[0] >= 1){
	        $media_id = mysql_query("
	            SELECT media_id
	            FROM media_to_keywords
	            WHERE keyword_id = '".$key[0]."'");
	        
	        //$media_ids = array();    
	        $true_media_id = fetchAllRows($media_id);
	        foreach($true_media_id as $id){
	 
	            $query = mysql_query("
	            SELECT filename
	            FROM media
	            WHERE mediaid = '".$id[0]."'");
	            ?>
	            <?php
	            $media_Arr[] = mysql_fetch_row($query)[0]; 
	            ?>
	            <?php
	        }
	        }
        }
   }
   
?>
        <body style="padding:0;margin:0">
            <ul>
                <li><a id="floatleft" class="active" href="./home.php">Home</a></li>
                <li><a id="floatleft" href="./media_upload.php">Upload Media</a></li>
                <li><a id="floatleft" href="./favoriteview.php">Favorite playlist</a></li>
	            <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
	            <li><a id="floatleft" href='./inbox.php'>Inbox</a></li>
	            <form action="<?php echo "home.php";?>" method="post">
   			        <button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	            </form>
	        </ul>

            <div class="result">
            <?php
             $media_Arr = array_values(array_unique($media_Arr, SORT_REGULAR));
            ?>
            <center>
                <div>
                <form action="search.php" method="get">
                <label>
                    Search
                    <input type="text" name="keywords" autocomplete="off" value="<?php echo $_GET['keywords'];?>">
                </label>
                <input type="submit" value="Search">
            </form>
            <style>
            select:required:invalid {align-content: center; color: grey;}
            option[value=""][disabled]{ display: none;}
            option{ color: black; }
            </style>
            <form action="category.php" method="get">
                <select required id="category" name="category">
                    <option value="" disabled selected>Categories</option>
                    <option value="Sports">Sports</option>
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
            $rowSize=3;
            for($i = 0; $i < count($media_Arr); $i++){
                $query = "SELECT * FROM media WHERE filename='".$media_Arr[$i]."';";
                $result = mysql_query($query);
                if(!$result){
                    die("could not query the media table in the database: <br />".mysql_error());
                }
            ?>
            <?php
            if($result_row = mysql_fetch_row($result)){
            ?>
            <?php
                if($i % $rowSize ==0){
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
						</center>
						</td>
					<?php
				}else{
					break;
				}
			}
}
			?>
		    </tr>
		    </table>
		</center>
		</div>
</body>
