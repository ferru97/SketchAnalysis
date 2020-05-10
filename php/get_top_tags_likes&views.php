<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 22/03/2020
 * Time: 20:33
 */

ini_set('max_execution_time', '0');
mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){

    $cat = $_GET["cat"];
    $sql = "";
    if($cat == '0'){
        $sql2 = "SELECT SUM(models.like_count) as like_num, SUM(models.view_count) as view_num , SUM(models.comment_count) as comm_num  ,model_tags.tag AS tag, COUNT(model_tags.id) as num FROM (model_tags INNER JOIN models 
                    ON model_tags.id_model=models.uid) INNER JOIN model_categories ON  model_categories.id_model=models.uid  
                    GROUP BY model_tags.tag ORDER BY num DESC LIMIT 200";
    }else{
        $sql2 = "SELECT SUM(models.like_count) as like_num, SUM(models.view_count) as view_num ,SUM(models.comment_count) as comm_num ,model_tags.tag AS tag, COUNT(model_tags.id) as num FROM (model_tags INNER JOIN models 
                    ON model_tags.id_model=models.uid) INNER JOIN model_categories ON  model_categories.id_model=models.uid  
                    WHERE model_categories.id_category='$cat' GROUP BY model_tags.tag ORDER BY num DESC LIMIT 100";
    }

    $rs2 = mysqli_query($conn,$sql2) or die ("Errore ".mysqli_error($conn));
    if($rs2){
        $result = [];
        $c = 0;
        while($row = mysqli_fetch_array($rs2)){
            //[0]tag - [0]num -[0]like_num -[0]view_num -[0]comm_num
            $result[$c][0] = $row["tag"];
            $result[$c][1] = $row["num"];
            $result[$c][2] = $row["like_num"];
            $result[$c][3] = $row["view_num"];
            $result[$c][4] = $row["comm_num"];
            $c++;
        }

        echo json_encode($result);
    }
}else
    echo "Error";


?>