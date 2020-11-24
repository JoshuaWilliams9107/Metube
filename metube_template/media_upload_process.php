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
							  mediaid,filename,filepath,type,title,description,category)".
                              "values(NULL,'".urlencode($_FILES["file"]["name"])."','$dirfile','
                              ".$_FILES["file"]["type"]."','" .$_POST['title']."','
                              ".$_POST['description']."','".$_POST['category']."')";
					$queryresult = mysql_query($insert)
						  or die("Insert into Media error in media_upload_process.php " .mysql_error());

					$result="0";
					
                    $mediaid = mysql_insert_id();

                    //insert and check keywords and keywords relation tables
                    $checker = false;
                    if(isset($_POST['keywords']) && !empty($_POST['keywords'])){    
                        $keywords = explode(' ', $_POST['keywords']);//TODO check for mysql injections
                        foreach($keywords as $word){
                            $check = "SELECT * FROM keyword_table WHERE keyword = '$word'";
                            $result = mysql_query($check) or die("Selected from keyword_table" .mysql_error());
                            $data = mysql_fetch_array($result, MYSQL_NUM);
                            if($data[0] > 1){
                                $checker = true;
                                $add = "SELECT keyword_id FROM keyword_table WHERE keyword = '$word'";
                                $word_id = mysql_query($add);
                                $insertMKR = "INSERT into media_to_keywords(media_id, keyword_id) VALUES('$mediaid', '$add')";
                                $queryresult = mysql_query($insertMKR) 
                                    or die("Insert into media_to_keywords in media_upload_process.php" .mysql_error());
                            }
                            else{    
                                $insertK = "INSERT into keyword_table(keyword) VALUES('$word')";
                                $queryresult = mysql_query($insertK)
                                    or die("Insert into keyword_table in media_upload_process.php" .mysql_error());

                                $keywordsid = mysql_insert_id();

                                $insertMK = "INSERT into media_to_keywords(media_id, keyword_id) VALUES('$mediaid', '$keywordsid')";
                                $queryresult = mysql_query($insertMK) 
                                    or die("Insert into media_to_keywords in media_upload_process.php" .mysql_error());


                                //TODO Need to restructure keywords for all of it to work with the for each loop

                            }
                        }
                    }
                    elseif(isset($_POST['keywords'])){
                        echo "keywords is empty";
                    }
                    else{
                        echo "keywords is not set";
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
