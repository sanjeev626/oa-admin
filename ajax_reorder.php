<?php 

  session_start();

  error_reporting(0);

  include('classes/call.php');



if(isset($_GET['reorder_id']))

{

  $id=$_GET['reorder_id'];

  $session_id=$mydb->getValue('client_id','tbl_sales','id='.$id);

  $result1 =$mydb->getQuery('*','tbl_orderreview','sales_id='.$id);      



  while ($checkoutorder = $mydb->fetch_array($result1))

  {   

    $quantity_by_user=$checkoutorder['quantity'];

    $med=$checkoutorder['medicine_name'];

    $med_dose=$checkoutorder['medicine_type'];

    $data="";

    $data['session_id']=$session_id;

    $data['quantity']=$quantity_by_user;

    $data['medicine_name']=$med;

    $data['medicine_type']=$med_dose;

    $mydb->insertQuery('tbl_temporder',$data);

  }

}

?>