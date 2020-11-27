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
        
        $media_ids = array();    
        $true_media_id = mysql_fetch_array($media_id);
        foreach($true_media_id as $id){
            echo $id;    
        }

        $query = mysql_query("
            SELECT *
            FROM media
            WHERE mediaid = '".$true_media_id['media_id']."'");
        ?>
        <?php
        $query_file = mysql_fetch_assoc($query);
        $query = array_unique($query_file);
        ?>
        <body style="padding:0, margin:0;">
        <ul>
            <li><a id="floatleft" href="./home.php">Home</a></li>
        <ul>
        <center>
        <table style="width:70%">
        <tr>
        <?php
        $rowSize = 3;
        if(mysql_num_rows($query) == 0){
            echo "<p style='font-size:20px;'> No videos found </p>";
        }
        for($i=0; $i<mysql_num_rows($query); $i++){
            if($searchinfo = mysql_fetch_row($query)){
                if($i % $rowSize == 0){
                    ?>
                    </tr>
                    <tr>
                <?php } ?>
                <td>
                    <center>
                <a href="./media.php?id=<?php echo $searchinfo[0]:?>">

                <?php if(strpos($searchinfo[3], 'image') !== false){?>
                    <img src="<?php echo $searchinfo[2].$searchinfo[1]; ?>"
                alt="thumbnail" width=250px height=150px/><br>
                <?php} else if(!is_null($searchinfo[9])){?>
                <img src="<?php echo $searchinfo[2]."thumbnail/".$searchinfo[9]?>"
                alt="thumbnail" width=250px height=150px/><br>
                
                <?php } else{?>
                <img src="uploads/metube/BlankVideo.png"
                alt="blank user image" width=250px height=150px/><br>

                <?php
                }
                echo "<p>".$searchinfo[4]."<p>";?>
                </a>
                </center>
                </td>
            <?php
            }
            else{
                break;
            }
        }
        ?>
        </tr>
        </table>
        </div>
    </body>
