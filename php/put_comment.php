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

    $comment = mysqli_real_escape_string($conn,$_POST["text"]);
    $dt = date("Y-m-d H:i:s");

    $sql = "INSERT INTO comments VALUES (DEFAULT,'$comment','$dt')";
    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs)
        echo "ok";

}else
    echo "Error";

?>