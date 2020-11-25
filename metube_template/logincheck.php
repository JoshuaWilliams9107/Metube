<?php
 if(empty($_SESSION['username'])){
 	header('Location: index.php?mustlogin=true');
 }
?>
