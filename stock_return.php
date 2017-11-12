<?php /**
 * @Author: Madmax
 * @Date:   2016-03-21 15:04:42
 * @Last Modified by:   Madmax
 * @Last Modified time: 2016-03-21 15:56:53
 */ 

if(isset($_GET['stock_return_id'])) {
        $uid = $_GET['stock_return_id'];
        $creditmemo_id = $mydb->getValue('creditmemo_id', 'tbl_stock', 'id=' . $uid);
        $distributor_id = $mydb->getValue('distributor_id', 'tbl_creditmemo', 'id=' . $creditmemo_id);
        	$rascreditmemo = $mydb->getArray('*','tbl_creditmemo','id='.$creditmemo_id);
        $rasstock = $mydb->getArray('*','tbl_stock','id='.$uid);

    }

?>
<form action="" method="post" name="stock_edit">
  <table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl">
	<tr class="TitleBar">
      <td class="TtlBarHeading" colspan="2">Update Stock</td>
		<div style="float:right"></div></td>
		
    </tr>		
	<?php if(isset($_GET['message'])){?>
	<tr>
	  <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
	</tr>
	<?php } ?>    
	<tr>
	  <td width="17%" align="right" class="TitleBarA"><strong>Distributor:</strong></td>
		  <td class="TitleBarA">
		  	<select name="distributor_id" id="distributor_id" style="width:30%" readonly>  
			   			<option>---</option>
			   			<?php 
							$result = $mydb->getQuery('*','tbl_distributor');
							$distributor_name_from_db=$mydb->getValue('fullname','tbl_distributor','id='.$distributor_id);
							while($rasMember = $mydb->fetch_array($result))
							{
								$distributor_id = $rasMember['id'];
								$distributor_name = $rasMember['fullname']; 
								
								
								if($distributor_name_from_db == $distributor_name){
						?>
							<option value ='<?php echo "$distributor_id";?>' selected ><?php echo $distributor_name; ?></option>								
						<?php }else{ ?>
							<option value ='<?php echo "$distributor_id";?>'><?php echo $distributor_name; ?></option>														
					<?php 		}
							} ?>	
			</select>
		  </td>
	</tr>
	
	<tr>
	  <td align="right" class="TitleBarA"><strong>Invoice No:</strong></td>
	  <td class="TitleBarA"><input name="invoice_no" id="invoice_no" type="text" value="<?php echo $rascreditmemo['invoice_no'];?>" class="inputBox" style="width:50%" required/></td>
    </tr>
    
    <tr>
	  <td align="right" class="TitleBarA"><strong>Recieved Date:</strong></td>
	  <td class="TitleBarA"><input name="invoice_eng_date" id="invoice_eng_date"   value="<?php   $var1=$rascreditmemo['invoice_eng_date'];echo date('d-m-Y',strtotime($var1));?>" class="inputBox" style="width:50%" required/></td>
    </tr>
	
	<tr>
	  <td align="right" class="TitleBarA"><strong>Item description:</strong></td>
	  <td class="TitleBarA"><input name="item_description" id="item_description" type="text" value="<?php echo $rasstock['item_description'];?>" class="inputBox" style="width:50%" required/></td>
    </tr>
	
	
    <tr>
	  <td align="right" class="TitleBarA"><strong>Expiry Date:</strong></td>
	  <td class="TitleBarA"><input name="expiry_date" id="expiry_date"  value="<?php  $var2=$rasstock['expiry_date'];echo  date('d-m-Y',strtotime($var2)); ?>" class="inputBox" style="width:50%"/></td>
    </tr>
     <tr>
	  <td align="right" class="TitleBarA"><strong>Stock (Pack*Qty):</strong></td>
	  <td class="TitleBarA"><input name="stock" id="stock" type="text" class="inputBox" style="width:50%" value="<?php echo $rasstock['stock'];?>" readonly/></td>
    </tr>
     <tr>
	  <td align="right" class="TitleBarA"><strong>Remaining Quantity:</strong></td>
	  <td class="TitleBarA"><input name="quantity" id="quantity" type="text" value="<?php echo ($rasstock['stock']-$rasstock['sales']); ?>" class="inputBox" style="width:50%" readonly/></td>
    </tr>

    <tr>
	  <td align="right" class="TitleBarA"><strong>Return Quantity:</strong></td>
	  <td class="TitleBarA"><input name="quantity" id="quantity" type="text" value="<?php echo ($rasstock['stock']-$rasstock['sales']); ?>" class="inputBox" style="width:50%" onkeyup="mul()"/></td>
    </tr>


	<tr>
	  <td align="right" class="TitleBarA"><strong>Total price:</strong></td>
	  <td class="TitleBarA"><input name="total_price" id="total_price" type="text" class="inputBox" style="width:50%" value="<?php echo $rasstock['total_price'];?>" readonly/></td>
    </tr> 
    <tr>
	  <td align="right" class="TitleBarA"><strong>Return price :</strong></td>
	  <td class="TitleBarA"><input name="total_price" id="total_price" type="text" class="inputBox" style="width:50%"/></td>
    </tr>    
	<tr>
	  <td align="right" class="TitleBarA">&nbsp;</td>
	  <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnDo" type="submit" value="Return" class="button" /></td>
	</tr>
  </table>
</form>

