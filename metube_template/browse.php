<!DOCTYPE html>
<?php
	session_start();
	include_once "function.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media browse</title>
<link rel="stylesheet" type="text/css" href="css/default.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<script type="text/javascript" src="js/jquery-latest.pack.js"></script>
<script type="text/javascript">
function saveDownload(id)
{
	$.post("media_download_process.php",
	{
       id: id,
	},
	function(message) 
    { }
 	);
} 
</script>
</head>

<body>
<p>Welcome <?php echo $_SESSION['username'];?></p>
<div id='upload_result'>
<a id="floatleft" class="active" href="./home.php">Home</a>
<?php 
	if(isset($_REQUEST['result']) && $_REQUEST['result']!=0)
	{
		
		echo upload_error($_REQUEST['result']);

	}
?>
</div>
<br/><br/>
<?php
	$query = "SELECT * from media"; 
	$result = mysql_query( $query );
	if (!$result)
	{
	   die ("Could not query the media table in the database: <br />". mysql_error());
	}
?>
    
    <div style="background:#339900;color:#FFFFFF; width:150px;">Uploaded Media</div>
	<table width="50%" cellpadding="0" cellspacing="0">
		<?php
			while ($result_row = mysql_fetch_row($result))
			{ 
		?>
        <tr valign="top">			
			<td>
					<?php 
						echo $result_row[0];
					?>
			</td>
            <td>
            	<a href="media.php?id=<?php echo $result_row[0];?>" target="_blank"><?php echo $result_row[1];?></a> 
            </td>
            <td>
                <!-- <a href="<?php echo $result_row[2].$result_row[1];?>" target="_blank" onclick="javascript:saveDownload(<?php echo $result_row[0];?>);">Download</a> -->
                <a href="<?php echo $result_row[2].$result_row[1];?>" download> Download </a>
            </td>
		</tr>
        <?php
			}
		?>
	</table>

</body>
</html>
