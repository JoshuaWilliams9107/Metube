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
function addUserToDatabase($username, $password, $email)
{
	$query = "select * from account where username='$username'";
	$result = mysql_query( $query );
	if(mysql_num_rows($result) != 0){
		return 1; //Username already exists in database
	}

	$query = "INSERT INTO account (username,password,email) VALUES ('$username','$password','$email')";
	$result = mysql_query( $query );
		
	if (!$result)
	{
	   die ("addUserToDatabase() failed. Could not query the database: <br />". mysql_error());
	}
	else{
		return 0; //Successfully added user
	}	
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
