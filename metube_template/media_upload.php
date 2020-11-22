<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<title>Media Upload</title>
</head>

<body>

<form method="post" action="media_upload_process.php" enctype="multipart/form-data" >
 
  <p style="margin:0; padding:0">
  <input type="hidden" name="MAX_FILE_SIZE" value="1073741824" />
   Add a Media: <label style="color:#663399"><em> (Each file limit 1G)</em></label><br/>
   <input  name="file" type="file" size="50" />

<label for="category" method="post">Choose a category:</label>
  <select id="category" name="category">
    <option value="Sports" selected>Sports</option>
    <option value="Education">Education</option>
    <option value="Video Games">Video Games</option>
    <option value="Vlogs">Vlogs</option>
    <option value="Podcasts">Podcasts</option>
    <option value="Entertainment">Entertainment</option>
    <option value="Others">Others</option>
  </select>

    <br>

<label for="keywords" method="get">Enter video keywords:</label>

<textarea id="keywords" name="keywords" rows="4" cols="50" placeholder="Seperated by space -> Ex. funny educational video_game">
</textarea>
  
	<input value="Upload" name="submit" type="submit" />
  </p>
 
                
 </form>

</body>
</html>
