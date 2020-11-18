<?php
session_start();
include_once "function.php";
<link rel="stylessheet" href="css/bootstrap-4.5.3-dist/css/bootstrap.css">

if(isset($_GET['keywords'])){
    $keywords = mysql_escape_string($_GET['keywords']);

    $query = mysql_query("
        SELECT filename
        FROM media
        WHERE filename LIKE '%{$keywords}%'
    ");
    ?>
    <div class="num_results">
        Found <?php echo mysql_num_rows($query); ?> results.
    </div>

    <?php
    if(mysql_num_rows($query)){
        while($r = mysql_fetch_object($query)){
        ?>
            <div class="result">
                <a href="#"><?php echo $r->filename; ?> </a>     
            </div>
            <?php
        }
    }
}
