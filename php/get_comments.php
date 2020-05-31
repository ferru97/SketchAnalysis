<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 30/05/2020
 * Time: 00:08
 */

mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){

    $sql = "SELECT comment,date FROM comments ORDER BY date DESC";
    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $return = [];
        $i = 0;
        while($row = mysqli_fetch_array($rs)){
            $return[$i][0] = $row[0];
            $return[$i][1] = $row[1];
            $i++;
        }
        echo json_encode($return);

    }

}else
    echo "Error";
?>