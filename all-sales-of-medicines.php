<?php
if(isset($_GET['mid']))
    $medicine_id = $_GET['mid'];
else
    $medicine_id = 0;

if(isset($_POST['btnSubmit']))
{
    for($i=0;$i<count($_POST['stock_id']); $i++)
    {
        $stock_id = $_POST['stock_id'][$i];
        $sales = $_POST['sales'][$i];
        $data='';
        $data['sales'] = $sales;
        $mydb->updateQuery('tbl_stock',$data,'id='.$stock_id);
    }
}
?>

<form action="" method="post" name="frmFix">    
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <tr class="TitleBar">
        <td colspan="11" class="TtlBarHeading">Stock - ERROR - FIX</td>
    </tr>
    <tr>
        <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Date</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Invoice No.</strong></td>
        <td width="15%" valign="top" class="titleBarB" align="center"><strong>Distributor</strong></td>
        <td width="15%" valign="top" class="titleBarB" align="center"><strong>Medicine Name</strong></td>
        <td width="7%" valign="top" class="titleBarB" align="center"><strong>Batch No.</strong></td>
        <td width="12%" valign="top" class="titleBarB" align="center"><strong>Stock</strong></td>
        <td width="5%" valign="top" class="titleBarB" align="center"><strong>Sales</strong></td>
        <td width="8%" valign="top" class="titleBarB" align="center"><strong>Total Purchase</strong></td>
        <td width="8%" valign="top" class="titleBarB" align="center"><strong>Total Sales</strong></td>
        <td width="8%" valign="top" class="titleBarB" align="center"><strong>In stock</strong></td>
    </tr>
    <?php
    $counter = 0;
    $total_stock = 0;
    $result = $mydb->getQuery("ts.*,tc.invoice_nepali_date,tc.invoice_no,td.fullname", "tbl_stock ts INNER JOIN tbl_creditmemo tc ON ts.creditmemo_id=tc.id INNER JOIN tbl_distributor td ON tc.distributor_id=td.id", "medicine_id=".$medicine_id);
    while ($rasStock = $mydb->fetch_array($result)) {
        $medicine_id = $rasStock['medicine_id'];
    $style = '';
    if($rasStock['stock']!=$rasStock['sales'])
        $style='style="background-color: red; color: white;"';
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top" <?php echo $style;?>><input name="stock_id[]" type="hidden" id="stock_id[]" value="<?php echo $rasStock['id']; ?>"/>
            <?php echo ++$counter; ?></td>
        <td class="titleBarA" align="center" valign="top" <?php echo $style;?>><?php echo $rasStock['invoice_nepali_date'];?></td>
        <td class="titleBarA" align="center" valign="top" <?php echo $style;?>><?php echo $rasStock['invoice_no'];?></td>
        <td class="titleBarA" align="center" valign="top" <?php echo $style;?>><?php echo $rasStock['fullname'];?></td>
        <td class="titleBarA" align="center" valign="top" <?php echo $style;?>><?php echo $mydb->getValue('medicine_name','tbl_medicine','id='.$medicine_id);?></td>
        <td class="titleBarA" align="center" valign="top" <?php echo $style;?>><?php echo $rasStock['batch_number'];?></td>
        <td class="titleBarA" valign="top" <?php echo $style;?>><?php echo $rasStock['stock']; $total_stock=$rasStock['stock']+$total_stock;?></td>
        <td class="titleBarA" valign="top" <?php echo $style;?>><input type="text" name="sales[]" style="width:50px; color: #000000;" value="<?php echo $rasStock['sales'];?>"></td>
        <td class="titleBarA" valign="top" <?php echo $style;?>><?php echo $stock = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id); ?></td>
        <td class="titleBarA" valign="top" <?php echo $style;?>><?php echo $sales = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id); ?></td>
        <td class="titleBarA" valign="top" <?php echo $style;?>><?php echo $stock-$sales;?></td>
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
        <td class="titleBarA" valign="top"><?php echo $total_stock;?></td>
        <td class="titleBarA" valign="top"><input type="submit" name="btnSubmit" class="button" value="Update"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
    </tr>
</table>
</form>



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