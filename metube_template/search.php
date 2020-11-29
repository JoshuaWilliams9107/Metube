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
            WHERE keyword REGEXP '[[:<:]]{$word}'
        ");
        $key_id = mysql_fetch_assoc($key_id);
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
            $media_Arr[] = mysql_fetch_row($query)[0]; 
            ?>
            <?php
        }
   }
   
?>
            <div class="result">
            <?php
             $media_Arr = array_values(array_unique($media_Arr, SORT_REGULAR));
             for($i = 0; $i < count($media_Arr); $i++){?>
                    <a href="#"><?php echo $media_Arr[$i]; ?></a><br>  
            <?php } ?>
            </div>
<?php
}
