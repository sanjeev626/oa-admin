<?php
require_once("classes/call.php");
/*=====================medicine store search in nepal================================ */
 $q=$_GET["term"];
 
 $query=mysql_query("SELECT id,medicine_name FROM tbl_medicine WHERE medicine_name LIKE '$q%' GROUP BY medicine_name ORDER BY id LIMIT 20");
 $query2=mysql_query("SELECT id,name FROM tbl_distributor WHERE name LIKE '$q%' GROUP BY name ORDER BY id LIMIT 20");
 $query3=mysql_query("SELECT id,name FROM tbl_company WHERE name LIKE '$q%' GROUP BY name ORDER BY id LIMIT 20");
 //$query3=mysql_query("SELECT id,name FROM tbl_company WHERE )
 $json=array();
 
    while($medicine=mysql_fetch_array($query)){


         $json[]=array(
                        'value'=> $medicine["medicine_name"],
                        'label'=> $medicine["medicine_name"]."-medicine",
                        'the_link'=> "index.php?manager=storeinformation&medicinelist=".$medicine["id"]
                        );
    }
 
 	while($distributor=mysql_fetch_array($query2)){
         $json[]=array(
                        'value'=> $distributor["name"],
                        'label'=> $distributor["name"]."-distributor",
                        'the_link'=> "index.php?manager=storeinformation&distributorlist=".$distributor["id"]
                        );
    }
    while($company=mysql_fetch_array($query3)){
         $json[]=array(
                        'value'=> $company["name"],
                        'label'=> $company["name"]."-company",
                        'the_link'=> "index.php?manager=storeinformation&companylist=".$company["id"]
                        );
    }
 echo json_encode($json);

?> 