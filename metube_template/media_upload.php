<?php
session_start();
include_once "logincheck.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<title>Media Upload</title>
</head>

<body>
<ul>
    <li><a id="floatleft" href="./home.php">Home</a></li>
    <li><a id="floatleft" href="./media.php">Browse Media</a></li>
    <?php if($_SESSION['username'] != ""){?>
      <li><a id="floatleft" class="active" href='media_upload.php'>Upload Media</a></li>
      <li><a id="floatleft" href='./favoriteview.php'>Favorite playlist</a></li>
    <li><a id="floatleft" href="./channel.php?username=<?php echo $_SESSION['username']?>">My Channel</a></li>
    <li><a id="floatleft" href='./inbox.php'>Inbox</a></li>
    <?php }?>
    <form action="<?php echo "home.php";?>" method="post">
        <button id="logout" type="submit" name="logout" value="true" class="btn-link">Logout</button>
    </form>
  </ul>
<form method="post" action="media_upload_process.php" enctype="multipart/form-data" >
 
  <p style="margin:0; padding:0">
  <input type="hidden" name="MAX_FILE_SIZE" value="1073741824" />
   Add a Media: <label style="color:#663399"><em> (Each file limit 1G)</em></label><br/>
   <input  name="file" type="file" size="50" />

<br>
<br>

<label for"title">Title:</label><br>
<input type="text" id="title" name="title"><br>

<br>

<label for "thumbnail">Thumbnail: <em> (Only needed when uploading non-image files)</em></label><br>
<input type="file" id="thumbnail" name="thumbnail" size="50"><br>

<br>

<label for="description">Description:</label><br>
<textarea type="text" id="description" name="description"></textarea>

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
    <br>

<label for="keywords" method="post">Enter video keywords:</label> <!-- TODO: Need to make it GET eventually -->
<textarea id="keywords" name="keywords" rows="4" cols="50" placeholder="Seperated by space -> Ex. funny educational video_game">
</textarea>

    <br>
	<input value="Upload" name="submit" type="submit" />
  </p>
 
                
 </form>

</body>
</html>
