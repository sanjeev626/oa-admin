<div class="container">
    <div class="row">
        <div class="table-responsive ">
            <table class="table table-bordered refill">
                <h2 class="call"><span><i class="fa fa-reply" aria-hidden="true"></i></span>Purchase Return Section</h2>
                <tr>
                    <th width="5%">S.N</th>
                    <th width="6%">Quantity</th>
                    <th width="20%" style="text-align:left;">Medicine Name</th>
                    <th width="10%">expiry_date</th>
                    <th width="10%">Batch Number</th>
                    <th width="10%">Invoice</th>
                    <th width="20%" style="text-align:left;">Distributors</th>
                    <th width="9%">Contact</th>
                    <th width="5%">&nbsp;</th>
                </tr>                                                
                <?php
                $count=0;
                $return_list = mysql_query("SELECT id,item_description,batch_number,return_quantity,creditmemo_id,stock,sales,expiry_date,DATEDIFF(expiry_date, now()) AS days FROM tbl_stock WHERE return_quantity >0 ORDER BY    return_date");
                
                while ($ras_return_list = $mydb->fetch_array($return_list)) {
                $credit_id = $ras_return_list['creditmemo_id'];
                $disbutor_id = $mydb->getValue('distributor_id', 'tbl_creditmemo', 'id=' . $credit_id);
                $invoice_no = $mydb->getValue('invoice_no', 'tbl_creditmemo', 'id=' . $credit_id);

                $distributor_name = $mydb->getArray('fullname,landline,mobile', 'tbl_distributor', 'id=' . $disbutor_id);
                ?>
                <tr>
                    <td><?php echo ++$count; ?></td>
                    <td><?php echo $ras_return_list['return_quantity']; ?></td>
                    <td style="text-align:left;"><?php echo $ras_return_list['item_description']; ?></td>
                    <td><?php echo $ras_return_list['expiry_date']; ?></td>
                    <td><?php echo $ras_return_list['batch_number']; ?></td>
                    <td><?php echo $invoice_no; ?></td>
                    <td style="text-align:left;"><?php echo $distributor_name['fullname']; ?></td>
                    <td><?php echo $distributor_name['landline'] . ',' . $distributor_name['mobile']; ?></td>
                    <td><a href="<?php echo ADMINURLPATH; ?>stock_edit&id=<?php echo $ras_return_list['id'];?>&do=purchase_return"><img src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>