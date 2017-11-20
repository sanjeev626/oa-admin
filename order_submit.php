<?php
session_start();
include('classes/call.php');
unset($_SESSION['date_order']);
unset($_SESSION['user_id']);

$user_id=$_POST['user_id'];
$user_id_old=$_POST['oldUser'];

 $date_order=$_POST['datedate'];
 $newDate = date("Y-m-d", strtotime($date_order));
 $discount_rate=0;
 $discount_amount=0;
 $image_id=$_POST['image_value'];
 $net_amounts_value=0;
 $val_total=$discount_amount+$net_amounts_value;
 $sales_type = $_POST['sales_type'];


 $data1='';
 $data1['client_id']=$user_id;
 $data1['date']=$newDate;
 $data1['image_id']=$image_id;
 $data1['discount_percentage']=$discount_rate;
 $data1['discount_amount']=$discount_amount;
 $data1['total_amount']=$val_total;
 $data1['net_amount']=$net_amounts_value;
 $data1['sales_type']=$sales_type;
// print_r($data1);
//die();
 $sales_id=$mydb->insertQuery('tbl_sales',$data1);
$result1 = $mydb->getQuery('*','tbl_temporder','session_id='.$user_id_old);

while ($checkoutorder = $mydb->fetch_array($result1))
{
	$data = '';
	$data['sales_id'] = $sales_id;
	$data['medicine_id'] = $checkoutorder['medicine_id'];
	$data['stock_id'] = $checkoutorder['stock_id'];
	$data['medicine_name'] = $checkoutorder['medicine_name'];
	$data['medicine_type']=$checkoutorder['medicine_type'];
	$data['refill_day'] = $checkoutorder['refill_day'];
	$data['quantity'] = $checkoutorder['quantity'];
	// $data['Rate']=$checkoutorder['Rate'];
	// $data['total_amount']=$checkoutorder['total_amount'];

	$mydb->insertQuery('tbl_orderreview',$data);
	
}
$mydb->deleteQuery('tbl_temporder','session_id='.$user_id);

?>

