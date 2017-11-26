<?php
require_once("classes/call.php");
 $q=$_GET["term"];
 //echo $mydb->getQuery('id,medicine_id,item_description,expiry_date,stock,sales','tbl_stock',"item_description LIKE '%".$q."%' AND stock>sales ORDER BY expiry_date LIMIT 15",'1');
 $query=$mydb->getQuery('id,medicine_id,item_description,expiry_date,stock,sales','tbl_stock',"item_description LIKE '%".$q."%' AND stock>sales ORDER BY item_description,expiry_date LIMIT 15");
 $json=array();
    while($rasStock=$mydb->fetch_array($query)){
    	$available_stock = $rasStock["stock"]-$rasStock["sales"];
        $label = $rasStock["item_description"].' ('.$available_stock.') - '.$rasStock["expiry_date"];
        $value = $rasStock["item_description"];
        //echo $label."<br>";
        $json[]=array(
        'value'=> $value,
        'label'=> $label,
        'stock_id'=> $rasStock["id"],
        'medicine_id'=> $rasStock["medicine_id"]
        //'the_link'=>"index.php?page=order_manage&addtolist=".$rasStock["id"]
        );   
    }
 echo json_encode($json);
?> 