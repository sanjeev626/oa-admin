<?php
if(isset($_GET['sales_id']))
{
	$sales_id=$_GET['sales_id'];

	$item_details = $mydb->getValue('item_details','tbl_sales','id='.$sales_id);
	if(!empty($item_details))
	{
		$aa = explode("///",$item_details);
		//print_r($aa);
		$mydb->deleteQuery('tbl_print_temp_med','print_id ='.$sales_id);
		$item_name = $aa['0'];
		$total_amount1 = $aa['1'];
		$discount1 = $aa['2'];
		$net_amount1 = $aa['3'];
		$data='';
		$data['print_id'] = $sales_id;
		$data['qty'] = 1;
		$data['med_name'] = $aa['0'];
		$data['rate'] = $aa['1'];
		$data['amount'] = $aa['1'];
		$mydb->insertQuery('tbl_print_temp_med',$data);

	}

	//echo $mydb->getArray('client_id,date,total_amount,discount_amount,discount_percentage,net_amount','tbl_sales','id='.$sales_id,'1');
	$result=$mydb->getArray('client_id,date,total_amount,discount_amount,discount_percentage,net_amount','tbl_sales','id='.$sales_id);
	$result1=$mydb->getArray('name,address,phone','users','id="'.$result['client_id'].'"');
	//echo "Hello, I am here.";exit();

	 //$tempPrintName = $mydb->getCount('*','tbl_temp_print_name','print_id ='.$_GET['print_id']);
	 //$tempPrintMed = $mydb->getCount('*','tbl_print_temp_med','print_id ='.$_GET['print_id']);
//if($tempPrintName=='0')
{
//	echo 'chaena';
	?>

	<div id="printarea">
		 <table  width="270px" style="font-size:10px;">
			<tr>
				<td colspan="6" align="center">
					<table  width="100%" style="font-size:12px;">
					<tr>
						<td style="font-size:13px;" colspan="4" align="center">
						<!-- <span style="font-size:16px; font-weight:bold;">Online Aushadhi Pvt. Ltd.</span></br>A Delivery Partner for S.M Pharma-->ORDER CONFIRMATION</br>onlineaushadhi.com
						</td>
					</tr>

					<tr>
						<td colspan="4" align="center" style="padding-bottom:15px;"><!-- PAN NO : 604311632, -->PHONE : 9841568568</td>
					</tr>
					<tr>
				 		<td width="50px;"><strong>ESTIMATE No.</strong></td>
				 		<td><strong><?php echo str_pad($sales_id,6,0,STR_PAD_LEFT)?></strong></td>
				 		<td colspan="2" align="right"><strong>DATE:&nbsp;&nbsp;<?php echo date("d-m-Y");?></strong></td>
				 	</tr>

					<tr>
						<td colspan="1" style="width:100px;"><strong>M/S</strong></td>
						<td colspan="3" align="left"><?php  echo ucfirst($result1['name']);?></td>
					</tr>
					<tr>
						<td colspan="1"><strong>Address</strong></td>
						<td colspan="3" align="left">
							<?php
								//if(!empty($result1['house_no'])){echo "House no : ".$result1['house_no'].',';}
								//if(!empty($result1['street_name'])){echo $result1['street_name'].',';}
								//if(!empty($result1['ward_no'])){echo $result1['ward_no'].',';}
								if(!empty($result1['address'])){echo $result1['address'];}
							?>
						</td>
					</tr>
					<tr>
						<td colspan="1" style="padding-bottom:15px;"><strong>Contact No.</strong></td>
						<td colspan="3" style="padding-bottom:15px;" align="left"><?php  echo $result1['phone'];?></td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td width="25px" align="center" style="border-bottom:1pt solid black; font-weight:bold; font-size:9px;"><strong>Qty</strong></td>
				<td width="100px" style="border-bottom:1pt solid black; font-weight:bold; font-size:9px;"><strong>Particulars</strong></td>
				<td width="37px" style="border-bottom:1pt solid black; font-weight:bold; font-size:9px;"><strong>Batch</strong></td>
				<td width="37px" style="border-bottom:1pt solid black; font-weight:bold; font-size:9px;"><strong>Expiry</strong></td>
				<td width="25px" style="border-bottom:1pt solid black; font-weight:bold; font-size:9px;" align="right" ><strong>Rate</strong></td>
				<td width="45px" style="border-bottom:1pt solid black; font-weight:bold; font-size:9px;" align="right" ><strong>Amount</strong></td>
			</tr>
			<?php
			if(empty($item_details))
			{
				$count=1;
				$total_amount1 = 0;
				$result3=$mydb->getQuery("*","tbl_orderreview","sales_id=".$sales_id);
				while($result2=$mydb->fetch_array($result3))
				{
					$medicine_id = $result2['medicine_id']; 
					$rasStock = $mydb->getArray('id,sales,stock,pack,rate,sp_per_tab,expiry_date,batch_number','tbl_stock','medicine_id="'.$medicine_id.'" AND stock>sales ORDER BY expiry_date ASC LIMIT 1');
					$stock_id = $rasStock['id'];
					//$rasStock = $mydb->getArray('expiry_date,batch_number','tbl_stock','id='.$stock_id);
					$sub_total = $rasStock['sp_per_tab']*$result2['quantity'];
					$total_amount1 = $total_amount1+$sub_total;
					$net_amount1 = $total_amount1;
				?>
				<tr>
					<td valign="top" style="font-size:9px;" align="center"><?php echo $result2['quantity'];?></td>
					<td valign="top" style="font-size:9px; padding-right:5px;"><?php echo $result2['medicine_name'];?></td>
					<td valign="top" style="font-size:9px;"><?php echo $rasStock['batch_number'];?></td>
					<td valign="top" style="font-size:9px;"><?php echo substr($rasStock['expiry_date'],0,7);?></td>
					<td valign="top" style="font-size:9px;" align="right"><?php echo $rasStock['sp_per_tab'];?></td>
					<td valign="top" style="font-size:9px;" align="right"><?php echo number_format(($sub_total),2);?></td>
				</tr>
				<?php
				$count++;
				}
			}
			else
			{ 
				?>
				<tr>
					<td valign="top" align="center">1</td>
					<td valign="top"><?php echo $item_name;?></td>
					<td valign="top" align="right"><?php echo $total_amount1;?></td>
					<td valign="top" align="right"><?php echo number_format($total_amount1,2);?></td>
				</tr>
				<?php 
			} 
			?>

			<tr>
				<td valign="top" colspan="5" align="right" style="border-top:1pt solid black;"><strong>Total amount</strong></td>
				<td valign="top" align="right" style="border-top:1pt solid black;"><?php if(isset($total_amount1)) echo number_format($total_amount1,2); else echo $result['total_amount'];?></td>
			</tr>
			<tr>
				<td valign="top" colspan="5"  align="right"><strong>Discount</strong></td>
				<td valign="top" align="right"><?php //if(isset($discount1)) echo number_format($discount1,2); else echo number_format($result['discount_amount'],2);?></td>
			</tr>
			<tr>
				<td valign="top" colspan="5" align="right" style="border-top:2pt solid black;"><strong>Net Amount</strong></td>
				<td valign="top" align="right" style="border-top:2pt solid black;"><strong><?php $value1=$result['net_amount']; if(isset($net_amount1)) echo number_format($net_amount1,2); else echo number_format($value1,2);?></strong></td>
			</tr>
			<tr>
				<td colspan="6" style="padding-bottom:3px;"><strong>In Words: </strong><?php if(isset($net_amount1)) echo convert_number($net_amount1); else echo convert_number($value1);?>&nbsp;Rupees Only</td>
			</tr>

			<tr>
				<td valign="top" colspan="6" style="border-top:1pt solid black; padding-top:3px;"><strong>Note : </strong><br>
				<ul>
					<li>- Medicines once sold can be taken back or exchanged in next order strictly based on our return policy. Please check our website for more details.</li>
					<li>- Store medicines in cool, dry place &amp; out of reach of children.</li>
					<li>- This is just a computer generated estimate.</li>
				</ul>
			    </td>

			</tr>
			<tr>
				<td valign="bottom" colspan="6" style="padding-top:40px; text-align:right;">Signature</td>

			</tr>
			</table>
			</div>

		<table width="100%" class="FormTbl">
			<tr>
	 			<td><input type="button" name="btnprint" id="btnprint" value="Print" class="button" onclick="printDiv('printarea')">
					<input type="button" name="btnedit"  value="Edit" class="button" onclick="window.location='<?php echo SITEROOTADMIN.ADMINURLPATH?>print_edit&print_id=<?php echo $sales_id;?>';">
				</td>


			</tr>
		</table>

	<?php
}

	
?>


<?php
}

else{
	echo "No result found";
}
?>
<script type="text/javascript">

function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();
		 window.location = "<?php echo SITEROOTADMIN.ADMINURLPATH?>printdel&print_id=<?php echo $_GET['print_id'];?>";
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
