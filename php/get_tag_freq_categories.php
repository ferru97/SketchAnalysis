<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 01/05/2020
 * Time: 19:25
 */

mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");


if($conn){
    $tag = utf8_encode($_GET["tag"]);
    $sql = "SELECT * FROM categories";

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $return = [];
        $i = 0;
        while($row = mysqli_fetch_array($rs)){

            $cat = $row["id"];
            $sql2 = "SELECT COUNT(model_tags.id) as num FROM (model_tags INNER JOIN models 
                    ON model_tags.id_model=models.uid) INNER JOIN model_categories ON 
                    model_categories.id_model=models.uid WHERE model_categories.id_category='$cat'
                    AND tag='$tag' ORDER BY num DESC";

            $rs2 = mysqli_query($conn,$sql2) or die ("Errore ".mysqli_error($conn));
            if($rs2){
                $return[$i][0] = $row["name"];
                $k = 0;
                while($row2 = mysqli_fetch_array($rs2)){
                    $return[$i][1] = $row2["num"];
                    $k++;
                }
                $i++;
            }else
                echo "Error";
        }
        echo json_encode($return);

    }else
        echo "Error";
}else
    echo "Error";


?>