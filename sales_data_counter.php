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

//SELECT * FROM `tbl_sales` WHERE `client_id`="1087"
if(isset($start_date) && isset($end_date))
	$result = $mydb->getQuery('*','tbl_sales','date BETWEEN "'.$start_date.'" AND "'.$end_date.'"');
else
	$result = $mydb->getQuery('*','tbl_sales','client_id="1087"');
//exit();
?>
<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">     
	<tr>
		<td width="2%" class="titleBarB"><strong>S. N.</strong></td>	
		<td width="2%" class="titleBarB"><strong>ORDER Number</strong></td>	
		<td width="13%" class="titleBarB"><strong>Date</strong></td>
		<td width="5%" class="titleBarB"><strong>Sale Amount</strong></td>
		<td width="10%" class="titleBarB"><strong>Cost Price</strong></td>
		<td width="5%" class="titleBarB"><strong>Profit/Loss</strong></td>				 
	</tr>
	<?php 
	$counter = 0;
	$net_amount_total=0;
	while($rasSales = $mydb->fetch_array($result))
	{
	    $sales_id=$rasSales['id'];
		$query2 = $mydb->getQuery('tor.*,tst.item_description','tbl_order tor INNER JOIN tbl_stock tst ON tst.medicine_id=tor.medicine_id','tor.sales_id='.$sales_id,'1');
		//echo "<br>".$query2;
		$result2 = $mydb->getQuery('tor.*,tst.item_description,tst.rate,tst.pack,tmd.category,tst.sp_per_tab,tst.cp_per_tab','tbl_order tor INNER JOIN tbl_stock tst ON tst.medicine_id=tor.medicine_id INNER JOIN tbl_medicine tmd ON tmd.id=tor.medicine_id','tor.sales_id='.$sales_id.' GROUP BY tor.id' );
					
		//print_r($rasSales);	
		$ctotal = 0;
		while($rasOrder = $mydb->fetch_array($result2))	
		{
			$ctotal+=$rasOrder['cp_per_tab']*$rasOrder['cp_per_tab']
		}	
		?>
		<tr>
		  <td align="center" class="titleBarA" valign="top"><?php  echo ++$counter;?></td>
		  <td align="center" class="titleBarA" valign="top"><?php  echo $sales_id;?></td>
		  <td class="titleBarA" valign="top"><?php echo $rasSales['date'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasSales['net_amount']; $net_amount_total+=$rasSales['net_amount'];?></td>
		  <td align="center" class="titleBarA" valign="top"></td>
		  <td align="center" class="titleBarA" valign="top"></td>
		</tr>
		<?php
	}
	?>
		<tr>
		  <td align="center" class="titleBarA" valign="top"></td>
		  <td align="center" class="titleBarA" valign="top"></td>
		  <td class="titleBarA" valign="top"></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $net_amount_total;?></td>
		  <td align="center" class="titleBarA" valign="top"></td>
		  <td align="center" class="titleBarA" valign="top"></td>
		</tr>
</table>
</form>