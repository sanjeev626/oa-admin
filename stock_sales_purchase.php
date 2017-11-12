<?php
$medicine_id = $_GET['medicine_id'];
?>
<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">        
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB" colspan="7"><strong>Purchase</strong></td>
	</tr>  
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB"><strong>S.N.</strong></td>
		<td class="titleBarB"><strong>Stock ID</strong></td> 
		<td class="titleBarB"><strong>Invoice Number</strong></td> 
		<td class="titleBarB"><strong>Date</strong></td> 
		<td class="titleBarB"><strong>Distributor</strong></td> 	  
		<td class="titleBarB"><strong>Medicine name</strong></td>  
		<td class="titleBarB"><strong>Purchase</strong></td>	
		<td class="titleBarB"><strong>Stock</strong></td>			 
	</tr>
	<?php 
	$counter = 0;
	$result = $mydb->getQuery('*','tbl_stock','medicine_id='.$medicine_id);
	$total_stock = 0;
	while($rasStock = $mydb->fetch_array($result))
	{
    	$creditmemo_id=$rasStock['creditmemo_id'];
    	$rasCmemo = $mydb->getArray('distributor_id,invoice_nepali_date,invoice_eng_date,invoice_no','tbl_creditmemo','id='.$creditmemo_id);
    	$distributor_id = $rasCmemo['distributor_id'];
    	$distributor = $mydb->getValue('fullname','tbl_distributor','id='.$distributor_id);
    	//$TotalPurchased = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id);
    	//$TotalSold = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id);
    	//$category = $mydb->getValue('category','tbl_medicine','id='.$medicine_id);
    	//$total_quantity
    	//SELECT ts.item_description, tc.invoice_no, ts.quantity, ts.deal, ts.stock, td.fullname FROM tbl_stock ts INNER JOIN tbl_creditmemo tc ON tc.id=ts.creditmemo_id INNER JOIN tbl_distributor td ON td.id=tc.distributor_id WHERE medicine_id=15903
	?>
	<tr>
	  <!-- <td align="center" class="titleBarA" valign="top"><?php  echo $creditmemo_id;?></td> -->
	  <td class="titleBarA" valign="top"><?php echo ++$counter;?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasStock['id'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasCmemo['invoice_no'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasCmemo['invoice_nepali_date'].'/'.$rasCmemo['invoice_eng_date'];?></td>	  
	  <td class="titleBarA" valign="top"><?php echo $distributor;?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasStock['item_description'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasStock['stock']; $total_stock = $total_stock+$rasStock['stock'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasStock['sales'];?></td>
	</tr>
	<?php	
	}
	?> 
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB"></td>
		<td class="titleBarB"></td> 
		<td class="titleBarB"></td> 
		<td class="titleBarB"></td> 	  
		<td class="titleBarB"></td>  
		<td class="titleBarB"><?php echo $total_stock;?></td>	
		<td class="titleBarB"></td>			 
	</tr>
</table>
</form>


<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">        
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB" colspan="8"><strong>Sales</strong></td>
	</tr>  
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB"><strong>S.N.</strong></td>
		<td class="titleBarB"><strong>Stock ID</strong></td> 
		<td class="titleBarB"><strong>Distributor</strong></td>
		<td class="titleBarB"><strong>Date</strong></td> 
		<td class="titleBarB"><strong>Sold to</strong></td> 	  
		<td class="titleBarB"><strong>Medicine name</strong></td>  
		<td class="titleBarB"><strong>Quantity</strong></td>	
		<td class="titleBarB"><strong>Rate</strong></td>			 
	</tr>
	<?php 
	$counter = 0;
	$result = $mydb->getQuery('*','tbl_order','medicine_id='.$medicine_id.' ORDER BY sales_id');
	$total_quantity = 0;
	while($rasSales = $mydb->fetch_array($result))
	{
    	$sales_id=$rasSales['sales_id'];
    	$stock_id=$rasSales['stock_id'];
    	$rass = $mydb->getArray('delivery_date,client_id','tbl_sales','id='.$sales_id); 
    	$delivery_date = $rass['delivery_date'];
    	$client_id = $rass['client_id'];
    	$client_name = $mydb->getValue('fullname','users','id='.$client_id);
    	$creditmemo_id = $mydb->getValue('creditmemo_id','tbl_stock','id='.$stock_id);
    	$distributor_id = $mydb->getValue('distributor_id','tbl_creditmemo','id='.$creditmemo_id);
    	$distributor_name = $mydb->getValue('fullname','tbl_distributor','id='.$distributor_id);
   ?>
	<tr>
	  <td class="titleBarA" valign="top"><?php echo ++$counter;?></td>  
	  <td class="titleBarA" valign="top"><?php echo $rasSales['stock_id'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $distributor_name;?></td>
	  <td class="titleBarA" valign="top"><?php echo $delivery_date;?></td>	  
	  <td class="titleBarA" valign="top"><?php echo $client_name;?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasSales['medicine_name'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasSales['quantity']; $total_quantity = $total_quantity+$rasSales['quantity'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasSales['Rate'];?></td>
	</tr>
	<?php	
	}
	?> 
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB"></td>
		<td class="titleBarB"></td> 
		<td class="titleBarB"></td> 
		<td class="titleBarB"></td> 	  
		<td class="titleBarB"></td> 	  
		<td class="titleBarB"></td> 
		<td class="titleBarB"><?php echo $total_quantity;?></td>	
		<td class="titleBarB"></td>			 
	</tr>
</table>
</form>



<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">        
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB" colspan="8"><strong>Summary</strong></td>
	</tr>  
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB"><strong>S.N.</strong></td>
		<td class="titleBarB"><strong>Stock ID</strong></td> 
		<td class="titleBarB"><strong>Distributor</strong></td>
		<td class="titleBarB"><strong>Date</strong></td> 
		<td class="titleBarB"><strong>Sold to</strong></td> 	  
		<td class="titleBarB"><strong>Medicine name</strong></td>  
		<td class="titleBarB"><strong>Sales</strong></td>	
		<td class="titleBarB"><strong>Stock</strong></td>			 
	</tr>
	<?php 
	$counter = 0;
	$result = $mydb->getQuery('*','tbl_order','medicine_id='.$medicine_id.' GROUP BY stock_id ORDER BY stock_id');
	$total_quantity = 0;
	while($rasSales = $mydb->fetch_array($result))
	{
    	$sales_id=$rasSales['sales_id'];
    	$stock_id=$rasSales['stock_id'];
    	$rass = $mydb->getArray('delivery_date,client_id','tbl_sales','id='.$sales_id); 
    	$delivery_date = $rass['delivery_date'];
    	$client_id = $rass['client_id'];
    	$client_name = $mydb->getValue('fullname','users','id='.$client_id);
    	$creditmemo_id = $mydb->getValue('creditmemo_id','tbl_stock','id='.$stock_id);
    	$distributor_id = $mydb->getValue('distributor_id','tbl_creditmemo','id='.$creditmemo_id);
    	$distributor_name = $mydb->getValue('fullname','tbl_distributor','id='.$distributor_id);
    	$stock_amount = $mydb->getValue('stock','tbl_stock','id='.$stock_id);
    	$total_sale = $mydb->getSum('quantity','tbl_order','stock_id='.$stock_id);

   ?>
	<tr>
	  <td class="titleBarA" valign="top"><?php echo ++$counter;?></td>  
	  <td class="titleBarA" valign="top"><?php echo $rasSales['stock_id'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $distributor_name;?></td>
	  <td class="titleBarA" valign="top"><?php echo $delivery_date;?></td>	  
	  <td class="titleBarA" valign="top"><?php //echo $client_name;?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasSales['medicine_name'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $total_sale; $total_quantity = $total_quantity+$total_sale;?></td>
	  <td class="titleBarA" valign="top"><?php echo $stock_amount;?></td>
	</tr>
	<?php	
	}
	?> 
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB"></td>
		<td class="titleBarB"></td> 
		<td class="titleBarB"></td> 
		<td class="titleBarB"></td> 	  
		<td class="titleBarB"></td> 	  
		<td class="titleBarB"></td> 
		<td class="titleBarB"><?php echo $total_quantity;?></td>	
		<td class="titleBarB"></td>			 
	</tr>
</table>
</form>