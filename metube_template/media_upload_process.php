<?php
session_start();
include_once "function.php";
include_once "logincheck.php";
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
      $rand_file = time() . '_' . rand(100, 999) . '.' . end(explode(".", $_FILES["file"]["name"]));
      $upfile = $dirfile.urlencode($rand_file);
      
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

                                $insert = "INSERT into media(
                                    mediaid,filename,filepath,type,title,description,category)
                                    VALUES(NULL, '".$rand_file."', '".$dirfile."',
                                    '".$_FILES["file"]["type"]."','".mysql_real_escape_string($_POST["title"])."',
                                    '".mysql_real_escape_string($_POST["description"])."','".$_POST["category"]."')";
                    $queryresult = mysql_query($insert)
                          or die("Insert into Media error in media_upload_process.php " .mysql_error());

                    $result="0";
                    
                    $mediaid = mysql_insert_id();

                    //insert and check keywords and keywords relation tables
                    if(isset($_POST['keywords']) && !empty($_POST['keywords'])){    
                        $keywords = explode(' ', $_POST['keywords']);//TODO check for mysql injections
                        foreach($keywords as $word){
                            $check = "SELECT * FROM keyword_table WHERE keyword = '$word'";
                            $result = mysql_query($check) or die("Selected from keyword_table" .mysql_error());
                            $data = mysql_fetch_array($result, MYSQL_NUM);
                            if($data[0] > 1){
                                $add = "SELECT `keyword_id` FROM `keyword_table` WHERE `keyword` = '$word'";
                                $word_id = mysql_query($add)
                                    or die("Selecting from keyword table to get keyword_id in media_upload_process.php" .mysql_error());
                                $grab = mysql_fetch_assoc($word_id);
                                $insertMKR = "INSERT into media_to_keywords(media_id, keyword_id) VALUES('$mediaid', '".$grab['keyword_id']."')";
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
                
                    //Thumbnail upload
                    if(!empty($_FILES["thumbnail"]['name'])){
                        if(!file_exists($dirfile."/thumbnail"))
                            mkdir($dirfile."/thumbnail", 0744);
                        $update = "UPDATE media SET thumbnailname='".urlencode($_FILES["thumbnail"]["name"])."' WHERE filename='".$rand_file."' AND filepath='".$dirfile."';";
                        $queryresult = mysql_query($update)
                        or die("Update Thumbnail in Media error in media_upload_process.php " .mysql_error());;
                        if(is_uploaded_file($_FILES["thumbnail"]["tmp_name"])){
                            if(move_uploaded_file($_FILES["thumbnail"]["tmp_name"],$dirfile."/thumbnail/".urlencode($_FILES["thumbnail"]["name"]))){
                                chmod($dirfile."/thumbnail/".urlencode($_FILES["thumbnail"]["name"]), 0644);
                            }
                        }else{
                            $result="8"; //upload thumbnail failed
                        }
                    }
                    //End Thumbnail upload
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
