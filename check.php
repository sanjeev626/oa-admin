<?php
// This is a sample code in case you wish to check the username from a mysql db table
 require_once("../classes/call.php");
if(isset($_POST['invoice_no'])) {
$invoice_no = $_POST['invoice_no'];
 


 
$sql_check = mysql_query("select id from tbl_creditmemo where invoice_no='".$invoice_no."'");
// or die(mysql_error());
 
if(mysql_num_rows($sql_check)) {
    echo '<font color="red">The invoice_no <strong>'.$invoice_no.'</strong>'.
' is already in database.</font>';
} else {
    echo 'OK';
}
}
?>