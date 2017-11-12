<script type="text/javascript">
    $(document).ready(function(){
        $("#usersearch").autocomplete({
            source:'ajaxoverallsearch.php?type=ordersearch' + $("#usersearch").val(),
            minLength:1,

            select:function(e,ui) {

                location.href = ui.item.the_link;

            }

        });
    });
</script>
<!-- ===========================to view order list of specified person===================================== -->
<?php
if(isset($_GET['salesdetails']))
{
    $id=$_GET['salesdetails'];
    $reviewstatus=1;
    $result = $mydb->getQuery("*", "tbl_sales", "client_id='".$id."' AND client_id!=1087 AND review_status='" . $reviewstatus ."' ORDER BY id desc");
    ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <tr class="TitleBar">
        <td colspan="6" class="TtlBarHeading">Sales
            <div style="float:right"></div>
        </td>
        <td colspan="2" class="TtlBarHeading" width="100%"><input name="search" type="text" id="usersearch" Placeholder="Search by Name" class="button" style="width:250px;"/></td>
    </tr>
    <tr>
        <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
        <td width="20%" valign="top" class="titleBarB" align="center"><strong>Client Name</strong></td>
        <td width="12%" valign="top" class="titleBarB" align="center"><strong>Purchase Date</strong></td>
        <td width="6%" valign="top" class="titleBarB" align="center"><strong>Number of Drugs</strong></td>
        <td width="8%" valign="top" class="titleBarB" align="center"><strong>Total Amount</strong></td>
        <td width="10%" valign="top" class="titleBarB" align="center"><strong>Payment</strong></td>
        <td width="30%" valign="top" class="titleBarB" align="center"><strong>Remarks</strong></td>
        <td width="20%" valign="top" classs="titleBarB" align="center"><strong>Details</strong></td>
    </tr>

<?php
    $counter = 0;
    while ($rasSales = $mydb->fetch_array($result)) {
      $remarks = $rasSales['Remarks'];
    $users = $mydb->getValue('name', 'users', 'id=' . $id);
    $hasReturned = $mydb->getCount('id','tbl_sales_return','sales_id="'.$rasSales['id'].'"');
    if($hasReturned>0)
    {
        if(!empty($remarks))
            $remarks.="<br>";
        $rasReturn = $mydb->getArray('id,total_sales_return_amount,refund_status,refund_date,refund_sales_id','tbl_sales_return','sales_id="'.$rasSales['id'].'"');       
        $return_amount = $rasReturn['total_sales_return_amount'];
        $remarks.="Refund Amount : Nrs. ".$return_amount;
        if($rasReturn['refund_status']==0)
        {
            $remarks.="<br>Refund Status : Not Refunded";
            //$a = '<a href="'..'"';
            $a = ' <a href="' . ADMINURLPATH . 'sales_return_refund&sales_return_id=' . $rasReturn['id'] . '">Refund it</a>';
            $remarks.=$a;
        }
        else
            $remarks.="<br>Refund Status : Refunded on ".$rasReturn['refund_date']." for Sales ID ".$rasReturn['refund_sales_id'];

    }
    ?>
    <tr>
        <td class="titleBarA" align="center" valign="top"><input name="eid[]" type="hidden" id="eid[]" value="<?php echo $rasSales['id']; ?>"/>
            <?php echo ++$counter; ?></td>
        <td class="titleBarA" align="center" valign="top"><?php echo $users ?></td>
        <td class="titleBarA" valign="top"><?php echo date('d-m-Y', strtotime($rasSales['date'])); ?></td>
        <td class="titleBarA" valign="top"><?php echo $mydb->getCount('id', 'tbl_order', 'sales_id=' . $rasSales['id']); ?></td>
        <td class="titleBarA" valign="top"><?php echo $rasSales['net_amount']; ?></td>
        <td class="titleBarA" valign="top">
            <?php if ($rasSales['payment']) {
                echo $rasSales['payment'];
            } else {
                echo "Not delivered Yet";
            } ?></td>
        <td class="titleBarA" valign="top"><?php echo $remarks; ?></td>
        <td class="titleBarA" Valign="top">
            <a href="<?php echo ADMINURLPATH;?>sales_details&sales_id=<?php echo $rasSales['id']; ?>">View Details</a>
            <?php if($hasReturned>0){?>
             | 
             <a href="<?php echo ADMINURLPATH;?>sales_return&sales_id=<?php echo $rasSales['id']; ?>">Sales return</a>
            <?php } ?>
        </td> 
    </tr>
    <?php
    } ?>
</table>

<?php }
//====================================To display all list of buyer who buy medicine================================//
else{

$reviewstatus = 1;
$num_rec_per_page = 50;
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $num_rec_per_page;
$result = $mydb->getQuery("*", "tbl_sales", "review_status='" . $reviewstatus . "' AND client_id!=1087 ORDER BY id desc limit " . $start_from . "," . $num_rec_per_page . "");//extract order data from tbl_order

$count = mysql_num_rows($result);//count no of rows in tbl_order
?>


<form action="" method="post" name="tbl_sales">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
        <?php if (isset($_GET['message'])) { ?>
            <tr>
                <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
                <!--if message is set ,display in top-->
            </tr>
        <?php } ?>
        <tr class="TitleBar">
            <td colspan="6" class="TtlBarHeading">Sales
                <div style="float:right"></div>
            </td>
            <td colspan="2" class="TtlBarHeading" width="100%"><input name="search" type="text" id="usersearch" Placeholder="Search by Name" class="button" style="width:250px;"/></td>
        </tr>
        <?php
        if ($count != 0)//if there is data in tbl_order then it enters to loop;
        {
            ?>
            <tr>
                <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
                <td width="20%" valign="top" class="titleBarB" align="center"><strong>Client Name</strong></td>
                <td width="12%" valign="top" class="titleBarB" align="center"><strong>Purchase Date</strong></td>
                <td width="6%" valign="top" class="titleBarB" align="center"><strong>Number of Drugs</strong></td>
                <td width="8%" valign="top" class="titleBarB" align="center"><strong>Total Amount</strong></td>
                <td width="10%" valign="top" class="titleBarB" align="center"><strong>Payment</strong></td>
                <td width="30%" valign="top" class="titleBarB" align="center"><strong>Remarks</strong></td>
                <td width="20%" valign="top" classs="titleBarB" align="center"><strong>Details</strong></td>
            </tr>
            <?php


            $counter = 0;
            while ($rasSales = $mydb->fetch_array($result)) {
                $count1 = $mydb->getCount('client_id', 'tbl_login', 'session_id=' . $rasSales['client_id']);

                if ($count1 == 0) {
                    $users = $mydb->getValue('name', 'users', 'id=' . $rasSales['client_id']);
                } else {
                    $client_id1 = $mydb->getValue('client_id', 'tbl_login', 'session_id=' . $rasSales['client_id']);
                    $users = $mydb->getValue('name', 'users', 'id=' . $client_id1);
                }
                ?>
                <tr>
                    <td class="titleBarA" align="center" valign="top"><input name="eid[]" type="hidden" id="eid[]"
                                                                             value="<?php echo $rasSales['id']; ?>"/>
                        <?php echo ++$counter; ?></td>
                    <td class="titleBarA" align="center" valign="top"><?php echo $users ?></td>
                    <td class="titleBarA" valign="top"><?php echo date('d-m-Y', strtotime($rasSales['date'])); ?></td>
                    <td class="titleBarA"
                        valign="top"><?php echo $mydb->getCount('id', 'tbl_order', 'sales_id=' . $rasSales['id']); ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasSales['net_amount']; ?></td>
                    <td class="titleBarA" valign="top"><?php if ($rasSales['payment']) {
                            echo $rasSales['payment'];
                        } else {
                            echo "Not delivered Yet";
                        } ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasSales['Remarks']; ?></td>
                    <td class="titleBarA" Valign="top"><a
                            href="<?php echo ADMINURLPATH; ?>sales_details&sales_id=<?php echo $rasSales['id']; ?>">View
                            Details</a></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td class="message" colspan="4">No Sales Record</td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
    //=====================Use pagination concept==========================================
    if (isset($page)) {
        ?>

        <nav style="text-align:center;">
            <ul class="pagination">
                <?php
                $rs_result = $mydb->getCount('id', 'tbl_sales', 'review_status="1" AND client_id!=1087');

                $total_pages = ceil($rs_result / $num_rec_per_page);

                if ($page <= 1) {

                } else {
                    $j = $page - 1;
                    echo '<li >
                            <a href="' . ADMINURLPATH . 'sales&page=' . $j . '" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>';
                }


                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i <> $page) {

                        echo '<li><a href ="' . ADMINURLPATH . 'sales&page=' . $i . '" >' . $i . '</a></li>';
                    } else {
                        echo '<li >

                                <span aria-hidden="true">' . $i . '</span>
                            </a>
                        </li>';
                    }
                }


                if ($page == $total_pages) {

                } else {
                    $j = $page + 1;
                    echo "<li>
                    <a href='" . ADMINURLPATH . 'sales&page=' . $j . "' aria-label='Next'>
                        <span aria-hidden='true'>&raquo;</span>
                    </a>
                </li>";
                }
                ?>
            </ul>
        </nav>

    <?php }?>
</form>

  <?php  }?>

