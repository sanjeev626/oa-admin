<?php
include('../classes/call.php');
if($_POST)
{
$q=$_POST['search'];

	$invoice=mysql_query("SELECT invoice_no from tbl_creditmemo WHERE invoice_no LIKE '$q%'");
	$count_invoice_no=mysql_num_rows($invoice);
	if($count_invoice_no>0)
	{

	echo "<b>INVOICE NUMBER</b> ";
	echo "<br>";
	}
//$sql_res=mysql_query("SELECT id,item_description FROM tbl_stock WHERE item_description LIKE '$q%' ORDER BY id LIMIT 5");
//$sql_res="SELECT tc.id,tc.invoice_no,ts.item_description FROM tbl_creditmemo tc INNER JOIN tbl_stock ts ON ts.creditmemo_id=tc.id WHERE tc.invoice_no LIKE '$q%' OR ts.item_description LIKE '$q%' GROUP BY tc.id ";
 	
 	$sql_res=mysql_query("SELECT id,invoice_no FROM tbl_creditmemo WHERE invoice_no LIKE '$q%' ORDER BY id LIMIT 5");
	while($row=mysql_fetch_array($sql_res))
	{

	$id = $row['id'];
	$title=$row['invoice_no'];

	?>
	<style type="text/css">	
		a
		{
			color: #888;
			text-decoration: none;
		}
	</style>
	
	<a href = "index.php?manager=stock&invoice_id=<?php echo $id; ?>">
		<div class="show" align="left">
		<span class="name"><?php echo $title ?></span>
		</div>
	</a>
	<?php
	}


	$item=mysql_query("SELECT item_description from tbl_stock WHERE item_description LIKE '$q%'");
	$count_item_no=mysql_num_rows($item);
	if($count_item_no>0)
	{
	echo "<b>MEDICINE NAME</b> ";
	}
	$sql_res1=mysql_query("SELECT id,item_description FROM tbl_stock WHERE item_description LIKE '$q%' ORDER BY id LIMIT 5");
//$sql_res="SELECT tc.id,tc.invoice_no,ts.item_description FROM tbl_creditmemo tc INNER JOIN tbl_stock ts ON ts.creditmemo_id=tc.id WHERE tc.invoice_no LIKE '$q%' OR ts.item_description LIKE '$q%' GROUP BY tc.id ";
// 	$sql_res=mysql_query("SELECT id,invoice_no FROM tbl_creditmemo WHERE invoice_no LIKE '$q%' ORDER BY id LIMIT 5");

while($row1=mysql_fetch_array($sql_res1))
	{

	$id1 = $row1['id'];
	$title1=$row1['item_description'];

	?>

	<style type="text/css">	
		a
		{
			color: #888;
			text-decoration: none;
		}
	</style>

	
	<a href = "index.php?manager=stock&addtolist=<?php echo $id1; ?>">
		<div class="show" align="left">
		<span class="name"><?php echo $title1 ?></span>
		</div>
	</a>
	<?php
	}
}
?>
