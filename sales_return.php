<?php
if(isset($_GET['sales_id']))
{
    $sales_id = $_GET['sales_id'];
    $rasSales = $mydb->getArray('client_id, discount_percentage, discount_amount, net_amount','tbl_sales','id='.$sales_id); 
    $result = $mydb->getQuery('*','tbl_order','sales_id='.$sales_id);//extract order data from tbl_order
    $count = mysql_num_rows($result);//count no of rows in tbl_order
    // $discount = $mydb->getValue('discount_percentage','tbl_sales','id='.$sales_id);
}

if(isset($_POST['btnReturn']))
{
  //print_r($_POST);
  $message="";
  $totalAmount=0;
  $discountPercentage = $rasSales['discount_percentage'];

  //Blank Entry in sales return table
  $data_sales_return='';
  $data_sales_return['sales_id']=$sales_id;
  $data_sales_return['client_id']=$rasSales['client_id'];
  $data_sales_return['return_date']=$_POST['return_date'];
  $sales_return_id = $mydb->insertQuery('tbl_sales_return',$data_sales_return); 

  for($i=0;$i<count($_POST['order_id']);$i++)
  {
    $return_quantity = $_POST['return_quantity'][$i];
    $previous_quantity = $_POST['previous_quantity'][$i];
    $medicine_id = $_POST['medicine_id'][$i]; 
    $medicine_name = $mydb->getValue('medicine_name','tbl_medicine','id='.$medicine_id);
    if(!empty($return_quantity) && $return_quantity>0)
    {
      if($return_quantity > $previous_quantity)
      {
        $message .= "<br>Return quantity for ".$medicine_name." cannot be greater than ordered quantity";
      }
      else
      {        
        $stock_id = $_POST['stock_id'][$i];
        $previous_return_quantity = $_POST['previous_return_quantity'][$i];
        $order_id = $_POST['order_id'][$i];
        $rate = $_POST['rate'][$i]; 
        $amount_temp = ($previous_return_quantity+$return_quantity)*$rate;
        $amount = $amount_temp-($discountPercentage*$amount_temp/100);
        $totalAmount+=$amount;
        //echo "<br>";echo "<br>";
        //echo 'order_id='.$order_id.' --> return_quantity='.$return_quantity.' --> medicine_id='.$medicine_id.' --> rate='.$rate.' --> amount_temp='.$amount_temp;
        //echo "<br>";

        //update in tbl_order
        $data_order='';
        $data_order['return_quantity']=$previous_return_quantity+$return_quantity;
        $data_order['refund_amount']=$amount;
        $mydb->updateQuery('tbl_order',$data_order,'id='.$order_id);
        //UPDATE tbl_order ends Here

        //UPDATE in tbl_stock
        $previous_sales = $mydb->getValue('sales','tbl_stock','id='.$stock_id);
        $new_sales = $previous_sales-$return_quantity;
        $data_stock="";
        $data_stock['sales']=$new_sales;
        $mydb->updateQuery('tbl_stock',$data_stock,'id='.$stock_id);
        //UPDATE tbl_stock ends Here

        $data_sales_return_medicine='';
        $data_sales_return_medicine['sales_id']=$sales_id;
        $data_sales_return_medicine['sales_return_id']=$sales_return_id;
        $data_sales_return_medicine['order_id']=$order_id;
        $data_sales_return_medicine['medicine_id']=$medicine_id;
        $data_sales_return_medicine['quantity']=$return_quantity;
        $data_sales_return_medicine['rate']=$rate;
        $data_sales_return_medicine['amount']=$amount;
        $mydb->insertQuery('tbl_sales_return_medicine',$data_sales_return_medicine);
      }
    }
  }
    $data_sales_return='';
    $data_sales_return['total_sales_return_amount']=$totalAmount;
    $mydb->updateQuery('tbl_sales_return',$data_sales_return,'id='.$sales_return_id);
    if(isset($message) && !empty($message)) 
      $url = ADMINURLPATH.'sales_return&sales_id='.$sales_id.'&message='.$message;    
    else
    $url = ADMINURLPATH.'sales_return&sales_id='.$sales_id;        
    $mydb->redirect($url);
    //echo $totalAmount;
    //echo "<br>";
}

if(isset($_POST['btnupdate']))
{
  
  $salesid=$_POST['salesid'];

   foreach ($_POST as $key => $value)
   {
      if($key!="btnupdate"&&$key!="salesid")  
        $data[$key]=$value;
   }

   $mydb->updateQuery('tbl_sales',$data,'id='.$salesid);
    $url = ADMINURLPATH.'sales';    
    $mydb->redirect($url);
}
?>  

<form action="" method="POST" name="sales">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <?php if(isset($_GET['message'])){?>
    <tr>
      <td colspan="9" class="message"><?php echo $_GET['message']; ?></td>
      <!--if message is set ,display in top--> 
    </tr>
    <?php } ?>
    <tr class="TitleBar">
      <td colspan="8" class="TtlBarHeading">Sales
        <div style="float:right"></div></td>
         <td class="TtlBarHeading" style="width:50px;"><input name="btnAdd" type="button" value="Back" class="button" onClick="window.location='<?php echo ADMINURLPATH;?>sales'" /></td>
    </tr>
<?php
if($count!= 0)//if there is data in tbl_order then it enters to loop;
{
	$total_amount=0;
?>
  <tr>
    <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
    <td width="8%" valign="top" class="titleBarB" align="center"><strong>Quantity</strong></td>
    <td width="5%" valign="top" class="titleBarB" align="center"><strong>Return</strong></td>
    <td valign="top" class="titleBarB" align="center"><strong>Medicine Name</strong></td>
    <td width="10%" valign="top" class="titleBarB" align="center"><strong>Batch</strong></td>
    <td width="10%" valign="top" class="titleBarB" align="center"><strong>Expiry Date</strong></td>
    <td width="10%" valign="top" class="titleBarB" align="right"><strong>Rate </strong></td>
    <td width="10%" valign="top" class="titleBarB" align="center"><strong>Total Amount</strong></td>
    <td width="2%" valign="top" classs="titleBarB" align="center"><strong></strong></td>
  </tr>
  <?php
			

	$counter = 0;
	while($rasOrder = $mydb->fetch_array($result))
	{
    $medicine_name = $mydb->getValue('medicine_name','tbl_medicine','id='.$rasOrder['medicine_id']);
    $amount = ($rasOrder['quantity']*$rasOrder['Rate']);
    $total_amount+=$amount;
    $stock_id = $rasOrder['stock_id'];
    $rasStock = $mydb->getArray('expiry_date,batch_number','tbl_stock','id='.$rasOrder['stock_id']);
    $expiry_date = $rasStock['expiry_date'];
    $batch_number = $rasStock['batch_number'];
	?>
  <tr>
    <td class="titleBarA" align="center" valign="top"><?php echo ++$counter;?></td>
    <td class="titleBarA" align="center" valign="top">
      <?php 
      echo $rasOrder['quantity']; 
      if(!empty($rasOrder['return_quantity'])) 
      {
        echo "(-".$rasOrder['return_quantity'].")"; 
        echo "<br><span style='color:red;'>(-Nrs. ".$rasOrder['refund_amount'].")</span>"; 
      }
      ?>
    </td>
    <td class="titleBarA" align="center" valign="top">
      <input type="hidden" name="medicine_id[]" id="medicine_id[]" value="<?php echo $rasOrder['medicine_id'];?>"/>
      <input type="hidden" name="stock_id[]" id="stock_id[]" value="<?php echo $rasOrder['stock_id'];?>"/>      
      <input type="hidden" name="previous_quantity[]" id="previous_quantity[]" value="<?php echo $rasOrder['quantity']-$rasOrder['return_quantity'];?>"/>
      <input type="text" name="return_quantity[]" id="return_quantity[]"/>
      <input type="hidden" name="order_id[]" id="order_id[]" value="<?php echo $rasOrder['id'];?>"/>
      <input type="hidden" name="previous_return_quantity[]" id="previous_return_quantity[]" value="<?php echo $rasOrder['return_quantity'];?>"/>
    </td>
    <td class="titleBarA" valign="top"><?php echo $medicine_name;?></td>
    <td class="titleBarA" valign="top"><?php echo $batch_number;?></td>
    <td class="titleBarA" valign="top"><?php echo $expiry_date;?></td>
    <td class="titleBarA" valign="top" align="right">
      <?php echo $rasOrder['Rate'];?>
      <input type="hidden" name="rate[]" id="rate[]" value="<?php echo $rasOrder['Rate'];?>"/>

    </td>
    <td class="titleBarA" valign="top" align="right"><?php echo $amount; ?></td>
    <td class="titleBarA" Valign="top"></td>
  </tr>
  <?php		
  }
  ?>
  <tr>
    <td class="titleBarA"></td>
    <td class="titleBarA"></td>
    <td class="titleBarA">
      <input name="return_date" type="text" id="return_date" placeholder="yyyy-mm-dd" style="margin-bottom:5px;">
      <input type="submit" name="btnReturn" id="btnReturn" value="Return" class="button">
      <input name="salesid" type="hidden" id="salesid" value="<?php echo $sales_id;?>">
    </td>
    <td class="titleBarA" colspan="4" style="text-align:right;">Total:</td>
    <td class="titleBarA" valign="top" align="right"><div id="val"><?php echo ($round_total_amount=$total_amount);?></div><input name="total_amount" type="hidden" id="total_amt" value="<?php echo $round_total_amount=$total_amount;?>"></td>
    <td class="titleBarA" Valign="top"></td>
  </tr> 
  <tr>
      <td class="titleBarA" colspan="7" style="text-align:right;">Discount Percentage: </td>
      <td class="titleBarA" valign="top" align="right">
        <?php echo $rasSales['discount_percentage'];?></td>
      <td class="titleBarA" Valign="top"></td>
  </tr>
  <tr>
      <td class="titleBarA" colspan="7" style="text-align:right;">Discount Amount : </td>
      <td class="titleBarA" valign="top" align="right"><?php echo $mydb->getValue('discount_amount','tbl_sales','id='.$sales_id);?></td>
      <td class="titleBarA" Valign="top"></td>
    </tr> 
    <tr> 
      <td class="titleBarA" colspan="7" style="text-align:right;">Grand Total : </td>
      <td class="titleBarA" valign="top" align="right">
        <?php echo $mydb->getValue('net_amount','tbl_sales','id='.$sales_id);?><br>
        <?php $order_stat=$mydb->getValue('order_status','tbl_sales','id='.$sales_id);?>
        </td>
      <td class="titleBarA" Valign="top"></td>     
    </tr>
    <?php
		}
		else
		{
		?>
    <tr>
      <td class="message" colspan="6">No Sales Record</td>
    </tr>
    <?php
		}
		?>


<script type="text/javascript">


    function mul()
    {
          var txtdisper=document.getElementById('discount_percentage').value;
        
          var el = $("#val");
         var txttotalamount = parseFloat(el.text());
      
     
     
           if (txtdisper ==""){ txtdisper = 0.00;}
          
           if (txttotalamount ==""){txttotalamount = 0.00;}
            var discount_amount=(parseFloat(txtdisper)*parseFloat(txttotalamount)/100);
            
           var grand_amount=parseFloat(txttotalamount)-parseFloat(discount_amount);
         
           if (!isNaN(discount_amount)) {
                  document.getElementById('discount_amt').value = parseFloat(Math.round(discount_amount* 100) / 100).toFixed(2);
       
               }
        
            if (!isNaN(grand_amount)) {
                  document.getElementById('grand_total').value = parseFloat(Math.round(grand_amount* 100) / 100).toFixed(0);

               }
     }
   </script>

   <script type="text/javascript">
      function sub()
      {
        var txtdiscountamount=document.getElementById('discount_amt').value;
       
        var el = $("#val");
        var txttotalamount = parseFloat(el.text());
       // alert(txttotalamount);
        if(txtdiscountamount==""){txtdiscountamount = 0.00;}
        var grand_amount=parseFloat(txttotalamount)-parseFloat(txtdiscountamount);
        
         if (!isNaN(grand_amount))
             {
                  document.getElementById('grand_total').value = parseFloat(Math.round(grand_amount* 100) / 100).toFixed(0);

               }

      }



  </script>

  </table>
</form>
