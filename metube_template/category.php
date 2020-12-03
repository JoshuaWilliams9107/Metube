<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();

include_once "function.php";

$categoryquery = mysql_query("SELECT * FROM media WHERE category = '".$_GET['category']."';");

?>
<body style="padding:0;margin:0;">
    <ul>
        <li><a id="floatleft" href="./home.php">Home</a></li>
        <li><a id="floatleft" href="./media_upload.php">Upload Media</a></li>
        <li><a id="floatleft" href="./favoriteview.php">Favorite Playlist</a></li>
        <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
        <li><a id="floatleft" href="./inbox.php">Inbox</a></li>
	    <form action="<?php echo "home.php";?>" method="post">
   			<button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
	    </form>
    </ul>
    
    <div class="result">
    <center>
    <div>
        <form action="search.php" method="get">
        <label>
            Search
            <input type="text" name="keywords" autocomplete="off" value="<?php if(isset($_GET['keywords']))echo $_GET['keywords'];?>">
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

    if(mysql_num_rows($categoryquery) == 0){
        echo "<p style='font-size:20px;'>No videos for this category</p>";
    }
    for($i=0; $i<mysql_num_rows($categoryquery);$i++){
        if($categoryinfo = mysql_fetch_row($categoryquery)){
            if($i % $rowSize == 0){
            ?>
                </tr>
                <tr>
            <?php } ?>
            <td>
                <center>
                <a href="./media.php?id=<?php echo $categoryinfo[0];?>">
                
                <?php if(strpos($categoryinfo[3], 'image') !== false){?>
                    <img src="<?php echo $categoryinfo[2].$categoryinfo[1];?>"
                alt="thumbnail" width=250px height=150px/> <br>
                <?php } else if(!is_null($categoryinfo[9])){?>
                <img src="<?php echo $categoryinfo[2]."thumbnail/".$categoryinfo[9]?>"
                alt="thumbnail" width=250px height=150px/><br>

                <?php }else{?>
                <img src="uploads/metube/BlankVideo.png"
                alt="blank user image" width=250px height=150px/><br>

                <?php
            }
                echo "<p>".$categoryinfo[4]."</p>";?>
                </a>
                </center>
                </td>
            <?php
        }
        else{
            break;    
        }
        
    }
    ?>
    </tr>
    </table>
    </center>
    </div>
</body>
