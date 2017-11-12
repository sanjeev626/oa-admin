<script type="text/javascript">
 function callDelete(gid)
 {
  if(confirm('Are you sure to delete ?'))
  {
  window.location='?manager=order_review&del_id='+gid;
  }
 }
</script>

<?php 
if(isset($_GET['sales_id']))
{
	$sales_id=$_GET['sales_id'];
	$result=$mydb->getQuery('*','tbl_orderreview','sales_id='.$sales_id);//extract order data from tbl_orderreview
	$count=mysql_num_rows($result);//count no of rows in tbl_order

  if(isset($_GET['do']) && $_GET['do']=="add")
  {
    $res = $mydb->getQuery('','','');
  }
?>

<form action="index.php?manager=order_confirm&sales_id=<?php echo $sales_id;?>" method="post" name="tbl_sales">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <tr class="TitleBar">
      <td colspan="8" class="TtlBarHeading">Order Details<div style="float:right"></div></td>
    </tr>
    <tr>
          <td width="2%" valign="top" class="titleBarB" colspan="4"><strong>Name : </strong><strong><?php $clientid=$mydb->getValue('client_id','tbl_sales','id='.$sales_id);
           echo ucfirst($user=$mydb->getValue('name','users','id='.$clientid));?></strong></td>   
          <td width="2%" valign="top" class="titleBarB" colspan="4"><strong>Order Date : </strong><?php echo date('d-m-y',strtotime($date=$mydb->getValue('date','tbl_sales','id='.$sales_id)));?></td>
    </tr>
    <tr>
      <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
      <td width="" valign="top" class="titleBarB"><strong>Medicine Name</strong></td>
      <td width="5%" valign="top" class="titleBarB" align="center"><strong>Batch</strong></td>
      <td width="5%" valign="top" class="titleBarB" align="center"><strong>Rate</strong></td>
      <td width="10%" valign="top" class="titleBarB" align="center"><strong>Requested Quantity</strong></td>
      <td width="5%" valign="top" class="titleBarB" align="center"><strong>Total</strong></td>
      <td width="10%" valign="top" class="titleBarB" align="center"><strong>Available Quantity </strong></td>
      <td width="20%" valign="top" classs="titleBarB" align="center"><strong>Remarks</strong></td>
    </tr>
    <?php
		$counter=0;
		while($rasOrder =$mydb->fetch_array($result))
		{									
  		$confirm = 1;
  		$medicine=$rasOrder['medicine_name'];
      $medicine_id=$rasOrder['medicine_id'];
     
  		$med=preg_replace('/[^A-Za-z0-9]/',"",$medicine);
  		$quantity=$rasOrder['quantity'];
  		$stockquantity = $mydb->getSum('stock','tbl_stock','medicine_id="'.$medicine_id.'"');
      $salesquantity = $mydb->getSum('sales','tbl_stock','medicine_id="'.$medicine_id.'"');
      
      $rasStock=$mydb->getArray('sp_per_tab,batch_number','tbl_stock','medicine_id="'.$medicine_id.'" AND stock>sales');

      $rate = $rasStock['sp_per_tab'];
      $batch_number = $rasStock['batch_number'];
      $available_quantity=$stockquantity-$salesquantity;
      
      
		if($quantity<=$available_quantity)
		{
			$drug_status = 'Available';
			$color = 'GREEN';
		}
		else
		{
			$drug_status = 'Ordered Quantity Not Available. <br>At least '.($quantity-$available_quantity).' required to complete this order';
			$color = 'RED';						
			$confirm = 0;			
		}
    $total = $rate*$quantity;
    $gtotal+=$total;
		?>
			<tr>
  			<td class="titleBarA" valign="top"><?php echo ++$counter;?></td>
  			<td class="titleBarA" valign="top" style="color:green"><?php echo $medicine;?></td>
        <td class="titleBarA" valign="top"><?php echo $batch_number;?></td>
        <td class="titleBarA" valign="top"><?php echo $rate;?></td>
        <td class="titleBarA" valign="top"><?php echo $quantity;?></td>
        <td class="titleBarA" valign="top" align="right"><?php echo number_format($rate*$quantity,2);?></td>
  			<td class="titleBarA" valign="top"><?php echo $available_quantity;?></td>            
  			<td class="titleBarA" valign="top" style="color:<?php echo $color;?>;"><?php echo $drug_status;?></td>
      </tr>
			<?php			
		}//while section closed
		?> 
      <tr>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top" align="right"><?php echo number_format($gtotal,2);?></td>
        <td class="titleBarA" valign="top"></td>            
        <td class="titleBarA" valign="top"></td>
      </tr>       
		<tr>
			<td class="titleBarA" valign="top" colspan="8" align="right">
        <input name="btnedit" type="button" value="edit" class="button" onclick="window.location='index.php?manager=order_confirmedit&sales_id=<?php echo $sales_id;?>'">
        <input name="btnConfirm" type="submit" value="Confirm" class="button" onclick="window.location='index.php?manager=order_confirm&sales_id=<?php echo $sales_id;?>'" <?php if($confirm=='0') echo 'disabled="disabled"';?>>
      </td>
  </tr>
  </table>
</form>
<?php
}//isset wala if closed
elseif(isset($_GET['del_id']))
{
  $delId = $_GET['del_id'];
    $mess = $mydb->deleteQuery('tbl_sales','id='.$delId);
    $mess1=$mydb->deleteQuery('tbl_orderreview','sales_id='.$delId);
}

else{
	$result = $mydb->getQuery('*','tbl_sales','review_status="0"');//extract order data from tbl_order

	$count = mysql_num_rows($result);//count no of rows in tbl_order

?>
<form action="" method="post" name="tbl_sales">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <?php if(isset($_GET['message'])){?>
    <tr>
      <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
      <!--if message is set ,display in top--> 
    </tr>
    <?php } ?>
    <tr class="TitleBar">
      <td colspan="8" class="TtlBarHeading">Sales
        <div style="float:right"></div></td>
    </tr>
    <?php
	if($count!= 0)//if there is data in tbl_order then it enters to loop;
	{
	?>
    <tr>
      <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
      <td width="20%" valign="top" class="titleBarB" align="center"><strong>Client Name</strong></td>
      <td width="20%" valign="top" class="titleBarB" align="center"><strong>Contact Details</strong></td>
      <td width="12%" valign="top" class="titleBarB" align="center"><strong>Purchase Date</strong></td>
      <td width="6%" valign="top" class="titleBarB" align="center"><strong>Number of Drugs</strong></td>
      <td width="20%" valign="top" classs="titleBarB" align="center"><strong>Details</strong></td>
    </tr>
    <?php
			

		$counter = 0;
		while($rasSales = $mydb->fetch_array($result))
		{
         $count1=$mydb->getCount('client_id','tbl_login','session_id='.$rasSales['client_id']);
                  
                    if($count1==0)
                    {
                      $users=$mydb->getValue('name','users','id='.$rasSales['client_id']);
                      $phone = $mydb->getValue('phone','users','id='.$rasSales['client_id']);
                    }else
                    {
                        $client_id1=$mydb->getValue('client_id','tbl_login','session_id='.$rasSales['client_id']);
                        $users=$mydb->getValue('name','users','id='.$client_id1);
                        $phone = $mydb->getValue('phone','users','id='.$client_id1);
                    }
		?>
    <tr>
      <td class="titleBarA" align="center" valign="top"><input name="eid[]" type="hidden" id="eid[]" value="<?php echo $rasSales['id'];?>" />
        <?php echo ++$counter;?></td>
      <td class="titleBarA" align="center" valign="top"><?php echo $users;?></td>
      <td class="titleBarA" align="center" valign="top"><?php echo $phone;?></td>
      <td class="titleBarA" valign="top"><?php echo $rasSales['date'];?></td>
      <td class="titleBarA" valign="top"><?php echo $mydb->getCount('id','tbl_orderreview','sales_id='.$rasSales['id']);?></td>
      <td class="titleBarA" Valign="top"><a href="<?php echo ADMINURLPATH;?>order_review&sales_id=<?php echo $rasSales['id'];?>">View Details</a>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <a href="javascript:void(0);" onclick="callDelete('<?php echo $gid=$rasSales['id']; ?>')">Delete</a></td>

    </tr>
    <?php
		}
}
		else
		{
		?>
    <tr>
      <td class="message" colspan="4">No Sales Record</td>
    </tr>
    <?php
		}
	}
		?>
  </table>
</form>
