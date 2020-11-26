<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();
include_once "function.php";
include_once "logincheck.php";
if(isset($_GET['keywords'])){
    $keywords = mysql_escape_string($_GET['keywords']);

    $key_id = mysql_query("
        SELECT keyword_id
        FROM keyword_table
        WHERE keyword LIKE '%{$keywords}%'
    ");

    $true_key_id = mysql_fetch_assoc($key_id);
    
    $media_id = mysql_query("
        SELECT media_id 
        FROM media_to_keywords
        WHERE keyword_id = '".$true_key_id['keyword_id']."'");
    
    $true_media_id = mysql_fetch_assoc($media_id);

    $query = mysql_query("
        SELECT filename
        FROM media
        WHERE mediaid = '".$true_media_id['media_id']."'");
    
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
