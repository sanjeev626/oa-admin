<script type="text/javascript">

	function callDelivery(delivery_id)
	{
		var methodpaid=document.querySelector('input[name="payment"]:checked').value;
		var remark=document.getElementById('comment').value;
		var refill=document.getElementById('refill').value;

		if(confirm('Are you sure medicine is delivered?'))
		{
			window.location='?manager=order&uid='+delivery_id+'&payment='+methodpaid+'&remarks='+remark+'&refill='+refill;
			return false;
		}
	}

	function callDelete(gid)
	{
		if(confirm('Are you sure to cancel order ?'))
		{
			window.location='?manager=order&did='+gid;
			return false;
		}
	}

	function callDeletesales(sales_id)
	{
		if(confirm('Are you sure to cancel order ?'))
		{
			window.location='?manager=order&did_sal='+sales_id;
			return false;
		}
	}

</script>

<?php
/****Delete Data from Database****/
if(isset($_GET['did']))
{

	$delId = $_GET['did'];

	$stock=$mydb->getArray('stock_id,quantity','tbl_order','id='.$delId);
	$sales_value=$mydb->getValue('sales','tbl_stock','id='.$stock['stock_id']);
	$modified_value=$sales_value-$stock['quantity'];
	$data='';
	$data['sales']=$modified_value;
	$mydb->updateQuery('tbl_stock',$data,'id='.$stock['stock_id']);

	$val=$mydb->getValue('sales_id','tbl_order','id='.$delId);
	$mess = $mydb->deleteQuery('tbl_order','id='.$delId);

	$count_val=$mydb->getCount('id','tbl_order','sales_id='.$val);
	if($count_val==0)
	{
		$mydb->deleteQuery('tbl_sales','id='.$val);
	}

}

/*Update of Database tbl_order,tbl_sales and tbl_stock.sales column*/

if(isset($_GET['uid']))
{

	if(isset($_GET['payment']))
	{
		$updateId = $_GET['uid'];

		$pay=$_GET['payment'];
		$comment=$_GET['remarks'];
		$refill = $_GET['refill'];

		$data='';
		$data['order_status']=1;
		$data['delivery_date']=date("Y-m-d");
		$data['refill_day'] = $refill;
		$delib_date=$data['delivery_date'];
		if($refill=='0'||$refill==''){
			$refill_time  = '+24 days';
			$refill = '';
		}
		else{
			$refill_time  = '+'.$refill.' days';

		}

		$data['Refill_date']=date("Y-m-d",strtotime($refill_time,strtotime($delib_date)));


		$data['payment']=$pay;
		$data['Remarks']=$comment;

		$mess = $mydb->updateQuery('tbl_sales',$data,'id='.$updateId);

		$data1='';
		$data1['status']=1;
		$data1['refill_day'] = $refill;
		$update_order=$mydb->updateQuery('tbl_order',$data1,'sales_id='.$updateId);
	}




}

if(isset($_GET['did_sal']))
{
	$del_sales=$_GET['did_sal'];
	$val=$mydb->getQuery('*','tbl_order','sales_id='.$del_sales);
	while($rasvalue=$mydb->fetch_array($val))
	{

		$id=$rasvalue['stock_id'];

		$sales_value=$mydb->getValue('sales','tbl_stock','id='.$id);

		$modified_value=$sales_value-$rasvalue['quantity'];

		$data='';
		$data['sales']=$modified_value;
		$mydb->updateQuery('tbl_stock',$data,'id='.$id);
	}
	$mydb->deleteQuery('tbl_order','sales_id='.$del_sales);
	$mydb->deleteQuery('tbl_sales','id='.$del_sales);

}

//$result = $mydb->getQuery('*','tbl_sales');
//$count = mysql_num_rows($result);
?>






<form action="" method="post" name="tbl_conform_order" id="form1" class="cmxform">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">

		<tr class="TitleBar">
			<td colspan="5" class="TtlBarHeading">Order Confirmation
				<div style="float:right"></div>
			</td>
			<td class="TtlBarHeading" style="width:50px;"><input name="btnAdd" type="button" value="Add order" class="button" onClick="window.location='<?php echo ADMINURLPATH;?>order_manage'" /></td>
		</tr>




		<!--to show order confirmation table-->
		<?php
		$order_val=0;

		$count=$mydb->getCount('id','tbl_sales','order_status="'.$order_val.'"AND net_amount>0');

		$result = $mydb->getQuery('*','tbl_sales','order_status="'.$order_val.'"AND net_amount>0');
		//echo $result;
		if($count==0)
		{
			?>
			<tr>
				<td class="message" colspan="4">No medicines  has been queued for confirmation</td>
			</tr>

		<?php	}
		else
		{					//fetch data from tbl_sales;
			//use of while loop inside while;
			//while loop first run for tbl_order and inside this runs
			//another while loop to fetch data from tbl_order;
			/*--while loop start to fetch data from tbl_sales*/
			while($rasvalue=$mydb->fetch_array($result))
			{
				$sales_id=$rasvalue['id'];
				$delivery_id=$sales_id;
				$client_id=$rasvalue['client_id'];

				?>

				<tr>
					<td width="2%" class="titleBarB" align="center"><strong>S.N</strong></td>
					<td width="20%" class="titleBarB" align="center"><strong>username</strong></td>
					<td width="30%" class="titleBarB" align="center"><strong>Medicine Name</strong></td>
					<td width="30%" class="titleBarB" align="center"><strong>Quantity</strong></td>
					<td width="18%"  class="titleBarB" align="center" colspan="2"><strong>Option</strong></td>

				</tr>

				<?php


				$result_order = $mydb->getQuery('*','tbl_order','sales_id='.$sales_id);


				$counter = 0;
				/*--while loop start to fetch data from tbl_order*/
				while($order_val=$mydb->fetch_array($result_order))
				{
					$count1=$mydb->getCount('client_id','tbl_login','session_id='.$client_id);

					if($count1==0)
					{
						$users=$mydb->getValue('name','users','id='.$client_id);
					}else
					{
						$client_id1=$mydb->getValue('client_id','tbl_login','session_id='.$client_id);
						$users=$mydb->getValue('name','users','id='.$client_id1);
					}
					$gid=$order_val['id'];
					$med=$order_val['medicine_name'];
					//echo $med;
					$med =  preg_replace('/[^A-Za-z0-9]/',"", $med);
					$med1=$mydb->getValue("item_description","tbl_stock","description='".$med."'");

					$medicine_name = $mydb->getValue('medicine_name','tbl_medicine','id="'.$order_val['medicine_id'].'"');

					?>


					<tr>
						<td class="titleBarA" align="center" valign="top"> <input name="eid[]" type="hidden" id="eid[]" value="<?php echo $order_val['id'];?>" /><?php echo ++$counter;?></td>
						<td class="titleBarA" align="center" valign="top"><?php echo stripslashes(ucfirst($users));?></td>
						<td class="titleBarA" align="center" valign="top"><?php echo $medicine_name;?></td>
						<td class="titleBarA" align="center" valign="top"><?php echo ucfirst($order_val['quantity']);?></td>
						<td class="titleBarA"  colspan="2" align="center" valign="top"><input type="submit" align="center" name="btndelay" id="btncancel" value="Cancel " class="button" OnClick="return callDelete('<?php echo $gid; ?>')" /></td>
					</tr>





				<?php } //while loop for tbl_order closed?>
				<tr>
					<td class="titleBarB" align="center"><strong>Order Date</strong></td>
					<td class="titleBarB" align="center"><?php echo date('d-m-Y',strtotime($rasvalue['date']));?></td>
				</tr>
				<tr>
					<td class="titleBarB" align="center"><strong>Refill after</strong></td>
					<td class="titleBarB" align="center">
						<input type="number" name="refill" id="refill" class="inputbox" value="<?php echo $rasvalue['refill_day']?>"
						placeholder="days in number">
						<Br><span>Example: 60 Days</span>
					</td>
				</tr>

				<tr>
					<td class="titleBarB" align="center"><strong>Paid</strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="payment" value="paid" id="payment" required></td>
					<td class="titleBarB" align="center"><strong>Due</strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="payment" value="due" id="payment" ></td>

				</tr>



				<tr>
					<td class="titleBarB" align="center"><strong>Comment</strong></td>
					<td class="titleBarB" align="center"><textarea cols="4" name="comment" id="comment" ></textarea></td>
				</tr>

				<tr>
					<td class="titleBarA" align="center" valign="top">
						<input type="submit" name="btndeliver" id="btndeliver" value="Delivered " class="button" OnClick="return callDelivery('<?php echo $delivery_id;?>')" />
					</td>

					<td class="titleBarA" align="center" valign="top">
						<input type="submit" name="btndelay" id="btncancel" value="Cancel " class="button" OnClick="return callDeletesales('<?php echo $sales_id; ?>')" />
					</td>


				</tr>




				<tr>
					<td>&nbsp;</td>
				</tr>


			<?php }//while loop for tbl_sales closed;
		}//main if loop closed



		?>
	</table>
</form>