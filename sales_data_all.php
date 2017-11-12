<?php 
//print_r($_POST);
if(isset($_GET['start_date']))
	$start_date = $_GET['start_date'];
else
	$start_date = "2014-01-01";

if(isset($_GET['end_date']))
	$end_date = $_GET['end_date'];
else
	$end_date = "2017-04-30";



 //echo $mydb->getQuery('*','tbl_stock','medicine_id="'.$medicine_id.'" ORDER BY id DESC','1');
//$result = $mydb->getQuery('*','tbl_sales','delivery_date BETWEEN "'.$start_date.'" AND "'.$end_date.'"','1' );
$query = $mydb->getQuery('ts.*,tc.fullname','tbl_sales ts INNER JOIN users tc ON tc.id=ts.client_id','discount_amount="0" AND delivery_date BETWEEN "'.$start_date.'" AND "'.$end_date.'" ORDER BY delivery_date ASC','1');
echo $query;
$result = $mydb->getQuery('ts.*,tc.fullname','tbl_sales ts INNER JOIN users tc ON tc.id=ts.client_id','delivery_date BETWEEN "'.$start_date.'" AND "'.$end_date.'" ORDER BY delivery_date ASC');
//exit();
?>
<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">     
	<tr>
		<td width="2%" class="titleBarB"><strong>ORDER Number</strong></td>	
		<td width="20%" class="titleBarB"><strong>Sold To</strong></td>	
		<td width="9%" class="titleBarB"><strong>Delivery Date</strong></td>	  
		<td class="titleBarB"><strong>Product name</strong></td>	
		<td width="7%" class="titleBarB"><strong>Quantity</strong></td>
		<td width="5%" class="titleBarB"><strong>Selling Price</strong></td>
		<td width="5%" class="titleBarB"><strong>Total Amount</strong></td>
		<td width="5%" class="titleBarB"><strong>Discount Amount</strong></td>
		<td width="10%" class="titleBarB"><strong>Grand Total</strong></td>
		<td width="5%" class="titleBarB"><strong>Category</strong></td>				 
	</tr>
	<?php 
	$counter = 0;
	while($rasSales = $mydb->fetch_array($result))
	{
	    $sales_id=$rasSales['id'];
		$query2 = $mydb->getQuery('tor.*,tst.item_description','tbl_order tor INNER JOIN tbl_stock tst ON tst.medicine_id=tor.medicine_id','tor.sales_id='.$sales_id,'1');
		//echo "<br>".$query2;
		$result2 = $mydb->getQuery('tor.*,tst.item_description,tst.rate,tst.pack,tmd.category,tst.sp_per_tab','tbl_order tor INNER JOIN tbl_stock tst ON tst.medicine_id=tor.medicine_id INNER JOIN tbl_medicine tmd ON tmd.id=tor.medicine_id','tor.sales_id='.$sales_id.' GROUP BY tor.id' );
					
		//print_r($rasSales);	
		while($rasOrder = $mydb->fetch_array($result2))	
		{	
			$total_amount = $rasSales['total_amount'];
			$discount_amount = $rasSales['discount_amount'];
			$net_amount = $rasSales['net_amount'];
			
			//if($net_amount!=$total_amount)
			//if($total_amount>$net_amount)
			{
				/*$discount = $total_amount-$net_amount;
				$data_dis = '';
				$data_dis['discount_amount'] = $discount;
				$mydb->updateQuery('tbl_sales',$data_dis,'id='.$sales_id);*/
		?>
		<tr>
		  <td align="center" class="titleBarA" valign="top"><?php  echo $sales_id;?></td>
		  <td class="titleBarA" valign="top"><?php echo $rasSales['fullname'];?></td>
		  <td class="titleBarA" valign="top"><?php echo $rasSales['date'];?></td>
		  <td class="titleBarA" valign="top"><?php echo $rasOrder['item_description']; ?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasOrder['quantity'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo number_format($rasOrder['total_amount'],2);?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo number_format($rasSales['total_amount'],2);?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasSales['discount_amount'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo number_format($rasSales['net_amount'],2);?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasOrder['category'];?></td>
		</tr>
		<?php
			}			
		}
	}
	?>
</table>
</form>