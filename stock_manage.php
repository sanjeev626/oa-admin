<?php
if(isset($_GET['id']))
{
	$uid = $_GET['id'];
	$do = "Update";
}
else
{
	$uid = 0;
	$do = "Add";
}

if(isset($_POST['btnDo']) && $_POST['btnDo']=='Add')
{	
	$distributor_id = $_POST['distributor_id'];
	$invoice_no = $_POST['invoice_no'];

	if($mydb->getCount('id','tbl_creditmemo','distributor_id="'.$distributor_id.'" AND invoice_no="'.$invoice_no.'"')==0)
	{
		$engdate1=$_POST['invoice_eng_date'];		
		$engdate=date("Y-m-d",strtotime($engdate1));		
		list($yy,$mm,$dd) = explode("-", $engdate);		

		$convert=$nepalicalendar->eng_to_nep($yy,$mm,$dd);
		$eng_to_nep = implode("-", $convert);			

		$data='';
		$data['distributor_id'] = $_POST['distributor_id'];
		$data['invoice_no'] = $_POST['invoice_no'];
		$data['invoice_eng_date'] =$engdate;
		$data['invoice_nepali_date']=$eng_to_nep;
		$data['total_amount']=$_POST['total_amount'];
		$data['discount_amount']=$_POST['discount_amount'];
		$data['vat_amount']=$_POST['vat_amount'];
		$data['grand_amount']=$_POST['grand_amount'];
		
		$creditmemo_id = $mydb->insertQuery('tbl_creditmemo',$data);
			
		for($j=0;$j<count($_POST['batch_number']);$j++)
		{			
			if(!empty($_POST['item_description'][$j]))
			{
				$med = trim($_POST['item_description'][$j]);
				$companyname = trim($_POST['item_company'][$j]);
				//Insert if new medicine is new
				if(!empty($companyname))
				{
					$checkCompany=$mydb->getCount('id','tbl_company','fullname="'.$companyname.'"');
					if($checkCompany==0)
					{
					 	$dataComp='';
					 	$dataComp['fullname']=$companyname;
					 	$company_id = $mydb->insertQuery('tbl_company',$dataComp);
					}
					else
					{
						$company_id = $mydb->getValue('id','tbl_company','fullname="'.$companyname.'"');
					}
				}
				else
				{					
					$company_id = $mydb->getValue('company_id','tbl_medicine','medicine_name="'.$med.'"');
				}

				$checkMedicine=$mydb->getCount('id','tbl_medicine','medicine_name="'.$med.'"');
				if($checkMedicine==0)
				{				 	
				 	$data3='';
				 	$data3['medicine_name']=$med;
				 	$data3['company_id']=$company_id;
				 	$medicine_id = $mydb->insertQuery('tbl_medicine',$data3);
				}
				else
				{
					$medicine_id = $mydb->getValue('id','tbl_medicine','medicine_name="'.$med.'"');
				}

				//Insert into Stock
				$data2='';
				$data2['creditmemo_id']= $creditmemo_id;
				$data2['batch_number']= $_POST['batch_number'][$j];
				$data2['medicine_id'] = $medicine_id;
				$data2['item_description']= $_POST['item_description'][$j];
				$data2['pack']=$_POST['pack'][$j];
				$expdate1=$_POST['expiry_date'][$j];
				$expiry_date=date("Y-m-d",strtotime($expdate1));
				$data2['expiry_date']=$expiry_date;
				$data2['quantity'] = $_POST['quantity'][$j];
				$data2['deal'] = $_POST['deal'][$j];

				if($_POST['vat_amount']>0)
					$data2['rate'] = $_POST['rate'][$j]*1.13;
				else
					$data2['rate'] = $_POST['rate'][$j];

				$data2['deal_percentage']=$_POST['deal_per'][$j];
				$data2['stock']=$_POST['total_qty'][$j];
				$data2['total_price']=$_POST['total_price'][$j];
				$data2['cp_per_tab']=($data2['total_price']/(($data2['quantity']+$data2['deal'])*$data2['pack']));

				// if(!empty($data['vat_amount'])||$data['vat_amount']!=0)
				// {
				// 	$data2['cp_per_tab']=$data['cp_per_tab']+($data2['cp_per_tab']*0.13);
				// }
				// if(empty($_POST['rate_sales'][$j])||$_POST['rate_sales'][$j]=='0')
				// {					
				// 	$data2['sp_per_tab']=($data2['cp_per_tab']+($data2['cp_per_tab']*0.16));
				// }
				// else
				// {					
				// 	$data2['sp_per_tab'] = $_POST['rate_sales'][$j];
				// }
				$data2['sp_per_tab'] = $_POST['rate_sales'][$j];				
				$stock_id = $mydb->insertQuery('tbl_stock',$data2);
				if($stock_id>0)
				{
					$message = "New medicine information Has been added.";
				}
				else
				{
					$message = "ERROR!! Failed to add medicine information.";
				}
				$url = ADMINURLPATH."stock_manage&message=".$message;
				$mydb->redirect($url);
			}
		}
	}
}

?>

<html>
	<head>
		<script src="//code.jquery.com/jquery-1.11.2.js"></script>

		<script src="jquery_ui/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css"/>

	</head>
<body>

<SCRIPT type="text/javascript">	
	$(document).ready(function()
	{
		$(".cc_rate").keyup(function()
		{
			var i=0;
			var arr=[];
			$('.price_total').each(function()
			{
		        var total_amt=Number($(this).val());									        
		        if(total_amt=='')
		        {
		        	arr[i++] =0;
		       	}
		       else
		        {
		       		arr[i++]=Number($(this).val());
		       	}									        
			});
				
		    var myTotal=0;				
			for(var j=0;j<arr.length;j++)
			{							
				myTotal=myTotal+arr[j];
			}					
			document.getElementById('total_amount').value = parseFloat(Math.round(myTotal* 100) / 100).toFixed(2);


			//for Grand Total			
			var discount_amount=document.getElementById('discount_amount').value;
			var vat_amount=document.getElementById('vat_amount').value;

			var grandtotal =parseFloat(myTotal);

			if (!isNaN(discount_amount) && !isNaN(vat_amount))
			{
				var grandtotal =parseFloat(myTotal) - parseFloat(discount_amount) + parseFloat(vat_amount);
			}

			if (isNaN(discount_amount) && !isNaN(vat_amount))
			{
				var grandtotal =parseFloat(myTotal) + parseFloat(vat_amount);
			}

			if (!isNaN(discount_amount) && isNaN(vat_amount))
			{
				var grandtotal =parseFloat(myTotal) - parseFloat(discount_amount);
			}

			document.getElementById('grand_amount').value = parseFloat(Math.round(grandtotal*100)/100).toFixed(0);
				
		});

		//if deal is defined
		$(".deal_per").keyup(function()
		{					
			var k=0;
			var arrval=[];

			$('.price_total').each(function()
			{
		        var total_amt=Number($(this).val());					        
		        if(total_amt=='')
		        {
		        	arrval[k++] =0;
		       	}
		        else
		        {
		       		arrval[k++]=Number($(this).val());					       	}
						        
			});
				
		    var myTotal=0;				
			for(var j=0;j<arrval.length;j++)
			{							
				myTotal=myTotal+arrval[j];
			}
		
			document.getElementById('total_amount').value = parseFloat(Math.round(myTotal* 100) / 100).toFixed(2);

			//for Grand Total			
			var discount_amount=document.getElementById('discount_amount').value;
			var vat_amount=document.getElementById('vat_amount').value;

			var grandtotal =parseFloat(myTotal);

			if (!isNaN(discount_amount) && !isNaN(vat_amount))
			{
				var grandtotal =parseFloat(myTotal) - parseFloat(discount_amount) + parseFloat(vat_amount);
			}

			if (isNaN(discount_amount) && !isNaN(vat_amount))
			{
				var grandtotal =parseFloat(myTotal) + parseFloat(vat_amount);
			}

			if (!isNaN(discount_amount) && isNaN(vat_amount))
			{
				var grandtotal =parseFloat(myTotal) - parseFloat(discount_amount);
			}

			document.getElementById('grand_amount').value = parseFloat(Math.round(grandtotal*100)/100).toFixed(0);					
		});
			
			
		$( "#invoice_eng_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
		$( ".expiry_date" ).datepicker({ dateFormat: 'dd-mm-yy' });

			 
		$("#invoice_no").keyup(function () { //user types username on inputfiled
			var invoice_no = $(this).val(); 
			if(invoice_no.length>=1)
			   {
			  	 var dataString ='invoice_info='+ invoice_no;
                   $.ajax({ 
                        url: "check_invoice.php",
                        data: dataString,
                        type: "POST",
                        
                        success: function(response)
                        {
                            var obj=$.parseJSON(response); 
                            //console.log(obj.value1);
                            
                             var result=obj.value2;
                             if(result=='no')
                             {
                             	$('#status').html(obj.value1);

                             	  $('#addsub').attr('disabled','disabled');
                             }
                             else
                             {
                             	$('#status').html(obj.value1);
                             	  $('#addsub').removeAttr('disabled');
                             }

                                            
                        }            

                    });
					}
				// alert(invoice_no.length);
					if(invoice_no.length == 0)
						{
							$('#status').html('');
						}

			});

			$(".inputitem").autocomplete
            ({
                source:'search.php',
                minLength:1,
                success:function(data){
                
                $('#result').html(data);
                }                   
            });

			$(".list_company").autocomplete
            ({
                source:'list_company.php',
                minLength:1,
                success:function(data){                
                $('#listCompany').html(data);
                }                   
            });
	});
</SCRIPT>




<form action="" method="post" name="stockinsert" onSubmit="return call_validate(this,0,this.length);">
	<table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl">

		<tr class="TitleBar">
	      <td class="TtlBarHeading" colspan="9"><?php echo $do;?> stock details</td>
	      <td class="TtlBarHeading" style="padding-bottom:15px;" colspan="5">
		  <input name="btnDo" type="submit" value="<?php echo $do;?>" class="button" />
		  
	    </tr>		

	<?php if(isset($_GET['message'])){?>
		<tr>
		  <td colspan="6" class="message"><?php echo $_GET['message']; ?></td>
		</tr>
	<?php } ?>   
	
		

		<tr>
			<td align="right" class="TitleBarA"><strong>Distributor ID : </strong></td>
			<td class="TitleBarA" colspan="9">
			  	<Select name="distributor_id" id="distributor_id"> 
		   			<option>---</option>
		   			<?php 
						$result = $mydb->getQuery('*','tbl_distributor','1=1 order by fullname');
						while($rasMember = $mydb->fetch_array($result))
						{
							$distributor_id = $rasMember['id'];
							$distributor_name = $rasMember['fullname']; 
					?>							
					<option value ='<?php echo "$distributor_id";?>'><?php echo $distributor_name; ?></option>								
				<?php } ?>	
				</select>
			</td>
				
   		</tr> 
   
   <tr>
	  <td width="17%" align="right" class="TitleBarA"><strong>INV NO : </strong></td>
	  <td class="TitleBarA" colspan="1"><input name="invoice_no" id="invoice_no" type="text"  class="inputBox" style="width:75%"/></td>
	  <td class="TitleBarA" colspan="8"><input name="hinumber" id="hid_num" type="hidden"><div id="status"></div></td>
	</tr>

	<tr>
	  <td align="right" class="TitleBarA"><strong>INV Date English : </strong></td>
	  <td class="TitleBarA" colspan="9"><input name="invoice_eng_date" id="invoice_eng_date" type="text" value="" class="inputBox" style="width:12%"/></td>
    </tr>   
	<tr>			
		<td class="TitleBarA" valign="top"><strong>Item Name</strong></td>
		<td class="TitleBarA" valign="top"><strong>Pack</strong><br><span style="font-size:11px; color:black;">No. of tabs</span></td>
		<td class="TitleBarA" valign="top"><strong>Batch</strong></td>			
		<td class="TitleBarA" valign="top"><strong>Expiry Date</strong></td>
		<td class="TitleBarA" valign="top"><strong>Quantity</strong><br><span style="font-size:11px; color:black;">No. of strip/ph</span></td>
		<td class="TitleBarA" valign="top"><strong>Cost Price</strong><br><span style="font-size:11px; color:black;">per strip</span></td>
		<td class="TitleBarA" valign="top"><strong>Sale Price</strong><br><span style="font-size:11px; color:black;">per piece</span></td>
		<td class="TitleBarA" valign="top"><strong>Deal</strong></td>
		<td class="TitleBarA" valign="top"><strong>Deal%</strong></td>
		<td class="TitleBarA" valign="top"><strong>Stock</strong></td>
		<td class="TitleBarA" valign="top"><strong>Total Price</strong></td>
	</tr>

		<?php 
			
		for($i=1;$i<16;$i++)
		{	
		?>
	<tr>	
		<td><input class="inputitem" type="text" name="item_description[]" id="item_description[]" placeholder="Medicine Name"><br><input class="list_company" type="text" name="item_company[]" id="item_company[]" placeholder="Company Name"></td>
		<div id="result"></div><div id="listCompany"></div>
		<td class="TitleBarA"><input  style="width:100px;" class="pack_list" type="text" id="txt_pack_<?php echo $i;?>" name="pack[]" onkeyup="mul(<?php echo $i;?>)" placeholder="No of Tabs"></td>
		<td class="TitleBarA"><input style="width:80px;" type="text" name="batch_number[]"></td>
		<td class="TitleBarA"><input style="width:100px;" type="text"  class= "expiry_date"  name="expiry_date[]"></td>
		<td class="TitleBarA"><input style="width:80px;" type="number" id="txt_qty_<?php echo $i;?>" name="quantity[]" onkeyup="mul(<?php echo $i;?>)" placeholder="Strip/Phyle"></td>
		<td class="TitleBarA"><input  style="width:80px;" class="cc_rate" type="text" name="rate[]" id="txt_rate_<?php echo $i;?>" onkeyup="mul(<?php echo $i;?>)" placeholder="per strip"><input  style="width:80px;" class="cc_rate" type="text" name="vatRate[]" id="vatRate_<?php echo $i;?>" onkeyup="mul(<?php echo $i;?>)" placeholder="with VAT"></td>
		<td class="TitleBarA"><input  style="width:80px;" class="cc_sales" type="text" name="rate_sales[]" id="txt_sales_<?php echo $i;?>" placeholder="per tab"></td>
		<td class="TitleBarA"><input  style="width:80px;" type="text" id="txt_deal_<?php echo $i;?>" name="deal[]" onkeyup="mul(<?php echo $i;?>)"></td>
		<td class="TitleBarA"><input  style="width:80px;" class="deal_per" type="text" id="txt_deal_per_<?php echo $i;?>" name="deal_per[]" onkeyup="mul(<?php echo $i;?>)"></td>
		<td class="TitleBarA"><input  style="width:80px;" type="text"  id="txt_total_qty_<?php echo $i;?>" name="total_qty[]"></td>
		<td class="TitleBarA"><input style="width:120px;" class="price_total" type="text"  id="txt_total_price_<?php echo $i;?>" name="total_price[]" ></td> 
	</tr>

<?php }?>


	
<script type="text/javascript">

	function mul(num)
	{
		if(num)
		{
			var num1=num;
	
			var txtPackNum=document.getElementById('txt_pack_'+num1).value;//1
			var txtQtyNum=document.getElementById('txt_qty_'+num1).value;//10
			var txtDealNum=document.getElementById('txt_deal_'+num1).value;//1
			var txtDealPer=document.getElementById('txt_deal_per_'+num1).value;//12
			var txtRate=document.getElementById('txt_rate_'+num1).value;//5
			var txtVat=document.getElementById('vat_amount').value;//5


			if (txtPackNum == ""){txtPackNum= 0;}
	   		if (txtQtyNum == ""){txtQtyNum = 0;}
	   		if (txtDealNum == ""){txtDealNum = 0;}
	   		if (txtDealPer==""){txtDealPer=0;}
	   		if (txtRate==""){txtRate=0;}
				//to derive total number of tablet
			var result1 = parseFloat(txtQtyNum)+parseFloat(txtDealNum);//10+1=11
			var result=parseFloat(result1) * parseFloat(txtPackNum);//1*11=11
			//to derive total cost
			var qtyrate =parseFloat(txtQtyNum)* parseFloat(txtRate);//10*5=50
		   	var value=(parseFloat(txtDealPer)*parseFloat(txtRate)/100)*parseFloat(txtDealNum);//((12*5)/100)*12
		   	var sale_price = (parseFloat(txtRate)*1.16)/parseFloat(txtPackNum);
		   	if(parseFloat(txtVat)>0)
		   	{
		   		sale_price = (parseFloat(txtRate) * 1.13 * 1.16)/parseFloat(txtPackNum);
		   		document.getElementById('vatRate_'+num1).value = parseFloat(Math.round(parseFloat(txtRate) * 1.13 * 100)/100).toFixed(2);
		   	}


			
			
	  		if (!isNaN(result)) 
	  		{
	      		document.getElementById('txt_total_qty_'+num1).value =parseFloat(Math.round(result* 100) / 100).toFixed(2);
	   		}

	   		var finvalue=parseFloat(value)+parseFloat(qtyrate);//50*

	   		if (!isNaN(finvalue))
	   		{
	      		document.getElementById('txt_total_price_'+num1).value = parseFloat(Math.round(finvalue* 100) / 100).toFixed(2);
	      	}

	      	if (!isNaN(sale_price)) 
	  		{
	      		document.getElementById('txt_sales_'+num1).value =parseFloat(Math.round(sale_price* 100) / 100).toFixed(2);
	   		}
		}			
	}
</script>



	<tr>
			 <td align="right" class="TitleBarA"><strong>Total Amount:</strong></td>
			 <td class="TitleBarA" colspan="9"><input type="text" id="total_amount" class="inputBox" name="total_amount" style="width:10%" onkeyup=sub()></td>
    </tr>
    <tr>
			 <td align="right" class="TitleBarA"><strong>Discount:</strong></td>
			 <td class="TitleBarA" colspan="9"><input type="text" id="discount_amount"  class="inputBox" name="discount_amount" value="0" style="width:10%" onkeyup=sub()></td>
    </tr>
    <tr>
			 <td align="right" class="TitleBarA"><strong>VAT:</strong></td>
			 <td class="TitleBarA" colspan="9"><input type="text" id="vat_amount"  class="inputBox" name="vat_amount" style="width:10%" onkeyup=sub()></td>
    </tr>
    <tr>
			 <td align="right" class="TitleBarA"><strong>Grand Total</strong></td>
			 <td class="TitleBarA" colspan="9"><input type="text" id="grand_amount" class="inputBox" name="grand_amount" style="width:10%"></td>
    </tr>	
	
	<tr>
	  <td align="right" class="TitleBarA">&nbsp;</td>
	  <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnDo" id="addsub" type="submit" value="<?php echo $do;?>" class="button" disabled/></td>
	</tr>
		
	<script type="text/javascript">
      function sub()
      {
      	var txttotalamount=document.getElementById('total_amount').value;
      	
        var txtdiscountamount=document.getElementById('discount_amount').value;
        var totalvat=document.getElementById('vat_amount').value;
       
         
		if(txttotalamount ==""){txttotalamount = 0.00;}
        if(txtdiscountamount ==""){txtdiscountamount = 0.00;}
        if(totalvat ==""){totalvat = 0.00;}
        var mid_amount=parseFloat(txttotalamount)-parseFloat(txtdiscountamount);
        var grand_amount=parseFloat(mid_amount)+parseFloat(totalvat);
         if (!isNaN(grand_amount)) 
         {

                  document.getElementById('grand_amount').value = parseFloat(Math.round(grand_amount* 100) / 100).toFixed(0);

          }

      }



  </script>
	</table>
</form>
</body>
</html>
