<?php
error_reporting(E_ALL);
//print_r($_POST);
if(isset($_POST['fsubmit']))
{
	for($i=0;$i<count($_POST['sales_id']);$i++)
	{
		$sales_id = $_POST['sales_id'][$i];
		$item_details = $_POST['item_details'][$i];
		$sql2 = "UPDATE tbl_sales SET item_details='".$item_details."' WHERE id=".$sales_id;
		$mydb->query($sql2);
			//echo $sql2."</br>";
	}
}
?>
<div class="container upload">
    <div class="row">
        <div class="col-md-12">
            <h2><span>Order Form</span></h2>
        </div>
    </div>
	<div class="row">
	<?php
	//echo "test";
	//echo $mydb->getQuery('id,fullname,email','users','user_type="one_time_user"','1');
	// $res = $mydb->getQuery('id,fullname,email','users','user_type="one_time_user"');
	// while($ras = $mydb->fetch_array($res)
	// {
	// 	echo $ras['id']user_type="one_time_user";
	// }
	$cnt=0;
	$sql = 'SELECT tc.id,tc.name,tc.email, ts.id sales_id, ts.total_amount,ts.discount_amount,ts.net_amount,ts.item_details,ts.delivery_date FROM users tc INNER JOIN tbl_sales ts ON tc.id=ts.client_id  WHERE tc.user_type="one_time_user" ORDER BY tc.id';
	$result1 = $mydb->query($sql);
	?>
    <form class="upload" enctype="multipart/form-data" action="" method="POST" id="prescription_uplaod">
	    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
	        <tr>
	            <td class="titleBarB"><strong>S.N</strong></td>
	            <td class="titleBarB"><strong>ID</strong></td>
	            <td class="titleBarB"><strong>fullname</strong></td>
	            <td class="titleBarB"><strong>email</strong></td>
	            <td class="titleBarB"><strong>sales_id</strong></td>
	            <td class="titleBarB"><strong>delivery_date</strong></td>
	            <td class="titleBarB"><strong>Total Amount</strong></td>
	            <td class="titleBarB"><strong>discount_amount</strong></td>
	            <td class="titleBarB"><strong>net_amount</strong></td>
	            <td class="titleBarB"><strong>item_details</strong></td>
	        </tr>
	    <?php
	    while ($rasMember = $mydb->fetch_array($result1)) {
	    	++$cnt;
	    	?>
	    	<tr>
	            <td><?php echo $cnt;?></td>
	            <td><?php echo $rasMember['id'];?></td>
	            <td><?php echo $rasMember['name'];?></td>
	            <td><?php echo $rasMember['email'];?></td>
	            <td><?php echo $rasMember['sales_id'];?><input type="hidden" id="sales_id[]" name="sales_id[]" value="<?php echo $rasMember['sales_id'];?>">
</td>
	            <td><?php echo $rasMember['delivery_date'];?></td>
	            <td><?php echo $rasMember['total_amount'];?></td>
	            <td><?php echo $rasMember['discount_amount'];?></td>
	            <td><?php echo $rasMember['net_amount'];?></td>
	            <td><textarea rows="1" cols="25" id="item_details[]" name="item_details[]" placeholder="item name///total///discount///net total"><?php echo $rasMember['item_details'];?></textarea>         
</td>
	        </tr>
	    	<?php
	    	//echo $cnt.'--'.$rasMember['id'].'--'.$rasMember['fullname'].'--'.$rasMember['email'].'--'.$rasMember['sales_id'].'--'.$rasMember['total_amount'].'--'.$rasMember['discount_amount'].'--'.$rasMember['net_amount'].'--'.$rasMember['item_details']."</br>";

	    }

		?>
	        <tr>
	            <td colspan="9">&nbsp;</td>
	            <td><button type="submit" class="btn btn-default" id="fsubmit" name="fsubmit">Update</button></td>
	        </tr>
		</table>
	</form>
	</div>
</div>