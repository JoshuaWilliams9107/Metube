<?php
session_start();
include_once "function.php";

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
}
