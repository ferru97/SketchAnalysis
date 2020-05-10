<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 07/05/2020
 * Time: 18:50
 */

ini_set("memory_limit", -1);
mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){


    $sql = "SELECT url,name,date,like_count,view_count,comment_count,vertex_count,face_count, model_categories.id_category AS cat 
            FROM models INNER JOIN model_categories ON model_categories.id_model=models.uid ORDER BY cat";
    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $result2 = [];
        $c = 0;
        $last_cat = "";
        $cat_index = 0;
        $values = [];
        while ($row2 = mysqli_fetch_array($rs)){
            //[0]url - [1]name - [2]date - [3]-likeCount - [4]viewCount - [5]commentCount - [6]vertexCount - [7]faceCount
            if($last_cat=="")
                $last_cat = $row2["cat"];

            if($last_cat!=$row2["cat"]){
                $result2[$cat_index] = $values;
                $last_cat=$row2["cat"];
                $cat_index++;
                $c = 0;
                $values = [];
            }

            $values[$c][0] = $row2["url"];
            $values[$c][1] = $row2["name"];
            $values[$c][2] = $row2["date"];
            $values[$c][3] = $row2["like_count"];
            $values[$c][4] = $row2["view_count"];
            $values[$c][5] = $row2["comment_count"];
            $values[$c][6] = $row2["vertex_count"];
            $values[$c][7] = $row2["face_count"];
            $c++;
        }
        $result2[$cat_index++] = $values;

        echo json_encode($result2);
    }
}else
    echo "Error";

?>