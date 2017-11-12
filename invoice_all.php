<?php 
//if(isset($_GET['invoice_id']))
 {
	$query = 'SELECT s.*,c.invoice_no,c.distributor_id,c.invoice_eng_date FROM tbl_stock s, tbl_creditmemo c WHERE s.creditmemo_id = c.id';

    $result = mysql_query($query);
    $result = $mydb->getQuery('*','tbl_creditmemo');


	?>
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
     
			<tr>
				  <td width="15%" valign="top" class="titleBarB"><strong>Distributor</strong></td>	 
				  <td width="7%" class="titleBarB"><strong>Invoice No</strong></td>	
				  <td width="9%" class="titleBarB"><strong>Recieved Date</strong></td>
				  <td width="10%" class="titleBarB"><strong>Total Amount</strong></td>
				  <th align="center" class="titleBarB"><strong>Discount Amount</strong></th>
				  <td align="center" class="titleBarB"><strong>Vat Amount</strong></td>
				  <th align="center" class="titleBarB"><strong>Amount</strong></th>
				 
			</tr>

			<?php 
			while($rasInvoice = mysql_fetch_array($result))
			{
				?>		
				<tr>
				  <td class="titleBarA" valign="top"><?php echo $mydb->getValue('fullname','tbl_distributor','id='.$rasInvoice['distributor_id']);?></td>					  	 
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasInvoice['invoice_no'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasInvoice['invoice_eng_date'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasInvoice['total_amount'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasInvoice['discount_amount'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasInvoice['vat_amount'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasInvoice['grand_amount'];?></td>
				</tr>
				<?php			
				}
				}
			?>
</table>