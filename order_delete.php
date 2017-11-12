<?php
	session_start();
	error_reporting(0);
	include('classes/call.php');
	if(isset($_GET['status'])&&$_GET['status']=="med")
	{
		$oid=$_GET['order_id'];
		$_SESSION['date_order']=$_GET['orderdate'];
		$mydb->deleteQuery('tbl_temporder','id='.$oid);	
	}
	elseif(isset($_GET['status'])&&$_GET['status']=="clear")
	{
		$clearid=$_GET['order_id'];
		$_SESSION['date_order']=$_GET['orderdate'];
		$mydb->deleteQuery("tbl_temporder",'session_id='.$clearid);
	}
	else
	{
			echo "error";
	}

			
?>