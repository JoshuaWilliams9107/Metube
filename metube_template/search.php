<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();
include_once "function.php";
include_once "logincheck.php";
if(isset($_GET['keywords'])){
    $keywords = mysql_escape_string($_GET['keywords']);
    $keywords = explode(' ', $keywords);
    $media_Arr = array();
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
        
        //$media_ids = array();    
        $true_media_id = fetchAllRows($media_id);
        foreach($true_media_id as $id){
 
            $query = mysql_query("
            SELECT filename
            FROM media
            WHERE mediaid = '".$id[0]."'");
            ?>
            <?php
            $media_Arr[] = fetchAllRows($query); 
            ?>
            <?php
        }
   }
            $media_Arr[] = mysql_fetch_assoc($query);
    $media_Arr = array_unique($media_Arr, SORT_REGULAR);
?>

            <div class="result">
            <?php foreach($media_Arr as $r) ?>
                <a href="#"><?php echo implode(', ', $r); ?> </a>     
            </div>
<?php
}
