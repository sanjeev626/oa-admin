<?php
require_once("classes/call.php");
 $q=$_GET["term"];
 //$query=$mydb->query("SELECT id,medicine_name FROM tbl_medicine WHERE medicine_name LIKE '$q%' ORDER BY id LIMIT 15");
 $query=$mydb->getQuery('id,medicine_name','tbl_medicine',"medicine_name LIKE '".$q."%' ORDER BY id LIMIT 15");
 $json=array();
    while($student=$mydb->fetch_array($query)){
        $json[]=array(
        'value'=> $student["medicine_name"],
        'label'=> $student["medicine_name"]
        //'the_link'=>"index.php?page=order_manage&addtolist=".$student["id"]
        );    }
 echo json_encode($json);
?> 