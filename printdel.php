<?php
  $print_id =  $_GET['print_id'];
  $mydb->deleteQuery('tbl_print_temp_med','print_id='.$print_id);
  $mydb->deleteQuery('tbl_temp_print_name','print_id='.$print_id);
  $url = ADMINURLPATH.'print&print_id='.$print_id;
  	$mydb->redirect($url);
 ?>
