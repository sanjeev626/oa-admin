<?php 
//print_r($_POST);
if(isset($_GET['start_date']))
	$start_date = $_GET['start_date'];

if(isset($_GET['end_date']))
	$end_date = $_GET['end_date'];


	$start_date = "2012-07-17";
	$end_date = "2017-04-30";



 //echo $mydb->getQuery('*','tbl_stock','medicine_id="'.$medicine_id.'" ORDER BY id DESC','1');
//$result = $mydb->getQuery('*','tbl_sales','delivery_date BETWEEN "'.$start_date.'" AND "'.$end_date.'"','1' );
//$query = $mydb->getQuery('*','tbl_sales','date BETWEEN "'.$start_date.'" AND "'.$end_date.'"','1');
//echo $query;
$result = $mydb->getQuery('*','tbl_creditmemo','invoice_eng_date BETWEEN "'.$start_date.'" AND "'.$end_date.'" ORDER BY invoice_eng_date ASC');
//exit();
//date, product name, distributor name, invoice number, quantity, @ Cost price, category
?>
<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">     
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td class="titleBarB"><strong>Date</strong></td>	  
		<td class="titleBarB"><strong>Product name</strong></td>  
		<td class="titleBarB"><strong>Distributor</strong></td>	
		<td class="titleBarB"><strong>Invoice Number</strong></td>
		<td class="titleBarB"><strong>Inventory</strong></td>
		<td class="titleBarB"><strong>Cost price</strong></td>
		<td class="titleBarB"><strong>Category</strong></td>
		<td class="titleBarB"><strong>Stock</strong></td>
		<td class="titleBarB"><strong>Sales</strong></td>	
		<td class="titleBarB"><strong>Total Sales</strong></td>			 
	</tr>
	<?php 
	$counter = 0;
	while($rasPurchase = $mydb->fetch_array($result))
	{
	    $creditmemo_id=$rasPurchase['id'];
	    $distributor_id=$rasPurchase['distributor_id'];
	    $distributor_name = $mydb->getValue('name','tbl_distributor','id='.$distributor_id);
		//$query2 = $mydb->getQuery('tor.*,tst.item_description','tbl_stock tst','tst.sales_id='.$creditmemo_id,'1');
		//echo "<br>".$query2;
		//$result2 = $mydb->getQuery('tor.*,tst.item_description,tst.rate,tst.pack,tmd.category,tst.sp_per_tab','tbl_order tor INNER JOIN tbl_stock tst ON tst.medicine_id=tor.medicine_id INNER JOIN tbl_medicine tmd ON tmd.id=tor.medicine_id','tor.sales_id='.$sales_id.' GROUP BY tor.id' );
		//echo "<br>".$mydb->getQuery('tst.*,tmd.category','tbl_stock tst INNER JOIN tbl_medicine tmd ON tmd.id=tst.medicine_id','tst.creditmemo_id='.$creditmemo_id.' AND tst.sales<0 GROUP BY tst.id','1');
		$result2 = $mydb->getQuery('tst.*,tmd.category','tbl_stock tst INNER JOIN tbl_medicine tmd ON tmd.id=tst.medicine_id','tst.creditmemo_id='.$creditmemo_id.' AND tst.sales<0 GROUP BY tst.id' );
			
		//print_r($rasPurchase);	
		while($rasOrder = $mydb->fetch_array($result2))	
		{	
			$medicine_id = $rasOrder['medicine_id'];
			$resStock = $mydb->getQuery('*','tbl_stock','medicine_id='.$medicine_id );
			while($rasStock = $mydb->fetch_array($resStock))
			{
		?>
		<tr>
		  <!-- <td align="center" class="titleBarA" valign="top"><?php  echo $creditmemo_id;?></td> -->
		  <td class="titleBarA" valign="top"><?php echo $rasPurchase['invoice_eng_date'];?></td>
		  <td class="titleBarA" valign="top"><?php echo $rasStock['item_description'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $distributor_name;?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasPurchase['invoice_no'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasStock['stock']-$rasStock['sales'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo number_format($rasStock['rate']/$rasStock['pack'],2);?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasStock['category'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasStock['stock'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $rasStock['sales'];?></td>
		  <td align="center" class="titleBarA" valign="top"><?php echo $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id);?></td>
		</tr>
		<?php
			}			
		}
	}
	?>   
	<tr>
		<!-- <td class="titleBarB"><strong>ORDER Number</strong></td> -->	
		<td colspan="8" class="titleBarB">&nbsp;</td>
		<td class="titleBarB"><input name="btnUpdate" type="submit" value="Update" class="button" /></td>				 
	</tr>
</table>
</form>