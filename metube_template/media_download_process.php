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

//insert into download table
$insertDownload="insert into download(downloadid,username,mediaid) values(NULL,'$username','$mediaid')";
$queryresult = mysql_query($insertDownload)
    
$getDirectory ="SELECT filepath FROM media WHERE mediaid = '$mediaid' ";    
$dirfile = mysql_query($getDirectory);
$downfile = $dirfile.urlencode($_FILE["file"]["name"]);
$file = $downfile;

/*
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/force-download');
    header("Content-Disposition: attachment; filename=\"" . basename($file) . "\";");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($name); //showing the path to the server where the file is to be download
    exit;
}
 */
?>

