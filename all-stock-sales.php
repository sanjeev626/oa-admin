<?php
if(isset($_POST['btnSubmit']))
{
    for($i=0;$i<count($_POST['stock_id']);$i++)
    {
        $stock_id = $_POST['stock_id'][$i];
        $actual_sales = $_POST['actual_sales'][$i];

        $data='';
        $data['sales'] = $actual_sales;
        $mydb->updateQuery('tbl_stock',$data,'id='.$stock_id);
    }
}
?>

<form action="" method="post" name="frmFix">    
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <tr class="TitleBar">
        <td colspan="9" class="TtlBarHeading">Stock - ERROR - FIX</td>
    </tr>
    <tr>
        <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
        <td width="20%" valign="top" class="titleBarB" align="center"><strong>Medicine Name</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Total Stock</strong></td>
        <td width="15%" valign="top" class="titleBarB" align="center"><strong>Total Sales from Stock</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Total Sales</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Display Stock</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Actual Stock</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Action</strong></td>
    </tr>
    <?php
    $counter = 0;
    //echo $mydb->getQuery("ts.*,tm.medicine_name", "tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id", "stock>sales GROUP BY ts.medicine_id ORDER BY tm.medicine_name ASC",'1');
    $result = $mydb->getQuery("ts.id,ts.medicine_id,tm.medicine_name", "tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id", "stock>sales GROUP BY ts.medicine_id ORDER BY tm.medicine_name ASC");
	while ($rasStock = $mydb->fetch_array($result)) {
		$medicine_id = $rasStock['medicine_id'];
        //print_r($rasStock);
        $stock_ts = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id);
        $sales_ts = $mydb->getSum('sales','tbl_stock','medicine_id='.$medicine_id);
        $sales = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id);
        $ds = $stock_ts-$sales_ts;
        $as = $stock_ts-$sales;
        if($ds!=$as)
        {
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top"><input name="stock_id[]" type="hidden" id="stock_id[]" value="<?php echo $rasStock['id']; ?>"/><?php echo ++$counter; ?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $rasStock['medicine_name'];?></td>
        <td class="titleBarA" valign="top"><?php echo $stock_ts; ?></td>
        <td class="titleBarA" valign="top"><?php echo $sales_ts; ?></td>
        <td class="titleBarA" valign="top"><?php echo $sales; ?></td>
        <td class="titleBarA" valign="top"><?php echo $stock_ts-$sales_ts; ?></td>
        <td class="titleBarA" valign="top"><?php echo $stock_ts-$sales; ?></td>
        <td class="titleBarA" valign="top"><a href="<?php echo ADMINURLPATH;?>all-sales-of-medicines&mid=<?php echo $medicine_id;?>" target="_blank">Detail</a></td>
    </tr>
    <?php
    }
	/*
    $resRest = $mydb->getQuery('*','tbl_stock','medicine_id='.$medicine_id.' AND id!='.$rasStock['id']);
	while ($rasRest = $mydb->fetch_array($resRest)) {
		$medicine_id = $rasRest['medicine_id'];
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top"><?php echo ++$counter; ?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $mydb->getValue('medicine_name','tbl_medicine','id='.$medicine_id);?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $rasStock['batch_number'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasRest['stock'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasRest['sales'];?></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"><?php echo $stock = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id); ?></td>
        <td class="titleBarA" valign="top"><?php echo $sales = $mydb->getSum('quantity','tbl_order','status="1" AND medicine_id='.$medicine_id); ?></td>
        <td class="titleBarA" valign="top"><?php echo $stock-$sales;?></td>
    </tr>
    <?php
	}
    */
    } 
	?>    
    <tr>
        <td class="titleBarA" align="center" valign="top"></td>
        <td class="titleBarA" align="center" valign="top"></td>
        <td class="titleBarA" align="center" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"><input type="submit" name="btnSubmit" class="button"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
    </tr>
</table>
</form>