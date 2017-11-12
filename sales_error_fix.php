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
        <td width="20%" valign="top" class="titleBarB" align="center"><strong>Batch No.</strong></td>
        <td width="12%" valign="top" class="titleBarB" align="center"><strong>Stock</strong></td>
        <td width="6%" valign="top" class="titleBarB" align="center"><strong>Sales</strong></td>
        <td width="6%" valign="top" class="titleBarB" align="center"><strong>Actual Sales</strong></td>
        <td width="8%" valign="top" class="titleBarB" align="center"><strong>Total Purchase</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Total Sales</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>In stock</strong></td>
    </tr>
    <?php
    $counter = 0;
    $result = $mydb->getQuery("*", "tbl_stock", "sales>stock ORDER BY id desc");
	while ($rasStock = $mydb->fetch_array($result)) {
		$medicine_id = $rasStock['medicine_id'];
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top"><input name="stock_id[]" type="hidden" id="stock_id[]" value="<?php echo $rasStock['id']; ?>"/>
            <?php echo ++$counter; ?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $mydb->getValue('medicine_name','tbl_medicine','id='.$medicine_id);?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $rasStock['batch_number'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['stock'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['sales'];?></td>
        <td class="titleBarA" valign="top"><input type="text" name="actual_sales[]" style="width:50px;"></td>
        <td class="titleBarA" valign="top"><?php echo $stock = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id); ?></td>
        <td class="titleBarA" valign="top"><?php echo $sales = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id); ?></td>
        <td class="titleBarA" valign="top"><a href="<?php echo ADMINURLPATH;?>all-sales-of-medicines&mid=<?php echo $medicine_id;?>" target="_blank"><?php echo $stock-$sales;?></a></td>
    </tr>
    <?php
	$resRest = $mydb->getQuery('*','tbl_stock','medicine_id='.$medicine_id.' AND id!='.$rasStock['id']);
	while ($rasRest = $mydb->fetch_array($resRest)) 
    {
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