<?php
session_start();
include_once "function.php";

/******************************************************
*
* upload document from user
*
*******************************************************/

$username=$_SESSION['username'];


//Create Directory if doesn't exist
if(!file_exists('uploads/'))
	mkdir('uploads/', 0744);
$dirfile = 'uploads/'.$username.'/';
chmod($dirfile, 0755);
if(!file_exists($dirfile))
	mkdir($dirfile, 0744);


	if($_FILES["file"]["error"] > 0 )
	{ $result=$_FILES["file"]["error"];} //error from 1-4
	else
	{
	  $upfile = $dirfile.urlencode($_FILES["file"]["name"]);
	  
	  if(file_exists($upfile))
	  {
	  		$result="5"; //The file has been uploaded.
	  }
	  else{
			if(is_uploaded_file($_FILES["file"]["tmp_name"]))
			{
				if(!move_uploaded_file($_FILES["file"]["tmp_name"],$upfile))
				{
					$result="6"; //Failed to move file from temporary directory
				}
				else /*Successfully upload file*/
				{
                    			chmod($upfile, 0644);
					//insert into media table
					$insert = "insert into media(
							  mediaid, filename,filepath,type,category)".
							  "values(NULL,'". urlencode($_FILES["file"]["name"])."','$dirfile','".$_FILES["file"]["type"]."','".$_POST['category']."')";
					$queryresult = mysql_query($insert)
						  or die("Insert into Media error in media_upload_process.php " .mysql_error());

					$result="0";
					
					$mediaid = mysql_insert_id();

                    if(count($_GET['keywords']) >= 1){
                        $insertMK = "INSERT into media_to_keywords(media_id, keywords_id) VALUES('$mediaid', NULL)";
                        $queryresult = mysql_query($insertMK) 
                            or die("Insert into media_to_keywords in media_upload_process.php" .mysql_error());

                        $keywordsid = mysql_insert_id();
                        $keywords = explode(' ', _GET['keywords']);//TODO check for mysql injections

                        foreach($keywords as $keyword){
                            $insertK = "INSERT into keyword_table(keywords_id, keywords) VALUES('$keywordsid', $keyword)";
                            $queryresult = mysql_query($insertK);
                        }
                    }
					//insert into upload table
					$insertUpload="insert into upload(uploadid,username,mediaid) values(NULL,'$username','$mediaid')";
					$queryresult = mysql_query($insertUpload)
						  or die("Insert into view error in media_upload_process.php " .mysql_error());
				}
			}
			else  
			{
					$result="7"; //upload file failed
			}
		}
	}
	
	//You can process the error code of the $result here.
?>

<meta http-equiv="refresh" content="0;url=browse.php?result=<?php echo $result;?>">
