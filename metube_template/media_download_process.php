<?php
session_start();
include_once "function.php";

/******************************************************
*
* download by username
*
*******************************************************/

$username=$_SESSION['username'];
$mediaid=$_REQUEST['id'];

//insert into upload table
$insertDownload="insert into download(downloadid,username,mediaid) values(NULL,'$username','$mediaid')";
$queryresult = mysql_query($insertDownload)
	
?>


