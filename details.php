<script type="text/javascript">
    function callupdate(gid)
    {

        window.location = '?manager=details&updateid=' + gid;

    }

    function callRefill(refillid) {

        window.location = '?manager=details&refillid=' + refillid;

    }

    function callmail(mailid) {

        window.location = '?manager=details&mailid=' + mailid;

    }

    function notificationdisabled(product)
    {
        window.location="?manager=details&p_name="+product;
    }


    function savememo(id) {
        var id = id;
        var x = document.getElementById("memo"+id);
        /*alert(x.value);*/
        var memodata = x.value;
        var dataString='id='+ id+ '&memodata='+ memodata;
        if(memodata!=''){
            $.ajax({
                url:"addmemo.php",
                data: dataString,
                type: "POST",
                success: function(data) {
//               alert(data);
                }
            })
        }



    }

</script>
<div class="container">

    <?php
    if (isset($_GET['p_name'])) {
        $productnam           = $_GET['p_name'];
        $data                 = '';
        $data['notification'] = '0';
        $mydb->updatequery('tbl_stock', $data, 'item_description="' . $productnam . '"');
        $url = ADMINURLPATH . "details&action=stock_alert";

        $mydb->redirect($url);

    } elseif (isset($_GET['updateid'])) {
        $uid                 = $_GET['updateid'];
        $data                = '';
        $data['upload_from'] = f1;
        $mydb->updatequery('tbl_image', $data, 'id=' . $uid);
        $url = ADMINURLPATH . "details&action=prescription_upload";
        $mydb->redirect($url);
    } elseif (isset($_GET['refillid'])) {
        $uid                 = $_GET['refillid'];
        $data                = '';
        $data['call_status'] = call;
        $mydb->updatequery('tbl_sales', $data, 'id=' . $uid);
        $url = ADMINURLPATH . "details&action=refill";
        $mydb->redirect($url);
    } elseif (isset($_GET['mailid'])) {
        $uid       = $_GET['mailid'];

        $userlist  = $mydb->getArray('name,email', 'users', 'id=' . $uid);

        $toName    = $userlist['fullname'];
        $toEmail   = $userlist['email'];
        $fromName  = "Online aushadhi";
        $fromEmail = "care.onlineaushadhi@gmail.com";
        $subject   = "Message sent By  " . $fromEmail;


        $message = "Thank You for registering with Online Aushadhi. If you have any queries please call on 9841568568 to speak with our customer care representative.";
        $send    = $mydb->sendEmail($toName,$toEmail,$fromName, $fromEmail,$subject,$message);

        if ($send) {

            $data                  = '';
            $data['register_from'] = 'f1';
            $mydb->updatequery('users', $data, 'id=' . $uid);
            $url = ADMINURLPATH . "details&action=user_reg";
            $mydb->redirect($url);
        }
    } elseif (isset($_GET['profit_year'])) {
    $year = $_GET['profit_year'];

    $monthlist = mysql_query('SELECT DISTINCT MONTHNAME(`date`) as month FROM tbl_sales WHERE  YEAR(`date`)="' . $year . '"');
    ?>
    <div class="row">

        <div class="table-responsive">
            <table class="table table-bordered products">

                <h2 class="in-stock"><span><i class="fa fa-cubes"></i></span>Monthly Profit</h2>
                <tr>
                    <th width="15%">S.N.</th>
                    <th width="25%">Month</th>
                    <th width="15%" style="text-align:right;">Income</th>
                    <th width="15%" style="text-align:right;">Cost Price</th>
                    <th width="15%" style="text-align:right;">Discount Amount</th>
                    <th width="15%" style="text-align:right;">Monthly profit Amount</th>

                </tr>
                <?php
                $count = 0;

                while ($result7 = $mydb->fetch_array($monthlist)) {

                    $mon = $result7['month'];
                    $nmonth = date('m',strtotime($mon));
                    $netamtmonth = $mydb->getSum("net_amount", "tbl_sales", " YEAR(date(`date`))='" . $year . "' and   MONTHNAME(date(`date`))='" . $mon . "' and order_status=1");
                    $profitlist  = mysql_query('SELECT o.id,o.stock_id,o.medicine_name,o.quantity,s.date from tbl_sales s,tbl_order o WHERE s.id = o.sales_id AND YEAR(s.date)="' . $year . '" And MONTHNAME(s.date)="' . $mon . '"and order_status="1" ORDER By o.sales_id');

                    // Get total Discount
                    $sql_discount       = "SELECT SUM( discount_amount ) AS 'total_discount' FROM tbl_creditmemo WHERE discount_amount>0 AND MONTH( `invoice_eng_date` ) ='$nmonth' AND YEAR(`invoice_eng_date`)='$year'";
                    
                    $res_discount = mysql_query($sql_discount);
                    $ras_discount = mysql_fetch_array($res_discount);
                    $total_discount = $ras_discount['total_discount'];
                    //echo "total_discount = ".$total_discount;

                    ?>
                    <tr>
                        <td><?php echo ++$count; ?></td>
                        <td><?php echo $result7['month']; ?></td>
                        <?php
                        while ($result = $mydb->fetch_array($profitlist)) {
                            $stockinfo = $mydb->getQuery('cp_per_tab,sp_per_tab', 'tbl_stock', 'id="' . $result['stock_id'] . '"');
                            while ($stockinfo1 = $mydb->fetch_array($stockinfo)) {

                                $costprice = $stockinfo1['cp_per_tab'] * $result['quantity'];
                                $sumcp += $costprice;
                                $sumcostprice = $sumcp;
                            }
                        }
                        $total_month_profit = $netamtmonth - $sumcp + $total_discount;
                        $sumcp              = '';
                        ?>


                        <td style="text-align:right;"><?php echo $netamtmonth; ?></td>
                        <td style="text-align:right;"><?php echo $sumcostprice; ?></td>
                        <td style="text-align:right;"><?php echo $total_discount; ?></td>
                        <td style="text-align:right;"><?php
                            echo $total_month_profit;
                            $total_year_profit += $total_month_profit; /*echo $sql_discount;echo " ---- ".$total_discount; */ 
                            ?></td>
                    </tr>

                <?php }
                ?>
                <tr>
                    <td colspan="6" style="text-align:right;"><strong>Total : <?php echo $total_year_profit;?></strong></td>
                </tr>
                <?php
                } elseif (isset($_GET['p_year'])) {
                $year = $_GET['p_year'];

                $monthlist = mysql_query('SELECT DISTINCT MONTHNAME(invoice_eng_date) as month FROM tbl_creditmemo WHERE  YEAR(invoice_eng_date)="' . $year . '"Group BY MONTH(invoice_eng_date )');
                ?>
                <div class="row">

                    <div class="table-responsive">
                        <table class="table table-bordered products">

                            <h2 class="in-stock"><span><i class="fa fa-cubes"></i></span>Monthly Purchase</h2>
                            <tr>
                                <th width="15%">S.N.</th>
                                <th width="45%">Month</th>
                                <th width="15%">Total Purchase</th>
                                <th width="25%">Monthly Purchase Amount</th>

                            </tr>
                            <?php
                            $count = 0;

                            while ($result7 = $mydb->fetch_array($monthlist)) {
                                $monthlysum = $mydb->getSum('grand_amount', 'tbl_creditmemo', 'MONTHNAME(invoice_eng_date)="' . $result7['month'] . '"And YEAR(`invoice_eng_date`)="' . $year . '"');
                                if ($monthlysum != 0) {
                                    ?>

                                    <tr>
                                        <td><?php echo ++$count; ?></td>
                                        <td><?php echo $result7['month']; ?></td>
                                        <td><?php echo $mydb->getCount('id', 'tbl_creditmemo', 'MONTHNAME(invoice_eng_date)="' . $result7['month'] . '"And YEAR(`invoice_eng_date`)="' . $year . '"'); ?></td>
                                        <td><?php echo $monthlysum; ?></td>
                                    </tr>


                                    <?php
                                    $total += $monthlysum;

                                }

                            }if ($total > 0) {?>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td colspan="3"><strong><?php echo $total; ?></strong></td>

                                </tr>
                            <?php } else {echo "<td class='message' colspan='4'>No purchase record of this year</td>";}
                            } elseif (isset($_GET['s_year'])) {
                            $year      = $_GET['s_year'];
                            $monthlist = mysql_query('SELECT DISTINCT MONTHNAME(`date`) as month FROM tbl_sales WHERE  YEAR(`date`)="' . $year . '"Group BY MONTH(`date`)');

                            ?>
                            <div class="row">

                                <div class="table-responsive">
                                    <table class="table table-bordered products">

                                        <h2 class="in-stock"><span><i class="fa fa-cubes"></i></span>Monthly Sales</h2>
                                        <tr>
                                            <th width="15%">S.N.</th>
                                            <th width="45%">Month</th>
                                            <th width="15%">Total Sales</th>
                                            <th width="25%">Monthly Sales Amount</th>

                                        </tr>
                                        <?php
                                        $count = 0;

                                        while ($result7 = $mydb->fetch_array($monthlist)) {

                                            $monthlysum1 = $mydb->getSum('net_amount', 'tbl_sales', 'MONTHNAME(`date`)="' . $result7['month'] . '"And YEAR(`date`)="' . $year . '" and order_status=1');

                                            if ($monthlysum1 != 0) {
                                                ?>

                                                <tr>
                                                    <td><?php echo ++$count; ?></td>
                                                    <td><?php echo $result7['month']; ?></td>
                                                    <td><?php echo $mydb->getCount('id', 'tbl_sales', 'MONTHNAME(`date`)="' . $result7['month'] . '"And YEAR(`date`)="' . $year . '" and order_status=1'); ?></td>
                                                    <td><?php echo $monthlysum1; ?></td>
                                                </tr>


                                                <?php
                                                $total += $monthlysum1;

                                            }

                                        }
                                        if ($total > 0) {
                                            ?>

                                            <tr>
                                                <td><strong>Total</strong></td>
                                                <td colspan="3"><strong><?php echo $total; ?></strong></td>

                                            </tr>
                                            <?php
                                        } else {echo "<td class='message' colspan='4'>No sales record of this year</td>";}
                                        } elseif (isset($_GET['action']) && $_GET['action'] == 'prescription_upload') {
                                            $upload_from             = 'f0';
                                            $prescriptionimage       = $mydb->getquery("*", "tbl_image", "upload_from='" . $upload_from . "'");
                                            $prescriptionimage_count = mysql_num_rows($prescriptionimage);
                                            ?>


                                            <div class="row">
                                                <div class="table-responsive ">
                                                    <table class="table table-bordered prescription">
                                                        <?php if ($prescriptionimage_count > 0) {
                                                            ?>
                                                            <h2 class="upload"><span><i
                                                                        class="fa fa-file-powerpoint-o"></i></span>prescription
                                                                uploaded</h2>
                                                            <tr>
                                                                <th width="5%">s.n.</th>
                                                                <th width="20%">full name</th>
                                                                <th width="20%">email address</th>
                                                                <th width="15%">upload date</th>
                                                                <th width="10%">prescription</th>
                                                            </tr>
                                                            <?php while ($result1 = $mydb->fetch_array($prescriptionimage)) {
                                                                $userlist = $mydb->getarray('name,email', 'users', 'id="' . $result1['client_id'] . '"GROUP BY name');
                                                                $count    = 0;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo ++$count; ?></td>
                                                                    <td><?php echo $userlist['name']; ?></td>
                                                                    <td><?php echo $userlist['email']; ?></td>
                                                                    <td><?php echo $result1['date']; ?></td>
                                                                    <td>
                                                                        <!-- button trigger modal -->
                                                                        <button type="button" class="btn btn-primary "
                                                                                data-toggle="modal"
                                                                                data-target="#mymodal<?php echo $result1['id']; ?>">
                                                                            preview
                                                                        </button>
                                                                        <!-- modal -->
                                                                        <div class="modal fade"
                                                                             id="mymodal<?php echo $result1['id']; ?>"
                                                                             tabindex="-1"
                                                                             role="dialog"
                                                                             aria-labelledby="mymodallabel">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <button type="button"
                                                                                                class="close"
                                                                                                data-dismiss="modal"
                                                                                                aria-label="close"
                                                                                                onclick="callupdate('<?php echo $result1['id']; ?>')">
                                                                                            <span
                                                                                                aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                        <h4 class="modal-title"
                                                                                            id="mymodallabel">
                                                                                            prescription</h4>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img
                                                                                            src="../<?php echo $result1['image_path']; ?>/<?php echo $result1['image_name']; ?>"
                                                                                            alt="" width="570px;">
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                                class="btn btn-default"
                                                                                                data-dismiss="modal"
                                                                                                onclick="callupdate('<?php echo $gid = $result1['id']; ?>')">
                                                                                            close
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {?>
                                                            <tr>
                                                                <td class="message">No prescription has left
                                                                    to preview
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                        ?>


                                                    </table>
                                                </div>

                                            </div>
                                        <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'refill') {
                                        $currentdate      = date('Y-m-d');
                                        $refilldate       = mysql_query("SELECT * from tbl_sales WHERE Refill_date <='" . $currentdate . "'And call_status='nocall' and order_status=1 AND client_id!='1087'");
                                        $refilldate_count = mysql_num_rows($refilldate);

                                        ?>
                                </div>
                                <div class="row">
                                    <div class="table-responsive ">
                                        <table class="table table-bordered refill">
                                            <?php if ($refilldate_count > 0) {
                                                ?>
                                                <h2 class="call"><span><i class="fa fa-refresh"></i></span>refill cart  alert</h2>
                                                <tr>
                                                    <th width="5%">s.n.</th>
                                                    <th width="20%">full name</th>
                                                    <th width="15%">contact no.</th>
                                                    <th width="10%">last order detail</th>
                                                    <th width="10%">last Delivered date</th>
                                                    <th width="15%">Memo</th>
                                                    <th width="10%"></th>
                                                </tr>
                                                <?php
                                                $count = 0;
                                                while ($result5 = $mydb->fetch_array($refilldate)) {
                                                    $additional_phone = $mydb->getValue('additional_phone', 'users', 'id="' . $result5['client_id'] . '"');
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ++$count; ?></td>
                                                        <td><?php echo $mydb->getValue('name', 'users', 'id="' . $result5['client_id'] . '"'); ?></td>
                                                        <td>
                                                            <?php echo $mydb->getValue('phone', 'users', 'id="' . $result5['client_id'] . '"');?>
                                                            <?php if(!empty($additional_phone)) echo " / ".$additional_phone;?>
                                                        </td>
                                                        <td>
                                                            <!-- button trigger modal -->
                                                            <button type="button" class="btn btn-primary " data-toggle="modal"  data-target="#mymodal<?php echo $result5['id']; ?>"> details </button>
                                                            <!-- modal -->
                                                            <div class="modal fade"
                                                                 id="mymodal<?php echo $result5['id']; ?>" tabindex="-1"
                                                                 role="dialog" aria-labelledby="mymodallabel">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span> </button>
                                                                            <h4 class="modal-title" id="mymodallabel">  details</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="modal-body">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-bordered">
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th width="30%">product  name </th>
                                                                                            <th width="10%">rate</th>
                                                                                            <th width="15%">total quantity </th>
                                                                                            <th width="15%">price (nrs) </th>
                                                                                        </tr>
                                                                                        </thead>

                                                                                        <tbody>
                                                                                        <?php
                                                                                        $order_data = $mydb->getQuery('*', 'tbl_order', 'sales_id="' . $result5['id'] . '"');
                                                                                        while ($result6 = $mydb->fetch_array($order_data)) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td><?php echo $result6['medicine_name']; ?></td>
                                                                                                <td><?php echo $result6['Rate']; ?></td>
                                                                                                <td><?php echo $result6['quantity']; ?></td>
                                                                                                <td><?php echo number_format($result6['total_amount'], 2); ?></td>
                                                                                            </tr>
                                                                                        <?php }
                                                                                        ?>
                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                        <tr>
                                                                                            <td colspan="2"
                                                                                                rowspan="3"></td>
                                                                                            <td>total</td>
                                                                                            <td><?php echo $result5['total_amount']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>discount</td>
                                                                                            <td><?php echo $result5['discount_amount']; ?></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>total price</td>
                                                                                            <td><?php echo $result5['net_amount']; ?></td>
                                                                                        </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                    class="btn btn-default"
                                                                                    data-dismiss="modal">close
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?php echo date('d-m-Y', strtotime($result5['delivery_date'])); ?></td>
                                                        <td><textarea  cols="25" name="memo" id="memo<?php echo $result5['id']?>"  onfocusout="savememo(<?php echo $result5['id']?>)" ><?php
                                                                    if(!empty($result5['memo'])){
                                                                        echo $result5['memo'];
                                                                    }
                                                                ?></textarea></td>
                                                        <td>
                                                            <button class="btn called" type="submit" onclick="callRefill('<?php echo $refillid = $result5['id']; ?>')">
                                                                called
                                                            </button>
                                                        </td>
                                                    </tr>

                                                <?php }

                                            } else {
                                                echo '<td class="message">No Refill alert  has left to preview</td>';
                                            }
                                            ?>


                                        </table>

                                    </div>
                                </div>
                                <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'stock_alert') {?>
                                    <div class="row">
                                        <div class="table-responsive ">
                                            <table class="table table-bordered stock">
                                                <h2 class="stock-alert"><span><i class="fa fa-bolt"></i></span>stock alert</h2>
                                                <tr>
                                                    <th width="2%">S.N.</th>
                                                    <th width="20%">Product name</th>
                                                    <th width="35%">Distributers</th>
                                                    <th width="17%">Contact no.</th>
                                                    <th width="17%">Remaining Stock</th>
                                                    <th width="17%">Notification</th>
                                                </tr>
                                                <?php
                                                $resOos = $mydb->getQuery('medicine_id','tbl_stock','1 GROUP BY medicine_id');
                                                while($rasOos = $mydb->fetch_array($resOos))
                                                {
                                                    $medicine_id = $rasOos['medicine_id'];
                                                    $rasMedicine = $mydb->getArray('medicine_name,min_stock','tbl_medicine','id='.$medicine_id);
                                                    $stock_total = $mydb->getSum('stock-sales','tbl_stock','medicine_id='.$medicine_id);
                                                    if($stock_total>0 && $stock_total<=$rasMedicine['min_stock'])
                                                    {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ++$count; ?></td>
                                                        <td><?php echo $rasMedicine['medicine_name']; ?></td>
                                                        <?php


                                                        $distributorlist = mysql_query('select distinct `fullname`,`landline` from tbl_distributor INNER JOIN tbl_creditmemo ON tbl_distributor.id =tbl_creditmemo.distributor_id INNER JOIN tbl_stock ON tbl_creditmemo.id=tbl_stock.creditmemo_id where tbl_stock.medicine_id="' . trim($medicine_id) . '"');
                                                        ?>

                                                        <?php
                                                        $namef  = "";
                                                        $phonef = "";

                                                        while ($distributorlist1 = $mydb->fetch_array($distributorlist)) {

                                                            $namef .= $distributorlist1['fullname'] . "<br>";
                                                            $phonef .= $distributorlist1['landline'] . "<br>";
                                                            ?>

                                                        <?php }
                                                        ?>
                                                        <td style="text-align:left;"><?php echo $namef; ?></td>
                                                        <td><?php echo $phonef; ?></td>
                                                        <td>
                                                            <p><?php echo $stock_total;?></p>

                                                        </td>

                                                        <td><a href="?manager=medicine_manage&id=<?php echo $medicine_id;?>" target="_blank">Set Min Stock</a></td>
                                                    </tr>
                                                <?php 
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div>

                                    </div>

                                    <?php

                                    $product     = mysql_query("select distinct medicine_id from tbl_stock where  notification='1' ORDER BY item_description");
                                    $index       = 0;
                                    $stock_array = array();

                                    $stock_alert = count($stock_array);

                                    } elseif (isset($_GET['action']) && $_GET['action'] == 'user_reg') {
                                    $register_from      = 'f0';
                                    $registeruser       = $mydb->getquery("*", "users", "register_from='" . $register_from . "'And active=1");
                                    $registeruser_count = mysql_num_rows($registeruser);
                                    ?>
                                    <div class="row">
                                        <div class="table-responsive ">
                                            <table class="table table-bordered user">
                                                <?php if ($registeruser_count > 0) {
                                                    ?>
                                                    <h2 class="new-user"><span><i class="fa fa-user"></i></span>new user
                                                        registered</h2>
                                                    <tr>
                                                        <th width="2%">s.n.</th>
                                                        <th width="10%">Registered Date</th>
                                                        <th width="30%">full name</th>
                                                        <th width="25%">email address</th>
                                                        <th width="10%">address</th>
                                                        <th width="10%">contact no.</th>
                                                        <th width="5%"></th>
                                                    </tr>
                                                    <?php
                                                    $count = 0;
                                                    while ($result4 = $mydb->fetch_array($registeruser)) {

                                                        ?>
                                                        <tr>
                                                            <td><?php echo ++$count; ?></td>
                                                            <td><?php echo $result4['created_at']; ?></td>
                                                            <td><?php echo $result4['name']; ?></td>
                                                            <td><?php echo $result4['email']; ?></td>
                                                            <td><?php echo $result4['address']; ?></td>
                                                            <td><?php echo $result4['phone']; ?></td>

                                                            <td>
                                                                <button class="btn mail" type="submit" onclick="callmail('<?php echo $mailid = $result4['id']; ?>')">mail</button>
                                                            </td>
                                                        </tr>

                                                        <?php
                                                    }
                                                } else {
                                                    echo "<td class='message'>No user is registered</td>";
                                                }
                                                ?>
                                            </table>
                                        </div>

                                    </div>

                                <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'product_list') {
                                    $product       = mysql_query("SELECT DISTINCT description from tbl_stock GROUP BY item_description");
                                    $product_count = mysql_num_rows($product);
                                    ?>
                                    <div class="row">

                                        <div class="table-responsive ">
                                            <table class="table table-bordered products">
                                                <?php if ($product_count > 0) {
                                                    ?>
                                                    <h2 class="in-stock"><span><i class="fa fa-cubes"></i></span>Products
                                                        In Stock</h2>
                                                    <tr>
                                                        <th width="15%">S.N.</th>
                                                        <th width="60%">Product Name</th>
                                                        <th width="25%">Amount in stock</th>

                                                    </tr>
                                                    <?php
                                                    $count = 0;
                                                    while ($result1 = $mydb->fetch_array($product)) {
                                                        $result2 = mysql_query("SELECT item_description, sum(stock-sales)as total from tbl_stock where description='" . $result1['description'] . "'");

                                                        while ($result3 = $mydb->fetch_array($result2)) {
                                                            ?>

                                                            <tr>
                                                                <td><?php echo ++$count; ?></td>
                                                                <td><?php echo $result3['item_description']; ?></td>
                                                                <td><?php echo $result3['total']; ?></td>
                                                            </tr>

                                                            <?php
                                                        }
                                                    }

                                                    ?>


                                                <?php } else {
                                                    echo '<td>No product in stock</td>';
                                                }
                                                ?>

                                            </table>

                                        </div>
                                    </div>
                                <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'purchase') {

                                    $product1      = mysql_query("SELECT count(`id`) as total_purchase,YEAR(invoice_eng_date) as year,SUM(grand_amount) as total FROM tbl_creditmemo GROUP BY Year");
                                    $product_count = mysql_num_rows($product1);
                                    ?>
                                    <div class="row">

                                        <div class="table-responsive ">
                                            <table class="table table-bordered products">
                                                <?php if ($product_count > 0) {
                                                    ?>
                                                    <h2 class="in-stock"><span><i class="fa fa-cubes"></i></span>Annual
                                                        Purchase</h2>
                                                    <tr>
                                                        <th width="15%">S.N.</th>
                                                        <th width="45%">Year</th>
                                                        <th width="15%">Total Purchase</th>
                                                        <th width="25%">Year Expense Amount</th>

                                                    </tr>
                                                    <?php
                                                    $count = 0;
                                                    while ($result7 = $mydb->fetch_array($product1)) {

                                                        ?>

                                                        <tr>
                                                            <td><?php echo ++$count; ?></td>
                                                            <td>
                                                                <a href="<?php echo SITEROOT; ?>dacadmin/index.php?manager=details&p_year=<?php echo $result7['year']; ?>"><?php echo $result7['year']; ?></a>
                                                            </td>
                                                            <td><?php echo $result7['total_purchase']; ?></td>
                                                            <td><?php echo $result7['total']; ?></td>
                                                        </tr>

                                                        <?php

                                                    }

                                                    ?>


                                                <?php } else {
                                                    echo '<td>No product in stock</td>';
                                                }
                                                ?>

                                            </table>
                                        </div>

                                    </div>

                                <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'sales') {
                                    $product2      = mysql_query("SELECT COUNT(`id`)as total_sales,YEAR(`date`) as year,SUM(net_amount) as total FROM tbl_sales GROUP BY Year");
                                    $product_count = mysql_num_rows($product2);
                                    ?>
                                    <div class="row">

                                        <div class="table-responsive ">
                                            <table class="table table-bordered products">
                                                <?php if ($product_count > 0) {
                                                    ?>
                                                    <h2 class="in-stock"><span><i class="fa fa-cubes"></i></span>Annual
                                                        Sales</h2>
                                                    <tr>
                                                        <th width="15%">S.N.</th>
                                                        <th width="45%">Year</th>
                                                        <th width="15%">total_sales</th>
                                                        <th width="25%">Annual Sales Amount</th>

                                                    </tr>
                                                    <?php
                                                    $count = 0;
                                                    while ($result8 = $mydb->fetch_array($product2)) {

                                                        ?>

                                                        <tr>
                                                            <td><?php echo ++$count; ?></td>
                                                            <td>
                                                                <a href="<?php echo SITEROOT; ?>dacadmin/index.php?manager=details&s_year=<?php echo $result8['year']; ?>"><?php echo $result8['year']; ?></a>
                                                            </td>
                                                            <td><?php echo $result8['total_sales']; ?></td>
                                                            <td><?php echo $result8['total']; ?></td>
                                                        </tr>

                                                        <?php

                                                    }

                                                    ?>


                                                <?php } else {
                                                    echo '<td>No product in sales</td>';
                                                }
                                                ?>

                                            </table>
                                        </div>

                                    </div>
                                <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'profit') {

                                    $product2      = mysql_query("SELECT  YEAR(`date`) as year,SUM(net_amount) as total FROM tbl_sales GROUP BY Year");
                                    $product_count = mysql_num_rows($product2);



                                    ?>


                                    <div class="row">

                                        <div class="table-responsive ">
                                            <table class="table table-bordered products">
                                                <?php if ($product_count > 0) {
                                                    ?>
                                                    <h2 class="in-stock"><span><i class="fa fa-cubes"></i></span>Annual
                                                        Profit</h2>
                                                    <tr>
                                                        <th width="15%">S.N.</th>
                                                        <th width="60%">Year</th>
                                                        <th width="30%" style="text-align:right;">Annual Profit</th>


                                                    </tr>
                                                    <?php
                                                    $count = 0;
                                                    while ($result8 = $mydb->fetch_array($product2)) {

                                                        ?>

                                                        <tr>
                                                            <td><?php echo ++$count; ?></td>
                                                            <td>
                                                                <a href="<?php echo SITEROOT; ?>dacadmin/index.php?manager=details&profit_year=<?php echo $result8['year']; ?>"><?php echo $result8['year']; ?></a>
                                                            </td>


                                                            <?php
                                                            $monthlist = mysql_query('SELECT DISTINCT MONTHNAME(`date`) as month FROM tbl_sales WHERE  YEAR(`date`)="' . $result8['year'] . '"');
                                                            while ($result7 = $mydb->fetch_array($monthlist)) {

                                                                $mon         = $result7['month'];
                                                                $nmonth = date('m',strtotime($mon));
                                                                // Get total Discount
                                                                $sql_discount       = "SELECT SUM( discount_amount ) AS 'total_discount' FROM tbl_creditmemo WHERE discount_amount>0 AND MONTH( `invoice_eng_date` ) ='$nmonth' AND YEAR(`invoice_eng_date`)='".$result8['year']."'";
                                                                $res_discount = mysql_query($sql_discount);
                                                                $ras_discount = mysql_fetch_array($res_discount);
                                                                $total_discount = $ras_discount['total_discount'];
                                                                //echo "total_discount = ".$total_discount;


                                                                $netamtmonth = $mydb->getSum("net_amount", "tbl_sales", " YEAR(date(`date`))='" . $result8['year'] . "' and   MONTHNAME(date(`date`))='" . $mon . "'and order_status=1"); //2014

                                                                $profitlist = mysql_query('SELECT o.id,o.stock_id,o.medicine_name,o.quantity,s.date from tbl_sales s,tbl_order o WHERE s.id = o.sales_id AND YEAR(s.date)="' . $result8['year'] . '" And MONTHNAME(s.date)="' . $mon . '"and s.order_status="1" ORDER By o.sales_id');

                                                                while ($result = $mydb->fetch_array($profitlist)) {
                                                                    $stockinfo = $mydb->getQuery('cp_per_tab,sp_per_tab', 'tbl_stock', 'id="' . $result['stock_id'] . '"');
                                                                    while ($stockinfo1 = $mydb->fetch_array($stockinfo)) {

                                                                        $costprice = $stockinfo1['cp_per_tab'] * $result['quantity'];

                                                                        $sumcp += $costprice;

                                                                    }
                                                                }

                                                                $total_month_profit = $netamtmonth - $sumcp + $total_discount;
                                                                $sumcp              = '';
                                                                $total_year_profit += $total_month_profit;

                                                            }

                                                            ?>
                                                            <td style="text-align:right;"><?php echo $total_year_profit; ?></td>
                                                        </tr>

                                                        <?php
                                                        $total_year_profit = '';

                                                    }

                                                } else {
                                                    echo '<td>No product in sales</td>';
                                                }
                                                ?>

                                            </table>
                                        </div>

                                    </div>
                                    <?php

                                } elseif (isset($_GET['action']) && $_GET['action'] == 'medicine_alert') {

                                    $_medicine_list = mysql_query("SELECT id,item_description,batch_number,creditmemo_id,stock,sales,expiry_date,DATEDIFF(expiry_date, now()) AS days FROM tbl_stock WHERE expiry_date >now() AND DATEDIFF(expiry_date,now()) <150 AND sales<>stock ORDER BY expiry_date");

                                    ?>
                                    <div class="row">
                                        <div class="table-responsive ">
                                            <table class="table table-bordered refill">

                                                <h2 class="call"><span><i class="fa fa-refresh"></i></span>Meidicine Expiry Section
                                                </h2>
                                                <tr>
                                                    <th width="5%">S.N</th>
                                                    <th width="6%">Quantity</th>
                                                    <th width="20%">Medicine Name</th>
                                                    <th width="10%">expiry_date</th>
                                                    <th width="10%">Batch Number</th>
                                                    <th width="10%">Invoice</th>
                                                    <th width="20%">Distributors</th>
                                                    <th width="9%">Contact</th>
                                                    <th width="5%">Return</th>

                                                </tr>
                                                
                                                <?php
                                                while ($result_3 = $mydb->fetch_array($_medicine_list)) {
                                                    $credit_id = $result_3['creditmemo_id'];

                                                    $disbutor_id = $mydb->getValue('distributor_id', 'tbl_creditmemo', 'id=' . $credit_id);
                                                    $invoice_no = $mydb->getValue('invoice_no', 'tbl_creditmemo', 'id=' . $credit_id);

                                                    $distributor_name = $mydb->getArray('fullname,landline,mobile', 'tbl_distributor', 'id=' . $disbutor_id);

                                                    ?>


                                                    <tr>
                                                        <td><?php echo ++$count; ?></td>
                                                        <td><?php echo $result_3['stock']-$result_3['sales']; ?></td>
                                                        <td style="text-align:left;"><?php echo $result_3['item_description']; ?></td>
                                                        <td><?php echo $result_3['expiry_date']; ?></td>
                                                        <td><?php echo $result_3['batch_number']; ?></td>
                                                        <td><?php echo $invoice_no; ?></td>
                                                        <td style="text-align:left;"><?php echo $distributor_name['fullname']; ?></td>
                                                        <td><?php echo $distributor_name['landline'] . ',' . $distributor_name['mobile']; ?></td>
                                                        <td><a href="<?php echo ADMINURLPATH; ?>stock_edit&id=<?php echo $result_3['id'];?>&do=purchase_return"><img src="images/action_return.png" alt="Return" width="24" height="24" title="Return"></a></td>
                                                    </tr>


                                                <?php
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="9"><h2 class="call"><span><i class="fa fa-refresh"></i></span>Returned Meidicine Section</h2></td>
                                                </tr>
                                                <tr>
                                                    <th width="5%">S.N</th>
                                                    <th width="6%">Quantity</th>
                                                    <th width="20%">Medicine Name</th>
                                                    <th width="10%">expiry_date</th>
                                                    <th width="10%">Batch Number</th>
                                                    <th width="10%">Invoice</th>
                                                    <th width="20%">Distributors</th>
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






                                    <?php

                                }
                                ?>



                            </div>