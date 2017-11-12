<?php 
include('classes/call.php');
if(isset($_POST["invoice_info"]))
{

  
  $invoice_number = $_POST["invoice_info"]; 

  //check username in db
  $results = $mydb->getCount('id','tbl_creditmemo','invoice_no="'.$invoice_number.'"');
  
 
  //if returned value is more than 0, username is not available
  if($results) {
    $output='<img src="images/delete.png" alt="already_exist" id="image_val" height="25" width="25"/>';
    $output2='no';

  }else{
    $output='<img src="images/tick.gif" alt="available"  id="image_val" height="25" width="25"/>';
    $output2='yes';
  }
      $data=array("value1"=>$output,"value2"=>$output2 ); 
       echo json_encode($data);
}
?>