<?php
require_once("classes/call.php");
 $q=$_GET["term"];
 $query=mysql_query("SELECT id,medicine_name FROM tbl_medicine WHERE medicine_name LIKE '$q%' ORDER BY id LIMIT 15");
 $json=array();
    while($student=mysql_fetch_array($query)){
        $json[]=array(
        'value'=> $student["medicine_name"],
        'label'=> $student["medicine_name"]
        //'the_link'=>"index.php?page=order_manage&addtolist=".$student["id"]
        );    }
 echo json_encode($json);
?> 