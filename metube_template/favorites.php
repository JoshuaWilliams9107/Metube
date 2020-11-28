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
