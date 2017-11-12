<?php
require_once("classes/call.php");

 $q=$_GET["term"];
 //echo  $q;
 
 $query=mysql_query("SELECT id,item_description,medicine_id FROM tbl_stock WHERE item_description LIKE '$q%' GROUP BY item_description ORDER BY item_description LIMIT 20");
 $query2=mysql_query("SELECT id,invoice_no FROM tbl_creditmemo WHERE invoice_no LIKE '$q%' GROUP BY invoice_no ORDER BY id LIMIT 20");
 $query3=mysql_query("SELECT id,fullname FROM tbl_distributor WHERE fullname LIKE '$q%' GROUP BY fullname ORDER BY id LIMIT 20");
 
 $json=array();
 
    while($rasStock=mysql_fetch_array($query)){
         $json[]=array(
            'value'=> $rasStock["item_description"],
            'label'=> $rasStock["item_description"]." - Medicine Name",
            'the_link'=>"index.php?manager=stock&addtolist=".$rasStock["medicine_id"]
        );
    }
 
 	while($student2=mysql_fetch_array($query2)){
         $json[]=array(
                    'value'=> $student2["invoice_no"],
                    'label'=> $student2["invoice_no"]." - Invoice Number",
                    'the_link'=>"index.php?manager=stock&invoice_id=".$student2["id"]
                        );
    }
    while($student3=mysql_fetch_array($query3)){
         $json[]=array(
                    'value'=> $student3["fullname"],
                    'label'=> $student3["fullname"]." - Distributor",
                    'the_link'=>"index.php?manager=stock&distributor_list=".$student3["id"]
                        );
    }
 echo json_encode($json);

?> 