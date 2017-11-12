<?php
include('classes/call.php');
$data = '';
$id = $_POST['id'];
$memodata = $_POST['memodata'];
$data['memo'] = $memodata;
$mydb->updatequery('tbl_sales', $data, 'id=' . $id);
die('save vo');
?>