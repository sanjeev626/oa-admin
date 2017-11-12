<?php


	if(isset($_GET['id'])) {
        $uid = $_GET['id'];
        $creditmemo_id = $mydb->getValue('creditmemo_id', 'tbl_stock', 'id=' . $uid);
        $distributor_id = $mydb->getValue('distributor_id', 'tbl_creditmemo', 'id=' . $creditmemo_id);

    }
		if(isset($_POST['btnDo']))
		{	
			$data='';
			$check_v=0;
			$data['distributor_id']=$_POST['distributor_id'];
            $variable = $_POST['invoice_no'];

			$check_invoice_result = $mydb->getQuery('*','tbl_creditmemo');			
			
			
			while($q = $mydb->fetch_array($check_invoice_result))
				{	    									
							$check_invoice_no = $q['invoice_no'];	

							if($check_invoice_no == $variable){
								$check_v++;
							}
				}
            if($check_v>0){

            	$_disvat=$mydb->getArray('discount_amount,vat_amount','tbl_creditmemo','invoice_no="'.$variable.'"');
				$creditmemo_id_value = $mydb->getValue('id','tbl_creditmemo','invoice_no="'.$variable.'"');
				$update_invoice_value = "UPDATE tbl_stock SET creditmemo_id = '".$creditmemo_id_value."' WHERE id='".$uid."'";				
				mysql_query($update_invoice_value);

			}else{
				$data['invoice_no']=$_POST['invoice_no'];
			}
			$data['invoice_eng_date']=date('Y-m-d',strtotime($_POST['invoice_eng_date']));
			$mydb->updateQuery('tbl_creditmemo',$data,'id='.$creditmemo_id);

			$data="";					
			$data['expiry_date']=date('Y-m-d',strtotime($_POST['expiry_date']));
			$data['rate']=$_POST['rate'];
			$sales_rate=$_POST['sales_rate'];
			$data['deal_percentage']=$_POST['deal_percentage'];
			$data['deal']=$_POST['deal'];
			$data['total_price']=$_POST['total_price'];
			$data['stock']=$_POST['stock'];
			$data['pack']=$_POST['pack'];
			$data['quantity']=$_POST['quantity'];
			$data['item_description']=$_POST['item_description'];
			$data['batch_number']=$_POST['batch_number'];
			$data['cp_per_tab']=($data['total_price']/(($data['quantity']+$data['deal'])*$data['pack']));
			if($sales_rate=='0')
			{
				$data['sp_per_tab']=($data['cp_per_tab']+($data['cp_per_tab']*0.16));
			}
			else{
				$data['sp_per_tab']=$sales_rate;
			}

			if(isset($_GET['do']) && $_GET['do']=="purchase_return")
    		{
				$stock=$_POST['stock'];
				$sales=$_POST['sales'];
				$balance = $_POST['stock']-$_POST['sales'];

				$total_sales = $_POST['sales'] + $_POST['return_quantity'];
				if($balance>=$total_sales)
					$data['sales'] = $total_sales;

				$data['return_quantity']=$_POST['return_quantity'];
				$data['return_date']=$_POST['return_date'];
				$data['return_amount']=$_POST['return_amount'];
    		}
			
			$message = $mydb->updateQuery('tbl_stock',$data,'id='.$uid);
			
			/**
			 * update of tbl_creditmemo 
			 * 
			 */
			$price_of=$mydb->getSum('total_price','tbl_stock','creditmemo_id="'.$creditmemo_id.'"');
			$data1='';
			$data1['total_amount']=$price_of;
			 $data1['grand_amount']=($price_of+$_disvat['discount_amount'])+$_disvat['total_price'];
			$mydb->updateQuery('tbl_creditmemo',$data1,'id='.$creditmemo_id);
		

			$url = ADMINURLPATH."stock_edit&id=".$uid."&message=".$message;
			if(isset($_GET['do']))
				$url.="&do=purchase_return";
			$mydb->redirect($url);
			
		}


	$rasstock = $mydb->getArray('*','tbl_stock','id='.$uid);
	
	$rascreditmemo = $mydb->getArray('*','tbl_creditmemo','id='.$creditmemo_id);
	
	$rasdistributor = $mydb->getArray('*','tbl_distributor','id='.$distributor_id);

?>
<script type="text/javascript">


	$(document).ready(function() {
		$( "#invoice_eng_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
		$( "#expiry_date" ).datepicker({ dateFormat: 'dd-mm-yy' });

	});
</script>

<form action="" method="post" name="stock_edit">
  <table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl">
	<tr class="TitleBar">
      <td class="TtlBarHeading" colspan="2">Update Stock</td>
		<div style="float:right"></div></td>
		<td class="TtlBarHeading" style="width:50px;"><input name="btnAdd" type="button" value="Back" class="button" onClick="window.location='<?php echo ADMINURLPATH;?>stock'" /></td>
    </tr>		
	<?php if(isset($_GET['message'])){?>
	<tr>
	  <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
	</tr>
	<?php } ?>    
	<tr>
	  <td width="17%" align="right" class="TitleBarA"><strong>Distributor:</strong></td>
		  <td class="TitleBarA">
		  	<select name="distributor_id" id="distributor_id" style="width:30%">  
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
							<option value ='<?php echo "$distributor_id";?>' selected><?php echo $distributor_name; ?></option>								
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
	  <td align="right" class="TitleBarA"><strong>Pack(No. of tab):</strong></td>
	  <td class="TitleBarA"><input name="pack" id="pack" type="text" value="<?php echo $rasstock['pack'];?>" class="inputBox" style="width:50%" onkeyup="mul()"/></td>
    </tr>
	
	<tr>
	  <td align="right" class="TitleBarA"><strong>Batch No:</strong></td>
	  <td class="TitleBarA"><input name="batch_number" id="batch_number" type="text" value="<?php echo $rasstock['batch_number']; ?>" class="inputBox" style="width:50%"/></td>
    </tr>
    
    <tr>
	  <td align="right" class="TitleBarA"><strong>Expiry Date:</strong></td>
	  <td class="TitleBarA"><input name="expiry_date" id="expiry_date"  value="<?php  $var2=$rasstock['expiry_date'];echo  date('d-m-Y',strtotime($var2)); ?>" class="inputBox" style="width:50%"/></td>
    </tr>

    <tr>
	  <td align="right" class="TitleBarA"><strong>Quantity:</strong></td>
	  <td class="TitleBarA"><input name="quantity" id="quantity" type="text" value="<?php echo $rasstock['quantity']; ?>" class="inputBox" style="width:50%" onkeyup="mul()"/></td>
    </tr>

    <tr>
	  <td align="right" class="TitleBarA"><strong>CC/Rate:</strong></td>
	  <td class="TitleBarA"><input name="rate" id="rate" type="text" value="<?php echo $rasstock['rate']; ?>" class="inputBox" style="width:50%" onkeyup="mul()"/></td>
    </tr>

     <tr>
	  <td align="right" class="TitleBarA"><strong>sales per :</strong></td>
	  <td class="TitleBarA"><input name="sales_rate" id="sales_rate" type="text" value="<?php echo $rasstock['sp_per_tab']; ?>" class="inputBox" style="width:50%"/></td>
    </tr>

    <tr>
	  <td align="right" class="TitleBarA"><strong>Deal:</strong></td>
	  <td class="TitleBarA"><input name="deal" id="deal" type="text" value="<?php echo $rasstock['deal']; ?>" class="inputBox" style="width:50%" onkeyup="mul()"/></td>
    </tr>

    <tr>
	  <td align="right" class="TitleBarA"><strong>Deal %:</strong></td>
	  <td class="TitleBarA"><input name="deal_percentage" id="deal_percentage" type="text" value="<?php echo $rasstock['deal_percentage']; ?>" class="inputBox" style="width:50%" onkeyup="mul()"/></td>
    </tr>
    
    <script type="text/javascript">
		function mul()
		{
			 						
			var txtPackNum=document.getElementById('pack').value;
			var txtQtyNum=document.getElementById('quantity').value;
			var txtDealNum=document.getElementById('deal').value;
			var txtDealPer=document.getElementById('deal_percentage').value;
			var txtRate=document.getElementById('rate').value;

			if (txtPackNum == ""){txtPackNum= 0;}
       		if (txtQtyNum == ""){txtQtyNum = 0;}
       		if (txtDealNum == ""){txtDealNum = 0;}
       		if(txtDealPer==""){txtDealPer=0;}
       		if(txtRate==""){txtRate=0;}
       		//to derive total number of tablet
			 var result1 = parseFloat(txtQtyNum)+parseFloat(txtDealNum);
			 var result=parseFloat(result1) * parseFloat(txtPackNum);
			//to derive total cost
			var qtyrate =parseFloat(txtQtyNum)* parseFloat(txtRate);
			 var value=(parseFloat(txtDealPer)*parseFloat(txtRate)/100)*parseFloat(txtDealNum);
			
			

      		 if (!isNaN(result)) {
          			 document.getElementById('stock').value = Math.round(result);
       				}

       		var finvalue=parseFloat(value)+parseFloat(qtyrate);

       		if (!isNaN(finvalue)) {
          			 document.getElementById('total_price').value = Math.round(finvalue);
       				}						
			
			
		}
	</script>
    <tr>
	  <td align="right" class="TitleBarA"><strong>Stock (Pack*Qty):</strong></td>
	  <td class="TitleBarA"><input name="stock" id="stock" type="text" class="inputBox" style="width:50%" value="<?php echo $rasstock['stock'];?>" readonly/></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Total price:</strong></td>
	  <td class="TitleBarA"><input name="total_price" id="total_price" type="text" class="inputBox" style="width:50%" value="<?php echo $rasstock['total_price'];?>" readonly/></td>
    </tr>  
    <?php if(isset($_GET['do']) && $_GET['do']=="purchase_return")
    {
    	$stock = $rasstock['stock'];
    	$sales = $rasstock['sales'];
    	$balance = $stock - $sales;
	?>
    <tr>
	  <td class="TitleBarB" colspan="2"><strong>Purchase Return:</strong></td>
    </tr>
    <tr>
	  <td align="right" class="TitleBarA"><strong>Balance Quantity:</strong></td>
	  <td class="TitleBarA"><?php echo $balance;?></td>
    </tr>
    <tr>
	  <td align="right" class="TitleBarA"><strong>Return Quantity:</strong></td>
	  <td class="TitleBarA">
	  	<input name="sales" id="sales" type="hidden" value="<?php echo $rasstock['sales'];?>"/>
	  	<input name="return_quantity" id="return_quantity" type="text" class="inputBox" style="width:50%" value="<?php echo $rasstock['return_quantity'];?>"/>
	  </td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Return Date:</strong></td>
	  <td class="TitleBarA"><input name="return_date" id="return_date" type="text" class="inputBox" style="width:50%" value="<?php echo $rasstock['return_date'];?>" /></td>
    </tr> 
	<tr>
	  <td align="right" class="TitleBarA"><strong>Return Amount:</strong></td>
	  <td class="TitleBarA"><input name="return_amount" id="return_amount" type="text" class="inputBox" style="width:50%" value="<?php echo $rasstock['return_amount'];?>" /></td>
    </tr> 
	<?php
    }  
    ?>
	<tr>
	  <td align="right" class="TitleBarA">&nbsp;</td>
	  <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnDo" type="submit" value="Update" class="button" /></td>
	</tr>
  </table>
</form>
