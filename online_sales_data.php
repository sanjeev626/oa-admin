<?php
$counter_sales_id = "1087";

?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <tr class="TitleBar">
        <td colspan="9" class="TtlBarHeading">Counter Sales - Data</td>
    </tr>
    <tr>
        <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
        <td width="20%" valign="top" class="titleBarB" align="center"><strong>Date</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Sub Total</strong></td>
        <td width="15%" valign="top" class="titleBarB" align="center"><strong>Discount</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Grand Total</strong></td>
    </tr>
    <?php
    $counter = 0;
    $net_amount=0;
    $total_discount=0;
    $total_profit=0;
    $result = $mydb->getQuery("*", "tbl_sales", "client_id!=".$counter_sales_id);
	while ($rasSales = $mydb->fetch_array($result)) 
	{
		$sales_id = $rasSales['id'];
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top"><?php echo ++$counter; ?></td>
        <td class="titleBarA" valign="top"><?php echo $rasSales['delivery_date'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasSales['total_amount']; ?></td>
        <td class="titleBarA" valign="top"><?php echo $rasSales['discount_amount']; $total_discount+=$rasSales['discount_amount']; ?></td>
        <td class="titleBarA" valign="top"><?php echo $rasSales['net_amount']; $net_amount+=$rasSales['net_amount']; ?></td>
    </tr>
    <tr>
        <td colspan="5">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <tr>
        <td valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
        <td valign="top" class="titleBarB" align="center"><strong>Medicine Name</strong></td>
        <td valign="top" class="titleBarB" align="center"><strong>Quantity</strong></td>
        <td valign="top" class="titleBarB" align="center"><strong>Rate</strong></td>
        <td valign="top" class="titleBarB" align="center"><strong>Total Amount</strong></td>
        <td valign="top" class="titleBarB" align="center"><strong>Cost Price</strong></td>
        <td valign="top" class="titleBarB" align="center"><strong>Cost Total</strong></td>
        <td valign="top" class="titleBarB" align="center"><strong>Profit</strong></td>
    </tr>
    <?php
    $total_amount=0;
    $total_cost_amount=0;
    //echo $mydb->getQuery("tod.*,tst.cp_per_tab", "tbl_order tod INNER JOIN tbl_stock tst ON tst.id=tod.stock_id", "tod.sales_id=".$sales_id,'1'); echo "<br>";
    //$resOrder = $mydb->getQuery("tod.*,tst.cp_per_tab", "tbl_order tod INNER JOIN tbl_stock tst ON tst.medicine_id=tod.medicine_id", "tod.sales_id=".$sales_id);
    $resOrder = $mydb->getQuery("tod.*", "tbl_order tod", "tod.sales_id=".$sales_id);
	while ($rasOrder = $mydb->fetch_array($resOrder)) 
	{
       $cp_per_tab = $mydb->getValue('cp_per_tab','tbl_stock','id='.$rasOrder['stock_id']);
	?>
    <tr>
        <td class="titleBarA" valign="top"><?php echo $rasOrder['id'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasOrder['medicine_name'];?></td>
        <td class="titleBarA" valign="top"><?php echo $rasOrder['quantity']; ?></td>
        <td class="titleBarA" valign="top"><?php echo $rasOrder['Rate']; ?></td>
        <td class="titleBarA" valign="top"><?php echo $rasOrder['total_amount']; $total_amount+=$rasSales['total_amount']; ?></td>
        <td class="titleBarA" valign="top"><?php if($cp_per_tab>0) echo $cp_per_tab; else echo "stock_id : ".$rasOrder['stock_id']; //echo $rasOrder['cp_per_tab']; ?></td>
        <td class="titleBarA" valign="top"><?php echo $cp_per_tab*$rasOrder['quantity']; $total_cost_amount+=$cp_per_tab*$rasOrder['quantity']; ?></td>
        <td class="titleBarA" valign="top"><?php echo $profit = $rasOrder['total_amount']-($cp_per_tab*$rasOrder['quantity']); $total_profit+=$profit; ?></td>
    </tr>	
	<?php
	}
	?>
    <tr>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"><?php echo $rasSales['total_amount'];?></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
        <td class="titleBarA" valign="top"></td>
    </tr>  
    </table>
	</td>
    </tr>
	<?php
    }
	?>    
    <tr>
        <td class="titleBarA" align="center" valign="top"></td>
        <td class="titleBarA" align="center" valign="top"><strong>Gross Profit Margin : </strong><?php $gp = ($total_profit-$total_discount)*100/$net_amount; echo number_format($gp,2);?>%</td>
        <td class="titleBarA" align="center" valign="top"></td>
        <td class="titleBarA" valign="top"><strong>Total Sales : </strong><?php echo $net_amount;?></td>
        <td class="titleBarA" valign="top"><strong>Total Profit : </strong><?php echo $total_profit-$total_discount;?></strong></td>
    </tr>
</table>
</form>