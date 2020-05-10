<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 19/04/2020
 * Time: 12:08
 */

ini_set('memory_limit', '-1');
mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){
    $sql = "SELECT like_count,view_count,comment_count,categories.name as cat,url,models.name AS name, models.date AS date FROM (models INNER JOIN model_categories 
            ON model_categories.id_model=models.uid) INNER JOIN categories ON categories.id=model_categories.id_category ORDER BY cat";

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $return = [];
        $c = 0;
        while($row = mysqli_fetch_array($rs)){
            //view_count - like_count - comment_count - cat - url
            $return[$c][0] = $row["view_count"];
            $return[$c][1] = $row["like_count"];
            $return[$c][2] = $row["comment_count"];
            $return[$c][3] = $row["cat"];
            $return[$c][4] = $row["url"];
            $return[$c][5] = $row["name"];
            $return[$c][6] = $row["date"];
            $c++;
        }
        echo json_encode($return);
    }else
        echo "Error";
}else
    echo "Error";


?>