<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
  <tr>
    <td width="2%" valign="top" class="titleBarA" align="center"><strong>S.N</strong></td>
    <td class="titleBarA"><strong>Medicine Name</strong></td>
    <td width="20%" class="titleBarA"><strong>Total Received</strong></td>
    <td width="20%" class="titleBarA"><strong>Total Sold</strong></td>
    <td width="20%" class="titleBarA"><strong>Balance in stock</strong></td>
    <td width="8%" class="titleBarA">&nbsp;</td>
  </tr>
<?php
$sn = 0;
$result = $mydb->getQuery('*','tbl_stock','sales<0 ORDER BY item_description');
  while($rasSales = $mydb->fetch_array($result))
  {
     $medicine_id = $rasSales['medicine_id'];
       $item_description = $rasSales['item_description'];
       $total_in_stock = $mydb->getSum('stock','tbl_stock','medicine_id='.$medicine_id);
       $total_sales = $mydb->getSum('quantity','tbl_order','medicine_id='.$medicine_id);
       $balance_stock = $total_in_stock-$total_sales;
       //echo $item_description.' / Total Received : '.$total_in_stock.' / Total Sold : '.$total_sales.' / Balance in stock :'.$balance_stock;
       //echo "<br>";
       ?>
  <tr>
    <td class="titleBarB" width="2%" valign="top" align="center"><?php echo ++$sn;?></td>
    <td class="titleBarB"><?php echo $item_description;?></td>
    <td width="20%" class="titleBarB"><?php echo $total_in_stock;?></td>
    <td width="20%" class="titleBarB"><?php echo $total_sales;?></td>
    <td width="20%" class="titleBarB"><?php echo $balance_stock;?></td>
    <td width="8%" class="titleBarB"><?php //echo $item_description;?></td>
  </tr>
       <?php
    } 
?>
</table>