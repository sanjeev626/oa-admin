<?php

require_once("../classes/call.php");

//echo $string ="../classes/call.php");

 $q=$_GET["term"];

 

 $query=mysql_query("SELECT id,fullname FROM tbl_company WHERE fullname LIKE '$q%' ORDER BY id LIMIT 15");

 

$json=array();

 

    while($company=mysql_fetch_array($query)){

        $json[]=array(

        'value'=> $company["fullname"],

        'label'=> $company["fullname"],

        //'the_link'=>"index.php?page=order_manage&addtolist=".$company["id"]
        );

    }

 

 	

 echo json_encode($json);



?> 