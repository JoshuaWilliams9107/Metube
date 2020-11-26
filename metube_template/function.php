<?php
include "mysqlClass.inc.php";

function user_pass_check($username, $password)
{
	
	$query = "select * from account where username='$username'";
	$result = mysql_query( $query );
		
	if (!$result)
	{
	   die ("user_pass_check() failed. Could not query the database: <br />". mysql_error());
	}
	else{
		$row = mysql_fetch_row($result);
		if(strcmp($row[1],$password))
			return 2; //wrong password
		else 
			return 0; //Checked.
	}	
}
function addUserToDatabase($username, $password, $email, $firstName, $lastName)
{
	$query = "select * from account where username='$username'";
	$result = mysql_query( $query );
	if(mysql_num_rows($result) != 0){
		return 1; //Username already exists in database
	}

	$query = "INSERT INTO account (username,password,email,firstName,lastName) VALUES ('$username','$password','$email','$firstName','$lastName')";
	$result = mysql_query( $query );
		
	if (!$result)
	{
	   die ("addUserToDatabase() failed. Could not query the database: <br />". mysql_error());
	}
	else{
		return 0; //Successfully added user
	}	
}
function getUser($username){
	$result = mysql_query("SELECT * FROM account where username='$username'");
	$returnVal = mysql_fetch_row($result);
	return $returnVal;
}
function sendFriendRequest($recipient)
{
	$recipientUser = getUser($recipient);
	if(!$recipientUser){
		//1 = user does not exist
		return 1;
	}
	if($recipient == $_SESSION['username']){
		return 3; //You cannot send a friend request to yourself
	}
	$existsTest = mysql_query("SELECT * FROM contacts WHERE sender='".$_SESSION['username']."' and recipient='$recipient'");
	echo mysql_num_rows($existsTest);
	if(mysql_num_rows($existsTest) != 0){
		return 2; //Friend request already sent
	}
	$existsTest2 = mysql_query("SELECT * FROM contacts WHERE sender='$recipient' and recipient='".$_SESSION['username']."'");
	if(mysql_num_rows($existsTest2) != 0){
		return 2; //Friend request already sent
	}

	//0 is pending 1 is complete
	$query = "INSERT INTO contacts (sender,recipient,status) VALUES ('".$_SESSION['username']."','$recipient',0)";
	$result = mysql_query( $query );	
	if (!$result)
	{
	   die ("sendFriendRequest() failed. Could not query the database: <br />". mysql_error());
	}
	else{
		return 0; //Successfully added user
	}	
}
function getContacts(){
	$result = mysql_query("SELECT * FROM contacts where sender='".$_SESSION['username']."' OR recipient='".$_SESSION['username']."'");
	if(mysql_num_rows($result) > 0 ){
		return fetchAllRows($result);
	}else{
		return null;
	}
}
function fetchAllRows($result){
	if(mysql_num_rows($result) > 0 ){
		while($row = mysql_fetch_array($result)){
			  $dataArray[] = $row;
		}
	} 
	return $dataArray;
}
function updateMediaTime($mediaid)
{
	$query = "	update  media set lastaccesstime=NOW()
   						WHERE '$mediaid' = mediaid
					";
					 // Run the query created above on the database through the connection
    $result = mysql_query( $query );
	if (!$result)
	{
	   die ("updateMediaTime() failed. Could not query the database: <br />". mysql_error());
	}
}

function upload_error($result)
{
	//view erorr description in http://us2.php.net/manual/en/features.file-upload.errors.php
	switch ($result){
	case 1:
		return "UPLOAD_ERR_INI_SIZE";
	case 2:
		return "UPLOAD_ERR_FORM_SIZE";
	case 3:
		return "UPLOAD_ERR_PARTIAL";
	case 4:
		return "UPLOAD_ERR_NO_FILE";
	case 5:
		return "File has already been uploaded";
	case 6:
		return  "Failed to move file from temporary directory";
	case 7:
		return  "Upload file failed";
	}
}

function other()
{
	//You can write your own functions here.
}
	
?>
