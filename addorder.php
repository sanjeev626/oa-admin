<?php
	session_start();
	error_reporting(0);
	include('classes/call.php');
	
	$id=$_POST['user_id'];
	
			$medicine_id = $mydb->getValue('id','tbl_medicine','medicine_name="'.$_POST['medicinename'].'"');
			$_SESSION['user_id'] =$_POST['user_id'];
			$_SESSION['date_order'] =$_POST['date_order'];
			$_SESSION['image_value']=$_POST['image_ids'];
			$med=preg_replace('/\s+/S'," ",$_POST['medicinename']);

			$data1='';
			$data1['session_id'] =$_POST['user_id']; 
			$data1['medicine_id'] = $medicine_id;
			$data1['medicine_name'] = $_POST['medicinename'];
			$data1['medicine_type']=$_POST['dose'];
			$data1['quantity']=$_POST['quantity'];
			$mydb->insertQuery('tbl_temporder',$data1);		
	 


		
?>