<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();
include_once "function.php";
include_once "logincheck.php";
if(isset($_GET['keywords'])){
    $keywords = mysql_escape_string($_GET['keywords']);
    //$keywords = explode(' ', $keywords);
    
        $key_id = mysql_query("
            SELECT *
            FROM keyword_table
            WHERE keyword LIKE '%{$keywords}%'
        ");

        $true_key_id = mysql_fetch_assoc($key_id);
    
        $media_id = mysql_query("
            SELECT * 
            FROM media_to_keywords
            WHERE keyword_id = '".$true_key_id[0]."'");
    
        $true_media_id = mysql_fetch_assoc($media_id);

        $query = mysql_query("
            SELECT filename
            FROM media
            WHERE mediaid = '".$true_media_id[1]."'");
        ?>
        <?php
        $query = array_unique($query);
        ?>
        <div class="result">
        <?php for each($queary as $r) ?>
            <a href="#"><?php echo $r; ?> </a>     
        </div>
        <?php
    }
