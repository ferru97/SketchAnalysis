<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 22/03/2020
 * Time: 20:33
 */

mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){
    $limit = utf8_encode($_GET["limit"]);
    $sql = "SELECT * FROM categories";

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $return = [];
        $return[0][0] = "all";
        $return[0][1] = [];
        $i = 1;
        while($row = mysqli_fetch_array($rs)){

            $cat = $row["id"];
            $sql2 = "SELECT model_tags.tag AS tag, COUNT(model_tags.id) as num FROM (model_tags INNER JOIN models 
                    ON model_tags.id_model=models.uid) INNER JOIN model_categories ON 
                    model_categories.id_model=models.uid WHERE model_categories.id_category='$cat'
                    GROUP BY model_tags.tag,model_categories.id_category ORDER BY num DESC LIMIT ".$limit;

            $rs2 = mysqli_query($conn,$sql2) or die ("Errore ".mysqli_error($conn));
            if($rs2){
                $return[$i][0] = $row["name"];
                $occurrences = [];
                $k = 0;
                while($row2 = mysqli_fetch_array($rs2)){
                    $occurrences[$k][0] = $row2["tag"];
                    $occurrences[$k][1] = $row2["num"];
                    $k++;
                }
                $return[$i][1] = $occurrences;
                $i++;
            }else
                echo "Error";

        }

        //Cat all
        $sql3 = "SELECT model_tags.tag AS tag, COUNT(model_tags.id) as num FROM (model_tags INNER JOIN models 
                    ON model_tags.id_model=models.uid) INNER JOIN model_categories ON  model_categories.id_model=models.uid 
                    GROUP BY model_tags.tag ORDER BY num DESC LIMIT ".$limit;

        $rs3 = mysqli_query($conn,$sql3) or die ("Errore ".mysqli_error($conn));
        if($rs3){
            $occurrences = [];
            $k = 0;
            while($row3 = mysqli_fetch_array($rs3)){
                $occurrences[$k][0] = $row3["tag"];
                $occurrences[$k][1] = $row3["num"];
                $k++;
            }
            $return[0][1] = $occurrences;
        }
        echo json_encode($return);

    }else
        echo "Error";
}else
    echo "Error";


?>