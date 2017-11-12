<?php 
//if(isset($_GET['invoice_id']))
 {
	$tbl_creditmemo=$_GET['invoice_id'];
    $invoice_number = $mydb->getValue('invoice_no','tbl_creditmemo','id='.$tbl_creditmemo);
	$query = 'SELECT s.*,c.invoice_no,c.distributor_id,c.invoice_eng_date FROM tbl_stock s, tbl_creditmemo c WHERE s.creditmemo_id = c.id';

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