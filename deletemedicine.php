<?php
include('classes/call.php');
$id = $_POST['id'];
echo $id;
$query = $mydb->deleteQuery('tbl_orderreview','id='.$id );
//$mydb->deleteQuery('tbl_orderreview','id="'.$id.'"','1');
die('delete');
?>