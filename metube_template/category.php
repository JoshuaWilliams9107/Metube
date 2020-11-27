<link rel="stylesheet" type="text/css" href="css/default.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<?php
session_start();

include_once "function.php";
include_once "logincheck.php";

?>
<body style="padding:0;margin:0;">
    <ul>
        <li><a id="floatleft" href="./home.php">Home</a></li>
    <ul>

    <center>
    <table style="width:70%">
    <tr>
    <?php
    $rowSize=3;

    $categoryquery = mysql_query("SELECT * FROM media WHERE category = '".$_GET['category']."';");

    if(mysql_num_rows($categoryquery) == 0){
        echo "<p style='font-size:20px;'>No videos for this category</p>";
    }
    for($i=0; $i<mysql_num_rows($categoryquery);$i++){
        if($categoryinfo = mysql_fetch_row($categoryquery)){
            if($i % $rowSize == 0){
            ?>
                </tr>
                <tr>
            <?php } ?>
            <td>
                <center>
                <a href="./media.php?id=<?php echo $categoryinfo[0];?>">
                
                <?php if(strpos($categoryinfo[3], 'image') !== false){?>
                    <img src="<?php echo $categoryinfo[2].$categoryinfo[1];?>"
                alt="thumbnail" width=250px height=150px/> <br>
                <?php } else if(!is_null($categoryinfo[9])){?>
                <img src="<?php echo $categoryinfo[2]."thumbnail/".$categoryinfo[9]?>"
                alt="thumbnail" width=250px height=150px/><br>

                <?php }else{?>
                <img src="uploads/metube/BlankVideo.png"
                alt="blank user image" width=250px height=150px/><br>

                <?php
            }
                echo "<p>".categoryinfo[4]."</p>";?>
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
    </center>
    </div>
</body>
