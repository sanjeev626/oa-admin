<?php
error_reporting(0);
include 'classes/call.php';

$review            = 0;
$getorderreview    = $mydb->getQuery("*", "tbl_sales", "review_status='" . $review . "'");
$orderreview_count = mysql_num_rows($getorderreview);

$odstatus            = 0;
$review1             = 1;
$getorderdelivery    = $mydb->getQuery("*", "tbl_sales", "order_status='" . $odstatus . "'AND review_status='" . $review1 . "'AND net_amount>0.00");
$orderdelivery_count = mysql_num_rows($getorderdelivery);

$upload_from             = 'f0';
$prescriptionimage       = $mydb->getQuery("*", "tbl_image", "upload_from='" . $upload_from . "'");
$prescriptionimage_count = mysql_num_rows($prescriptionimage);

$register_from      = 'f0';
$registeruser       = $mydb->getQuery("*", "users", "register_from='" . $register_from . "'And active=1");
$registeruser_count = mysql_num_rows($registeruser);

$currentdate      = date("Y-m-d");

$refilldate       = mysql_query("SELECT * from tbl_sales WHERE Refill_date <='" . $currentdate . "'And call_status='nocall' and order_status=1 AND client_id!='1087'");
$refilldate_count = mysql_num_rows($refilldate);
if ($refilldate_count == null) {
    $refilldate_count = 0;
}

$product       = mysql_query("SELECT DISTINCT medicine_id from tbl_stock Where 1 ORDER BY medicine_id");
$product_count = mysql_num_rows($product);
//echo "<br>product_count = ".$product_count;
while ($result1 = $mydb->fetch_array($product)) {    
    $medicine_id = $result1['medicine_id'];
    $min_stock = $mydb->getValue('min_stock','tbl_medicine','id='.$medicine_id);
    $current_stock = $mydb->getSum('stock-sales','tbl_stock','medicine_id='.$medicine_id);
    if($current_stock>0 && $current_stock<=$min_stock){
      $stock_array[] = $medicine_id;
    }
}
$stock_alert = count($stock_array);

$date_val  = date("m");
$date_year = date("Y");
$sql       = "SELECT SUM( net_amount ) AS 'total' FROM tbl_sales WHERE MONTH( `date` ) ='$date_val' And YEAR(`date`)='$date_year'";
$total_val = mysql_query($sql);
$row       = mysql_fetch_row($total_val);
if ($row) {$total_sales = round($row[0]);}

$sql1       = "SELECT SUM( grand_amount ) AS 'total' FROM tbl_creditmemo WHERE MONTH( `invoice_eng_date` ) ='$date_val'And YEAR(`invoice_eng_date`)='$date_year'";
$total_val1 = mysql_query($sql1);
$row1       = mysql_fetch_row($total_val1);
if ($row1[0] != null) {$total_purchase = round($row1[0]);} else { $total_purchase = '0';}

// Get total cost price for the sold medicines only
$profitlist = mysql_query("SELECT o.id,o.stock_id,o.medicine_name,o.quantity,o.sales_id,s.date from tbl_sales s,tbl_order o WHERE s.id = o.sales_id AND year(date(s.date))=year(CURDATE()) and month(date(s.date))=month(CURDATE()) and s.order_status='1' ORDER By o.sales_id");
while ($result = $mydb->fetch_array($profitlist)) {

    $stockinfo = $mydb->getQuery('item_description,cp_per_tab,sp_per_tab', 'tbl_stock', 'id="' . $result['stock_id'] . '"');
    while ($stockinfo1 = $mydb->fetch_array($stockinfo)) {
        $costprice = $stockinfo1['cp_per_tab'] * $result['quantity'];
        $sumcp += $costprice;
    }
}
////////////////////////////////////////////////////////////////////////////////////
// Get total sold amount
$netamt       = $mydb->getSum("net_amount", "tbl_sales", " year(date(`date`))=year(CURDATE()) and month(date(`date`))=month(CURDATE()) and order_status=1");
///////////////////////////////////////////////////////////////////////////////////
// Get total Discount
$sql_discount       = "SELECT SUM( discount_amount ) AS 'total_discount' FROM tbl_creditmemo WHERE discount_amount>0 AND MONTH( `invoice_eng_date` ) ='$date_val'AND YEAR(`invoice_eng_date`)='$date_year'";
//echo $sql_discount ;
$res_discount = mysql_query($sql_discount);
$ras_discount = mysql_fetch_array($res_discount);
$total_discount = $ras_discount['total_discount'];
//echo "total_discount = ".$total_discount;
///////////////////////////////////////////////////////////////////////////////////
$total_profit = round($netamt - $sumcp + $total_discount);

$expirydate_count = $mydb->getCount('id', 'tbl_stock', 'expiry_date >now() AND DATEDIFF(expiry_date,now()) <150  AND sales<>stock');

$output1 .= '<li class="timeline-item" >
              <a href="' . SITEROOT . 'index.php?manager=details&action=user_reg">
                <div class="task-icon clearfix" >
                <span class="fa-stack fa-lg">
                  <i class="fa fa-circle fa-stack-2x" style="color:#22BAA0"></i>
                  <i class="fa fa-user fa-stack-1x fa-inverse"></i>
                </span>
                </div>

                <p class="task-details" >New user registered -' . $registeruser_count . '</p>
              </a>
            </li>

            <li class="timeline-item"  >
              <a href="' . SITEROOT . 'index.php?manager=details&action=prescription_upload" >
                <div class="task-icon clearfix" >
                  <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x" style="color:#7a6fbe"></i>
                    <i class="fa fa-file-powerpoint-o fa-stack-1x fa-inverse"></i>
                  </span>
                </div>

                <p class="task-details" >New Prescription Uploaded -' . $prescriptionimage_count . '</p>
              </a>
            </li>

            <li class="timeline-item"  >
              <a href="' . SITEROOT . 'index.php?manager=details&action=stock_alert">
                <div class="task-icon clearfix" >
                  <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x" style="color:#f25656"></i>
                    <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
                  </span>
                </div>

                <p class="task-details" >Stock Alert -' . $stock_alert . '</p>
              </a>
            </li>
              <li class="timeline-item"  >
              <a href="' . SITEROOT . 'index.php?manager=details&action=refill">
                <div class="task-icon clearfix" >
                <span class="fa-stack fa-lg">
                  <i class="fa fa-circle fa-stack-2x" style="color:#12AFCB"></i>
                  <i class="fa fa-refresh fa-stack-1x fa-inverse"></i>
                </span>
                </div>

                <p class="task-details" >Refill Call Alert -' . $refilldate_count . '</p>
              </a>
            </li>

              <li class="timeline-item" >
              <a href="' . SITEROOT . 'index.php?manager=order_review">
                <div class="task-icon clearfix" >
                <span class="fa-stack fa-lg">
                  <i class="fa fa-circle fa-stack-2x" style="color:#2b384e"></i>
                  <i class="fa fa-check-square-o fa-stack-1x fa-inverse"></i>
                </span>
                </div>

                <p class="task-details" >New order -' . $orderreview_count . '</p>
              </a>
            </li>


               <li class="timeline-item"  >
              <a href="' . SITEROOT . 'index.php?manager=details&action=medicine_alert">
                <div class="task-icon clearfix" >
                <span class="fa-stack fa-lg">
                  <i class="fa fa-circle fa-stack-2x" style="color:#444"></i>
                  <i class="fa fa-calendar-times-o fa-stack-1x fa-inverse"></i>
                </span>
                </div>

                <p class="task-details" >medicine Expiry  -' . $expirydate_count . '</p>
              </a>
            </li>';

$obj5 = $orderreview_count + $orderdelivery_count + $prescriptionimage_count + $registeruser_count + $refilldate_count + $stock_alert + $expirydate_count;

$data = array("value1" => $obj5, "value2" => $output1, "value3" => $orderreview_count, "value4" => $orderdelivery_count, "value5" => $prescriptionimage_count, "value6" => $registeruser_count, "value7" => $refilldate_count, "value8" => $stock_alert, "value9" => $product_count, "value10" => "Rs. $total_sales", "value11" => "Rs. $total_purchase", "value12" => "Rs.$total_profit", "value13" => $expirydate_count);
echo json_encode($data);
