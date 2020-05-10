<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 31/03/2020
 * Time: 23:16
 */

mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){
    $cat = $_GET["cat"];
    $unique = $_GET["unique"];
    $where = "";

    $sql = "";
    if($cat != "all"){
        $sql = "SELECT word, count FROM words_dictionary WHERE words_dictionary.id_category='$cat' ORDER BY words_dictionary.count DESC LIMIT 100";
    }else{
        $sql = "SELECT word, SUM(count) as count FROM words_dictionary GROUP BY word ORDER BY words_dictionary.count DESC LIMIT 100";
    }

    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $found = mysqli_num_rows($rs);
        $return = [];
        $words = [];
        $i = 0;
        while($row = mysqli_fetch_array($rs)){
            //[0] word - [1] #count
            $return[$i][0] = $row[0];
            $return[$i][1] = $row[1];
            array_push($words,$row[0]);
            $i++;
        }
        echo json_encode($return);
    }else
        echo "Error";
}else
    echo "Error";