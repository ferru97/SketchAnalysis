<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 04/05/2020
 * Time: 16:23
 */


ini_set('memory_limit', '-1');
mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){
    $return = array();
    $tag = $_GET["tag"];
    $sql = "SELECT models.uid AS id FROM models INNER JOIN model_tags ON model_tags.id_model=models.uid WHERE tag='$tag'";

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $c = 0;
        while($row = mysqli_fetch_array($rs)){
            $uid = $row["id"];
            $sql2 = "SELECT tag, COUNT(tag) AS num FROM models INNER JOIN model_tags ON model_tags.id_model=models.uid WHERE uid='$uid' GROUP BY tag";
            //$sql2 = "SELECT tag FROM models INNER JOIN model_tags ON model_tags.id_model=models.uid WHERE uid='$uid'";
            $rs2 = mysqli_query($conn,$sql2) or die ("Errore ".mysqli_error($conn));
            if($rs2){
                while($row2 = mysqli_fetch_array($rs2)){

                    if($row2["tag"] != $tag){
                        if (array_key_exists(strval(utf8_encode($row2["tag"])),$return))
                            $return[utf8_encode($row2["tag"])] += (int)$row2["num"];
                        else
                            $return[utf8_encode($row2["tag"])] = (int)$row2["num"];
                    }

                }

            }else
                echo "Error";
        }
        arsort($return);
        $subarray = array_slice($return,0,100);
        echo json_encode($subarray);
    }else
        echo "Error";
}else
    echo "Error";

?>