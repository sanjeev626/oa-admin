<?php
	session_start();
	error_reporting(0);
	include('classes/call.php');
	$id=$_POST['user_id'];
	$medicinename_id = $_POST['medicinename'];
	$aa = explode('-', $medicinename_id);
	$stock_id = $aa['0'];
	$medicinename = $aa['1'];
	$medicine_id = $aa['2'];
	$_SESSION['user_id'] =$_POST['user_id'];
	$_SESSION['date_order'] =$_POST['date_order'];
	$_SESSION['image_value']=$_POST['image_ids'];

	$data1='';
	$data1['session_id'] =$_POST['user_id']; 
	$data1['user_id'] =$_POST['user_id']; 
	$data1['stock_id'] = $stock_id;
	$data1['medicine_id'] = $medicine_id;
	$data1['medicine_name'] = $medicinename;
	$data1['quantity']=$_POST['quantity'];
	$data1['medicine_type']=$_POST['dose'];
	$mydb->insertQuery('tbl_temporder',$data1);		
?>