<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	session_start();
	include_once "function.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Media browse</title>
<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylessheet" href="css/bootstrap-4.5.3-dist/css/bootstrap.css">
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
<a href='media_upload.php'  style="color:#FF9900;">Upload File</a>
<div id='upload_result'>
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
            	<a href="<?php echo $result_row[2].$result_row[1];?>" target="_blank" onclick="javascript:saveDownload(<?php echo $result_row[0];?>);">Download</a>
            </td>
		</tr>
        <?php
			}
		?>
	</table>

</body>
</html>
