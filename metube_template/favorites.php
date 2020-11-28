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

$playlist=$_GET['playlist'];

$query = "SELECT playlistid FROM  playlist WHERE playlistname = '".$playlist."'";
$play = mysql_query($query);
$play_A = mysql_fetch_assoc($play);
$play_id = $play_A['playlistid'];

if(isset($_GET['playlist']) && !empty($_GET['playlist'])){    
    $check = "SELECT * FROM favorite_table WHERE username = '$userID'";
    $result = mysql_query($check) or die("Selected from favorite_table" .mysql_error());
    $data = mysql_fetch_array($result, MYSQL_NUM);
    if($data[0] > 1){
        $insertUFR = "UPDATE favorite_table SET playlist_id='".$play_id."' WHERE username='".$userID."')";
        $queryresult = mysql_query($insertUFR) 
            or die("Insert into media_to_favorites in favorite.php" .mysql_error());
        echo "Updated!";
        }
    else{    
        $insertF = "INSERT into favorite_table(username, playlist_id) VALUES('$userID', '$play_id')";
        $queryresult = mysql_query($insertF)
            or die("Insert into favorite_table in favorite.php" .mysql_error());
        }
    }
?>

