<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 06/05/2020
 * Time: 16:27
 */


ini_set("memory_limit", -1);
mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");
$cat = $_GET["cat"];
if($conn){

    $type = $_GET["type"];
    if($type==0)
        getVertexDistribution($conn,$cat);
    else
        getFaceDistribution($conn,$cat);

}else
    echo "Error";


function getVertexDistribution($conn,$cat){
    $sql = "SELECT vertex_count, COUNT(vertex_count) AS num FROM models INNER JOIN model_categories 
                  ON model_categories.id_model=models.uid WHERE model_categories.id_category='$cat' AND vertex_count!='0' GROUP BY vertex_count";
    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $result1 =[];
        $c = 0;
        while($row = mysqli_fetch_array($rs)){
            $result1[$c][0] = $row["vertex_count"];
            $result1[$c][1] = $row["num"];
            $c++;
        }

        echo json_encode($result1);
    }
}


function getFaceDistribution($conn,$cat){
    $sql = "SELECT face_count, COUNT(face_count) AS num FROM models INNER JOIN model_categories 
                  ON model_categories.id_model=models.uid WHERE model_categories.id_category='$cat' AND face_count!='0' GROUP BY face_count";
    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $result1 =[];
        $c = 0;
        while($row = mysqli_fetch_array($rs)){
            $result1[$c][0] = $row["face_count"];
            $result1[$c][1] = $row["num"];
            $c++;
        }

        echo json_encode($result1);
    }
}


?>