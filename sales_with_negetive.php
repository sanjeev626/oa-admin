<?php
    $result = $mydb->getQuery('*','tbl_stock','sales<0');
	while($rasSales = $mydb->fetch_array($result))
	{
	   $medicine_id = $rasSales['medicine_id'];
       $item_description = $rasSales['item_description'];
       $total_in_stock = $mydb->getSum('stock','tbl_stock','sales>0 AND medicine_id='.$medicine_id);
       $total_sales = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id);
       $balance_stock = $total_in_stock-$total_sales;
       echo $item_description.' balance_stock :'.$balance_stock;
       echo "<br>";
    }
?>;