<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 17/03/2020
 * Time: 23:54
 * Info: get views list per category
 */

mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){

    $return = [];
    $views_distribution = get_views_distribution($conn);
    if($views_distribution!="Error"){
        $return[0] = $views_distribution;
        $likes_distribution = get_likes_distribution($conn);
        if($likes_distribution != "Error"){
            $return[1] = $likes_distribution;
            echo json_encode($return);
        }else
            echo "Error";
    }else
        echo "Error";



}else
    echo "Error";


function get_views_distribution($conn){
    $sql = "SELECT categories.name as cat, models.view_count AS views, COUNT(models.uid) AS card_views FROM 
            (models INNER JOIN model_categories ON models.uid=model_categories.id_model) INNER JOIN categories 
            ON categories.id=model_categories.id_category GROUP BY models.view_count,model_categories.id_category ORDER BY model_categories.id_category";

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $return = [];
        $i = -1;
        $last_cat = "";
        while($row = mysqli_fetch_array($rs)){
            //[0] category_name - [1] #views - [2] views cardinality
            if($last_cat=="" || $last_cat!=$row[0]){
                $i++;
                $return[$i][0] = $row[0];
                $return[$i][1] = [];
            }
            array_push($return[$i][1], array($row[1],$row[2]));
            $last_cat = $row[0];

        }
        return $return;

    }else
        return "Error";

}


function get_likes_distribution($conn){
    $sql = "SELECT categories.name as cat, models.like_count AS likes, COUNT(models.uid) AS card_views FROM 
            (models INNER JOIN model_categories ON models.uid=model_categories.id_model) INNER JOIN categories 
            ON categories.id=model_categories.id_category GROUP BY models.like_count,model_categories.id_category ORDER BY model_categories.id_category";

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $return = [];
        $i = -1;
        $last_cat = "";
        while($row = mysqli_fetch_array($rs)){
            //[0] category_name - [1] #likes - [2] views cardinality
            if($last_cat=="" || $last_cat!=$row[0]){
                $i++;
                $return[$i][0] = $row[0];
                $return[$i][1] = [];
            }
            array_push($return[$i][1], array($row[1],$row[2]));
            $last_cat = $row[0];

        }
        return $return;

    }else
        return "Error";
}


function get_comments_distribution(){
    $sql = "SELECT categories.name as cat, models.like_count AS likes, COUNT(models.uid) AS card_views FROM 
            (models INNER JOIN model_categories ON models.uid=model_categories.id_model) INNER JOIN categories 
            ON categories.id=model_categories.id_category GROUP BY models.like_count,model_categories.id_category ORDER BY model_categories.id_category";
}


?>