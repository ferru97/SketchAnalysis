<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 23/03/2020
 * Time: 23:10
 */

mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){
    $sql = "SELECT categories.name as cat,COUNT(model_comments.id) AS comments FROM ((models INNER JOIN model_categories 
            ON models.uid=model_categories.id_model) INNER JOIN categories ON categories.id=model_categories.id_category) 
            INNER JOIN model_comments ON model_comments.model_id=models.uid GROUP BY model_categories.id_category ORDER BY cat";

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $return = [];
        $i = 0;
        while($row = mysqli_fetch_array($rs)){
            //[0] category_name - [1] #comments
            $return[$i][0] = $row[0];
            $return[$i][1] = $row[1];
            $i++;
        }
        echo json_encode($return);

    }else
        echo "Error";
}else
    echo "Error";


?>