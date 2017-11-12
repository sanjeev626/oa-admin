<?php
if(isset($_GET['mid']))
	$medicine_id = $_GET['mid'];
else
	$medicine_id = 0;
?>
<form action="" method="post" name="frmFix">    
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <tr class="TitleBar">
        <td colspan="9" class="TtlBarHeading">Medicine Sales Details : <?php echo $mydb->getValue('medicine_name','tbl_medicine','id='.$medicine_id);?></td>
    </tr>
    <tr>
        <td width="2%" valign="top" class="titleBarB" align="center"><strong>Sales ID</strong></td>
        <td width="20%" valign="top" class="titleBarB" align="center"><strong>Date</strong></td>
        <td width="20%" valign="top" class="titleBarB" align="center"><strong>Client Name</strong></td>
        <td width="12%" valign="top" class="titleBarB" align="center"><strong>Quantity</strong></td>
        <td width="6%" valign="top" class="titleBarB" align="center"><strong>Sales Batch</strong></td>
        <td width="6%" valign="top" class="titleBarB" align="center"><strong>Stock ID</strong></td>
        <td width="8%" valign="top" class="titleBarB" align="center"><strong>Stock</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Total Sales</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>In stock</strong></td>
    </tr>
<?php
    $totalQty = 0;
    $result = $mydb->getQuery("*", "tbl_order", "medicine_id=".$medicine_id.' ORDER BY stock_id ASC');
	while ($rasOrder = $mydb->fetch_array($result)) {
	$sales_id = $rasOrder['sales_id'];
	$rasSales = $mydb->getArray('id,client_id,date','tbl_sales','id='.$rasOrder['sales_id']);
	$rasStock = $mydb->getArray('id,batch_number,stock,sales','tbl_stock','id='.$rasOrder['stock_id']);
	$client_name = $mydb->getValue('name','users','id='.$rasSales['client_id']);
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top"><?php echo $rasSales['id']; ?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $rasSales['date'];?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $client_name;?></td>
        <td class="titleBarA" valign="top"><?php echo $rasOrder['quantity']; $totalQty = $totalQty+$rasOrder['quantity'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['batch_number'];?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $rasStock['id']; ?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['stock'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['sales'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasStock['stock']-$rasStock['sales'];?></td>
    </tr>
    <?php
    } 
	?>   
    <tr>
        <td class="titleBarA" align="center" valign="top"><?php //echo $rasSales['id']; ?></td>
        <td class="titleBarA" align="center" valign="top"><?php //echo $rasSales['date'];?></td>
        <td class="titleBarA" align="center" valign="top"><?php //echo $client_name;?></td>
        <td class="titleBarA" valign="top"><strong><?php echo $totalQty;?></strong></td>
        <td class="titleBarA" valign="top"><?php //echo $rasOrder['sales'];?></td>
        <td class="titleBarA" valign="top"><?php //echo $stock = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id); ?></td>
        <td class="titleBarA" valign="top"><?php //echo $sales = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id); ?></td>
        <td class="titleBarA" valign="top"><?php //echo $stock-$sales;?></td>
    </tr>
</table>
</form>