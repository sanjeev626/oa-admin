<?php
if(isset($_GET['print_id']))
{
	$printid=$_GET['print_id'];
	$result=$mydb->getArray('client_id,date,total_amount,discount_amount,discount_percentage,net_amount,item_details','tbl_sales','id='.$printid);
	$result1=$mydb->getArray('name,address,phone,additional_phone','users','id="'.$result['client_id'].'"');
	//print_r($result);
	$item_details = $result['item_details'];
if(isset($_POST['update_print']) && $_POST['update_print']=='Update'){
//print_r($_POST);
$print_id = $printid;
$name = $_POST['fullname'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$total_amout = $_POST['total_sum'];
$discount = $_POST['discount_amount'];
$net_amount = $_POST['discounted_amount'];
$date = $_POST['billdate'];

//Remove data of previous Edit
$mydb->deleteQuery('tbl_print_temp_med','print_id='.$print_id);

$data = '';
$data['print_id'] = $print_id;
$data['name'] = $name;
$data['address'] = $address;
$data['contact'] = $contact;
$data['total_amout'] = $total_amout;
$data['discount'] = $discount;
$data['net_amount'] = $net_amount;
$data['date']	= $date;


if($mydb->getCount('print_id','tbl_temp_print_name','print_id='.$print_id)>0)
	$mydb->updateQuery('tbl_temp_print_name',$data,'print_id='.$print_id);
else
	$mydb->insertQuery('tbl_temp_print_name',$data);


$mydb->deleteQuery('tbl_print_temp_med','print_id='.$print_id);
for($j=0;$j<count($_POST['medicine_name']);$j++)
{
	$data2='';
	$data2['print_id']=$print_id;
	$data2['qty']=$_POST['quantity'][$j];
	$data2['med_name']=$_POST['medicine_name'][$j];
	$data2['stock_id']=$_POST['stock_id'][$j];	
	$data2['rate']=$_POST['rate'][$j];
	$data2['amount']=$_POST['total_amount'][$j];
// 	echo '<pre>';
// print_r($data2);
// echo '</pre>';
	$mydb->insertQuery('tbl_print_temp_med',$data2);
}

//Update tbl_sales(item_details)
$data='';
$data['item_details'] = $_POST['item_details'];
$mydb->updateQuery('tbl_sales',$data,'id='.$printid);

//die();
$url = ADMINURLPATH.'print&print_id='.$printid;
	$mydb->redirect($url);
}
?>
<form name="printEdit" id="printEdit" action="" method="post">
	<table  width="270px" style="font-size:12px;" id="printtable">
	 <tr>
		 <td colspan="4" align="center">
			 <table  width="100%" style="font-size:12px;">
			<tr>
				<td style="font-size:13px;" colspan="4" align="center">
				<span style="font-size:14px;">Online Aushadhi Pvt. Ltd.</span></br>A Delivery Partner for S.M Pharma</br>onlineaushadhi.com
				</td>
			</tr>
			 <tr>
				 <td colspan="4" align="center" style="padding-bottom:15px;">PAN NO : 604311632, PHONE : 9841568568</td>
			 </tr>
			 <tr>
				 <td width="50px;"><strong>INVOICE No.</strong></td>
				 <td><strong><?php echo str_pad($printid,6,0,STR_PAD_LEFT)?></strong></td>
				 <td colspan="2" align="right">
					 <strong>DATE:&nbsp;&nbsp;</strong> <input type="text" name="billdate" id="billdate" class="billdate" value="<?php echo date("d-m-Y");?>">
					 <!--<strong>DATE:&nbsp;&nbsp;<?php /*echo date("d-m-Y");*/?></strong>--></td>
			 </tr>

			 <tr>
				 <td colspan="1" style="width:100px;"><strong>M/S</strong></td>
				 <td colspan="3" align="left"><input type="text" name="fullname" value="<?php  echo ucfirst($result1['name']);?>"></td>
			 </tr>
			 <tr>
				 <td colspan="1"><strong>Address</strong></td>
				 <td colspan="3" align="left">
					 <?php
						 if(!empty($result1['address'])){
							 	echo '<input type="text" name="address" value="'.$result1['address'].'">';
						 }
					 ?>
				 </td>
			 </tr>
			 <tr>
				 <td colspan="1" style="padding-bottom:15px;"><strong>Contact No.</strong></td>
				 <td colspan="3" style="padding-bottom:15px;" align="left">
					 <input type="text" name="contact" value="<?php  echo $result1['phone'];if($result1['additional_phone']) echo ', '.$result1['additional_phone'];?>">
					 </td>
			 </tr>
		 </table>
		 </td>
	 </tr>
	 <tr >
		 <td width="35px" align="center" style="border-bottom:1pt solid black; font-weight:bold;"><strong>QTY</strong></td>
		 <td width="105px" style="border-bottom:1pt solid black; font-weight:bold; font-weight:bold; font-weight:bold;"><strong>Particulars</strong></td>
		 <td width="65px" align="right" style="border-bottom:1pt solid black; font-weight:bold; font-weight:bold;"><strong>Rate</strong></td>
		 <td width="65px" align="right" style="border-bottom:1pt solid black; font-weight:bold;"><strong>Amount</strong></td>
		 <td>&nbsp;</td>
	 </tr>
	 <?php
	 $count=1;
	 $result3=$mydb->getQuery("*","tbl_order","sales_id=".$printid);
	 while($result2=$mydb->fetch_array($result3))
	 {

	 ?>
	 	<tr id="<?php echo $result2['id']?>" class="medicineDetail">
		 <td valign="top" align="center">
			 <input	type="hidden" name='detailid[]' value="<?php echo $result2['id']?>">
			 <input type="text" name="quantity[]" id="quantity_<?php echo $count;?>" value="<?php echo $result2['quantity'];?>" onchange="changeTotal('<?php echo $count;?>')">
			 <input	type="hidden" name='stock_id[]' value="<?php echo $result2['stock_id']?>">
		 </td>
		 <td valign="top"><input type="text" name="medicine_name[]" value="<?php echo $result2['medicine_name'];?>"></td>
		 <td valign="top" align="right"><input type="text" name="rate[]" id="rate_<?php echo $count;?>" value="<?php echo $result2['Rate'];?>" onchange="changeTotal('<?php echo $count;?>')"></td>
		 <td valign="top" align="right"><input type="text" class="total_amount" name="total_amount[]" id="total_amount_<?php echo $count;?>" value="<?php echo number_format($result2['total_amount'],2);?>" onchange="getTotal()"></td>
		 <td><input type='button' class="delButton" value='Delete medicine' id='delButton'></td>
	 </tr>
	 <?php

	 $count++;
	 }
	 ?>
	 <tfoot>
	 <tr>
		 <td >
			 <input type='button' class="" value='Add medicine' id='addButton' onclick="addmedicine()">
		 </td>
	 </tr>
	 <tr>
		 <td valign="top" colspan="3" align="right" style="border-top:1pt solid black;"><strong>Total amount</strong></td>
		 <td valign="top" align="right" style="border-top:1pt solid black;"><input name="total_sum" id="total_sum" value="<?php echo $result['total_amount'];?>"></td>
	 </tr>
	 <tr>
		 <td valign="top" colspan="2" align="right"></td>
		 <td valign="top" align="right"><strong>Discount</strong></td>
		 <td valign="top" align="right"><input value="<?php echo number_format($result['discount_amount'],2);?>" name="discount_amount" id="discount_amount" onblur="netAmount()"></td>
	 </tr>
	 <tr>
		 <td valign="top" colspan="2" align="right"></td>
		 <td valign="top" align="right" style="border-top:2pt solid black;"><strong>Net Amount</strong></td>
		 <td valign="top" align="right" style="border-top:2pt solid black;">
			 <input id="net_amout" value="<?php $value1=$result['net_amount']; echo number_format($value1,2);?>" name="discounted_amount">
			 <!-- <strong><?php //$value1=$result['net_amount']; echo number_format($value1,2);?></strong></td> -->
	 </tr>
	 <tr>
		 <td valign="top" colspan="3"><strong>Item Details : </strong> <input id="item_details" value="<?php echo $item_details;?>" name="item_details" style="width:300px;"></td>
		 <td valign="top" align="right"></td>
	 </tr>
	 <tr>
		 <td colspan="4" style="padding-bottom:3px;"><strong>In Words: </strong><?php echo convert_number($value1);?>&nbsp;Rupees Only</td>
	 </tr>

	 <tr>
		 <td valign="top" colspan="4" style="border-top:1pt solid black; padding-top:3px;"><strong>Note : </strong><br>
		 <ul>
			 <li>- Medicines once sold can be taken back or exchanged in next order strictly based on our return policy. Please check our website for more details.</li>
			 <li>- Store medicines in cool, dry place &amp; out of reach of children.</li>
		 </ul>
			 </td>

	 </tr>
	 <tr>
		 <td valign="bottom" colspan="4" style="padding-top:40px; text-align:right;">Signature</td>

	 </tr>

	 <tr>
		 <td>
			 <input type="submit" name="update_print" id="update_print" class="button" value="Update">
		 </td>


	 </tr>
 </tbody>
 </table>

</form>



<?php
}else{
	echo "No result found";
}
?>
<script>
	$( function() {
		$( "#billdate" ).datepicker({dateFormat: 'dd-mm-yy'});
	} );
</script>
<script type="text/javascript">
$(document).ready(function()
{
	$(document).on("click", ".delButton", function(){
		$(this).closest("tr").remove();
	});

});
function getTotal(){

		var sum = 0;

		var inputEle = document.getElementsByClassName("total_amount");
		for( var i =0; i<inputEle.length;i++){

			var a=inputEle[i].value;
				a=a.replace(/\,/g,''); // 1125, but a string, so convert it to number
				a=parseInt(a,10);
				sum = sum + a;
			var n = sum.toFixed(2);
			
		}
		//alert('total sum =' +n);
    document.getElementById("total_sum").value = Number(n).toLocaleString('en');
}
function changeTotal(counter)
{	
    quantity = document.getElementById("quantity_"+counter).value;
    rate = document.getElementById("rate_"+counter).value;
    total_amount = quantity*rate;
    document.getElementById("total_amount_"+counter).value = total_amount;
    getTotal();
}

function netAmount(){
	//alert('hello');
	var total_sum=document.getElementById('total_sum').value;
	total_sum = total_sum.replace(/\,/g,'');

	var discount_amount=document.getElementById('discount_amount').value;

	var net_amout = (total_sum-discount_amount).toFixed(2);
	//alert(net_amout);
	net_amout = Number(net_amout).toLocaleString('en');
	$('#net_amout').val(net_amout);
	//alert (net_amout);
}
function addmedicine(){

var	t = '<tr class="medicineDetail"><td><input	type="hidden" name="detailid[]" value=""><input type="text" name="quantity[]" value=""></td><td><input type="text" name="medicine_name[]" value=""></td> <td><input type="text" name="rate[]" value=""></td><td><input type="text" class="total_amount" name="total_amount[]" value="" onchange="getTotal()"></td> <td><input type="button" class="" value="Delete medicine" id="delButton" ></td></tr>';
	/*var wrapper         = $(".FormTbl");*/
	$("#printtable> tbody").append(t);

};
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

<?php
function convert_number($number)
{
if (($number < 0) || ($number > 999999999))
{
throw new Exception("Number is out of range");
}

$Gn = floor($number / 100000);  /* Millions (giga) */
$number -= $Gn * 100000;
$kn = floor($number / 1000);     /* Thousands (kilo) */
$number -= $kn * 1000;
$Hn = floor($number / 100);      /* Hundreds (hecto) */
$number -= $Hn * 100;
$Dn = floor($number / 10);       /* Tens (deca) */
$n = $number % 10;               /* Ones */

$res = "";

if ($Gn)
{
$res .= convert_number($Gn) . " Lacs";
}

if ($kn)
{
$res .= (empty($res) ? "" : " ") .
convert_number($kn) . " Thousand";
}

if ($Hn)
{
$res .= (empty($res) ? "" : " ") .
convert_number($Hn) . " Hundred";
}

$ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
"Nineteen");
$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
"Seventy", "Eigthy", "Ninety");

if ($Dn || $n)
{
if (!empty($res))
{
$res .= " and ";
}

if ($Dn < 2)
{
$res .= $ones[$Dn * 10 + $n];
}
else
{
$res .= $tens[$Dn];

if ($n)
{
$res .= "-" . $ones[$n];
}
}
}

if (empty($res))
{
$res = "zero";
}

return $res;
}

?>
