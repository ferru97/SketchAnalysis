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

if($conn){

    $sql = "SELECT AVG(vertex_count) AS avg_vertex,SUM(vertex_count) AS sum_vertex, STDDEV(vertex_count) AS std_vertex, AVG(face_count) 
                  AS avg_face,SUM(face_count) AS sum_face, STDDEV(face_count) AS std_face FROM models INNER JOIN model_categories 
                  ON model_categories.id_model=models.uid GROUP BY model_categories.id_category ORDER BY model_categories.id_category";
    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $result2 = [];
        $c = 0;
        while ($row2 = mysqli_fetch_array($rs)){
            $result2[$c][0] = $row2["avg_vertex"];
            $result2[$c][1] = $row2["std_vertex"];
            $result2[$c][2] = $row2["std_vertex"];
            $result2[$c][3] = $row2["avg_face"];
            $result2[$c][4] = $row2["std_face"];
            $result2[$c][5] = $row2["std_face"];
            $result2[$c][6] = $row2["sum_vertex"];
            $result2[$c][7] = $row2["sum_face"];
            $c++;
        }

        echo json_encode($result2);
    }
}else
    echo "Error";


?>