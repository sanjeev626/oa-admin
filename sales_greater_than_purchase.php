<form action="" method="post" name="frmFix">    
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <tr class="TitleBar">
        <td colspan="9" class="TtlBarHeading">Medicine Sales Details : <?php echo $mydb->getValue('medicine_name','tbl_medicine','id='.$medicine_id);?></td>
    </tr>
    <tr>
        <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
        <td width="20%" valign="top" class="titleBarB" align="center"><strong>Medicine Name</strong></td>
        <td width="12%" valign="top" class="titleBarB" align="center"><strong>Total Purchase</strong></td>
        <td width="6%" valign="top" class="titleBarB" align="center"><strong>Total Sales</strong></td>
        <td width="6%" valign="top" class="titleBarB" align="center"><strong>Difference</strong></td>
    </tr>
<?php
    $counter = 0;
    $result = $mydb->getQuery("*", "tbl_order", '1 GROUP BY medicine_id ORDER BY stock_id ASC');
	while ($rasOrder = $mydb->fetch_array($result)) {
	$medicine_id = $rasOrder['medicine_id'];
	$total_purchase = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id);
	$total_sales = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id);
	$difference = $total_purchase-$total_sales;
	if($difference<0)
	{
	//$rasSales = $mydb->getArray('id,client_id,date','tbl_sales','id='.$rasOrder['sales_id']);
	//$rasStock = $mydb->getArray('id,batch_number,stock,sales','tbl_stock','id='.$rasOrder['stock_id']);
	//$medicine_name = $mydb->getValue('fullname','users','id='.$rasSales['client_id']);
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top"><?php echo ++$counter; ?></td>
        <td class="titleBarA" align="center" valign="top"><a href="<?php echo ADMINURLPATH;?>medicine_sales_details&mid=<?php echo $medicine_id;?>" target="_blank"><?php echo $mydb->getValue('medicine_name','tbl_medicine','id='.$medicine_id);?></a></td>
        <td class="titleBarA" valign="top"><?php echo $total_purchase;?></td>
        <td class="titleBarA" valign="top"><?php echo $total_sales;?></td>
        <td class="titleBarA" valign="top"><?php echo $difference; ?></td>
    </tr>
    <?php
    }
	}
	?>
</table>
</form>