<?php
if(isset($_POST['btnSubmit']))
{
    for($i=0;$i<count($_POST['stock_id']);$i++)
    {
        $stock_id = $_POST['stock_id'][$i];
        $shop_stock = $_POST['shop_stock'][$i];

        $data='';
        $data['shop_stock'] = $shop_stock;
        $mydb->updateQuery('tbl_stock_shop',$data,'id='.$stock_id);
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
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Shop Stock</strong></td>
    </tr>
    <?php
    $counter = 0;
    //echo $mydb->getQuery("ts.*,tm.medicine_name", "tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id", "stock>sales GROUP BY ts.medicine_id ORDER BY tm.medicine_name ASC",'1');
    $result = $mydb->getQuery("*", "tbl_stock_shop");
	while ($rasStock = $mydb->fetch_array($result)) {
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top"><input name="stock_id[]" type="hidden" id="stock_id[]" value="<?php echo $rasStock['id']; ?>"/><?php echo ++$counter; ?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['medicine_name'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['total_stock'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['total_sales_from_stock'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['total_sales'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['display_stock'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['actual_stock'];?></td>
        <td class="titleBarA" valign="top"><input name="shop_stock[]" type="text" id="shop_stock[]" value="<?php echo $rasStock['shop_stock'];?>"/></td>
    </tr>
    <?php
    }
	?>    
    <tr>
        <td class="titleBarA" align="center" valign="top"></td>
        <td class="titleBarA" align="center" valign="top"></td>
        <td class="titleBarA" align="center" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"><input type="submit" name="btnSubmit" class="button" value="Update"></td>
    </tr>
</table>
</form>