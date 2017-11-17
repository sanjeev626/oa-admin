<link rel="stylesheet" type="text/css" href="dropdown/chrometheme/chromestyle.css"/>
<script type="text/javascript" src="dropdown/chromejs/chrome.js">


/***********************************************
* Chrome CSS Drop Down Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>


<script>
      $(document).ready(function(){

       // var callAjax = function(){

          $.ajax({
            method:'get',
            url:'notification.php',
            cache:false,

            success:function(response){
            var obj=$.parseJSON(response);
            $('#totalnoti').html(obj.value1);
            $('#result').html(obj.value2);
            }
          });

        //}
        //setInterval(callAjax,5000);
      });
</script>

<div class="chromestyle" id="chromemenu">
    <ul>
        <li><a href="index.php" title="Return Home">HOME</a></li>
        <li><a rel="dropmenu1" href="javascript:void(0);" title="CHANGE CONFIGURATION">CONFIG</a></li>
        <li><a rel="dropmenu3" href="javascript:void(0);" title="MANAGE USERS">USERS</a></li>
        <li><a href="<?php echo ADMINURLPATH;?>company" title="MANAGE COMPANY">COMPANY</a></li>
        <li><a href="<?php echo ADMINURLPATH;?>distributor" title="MANAGE USERS">DISTRIBUTOR</a></li>
        <li><a rel="dropmenu4" href="javascript:void(0);" title="MANAGE MEDICINE">MEDICINE</a></li>
        <li><a href="<?php echo ADMINURLPATH;?>stock" title="MANAGE STOCK DETAILS">STOCK DETAILS</a></li>
        <li><a href="<?php echo ADMINURLPATH;?>order" title="MANAGE ORDER">MANAGE ORDER</a></li>
        <li><a href="<?php echo ADMINURLPATH;?>order_review" title="ORDER REVIEW">ORDER REVIEW</a></li>
        <li><a href="<?php echo ADMINURLPATH;?>sales" title="MANAGE SALES">SALES</a></li>
        <li><a rel="dropmenu_counter_sales" href="javascript:void(0);" title="MANAGE COUNTER SALES">COUNTER SALES</a></li>
        <!-- <li><a rel="dropmenu2" href="javascript:void(0);" title="STOCK RETURN">STOCK RETURN</a></li> -->
        <li class="dropdown" style="position:relative;">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell" title="Notifications" style="font-size:18px;"></i><span class="badge" style="padding:3px 3px;font-size:9px;background-color:#e60f0d;position:absolute;bottom:7px;left:34px;" id="totalnoti"></span></a>

           <ul class="dropdown-menu" role="menu" style="width:280px;text-align:left;color:#333; max-height: 290px;overflow: hidden;border-radius:0px;margin-top:11px;" id="result"></ul>
        </li>

</ul>

</div>

<!--1st drop down menu -->
  <div id="dropmenu1" class="dropmenudiv">
    <a href="<?php echo ADMINURLPATH;?>config">Change Config</a>
    <a href="<?php echo ADMINURLPATH;?>changepass">Change Password</a>
</div>
<!--2st drop down menu -->
  <div id="dropmenu2" class="dropmenudiv">
    <a href="<?php echo ADMINURLPATH;?>stock_return_list">DISTRIBUTORS</a>
    <a href="<?php echo ADMINURLPATH;?>sales_return_list">CLIENTS</a>
</div>
<div id="dropmenu3" class="dropmenudiv">
    <a href="<?php echo ADMINURLPATH;?>users">All Users</a>
    <a href="<?php echo ADMINURLPATH;?>regular_user">Regular Users</a>
    <a href="<?php echo ADMINURLPATH;?>one_time_user">One Time Users</a>
    <a href="<?php echo ADMINURLPATH;?>registered_only">Registered Only</a>
    <a href="<?php echo ADMINURLPATH;?>registered_but_not_verified">Registered but not Verified</a>
</div>

<div id="dropmenu4" class="dropmenudiv">
    <a href="<?php echo ADMINURLPATH;?>medicine">Search Medicine</a>
    <a href="<?php echo ADMINURLPATH;?>merge_medicine_name">Merge Medicine Name</a>
    <a href="<?php echo ADMINURLPATH;?>medicine_without_company">Medicine without Company</a>
    <a href="<?php echo ADMINURLPATH;?>purchase_return">Purchase return</a>
    <a href="<?php echo ADMINURLPATH;?>all-stock-sales">Stock Error</a>
</div>

<div id="dropmenu_counter_sales" class="dropmenudiv">
    <a href="<?php echo ADMINURLPATH;?>order_manage&sales_type=counter_sales">Add Sales</a>
    <a href="<?php echo ADMINURLPATH;?>counter_sales_list">List All</a>
</div>




<script type="text/javascript">
    cssdropdown.startchrome("chromemenu");
</script>
