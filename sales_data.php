<?php 
//print_r($_POST);
if(isset($_GET['start_date']))
	$start_date = $_GET['start_date'];

if(isset($_GET['end_date']))
	$end_date = $_GET['end_date'];



 //echo $mydb->getQuery('*','tbl_stock','medicine_id="'.$medicine_id.'" ORDER BY id DESC','1');
//$result = $mydb->getQuery('*','tbl_sales','delivery_date BETWEEN "'.$start_date.'" AND "'.$end_date.'"','1' );
$query = $mydb->getQuery('*','tbl_sales','date BETWEEN "'.$start_date.'" AND "'.$end_date.'"','1');
//echo $query;
$result = $mydb->getQuery('*','tbl_sales','date BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
//exit();
?>
<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">     
	<tr>
		<td width="2%" class="titleBarB"><strong>ORDER Number</strong></td>	
		<td width="13%" class="titleBarB"><strong>Date</strong></td>	  
		<td class="titleBarB"><strong>Product name</strong></td>	
		<td width="7%" class="titleBarB"><strong>Quantity</strong></td>
		<td width="5%" class="titleBarB"><strong>Cost price</strong></td>
		<td width="10%" class="titleBarB"><strong>Sale price</strong></td>
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
		?>
		<tr>
		  <td align="center" class="titleBarA" valign="top"><?php  echo $sales_id;?></td>
		  <td class="titleBarA" valign="top"><?php echo $rasSales['date'];?></td>
		  <td class="titleBarA" valign="top"><?php echo $rasOrder['item_description'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasOrder['quantity'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo number_format($rasOrder['rate']/$rasOrder['pack'],2);?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasOrder['sp_per_tab'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasOrder['category'];?></td>
		</tr>
		<?php			
		}
	}
	?>
</table>
</form>