<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 26/04/2020
 * Time: 22:35
 */
mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){

    $sql2 = "SELECT AVG(models.like_count) AS avg_like , STDDEV(models.like_count) AS std_like, AVG(models.view_count) AS avg_view ,
     STDDEV(models.view_count) AS std_view, AVG(models.comment_count) AS avg_comment , STDDEV(models.comment_count) AS std_comment, 
     categories.name AS cat FROM( models INNER JOIN model_categories ON model_categories.id_model=models.uid ) INNER JOIN categories 
     ON categories.id=model_categories.id_category GROUP BY categories.id ORDER BY cat ASC";

    $rs2 = mysqli_query($conn,$sql2) or die ("Errore ".mysqli_error($conn));
    if($rs2){
        $result = [];
        $c = 0;
        while($row = mysqli_fetch_array($rs2)){
            $result[$c][0] = $row["avg_like"];
            $result[$c][1] = $row["std_like"];
            $result[$c][2] = "err";
            $result[$c][3] = $row["avg_view"];
            $result[$c][4] = $row["std_view"];
            $result[$c][5] = "err";
            $result[$c][6] = $row["avg_comment"];
            $result[$c][7] = $row["std_comment"];
            $result[$c][8] = "err";
            $result[$c][9] = $row["cat"];
            $c++;
        }

        for($k=0; $k<count($result); $k++){
            $cat = $result[$k][9];
            $sql = "SELECT like_count,view_count,comment_count FROM( models INNER JOIN model_categories ON model_categories.id_model=models.uid ) 
            INNER JOIN categories ON categories.id=model_categories.id_category WHERE categories.name='".$cat."'";
            $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
            if($rs){
                $like = Array();
                $view = Array();
                $comment = Array();
                while($row = mysqli_fetch_array($rs)){
                    $like[] = $row["like_count"];
                    $view[] = $row["view_count"];
                    $comment[] = $row["comment_count"];
                }
                sort($like);
                sort($view);
                sort($comment);
                $result[$k][2] = $like[intval(count($like)/2)];
                $result[$k][5] = $view[intval(count($view)/2)];
                $result[$k][8] = $comment[intval(count($comment)/2)];
            }
        }


        echo json_encode($result);
    }
}else
    echo "Error";


?>