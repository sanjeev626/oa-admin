<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">     
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB"><strong>S.N.</strong></td>
		<td class="titleBarB"><strong>Medicine ID</strong></td> 	  
		<td class="titleBarB"><strong>Medicine name</strong></td>  
		<td class="titleBarB"><strong>Total Purchased</strong></td>	
		<td class="titleBarB"><strong>Total Sold</strong></td>
		<td class="titleBarB"><strong>Inventory</strong></td>
		<td class="titleBarB"><strong>Category</strong></td>				 
	</tr>
	<?php 
	$counter = 0;
	$result = $mydb->getQuery('*','tbl_stock','1 GROUP BY medicine_id ORDER BY item_description');
	while($rasStock = $mydb->fetch_array($result))
	{
    	$creditmemo_id=$rasStock['creditmemo_id'];
    	$medicine_id=$rasStock['medicine_id'];
    	$TotalPurchased = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id);
    	$TotalSold = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id);
    	$category = $mydb->getValue('category','tbl_medicine','id='.$medicine_id);
    	//$total_quantity
    	//SELECT ts.item_description, tc.invoice_no, ts.quantity, ts.deal, ts.stock, td.fullname FROM tbl_stock ts INNER JOIN tbl_creditmemo tc ON tc.id=ts.creditmemo_id INNER JOIN tbl_distributor td ON td.id=tc.distributor_id WHERE medicine_id=15903
	?>
	<tr>
	  <!-- <td align="center" class="titleBarA" valign="top"><?php  echo $creditmemo_id;?></td> -->
	  <td class="titleBarA" valign="top"><?php echo ++$counter;?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasStock['medicine_id'];?></td>
	  <td class="titleBarA" valign="top"><?php echo $rasStock['item_description'];?></td>
	  <td align="center" class="titleBarA" valign="top"><?php echo $TotalPurchased;?></td>
	  <td align="center" class="titleBarA" valign="top"><?php echo $TotalSold;?></td>
	  <td align="center" class="titleBarA" valign="top"><?php echo $TotalPurchased-$TotalSold;?></td>
	  <td align="center" class="titleBarA" valign="top"><?php echo $category;?></td>
	</tr>
	<?php	
	}
	?>
</table>
</form>