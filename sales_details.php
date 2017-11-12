<script type="text/javascript">
       function callReorder(reorder_id)
       {        
        if(confirm('Are you sure medicine is reorder?'))
        {
          window.location='?manager=order_manage&reorder_id='+reorder_id;
          return false;
        }
       } 
  </script>
 

<?php 
if(isset($_POST['btnupdate']))
{
  
  $salesid=$_POST['salesid'];

   foreach ($_POST as $key => $value)
   {
      if($key!="btnupdate"&&$key!="salesid" && $key!="refund_sales_id" && $key!="subtotal")  
        $data[$key]=$value;
   }
   $refund_sales_ids='';
   for($ii=0;$ii<count($_POST['refund_sales_id']);$ii++)
   {
      $rsi=$_POST['refund_sales_id'][$ii];
      $rr = explode('_',$rsi);
      $refund_sales_ids.='Refund id : '.$rr['0'].' and '.'Amount : '.$rr['1'].', ';
   }
   $refund_sales_ids = substr($refund_sales_ids, 0,-2);
   //echo $refund_sales_ids;
   $data['refund_sales_ids']=$refund_sales_ids;
   //print_r($data);
   //echo $mydb->updateQuery('tbl_sales',$data,'id='.$salesid,'1');
   $mydb->updateQuery('tbl_sales',$data,'id='.$salesid);
   $url = ADMINURLPATH.'sales';    
   $mydb->redirect($url);
}
//In order to delete order and sales
if(isset($_POST['btncancel']))
{
    $salesid1=$_POST['salesid'];
    $orderlist=$mydb->getQuery('*','tbl_order','sales_id='.$salesid1);
    while($list1=$mydb->fetch_array($orderlist))
    {
       $orderid=$list1['stock_id'];
       $quantity=$list1['quantity'];
       $salesquantity=$mydb->getValue('sales','tbl_stock','id='.$orderid);
       $idlist=$list1['id'];
       $data='';
       $data['sales']=$salesquantity-$quantity;
       $mydb->updateQuery('tbl_stock',$data,'id='.$orderid);
       $mydb->deleteQuery("tbl_order","id=".$idlist);
    }
    $mydb->deleteQuery('tbl_sales','id='.$salesid1);
    $url = ADMINURLPATH.'sales';    
    $mydb->redirect($url);
}


if(isset($_GET['sales_id']))
{
    $sales_id = $_GET['sales_id'];
    $result = $mydb->getQuery('*','tbl_order','sales_id='.$sales_id);//extract order data from tbl_order
    $count = mysql_num_rows($result);//count no of rows in tbl_order
    // $discount = $mydb->getValue('discount_percentage','tbl_sales','id='.$sales_id);
    $rasSales = $mydb->getArray('*','tbl_sales','id='.$sales_id);
}
?>  

<form action="" method="POST" name="sales">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <?php if(isset($_GET['message'])){?>
    <tr>
      <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
      <!--if message is set ,display in top--> 
    </tr>
    <?php } ?>
    <tr class="TitleBar">
      <td colspan="7" class="TtlBarHeading">Sales
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
      <td width="5%" valign="top" class="titleBarB" align="center"><strong>Quantity</strong></td>
      <td valign="top" class="titleBarB" align="center"><strong>Medicine Name</strong></td>
      <td width="10%" valign="top" class="titleBarB" align="center"><strong>Batch</strong></td>
      <td width="10%" valign="top" class="titleBarB" align="center"><strong>Expiry Date</strong></td>
      <td width="10%" valign="top" class="titleBarB" align="center"><strong>Rate </strong></td>
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
      <td class="titleBarA" align="center" valign="top"><?php echo $rasOrder['quantity']; ?></td>
      <td class="titleBarA" valign="top"><?php echo $medicine_name;?></td>
      <td class="titleBarA" valign="top"><?php echo $batch_number;?></td>
      <td class="titleBarA" valign="top"><?php echo $expiry_date;?></td>
      <td class="titleBarA" valign="top"><?php echo $rasOrder['Rate'];?></td>
      <td class="titleBarA" valign="top"><?php echo $amount; ?></td>
      <td class="titleBarA" Valign="top"></td>
    </tr>
    <?php		
}      
       $statuscheck=$mydb->getValue('order_status','tbl_sales','id='.$sales_id);
       $order_amount=$mydb->getValue('net_amount','tbl_sales','id='.$sales_id);
        if($statuscheck==0&&$order_amount==0)
      {
        $client_id=$mydb->getValue('client_id','tbl_sales','id='.$sales_id);
        $discount_per=$mydb->getValue('discount_percentage','users','id='.$client_id);
?>
	<tr>
      <td class="titleBarA" colspan="6" style="text-align:right;"> Total : </td>
      <td class="titleBarA" valign="top"><div id="val"><?php echo $round_total_amount=$total_amount;?></div><input name="total_amount" type="hidden" id="total_amt" value="<?php echo $round_total_amount=$total_amount;?>"</td>
      <td class="titleBarA" Valign="top"></td>
    </tr>    
	<tr>
      <td class="titleBarA" colspan="6" style="text-align:right;">Discount Percentage: </td>
      <td class="titleBarA" valign="top"><input type="text" name="discount_percentage" id="discount_percentage" value="<?php if(isset($discount_per)) echo $discount_per;?>" onkeyup=mul()></td>
      <td class="titleBarA" Valign="top"></td>
  </tr>

  <tr>
      <td class="titleBarA" colspan="6" style="text-align:right;">Discount Amount : </td>
      <td class="titleBarA" valign="top"><input type="text" name="discount_amount" id="discount_amt" value="<?php if(isset($discount_per)) echo round(($discount_per/100)*$round_total_amount);?>" onkeyup=sub()></td>
      <td class="titleBarA" Valign="top"></td>
  </tr>
  <?php
  $hasRefund = $mydb->getCount('id','tbl_sales_return','client_id='.$client_id.' AND refund_status="0"');
  if($hasRefund>0)
  {
  ?>  
  <tr>
      <td class="titleBarA" colspan="6" style="text-align:right;">Sub Total : </td>
      <td class="titleBarA" valign="top">
        <input type="text" name="subtotal" id="subtotal" value="<?php if(isset($discount_per)) echo round($round_total_amount-(($discount_per/100)*$round_total_amount));?>">
      </td>
      <td class="titleBarA" Valign="top">
      </td>
    <tr>
  <tr>
      <td class="titleBarA" colspan="6" style="text-align:right;">Refund Amount : </td>
      <td class="titleBarA" valign="top" id="checks"> 
        <?php
        $counter=0;
        $result = $mydb->getQuery("id,return_date,total_sales_return_amount", "tbl_sales_return", "client_id='".$client_id."' AND refund_status='0' ORDER BY id desc");
        while($rasRefund = $mydb->fetch_array($result))
        {
          $id = $rasRefund['id'];
          $dropdown_value = $rasRefund['return_date'].' (Nrs. '.$rasRefund['total_sales_return_amount'].')';        
        ?> 
        <input type="checkbox" name="refund_sales_id[]" id="refund_sales_id<?php echo ++$counter;?>" value="<?php echo $id.'_'.$rasRefund['total_sales_return_amount'];?>"> <?php echo $dropdown_value;?>
         <br>          
        <?php 
        }
        ?> 
        <input type="hidden" name="total_refund_amount" id="total_refund_amount" value="0"> 
      </td>
      <td class="titleBarA" Valign="top"></td>
  </tr>
  <?php
  }
  ?>
    <tr>
      <td class="titleBarA" colspan="6" style="text-align:right;">Grand Total : </td>
      <td class="titleBarA" valign="top">
        <input type="text" name="net_amount" id="grand_total" value="<?php if(isset($discount_per)) echo round($round_total_amount-(($discount_per/100)*$round_total_amount));?>"><br><br>
        <input type="submit" name="btnupdate" id="btnupdate" value="Update" class="button">
        <input type="submit" name="btncancel" id="btncancel" value="Cancel" class="button">      
        <input name="salesid" type="hidden" id="salesid" value="<?php echo $sales_id;?>">
      </td>
      <td class="titleBarA" Valign="top">
      </td>
       <?php }else{

        ?>
    <tr>
      <td class="titleBarA" colspan="6" style="text-align:right;">Total:</td>
      <td class="titleBarA" valign="top"><div id="val"><?php echo ($round_total_amount=$total_amount);?></div><input name="total_amount" type="hidden" id="total_amt" value="<?php echo $round_total_amount=$total_amount;?>"</td>
      <td class="titleBarA" Valign="top"></td>
    </tr>    
 
  <tr>
      <td class="titleBarA" colspan="6" style="text-align:right;">Discount Percentage: </td>
      <td class="titleBarA" valign="top"><?php echo $mydb->getValue('discount_percentage','tbl_sales','id='.$sales_id);?></td>
      <td class="titleBarA" Valign="top"></td>
  </tr>

  <tr>
    <td class="titleBarA" colspan="6" style="text-align:right;">Discount Amount : </td>
    <td class="titleBarA" valign="top"><?php echo $mydb->getValue('discount_amount','tbl_sales','id='.$sales_id);?></td>
    <td class="titleBarA" Valign="top"></td>
  </tr>
  <?php
  if($rasSales['total_refund_amount']>0)
  {
    ?>
  <tr>
    <td class="titleBarA" colspan="6" style="text-align:right;">Sub Total : </td>
    <td class="titleBarA" valign="top"><?php echo $rasSales['total_amount']-$rasSales['discount_amount'];?></td>
    <td class="titleBarA" Valign="top"></td>
  </tr>
  <tr>
    <td class="titleBarA" colspan="6" style="text-align:right;">Refund Amount : </td>
    <td class="titleBarA" valign="top"><?php echo $rasSales['total_refund_amount'];?></td>
    <td class="titleBarA" Valign="top"></td>
  </tr>
  <tr>
    <td class="titleBarA" colspan="6" style="text-align:right;">Refund Remarks : </td>
    <td class="titleBarA" valign="top"><?php echo $rasSales['refund_sales_ids'];?></td>
    <td class="titleBarA" Valign="top"></td>
  </tr>
    <?php
  }
  ?>
   <tr>
      <td class="titleBarA" colspan="6" valign="top" style="text-align:right;">Grand Total : </td>
      <td class="titleBarA" valign="top">
        <?php echo $mydb->getValue('net_amount','tbl_sales','id='.$sales_id);?><br>
        <?php $order_stat=$mydb->getValue('order_status','tbl_sales','id='.$sales_id);?>
      </td>
      <td class="titleBarA" Valign="top"></td>    
    </tr>
    <tr>
      <td class="titleBarA" colspan="6" valign="top" style="text-align:right;">&nbsp;</td>
      <td class="titleBarA" valign="top">
        <input type="submit" name="btndeliver" id="btndeliver" value="Reorder" class="button" onclick="return callReorder('<?php echo $sales_id;?>')"<?php if($order_stat==0) echo "disabled";?>>
        <input type="button" name="btnprint" id="btnprint" value="Print" class="button" onclick="window.open('<?php echo SITEROOTADMIN.ADMINURLPATH?>print&print_id=<?php echo $sales_id;?>');">
        <input type="button" name="btnEdit" id="btnEdit" value="Edit" class="button" onclick="window.location='<?php echo SITEROOTADMIN.ADMINURLPATH?>order_confirmedit&confirmed_edit&sales_id=<?php echo $sales_id;?>';">
        <input name="salesid" type="hidden" id="salesid" value="<?php echo $sales_id;?>">
      </td>
      <td class="titleBarA" Valign="top"><input type="button" name="btnReturn" id="btnReturn" value="Sales return" class="button" onclick="window.location='<?php echo SITEROOTADMIN.ADMINURLPATH?>sales_return&sales_id=<?php echo $sales_id;?>';">
      </td>    
    </tr>
      
      <?php } ?>
     
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
    <tr>
      <td class="titleBarA" colspan="6" style="text-align:right;">Item Details : </td>
      <td class="titleBarA"><textarea name="item_details" id="item_details" cols="100" style="width:250px;resize: none;" placeholder="item name///total///discount///net total"><?php echo $mydb->getValue('item_details','tbl_sales','id='.$sales_id);?></textarea></td>
      <td class="titleBarA" Valign="top"><?php /*<input type="button" name="btnAdd" id="btnAdd" value="Add Item" class="button" onclick="window.open('<?php echo SITEROOTADMIN.ADMINURLPATH?>order_review&sales_id=<?php echo $sales_id;?>&do=add');">*/?></td>
     </td>     
    </tr>


<script type="text/javascript">


    function mul()
    {
          //alert(refund);
          var txtdisper=document.getElementById('discount_percentage').value;
        
          var el = $("#val");
         var txttotalamount = parseFloat(el.text());
      
     
     
           if (txtdisper ==""){ txtdisper = 0.00;}
          
           if (txttotalamount ==""){txttotalamount = 0.00;}
            var discount_amount=(parseFloat(txtdisper)*parseFloat(txttotalamount)/100);
            
           var grand_amount=parseFloat(txttotalamount)-parseFloat(discount_amount);

           var refund = $("#total_refund_amount").val();
           if( !refund ) {
            var refund=0;
            }
         
           if (!isNaN(discount_amount)) {
                  document.getElementById('discount_amt').value = parseFloat(Math.round(discount_amount* 100) / 100).toFixed(2);
       
               }
        
            if (!isNaN(grand_amount)) {
                  document.getElementById('grand_total').value = parseFloat(Math.round(grand_amount* 100) / 100).toFixed(0)-Math.round(refund);

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

  $("#checks :checkbox").change(function(e){
      if ($(this).is(":checked"))
      {
        text = $(this).val();
        arr = text.split('_');
        refund = arr['1'];
        //alert(arr['1']);
        total_refund_amount_prev = $("#total_refund_amount").val();
        total_refund_amount_new = parseFloat(refund)+parseFloat(total_refund_amount_prev);
        $("#total_refund_amount").val(parseFloat(total_refund_amount_new));
        //alert("checked Score: " + $(this).val());
        mul();
      }
      else
      {
        text = $(this).val();
        arr = text.split('_');
        refund = arr['1'];
        total_refund_amount_prev = $("#total_refund_amount").val();
        total_refund_amount_new = parseFloat(total_refund_amount_prev)-parseFloat(refund);
        total_refund_amount_new = Math.round(total_refund_amount_new * 100) / 100;
        //alert(refund+'---'+total_refund_amount_new);
        $("#total_refund_amount").val(total_refund_amount_new);
        //alert("checked Score: " + $(this).val());
        mul();
      }
  });

  </script>

  </table>
</form>
