<?php
header('Content-Type: text/html; charset=mb_internal_encoding("Latin-1");');

class Connetti {

	 function __construct(){}
    
    
    function get_conn($add,$us,$pss,$dbx) {

        $conn = mysqli_connect($add,$us,$pss,$dbx);
      
       if ($conn) return $conn;
    } 


} 

?>