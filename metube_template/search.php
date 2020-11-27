<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();
include_once "function.php";
include_once "logincheck.php";
if(isset($_GET['keywords'])){
    $keywords = mysql_escape_string($_GET['keywords']);
    $keywords = explode(' ', $keywords);
   foreach($keywords as $word){ 
        $key_id = mysql_query("
            SELECT keyword_id
            FROM keyword_table
            WHERE keyword LIKE '%{$word}%'
        ");
        $key_id = mysql_fetch_assoc($key_id);
        echo $key_id['keyword_id'];
        //$true_key_id = mysql_fetch_assoc($key_id);
    
        $media_id = mysql_query("
            SELECT media_id
            FROM media_to_keywords
            WHERE keyword_id = '".$key_id['keyword_id']."'");
    
        $true_media_id = mysql_fetch_assoc($media_id);
        foreach($true_media_id['media_id'] as $id){
            echo $id;    
        }

        $query = mysql_query("
            SELECT filename
            FROM media
            WHERE mediaid = '".$true_media_id['media_id']."'");
        ?>
        <?php
        $query_file = mysql_fetch_assoc($query);
        $query = array_unique($query_file);
        ?>
        <div class="result">
        <?php foreach($query as $r) ?>
            <a href="#"><?php echo $r; ?> </a>     
        </div>
        <?php
   }
}
