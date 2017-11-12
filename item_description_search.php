<?php
include('../classes/call.php');
if($_POST)
{
$q=$_POST['search'];
$sql_res=mysql_query("SELECT id,medicine_name FROM tbl_medicine WHERE medicine_name LIKE '$q%' ORDER BY id LIMIT 5");
while($row=mysql_fetch_array($sql_res))
{

$id = $row['id'];
$title=$row['medicine_name'];
?>
<style type="text/css">	
	a
	{
		color: #888;
		text-decoration: none;
	}
</style>
<a href = "index.php?manager=stock_manage&medicinelist=<?php echo $id; ?>">
		<div class="show" align="left">
	<span class="name"><?php echo $title ?></span>&nbsp;
	</div>
</a>
<?php
}
}
?>