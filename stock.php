<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"/>
<script type="text/javascript">
 function callDelete(id)
 {
	if(confirm('Are you sure to delete ?'))
	{
		window.location='?manager=stock&did='+id;
	}
 }

 function callDeletestock(id)
 {
	if(confirm('Are you sure to delete ?'))
	{
		window.location='?manager=stock&sid='+id;
	}
 }

  function callReturn(id)
 {
	if(confirm('Are you sure to return stock ?'))
	{
		window.location='?manager=stock_return&stock_return_id='+id;
	}
 }
</script>
<script type="text/javascript">
   $(document).ready(function(){
        $(".inputBox").autocomplete({
            source:'stock_search.php',
            minLength:1,
            select:function(e,ui) {
            	location.href = ui.item.the_link;            	
            }
            
        });
    });
</script>
<?php 

if(isset($_POST['btnUpdate']))
{
	$count = count($_POST['eid']);
	for($i=0;$i<$count;$i++)
	{
		$eid = $_POST['eid'][$i];
		$data='';
		$data['ordering'] = $_POST['ordering'][$i];				
		$mydb->updateQuery('tbl_distributor',$data,'id='.$eid);
	}
}
if(isset($_GET['did']))
{
	$delId = $_GET['did'];
	$creditmemo_id=$mydb->getValue('creditmemo_id','tbl_stock','id='.$delId);
    $mess = $mydb->deleteQuery('tbl_stock','id='.$delId);
    $mydb->deleteQuery('tbl_creditmemo','id='.$creditmemo_id);
    $url=ADMINURLPATH."stock";
    $mydb->redirect($url);

}
if(isset($_GET['sid']))
{
	$delId = $_GET['sid'];
    $mess = $mydb->deleteQuery('tbl_stock','id='.$delId);
    $url=ADMINURLPATH."stock";
    $mydb->redirect($url);
}
?>

<form>
  	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
<?php if(isset($_GET['message'])){?>
		<tr>
		  	<td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
		</tr>
<?php } ?>
	
	    <tr class="TitleBar">
	      <td class="TtlBarHeading" width="90%">Stock details</td>
	      <td class="TtlBarHeading"><input name="btnAdd" type="button" value="Add" class="button" onClick="window.location='<?php echo ADMINURLPATH;?>stock_manage'" /></td>
	    </tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
		<tr>	    	
	    	<td align="right" class="TitleBarA" width="20%"><strong>Search:</strong></td>
	    	<td class="TitleBarA" style="width:50px;"><input type="text" class="inputBox" id="searchid" placeholder="Search for medicine or invoice no or distributor" style="width:50%" value="<?php if(isset($_GET['addtolist'])){echo $mydb->getValue('medicine_name','tbl_medicine','id='.$_GET['addtolist']);} ?>"></td>
		</tr>

	</table>
</form>

 <?php 
 if(isset($_GET['addtolist']))
 {
	$medicine_id=$_GET['addtolist'];
	//$medicine_id = $mydb->getValue('medicine_id','tbl_stock','id='.$tbl_stock_id);
    //$description = $mydb->getValue('description','tbl_stock','id='.$tbl_stock_id);
	

	//echo $mydb->getQuery('*','tbl_stock','medicine_id="'.$medicine_id.'" ORDER BY id DESC','1');
	$result = $mydb->getQuery('*','tbl_stock','medicine_id="'.$medicine_id.'" ORDER BY id DESC');

	?>
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">     
	<tr>
		<td width="15%" align="center" class="titleBarB"><strong>Distributor</strong></td>	  
		<td width="7%" align="center" class="titleBarB"><strong>Invoice No</strong></td>	
		<td width="7%" align="center" class="titleBarB"><strong>Recieved Date</strong></td>
		<td width="10%" align="center" class="titleBarB"><strong>Item description</strong></td>
		<td align="center" class="titleBarB"><strong>Pack(No. of tab)</strong></td>
		<td width="5%" align="center" class="titleBarB"><strong>Batch No</strong></td>
		<td align="center" class="titleBarB"><strong>Expiry Date</strong></td>
		<td align="center" class="titleBarB" ><strong>Quantity</strong></td>
		<td align="center" class="titleBarB"><strong>CC/Rate</strong></td>
		<td align="center" class="titleBarB"><strong>SP</strong></td>
		<td align="center" width="3%" class="titleBarB"><strong>Deal</strong></td>
		<td align="center" width="3%" class="titleBarB"><strong>Deal%</strong></td>
		<td align="center" class="titleBarB"style="color:green"><strong>Stock</strong></td>
		<td align="center" class="titleBarB"style="color:green"><strong>Sales</strong></td>
		<td align="center" class="titleBarB"><strong>Total price</strong></td>
		<td align="center" class="TitleBarB">Options</td>				 
	</tr>
			<?php 
			$total_stock = 0;
			$total_sales = 0;
			while($rasMember5 = $mydb->fetch_array($result))
			{
			    $id=$rasMember5['id'];
				$creditmemo_id=$rasMember5['creditmemo_id'];
				$medicine_name=$rasMember5['item_description'];
							
							
					?>
			
					<tr>
					  <td class="titleBarA" valign="top">
					  	<?php  
					  		$distributor_id=$mydb->getValue('distributor_id','tbl_creditmemo','id='.$creditmemo_id);
					  		echo $result1=$mydb->getValue('fullname','tbl_distributor','id='.$distributor_id);
							 $val=$mydb->getValue('invoice_eng_date','tbl_creditmemo','id='.$creditmemo_id);
					  	?>
						</td>
					  <td class="titleBarA" valign="top"><?php  echo $result1=$mydb->getValue('invoice_no','tbl_creditmemo','id='.$creditmemo_id);?></td>
						<td align="center" class="titleBarA" valign="top"><?php  echo $result1=date('d-m-Y',strtotime($val));?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $medicine_name;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['pack'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['batch_number'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo date('d-m-Y',strtotime($rasMember5['expiry_date']));?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['quantity'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['rate'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['sp_per_tab'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['deal'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['deal_percentage'];?></td>
					  <td align="center" class="titleBarA" valign="top" style="color:green"><?php echo $rasMember5['stock']; $total_stock = $total_stock+$rasMember5['stock']; ?></td>
					  <td align="center" class="titleBarA" valign="top" style="color:green"><?php echo $rasMember5['sales']; $total_sales = $total_sales+$rasMember5['sales']; ?></td>
					  <?php $roudnum=$rasMember5['total_price'];

					  ?>
					  <td align="center" class="titleBarA" valign="top"><?php echo round($roudnum,2);?></td>					 
					  <td class="titleBarA" valign="top" align="center"><a href="<?php echo ADMINURLPATH; ?>stock_edit&id=<?php echo $id;?>"><img src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a>
					  <a href="javascript:void(0);" onclick="callDelete('<?php echo $id; ?>')"><img src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a>
					  <a href="javascript:void(0);" onclick="callReturn('<?php echo $id; ?>')"><img src="images/stock_return.jpg" alt="stock_return" width="24" height="24" title="stock Return"></a>

					</td>
					</tr>
					<?php			
					}
					?>

					<tr>
					  <td colspan="12" class="titleBarB" valign="top">&nbsp;</td>
					  <td align="center" class="titleBarB" valign="top"><strong><?php echo $total_stock;?></strong></td>
					  <td align="center" class="titleBarB" valign="top"><strong><?php echo $total_sales;?></strong></td>
					  <td align="center" class="titleBarB" valign="top"><strong><?php echo $total_stock-$total_sales;?></strong></td>					 
					  <td align="center" class="titleBarB" valign="top">&nbsp;</td>
					</tr>
					<?php
				}
					?>
</table>




<?php if(isset($_GET['invoice_id']))
 {
	$tbl_creditmemo=$_GET['invoice_id'];
    $invoice_number = $mydb->getValue('invoice_no','tbl_creditmemo','id='.$tbl_creditmemo);
	$query = 'SELECT s.*,c.invoice_no,c.distributor_id,c.invoice_eng_date FROM tbl_stock s, tbl_creditmemo c WHERE s.creditmemo_id = c.id AND invoice_no="'.$invoice_number.'"';

    $result = mysql_query($query);
       


	?>
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
     
			<tr>
				  <td width="15%" valign="top" class="titleBarB"><strong>Distributor</strong></td>	 
				  <td width="7%" class="titleBarB"><strong>Invoice No</strong></td>	
				  <td width="9%" class="titleBarB"><strong>Recieved Date</strong></td>
				  <td width="10%" class="titleBarB"><strong>Item description</strong></td>
				  <th align="center" class="titleBarB"><strong>Pack(No. of tab)</strong></th>
				  <td align="center" class="titleBarB"><strong>Batch number</strong></td>
				  <th align="center" class="titleBarB"><strong>Expiry_date</strong></th>
				  <th align="center" class="titleBarB" ><strong>Quantity</strong></th>
				  <th align="center" class="titleBarB"><strong>CC/Rate</strong></th>
					<th align="center" class="titleBarB"><strong>Deal</strong></th>
					<th align="center" class="titleBarB"><strong>Deal%</strong></th>
					<th align="center" class="titleBarB"><strong>Total Qty(Pack*Qty)</strong></th>
					<th align="center" class="titleBarB"><strong>Total price</strong></th>
					<th align="center" class="titleBarB"><strong>Options</strong></th>
				 
			</tr>

			<?php while($rasMember5 = mysql_fetch_array($result))
						{		

							$stock_id = $rasMember5['id'];
							$creditmemo_id=$rasMember5['creditmemo_id'];
							$medicine_name=$rasMember5['item_description'];
							 $val=$mydb->getValue('invoice_eng_date','tbl_creditmemo','id='.$creditmemo_id);
					?>
			
					<tr>
						<td class="titleBarA" valign="top">
							  	<?php  
							  		$distributor_id=$mydb->getValue('distributor_id','tbl_creditmemo','id='.$creditmemo_id);
							  		echo $va1=$mydb->getValue('fullname','tbl_distributor','id='.$distributor_id);
							  	?>
						</td>
					  	 
					  <td align="center" class="titleBarA" valign="top"><?php  echo $result1=$mydb->getValue('invoice_no','tbl_creditmemo','id='.$creditmemo_id);?></td>
					  <td align="center" class="titleBarA" valign="top"><?php  echo $result1=date('d-m-Y',strtotime($val));?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $medicine_name;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['pack'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['batch_number'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo date('d-m-Y',strtotime($rasMember5['expiry_date']));?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['quantity'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['rate'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['deal'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['deal_percentage'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['stock'];?></td>
					 <?php $roudnum=$rasMember5['total_price'];

					  ?>
					  <td align="center" class="titleBarA" valign="top"><?php echo round($roudnum,2);?></td>
					  <td align="center" class="titleBarA" valign="top" align="center"><a href="<?php echo ADMINURLPATH;?>stock_edit&id=<?php echo $stock_id;?>"><img src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a> <a href="javascript:void(0);" onclick="callDeletestock('<?php echo $stock_id; ?>')"><img src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a>
					    <a href="javascript:void(0);" onclick="callReturn('<?php echo $id; ?>')"><img src="images/stock_return.jpg" alt="stock_return" width="24" height="24" title="stock Return"></a></td>
					</tr>
					<?php			
					}
				}
					?>
</table>    


<?php if(isset($_GET['distributor_list']))
 {
	$dist_id=$_GET['distributor_list'];


    $result_val = $mydb->getQuery('*','tbl_creditmemo','distributor_id='.$dist_id);

  
       

	?>
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
     
			<tr>
				  <td width="15%" valign="top" class="titleBarB"><strong>Distributor</strong></td>	 
				  <td width="7%" class="titleBarB"><strong>Invoice No</strong></td>	
				  <td width="9%" class="titleBarB"><strong>Recieved Date</strong></td>
				  <td width="10%" class="titleBarB"><strong>Item description</strong></td>
				  <th align="center" class="titleBarB"><strong>Pack(No. of tab)</strong></th>
				  <td align="center" class="titleBarB"><strong>Batch number</strong></td>
				  <th align="center" class="titleBarB"><strong>Expiry_date</strong></th>
					<th align="center" class="titleBarB" ><strong>Quantity</strong></th>
					<th align="center" class="titleBarB"><strong>CC/Rate</strong></th>
					<th align="center" class="titleBarB"><strong>Deal</strong></th>
					<th align="center" class="titleBarB"><strong>Deal%</strong></th>
					<th align="center" class="titleBarB"><strong>Total Qty(Pack*Qty)</strong></th>
					<th align="center" class="titleBarB"><strong>Total price</strong></th>
					<th align="center" class="titleBarB"><strong>Options</strong></th>
				 
			</tr>

			<?php while($rasMember6 = mysql_fetch_array($result_val))
						{		

							$invoice_number = $rasMember6['invoice_no'];
							$query = 'SELECT s.*,c.invoice_no,c.distributor_id,c.invoice_eng_date FROM tbl_stock s, tbl_creditmemo c WHERE s.creditmemo_id = c.id AND invoice_no="'.$invoice_number.'"';
							$result = mysql_query($query);

							while($rasMember5 = mysql_fetch_array($result))
							{		

								$stock_id = $rasMember5['id'];
								$creditmemo_id=$rasMember5['creditmemo_id'];
								$medicine_name=$rasMember5['item_description'];
								 $val=$mydb->getValue('invoice_eng_date','tbl_creditmemo','id='.$creditmemo_id);
						?>
			
					<tr>
						<td class="titleBarA" valign="top">
							  	<?php  
							  		//$distributor_id=$mydb->getValue('distributor_id','tbl_creditmemo','id='.$creditmemo_id);
							  		echo $va1=$mydb->getValue('fullname','tbl_distributor','id='.$dist_id);
							  	?>
						</td>
					  	 
					  <td align="center" class="titleBarA" valign="top"><?php  echo $result1=$mydb->getValue('invoice_no','tbl_creditmemo','id='.$creditmemo_id);?></td>
					  <td align="center" class="titleBarA" valign="top"><?php  echo $result1=date('d-m-Y',strtotime($val));?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $medicine_name;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['pack'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['batch_number'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo date('d-m-Y',strtotime($rasMember5['expiry_date']));?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['quantity'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['rate'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['deal'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['deal_percentage'];?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['stock'];?></td>
					 <?php $roudnum=$rasMember5['total_price'];

					  ?>
					  <td align="center" class="titleBarA" valign="top"><?php echo round($roudnum,2);?></td>
					  <td align="center" class="titleBarA" valign="top" align="center"><a href="<?php echo ADMINURLPATH;?>stock_edit&id=<?php echo $stock_id;?>"><img src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a> <a href="javascript:void(0);" onclick="callDeletestock('<?php echo $stock_id; ?>')"><img src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a></td>
					</tr>
					<?php	
						}		
					}
				}
					?>
</table>    






