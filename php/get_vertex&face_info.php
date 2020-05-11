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
                  AS avg_face,SUM(face_count) AS sum_face, STDDEV(face_count) AS std_face,id_category AS cat FROM models INNER JOIN model_categories 
                  ON model_categories.id_model=models.uid GROUP BY model_categories.id_category ORDER BY model_categories.id_category";
    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $result2 = [];
        $c = 0;
        while ($row2 = mysqli_fetch_array($rs)){
            $result2[$c][0] = $row2["avg_vertex"];
            $result2[$c][1] = $row2["std_vertex"];
            $result2[$c][2] = "err";
            $result2[$c][3] = $row2["avg_face"];
            $result2[$c][4] = $row2["std_face"];
            $result2[$c][5] = "err";
            $result2[$c][6] = $row2["sum_vertex"];
            $result2[$c][7] = $row2["sum_face"];
            $result2[$c][8] = $row2["cat"];
            $c++;
        }


        for($k=0; $k<count($result2); $k++){
            $cat = $result2[$k][8];
            $sql = "SELECT vertex_count,face_count FROM models INNER JOIN model_categories
                      ON model_categories.id_model=models.uid WHERE model_categories.id_category='".$cat."'";
            $rs2 = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
            if($rs2){
                $vertices = Array();
                $faces = Array();
                while($row = mysqli_fetch_array($rs2)){
                    $vertices[] = $row["vertex_count"];
                    $faces[] = $row["face_count"];
                }
                sort($vertices);
                sort($faces);
                $result2[$k][2] = $vertices[intval(count($vertices)/2)];
                $result2[$k][5] = $faces[intval(count($faces)/2)];
            }
        }

        echo json_encode($result2);
    }
}else
    echo "Error";


?>