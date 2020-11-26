<?php
session_start();
include_once "function.php";
include_once "logincheck.php";

/******************************************************
*
* upload media to favorites from user
*
*******************************************************/

$userID=$_SESSION['username'];

$file=$_GET['filename'];
$query = "SELECT mediaid FROM media WHERE filename = '".$file."'";
$media = mysql_query($query);
$Media_A = mysql_fetch_assoc($media);
$mediaID = $Media_A['mediaid'];

    if(isset($_GET['filename']) && !empty($_GET['filename'])){    
        $check = "SELECT * FROM favorite_table WHERE media_id = '$mediaID'";
        $result = mysql_query($check) or die("Selected from keyword_table" .mysql_error());
        $data = mysql_fetch_array($result, MYSQL_NUM);
        if($data[0] > 1){
            $add = "SELECT `favorite_id` FROM `favorite_table` WHERE `media_id` = '$mediaID'";
            $fav_id = mysql_query($add)
                or die("Selecting from favorite table to get favorite_id in favorite.php" .mysql_error());
            $grab = mysql_fetch_assoc($fav_id);
            $insertUFR = "INSERT into media_to_favorites(username, favorite_id) VALUES('$userID', '".$grab['favorite_id']."')";
            $queryresult = mysql_query($insertUFR) 
                or die("Insert into media_to_favorites in favorite.php" .mysql_error());
        }
        else{    
            $insertF = "INSERT into favorite_table(favorite_id, media_id) VALUES(NULL, '$mediaID')";
            $queryresult = mysql_query($insertF)
                or die("Insert into favorite_table in favorite.php" .mysql_error());
            $favoriteID = mysql_insert_id();
            $insertUF = "INSERT into media_to_favorites(user_id, favorite_id) VALUES('$userID', '$favoriteID')";
            $queryresult = mysql_query($insertUF) 
                or die("Insert into media_to_keywords in media_upload_process.php" .mysql_error());
        }
    }
?>
