<?php
/**
 * Created by PhpStorm.
 * User: Vito
 * Date: 12/04/2020
 * Time: 00:37
 */

set_time_limit(0);
mb_internal_encoding("UTF-8");
include "connect.php";
$x = new Connetti();
$conn = $x->get_conn("127.0.0.1:3308","root","","sketchfab_data");

if($conn){


    $sql = "SELECT * FROM categories";
    $rs = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));
    if($rs){
        $result = [];
        $c = 0;

        while($row = mysqli_fetch_array($rs)){
            $catID = $row[0];
            $catName = $row[1];

            $sql = "SELECT model_comments.comment AS commentLen FROM (`model_comments` INNER JOIN  models 
                   ON models.uid=model_comments.model_id) INNER JOIN model_categories ON models.uid=model_categories.id_model WHERE model_categories.id_category='$catID' ";
            $rs2 = mysqli_query($conn,$sql) or die ("Errore ".mysqli_error($conn));

            if($rs2){
                $result[$c][0] = $catName;
                $num = mysqli_num_rows($rs2);
                $sum = 0;
                $sum2 = 0;

                while ($row2 = mysqli_fetch_array($rs2)){
                    $sum +=  count(explode(" ", $row2["commentLen"]));
                    $sum2 += strlen($row2["commentLen"]);
                }


                $mean = $sum/$num;
                $mean2 = $sum2/$num;

                $sum = 0;
                $sum2 = 0;
                mysqli_data_seek($rs2, 0);
                while ($row3 = mysqli_fetch_array($rs2)){
                    $sum =  $sum + pow((count(explode(" ", $row3["commentLen"]))-$mean),2) ;
                    $sum2 =  $sum2 + pow((strlen($row2["commentLen"])-$mean2),2) ;
                }

                $std = sqrt($sum/$num);
                $std2 = sqrt($sum2/$num);

                //0-1 mean & std #words in comments  --  2-3 #characters in comments

                $result[$c][1] = $mean;
                $result[$c][2] = $std;
                $result[$c][3] = $mean2;
                $result[$c][4] = $std2;

                $c++;
            }

        }

        echo json_encode($result);

    }else
        echo "Error";

}else
    echo "Error";



/* SQL function to calculate the number of words in a text
DELIMITER $$
CREATE FUNCTION wordcount(str LONGTEXT)
       RETURNS INT
       DETERMINISTIC
       SQL SECURITY INVOKER
       NO SQL
  BEGIN
    DECLARE wordCnt, idx, maxIdx INT DEFAULT 0;
    DECLARE currChar, prevChar BOOL DEFAULT 0;
    SET maxIdx=char_length(str);
    SET idx = 1;
    WHILE idx <= maxIdx DO
        SET currChar=SUBSTRING(str, idx, 1) RLIKE '[[:alnum:]]';
        IF NOT prevChar AND currChar THEN
            SET wordCnt=wordCnt+1;
        END IF;
        SET prevChar=currChar;
        SET idx=idx+1;
    END WHILE;
    RETURN wordCnt;
  END
$$
DELIMITER ;*/