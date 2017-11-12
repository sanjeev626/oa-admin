<script type="text/javascript">
    $(document).ready(function(){
        $("#usersearch").autocomplete({
            source:'ajaxoverallsearch.php?type=ordersearch' + $("#usersearch").val(),
            minLength:1,
            select:function(e,ui) {
                location.href = ui.item.the_link;
            }
        });

        $( "#from_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( "#to_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });
</script>
<!-- ===========================to view order list of specified person===================================== -->
<?php
{

$reviewstatus = 1;
$num_rec_per_page = 50;
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $num_rec_per_page;
$today = date('Y-m-d');
if(isset($_POST['btnList']))
{
    if($_POST['from_date']==$_POST['to_date'])
    { 
     $date = $_POST['from_date'];  
    $result = $mydb->getQuery("*", "tbl_sales", "review_status='" . $reviewstatus . "'  AND sales_type='counter_sales' AND date='".$date."' ORDER BY id desc ");
    } 
    else{ 
     $from_date = $_POST['from_date'];  
     $to_date = $_POST['to_date'];  

    $result = $mydb->getQuery("*", "tbl_sales", "review_status='" . $reviewstatus . "'  AND sales_type='counter_sales' AND date BETWEEN '".$from_date."' AND '".$to_date."' ORDER BY id desc ");
    }  
}
else
{
    $result = $mydb->getQuery("*", "tbl_sales", "review_status='" . $reviewstatus . "'  AND sales_type='counter_sales' AND date='".$today."' ORDER BY id desc limit " . $start_from . "," . $num_rec_per_page . "");
}
    
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
            <td colspan="8" class="TtlBarHeading">Counter Sales :</td>
        </tr>
        <tr class="TitleBar">
            <td colspan="8" class="TtlBarHeading"> 
                From Date : &nbsp;&nbsp;<input type="text" name="from_date" id="from_date" class="button" value="<?php  if(isset($_POST['from_date'])) echo $_POST['from_date']; else echo date('Y-m-d');?>">&nbsp;&nbsp;&nbsp;&nbsp;
                To Date : &nbsp;&nbsp;<input type="text" name="to_date" id="to_date" class="button" value="<?php if(isset($_POST['to_date'])) echo $_POST['to_date']; else echo date('Y-m-d');?>">
                    <button type="submit" name="btnList" id="btnList" class="button">List All</button>

            </td>
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

            $total_sales = 0;
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
                    <td class="titleBarA" align="center" valign="top"><?php echo ++$counter; ?></td>
                    <td class="titleBarA" align="center" valign="top"><?php echo $users ?></td>
                    <td class="titleBarA" valign="top"><?php echo date('d-m-Y', strtotime($rasSales['date'])); ?></td>
                    <td class="titleBarA"
                        valign="top"><?php echo $mydb->getCount('id', 'tbl_order', 'sales_id=' . $rasSales['id']); ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasSales['net_amount']; 
                        $total_sales = $total_sales+$rasSales['net_amount']; ?></td>
                    <td class="titleBarA" valign="top"><?php if ($rasSales['payment']) {
                            echo $rasSales['payment'];
                        } else {
                            echo "Not delivered Yet";
                        } ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasSales['Remarks']; ?></td>
                    <td class="titleBarA" Valign="top"><a
                            href="<?php echo ADMINURLPATH; ?>counter_sales_details&sales_id=<?php echo $rasSales['id']; ?>">View
                            Details</a></td>
                </tr>
                <?php
            }
            ?>

            <tr class="TitleBar">
                <td class="TtlBarHeading" colspan="4" style="text-align:right;"><strong>Total Sales :</strong></td>
                <td width="8%" valign="top" class="TtlBarHeading"><strong><?php echo $total_sales;?></strong></td>
                <td class="TtlBarHeading" colspan="3"></td>
            </tr>
            <?php
        } else {
            ?>
            <tr>
                <td class="message" colspan="8">No Sales Record</td>
            </tr>
            <?php
        }
        ?>
    </table>
</form>

  <?php  }?>