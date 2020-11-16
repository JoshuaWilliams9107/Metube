<?php
session_start();
include_once "function.php";
$link = $db->db_connect_id;

if(isset($_GET['keywords'])){
    $keywords = mysqli_real_escape_string($link, $_GET['keywords']);

    $query = $db->query("
        SELECT filename
        FROM media
        WHERE filename LIKE '%{$keywords}%'
    ");
    ?>
    <div class="num_results">
        Found <?php $query->num_rows; ?> results.
    </div>

    <?php
}
