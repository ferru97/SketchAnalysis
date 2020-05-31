<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 17/03/2020
 * Time: 23:24
 * Info: get views model and likes sum for each category
 */

mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){
    $sql = "SELECT categories.name as cat, COUNT(models.uid) AS n_models, SUM(models.view_count) 
    AS views, SUM(models.like_count) as likes FROM (models INNER JOIN model_categories ON models.uid=model_categories.id_model) 
    INNER JOIN categories ON categories.id=model_categories.id_category GROUP BY model_categories.id_category ORDER BY cat";

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $return = [];
        $i = 0;
        while($row = mysqli_fetch_array($rs)){
            //[0] category_name - [1] #models - [2] #views  [3] #likes
            $return[$i][0] = $row[0];
            $return[$i][1] = $row[1];
            $return[$i][2] = $row[2];
            $return[$i][3] = $row[3];
            $i++;
        }
        echo json_encode($return);

    }else
        echo "Error";
}else
    echo "Error";

?>