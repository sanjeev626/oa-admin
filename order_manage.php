<?php
/*if(isset($_GET['new_order']))
{
    $buying_id = date(YmdHis).rand(111,999);
    $_SESSION['buying_id'] = $buying_id;
    $mydb->redirect(ADMINURLPATH.'order_manage');
}
$buying_id = $_SESSION['buying_id'];
echo "<br>buying_id = ".$buying_id;*/
if(isset($_GET['reorder_id']))
{
    $id=$_GET['reorder_id'];
    $client_id=$mydb->getValue('client_id','tbl_sales','id='.$id);
    $result1 =$mydb->getQuery('*','tbl_order','sales_id="'.$id.'" And status=1 GROUP BY medicine_id');
    while ($checkoutorder = $mydb->fetch_array($result1))
    {
        /*print_r($checkoutorder);
        echo "<br>";*/
        $medicine_id = $checkoutorder['medicine_id'];
        $quantity_by_user=$mydb->getSum('quantity','tbl_order','sales_id="'.$id.'" And status=1 AND medicine_id="'.$medicine_id.'"');
        $med=$checkoutorder['medicine_name'];
        $med_dose=$checkoutorder['medicine_type'];
        $refill_day = $checkoutorder['refill_day'];
        $data='';
        $data['session_id']=$client_id;
        $data['medicine_id']=$medicine_id;
        $data['medicine_name']=$med;
        $data['quantity']=$quantity_by_user;
        $data['medicine_type']=$med_dose;
        $data['refill_day']=$refill_day;
        //print_r($data); die();
        $mydb->insertQuery('tbl_temporder',$data);
    }
    $_SESSION['user_id']=$client_id;
    $user_id_session=$_SESSION['user_id'];
    $image_id=$mydb->getValue('image_id','tbl_sales','id='.$id);
    $newDate = date("d-m-Y");
    $_SESSION['date_order']=$newDate;
    $url = ADMINURLPATH."order_manage&message=The medicine has been re-ordered.";
    $mydb->redirect($url);
}
elseif(isset($_SESSION['user_id']))
{
    $user_id_session = $_SESSION['user_id'];
    $image_id=$_SESSION['image_value'];
}
else
{
    $user_id_session=0;
}
//for image uplaod section
if(isset($_POST['fsubmit']) && isset($_FILES['photo_image']['name']))
{
    $img_name=$_FILES['photo_image']['name'];
    $path_info=pathinfo($img_name);
    if ($path_info['extension'] == 'jpg' || $path_info['extension'] == 'jpeg' || $path_info['extension'] == 'png' || $path_info['extension'] == 'gif'
        ||$path_info['extension'] == 'JPG' || $path_info['extension'] == 'JPEG' || $path_info['extension'] == 'PNG' || $path_info['extension'] == 'GIF')
    {
        $temp_name=$_FILES['photo_image']['tmp_name'];
        $file=$mydb->Uploadfile($img_name,$temp_name);
        $date_order=$_POST['order_date'];
        $newDate = date("Y-m-d", strtotime($date_order));
        $data1='';
        $data1['client_id']=$_POST['user_select'];
        $data1['date'] = $newDate;
        $data1['image_name']=$file['filename'];
        $data1['image_path']=$file['filepath'];
        $uid=$mydb->insertQuery('tbl_image',$data1);
        if($uid>0)
        {
            $message="Your image is successfully uploaded";
        }
        else
        {
            $message="Your image is not uploaded";
        }
    }
    else
    {
        $message="Invalid image format";
    }
    $url = ADMINURLPATH."order_manage&message=".$message;
    $mydb->redirect($url);
}

if(isset($_POST['btnUpdate']) && $_POST['btnUpdate']=="Update")
{
    for($u=0;$u<count($_POST['temp_order_id']);$u++)
    {
        $temp_order_id = $_POST['temp_order_id'][$u];
        $data_u = '';
        $data_u['quantity'] = $_POST['qty'][$u];
        $mydb->updateQuery('tbl_temporder',$data_u,'id='.$temp_order_id);
    }
}

$counter_sales_id = 0;
if(isset($_GET['sales_type'])&& $_GET['sales_type']=="counter_sales")
{
    $counter_sales_id = 1087;    
}
?>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('#user_id').click(function()
        {
            var user_ids=$("#user_id").val();
            if(user_ids!=0)
            {
                var dataString ='useridentifcation='+user_ids;
                $.ajax
                ({
                    url: "image_display.php",
                    data: dataString,
                    type: "GET",
                    success: function(response)
                    {
                        var obj=$.parseJSON(response);
                        $('#image_display').html(obj.value1);
                    }
                });
            }else{
                <?php $output='';?>
                $('#image_display').html('<?php echo $output;?>');
            }
        });
        $( "#date_order" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $("#searchid").autocomplete
        ({
            source:'liststock_ajax.php',
            minLength:1,
            select: function (e, ui) {
              $('#result').html(e);
              $("#stock_id").val(ui.item.stock_id);
              $("#medicine_id").val(ui.item.medicine_id);                
            }
        });
        //for insert data in tempory table ;
        $("#nextid").click(function()
        {
            var medicinename = $("#searchid").val();
            var quantityquantity = $("#quantity").val();
            var dose=$("#medicine_type:checked").val();
            var date_order=$("#date_order").val();
            var image_id=$("#image_display:checked").val();
            var user_ids=$("#user_id option:selected").val();
            var dataString = 'medicinename='+ medicinename + '&quantity='+ quantityquantity+'&dose='+dose+'&user_id='+user_ids+'&date_order='+date_order+'&image_ids='+image_id;
            if(medicinename!='' && quantityquantity!=''&& date_order!='' && image_id!='' && user_ids!='')
            {
                $.ajax
                ({
                    url: "addorder.php",
                    data: dataString,
                    type: "POST",
                    cache: false,
                    success: function(response){
                        window.location.href="index.php?manager=order_manage";
                    }
                });
            } return false;
        });
        //for checkout
        $("#checkout").click(function()
        {
            var user_ids=$("#user_id option:selected").val();
            var old_user_ids=$("#oldUser").val();
            //alert(user_ids);
            var date_value=$("#date_order").val();
            var image_id=$("#image_display:checked").val();
            var sales_type = $("#sales_type:checked").val();
            //alert(sales_type);
            var dataString='user_id='+ user_ids+ '&datedate='+ date_value+'&image_value='+image_id+'&oldUser='+old_user_ids+'&sales_type='+sales_type;
            $.ajax
            ({
                url: "order_submit.php",
                data: dataString,
                type: "POST",
                cache:false,
                success: function(data) {
                  //  alert(data);
                    window.location.href="index.php?manager=order_review";
                }
            });
        });
    });
    //to remove data
    function removeMedicine(val,status)

    {

        var order_val= val;



        var type=status;

        var date_order=$("#date_order").val();



        var dataString='order_id='+order_val+'&status='+type+'&orderdate='+date_order;



        $.ajax

        ({

            type:"GET",

            url:"order_delete.php",

            data:dataString,

            cache:false,

            success: function(response){

                window.location.href="index.php?manager=order_manage";

            }

        });



    }

</script>
<script>

    jQuery(document).ready(function(){

        jQuery("#prescription_uplaod").validationEngine();

        jQuery("#nextid").validationEngine();

    });

</script>

<div class="container upload">
  <div class="row">
    <div class="col-md-12">
      <h2><span>Order Form</span></h2>
    </div>
  </div>
  <div class="row form">
    <ul id="menu">
      <li class="current"><a data-id="div1">You can also order here</a></li>
      <li><a data-id="div2">Upload Your Prescription</a></li>
    </ul>
    <br>
    <br>
    <div class="pbox" id="div1">
      <div class="border-box form-horizontal" id="medicineorder">
        <div class="form-group">
          <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px; width:150px;">Sales Type</label>
          <input type="radio" name="sales_type" id="sales_type" value="online" checked="checked">
          Online
          <input type="radio" name="sales_type" id="sales_type" value="counter_sales" <?php if(isset($_GET['sales_type'])&& $_GET['sales_type']=="counter_sales") echo 'checked="checked"';?>>
          Counter Sales </div>
        <div class="form-group">
          <input type="hidden" name="oldUser" id="oldUser" value="<?php echo $user_id_session; ?>">
          <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;">User</label>
          <select class="form-control clearfix validate[required]" name="user_id" style="float:right; margin-right:300px; width:60%;" id="user_id" data-errormessage-value-missing="Please Select an user">
            <option value="0">Select User</option>
            <?php

                        $result = $mydb->getQuery('*','users','1=1 ORDER BY name');

                        while($rasMember = $mydb->fetch_array($result))

                        {

                            $user_id = $rasMember['id'];

                            $user_name = $rasMember['name'];

                            ?>
            <option value ="<?php echo $user_id;?>"<?php if($user_id_session==$user_id) echo 'selected'; elseif($counter_sales_id==$user_id) echo 'selected';?>><?php echo $user_name; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;">Date : </label>
          <input type="text" name="date_order" id="date_order" class="form-control clearfix validate[required] text-input" style="float:right;margin-right:300px;width:60%;" value="<?php if(isset($_SESSION['date_order'])) echo $_SESSION['date_order']; else echo date('d-m-Y');?>">
        </div>
        <?php

                if(isset($image_id))

                {



                    $result = $mydb->getQuery('*','tbl_image','id="'.$image_id.'"');

                    while($rasValue = $mydb->fetch_array($result))

                    {

                        $image_id=$rasValue['id'];

                        $image_name=$rasValue['image_name'];

                        $image_path=$rasValue['image_path'];



                        ?>
        <div class="form-group prescription" id="image_display">
          <label class="radio-inline">
            <input type="radio" name="image_display" id="image_display" value="<?php echo $image_id;?>"checked>
            <img style="width:50px;height:60px;" src="../<?php  echo $image_path;?>/<?php echo $image_name;?>" alt=""> </label>
        </div>
        <?php }

                }else

                {



                    ?>
        <div class="form-group prescription" id="image_display"> </div>
        <?php }?>
        <div class="row">
          <div class="col-md-1 col-sm-1 col-xs-1">
            <label for="sn">S.N. </label>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-3">
            <label for="quantity">Quantity/No. of tablet: </label>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-4">
            <label for="exampleInputEmail1">Product/Medicine Name</label>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2">
            <label for="type">Medicine Type:</label>
          </div>
          
          <!-- <div class="col-md-1 col-sm-1 col-xs-1"><label for="blank">Rate</label></div>

                    <div class="col-md-2 col-sm-2 col-xs-2"><label for="blank">Total Amount</label></div> --> 
          
        </div>
        
        <!--Append medicine on add more button-->
        
        <div id="addmedicine">
          <div class="row">
            <div class="col-md-1 col-sm-1 col-xs-1">
              <label for="number">&nbsp;</label>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
              <input min="1" type="number" id="quantity" name="quantity" value="1" />
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4">
              <input type="text" class="form-control" id="searchid" placeholder="Search for medicine" value="" required>
              <div id="result"> </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2 med-type">
              <label class="radio-inline">
                <input type="radio" name="medicine_type" id="medicine_type" value="one_time" checked>
                One-time </label>
              <label class="radio-inline">
                <input type="radio" name="medicine_type" id="medicine_type" value="regular">
                Regular </label>
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1">
              <input type="hidden" id="medicine_id" name="medicine_id" value="" />
              <input type="hidden" id="stock_id" name="stock_id" value="" />
              <button type="submit" class="btn btn-default next" id="nextid">Add Order</button>
            </div>
          </div>
        </div>
        
        <!--Append medicine on add more button-->
        
        <form action="" method="post" name="medicineinsert">
          <div id="display">
            <div class="row">
              <div class="col-md-1 col-sm-1 col-xs-1">
                <label for="sn">S.N. </label>
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                <label for="quantity">Quantity </label>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4">
                <label for="exampleInputEmail1">Product/Medicine Name</label>
              </div>
              <div class="col-md-2 col-sm-1 col-xs-1 med-type">
                <label for="type">Medicine Type:</label>
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1 med-type">
                <label for="blank">Rate</label>
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1 med-type">
                <label for="blank">Total</label>
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">&nbsp;</div>
            </div>
            <?php
            $gtotal=0;
            $output = "";
            $order_total=0;
            $count = 0;
            $result1 = $mydb->getQuery('*','tbl_temporder','session_id="'.$user_id_session.'"');
            while($order = $mydb->fetch_array($result1))
            {
            $order_id = $order['id'];
            $countStock = $mydb->getSum('stock-sales','tbl_stock','medicine_id="'.$order['medicine_id'].'" AND stock>sales ORDER BY expiry_date ASC');
            //echo $mydb->getSum('stock-sales','tbl_stock','medicine_id="'.$order['medicine_id'].'" AND stock>sales ORDER BY expiry_date ASC','1');
            if($countStock>=$order['quantity'])
            {
              $sp_per_tab = $mydb->getValue('sp_per_tab','tbl_stock','id="'.$order['stock_id'].'"');
              $total = $order['quantity']*$sp_per_tab;
            }
            else
            {
                if(empty($countStock))
                    $sp_per_tab = "<span style='color:red; font-weight:bold;'>Out of Stock</span>";
                else    
                    $sp_per_tab = $countStock." only Available";
                $total = 0;
            }
            $gtotal=$gtotal+$total;
            ?>
            <div class="row">
              <div class="col-md-1 col-sm-1 col-xs-1"><?php echo ++$count;?>
                <input type="hidden" id="temp_order_id[]" name="temp_order_id[]" value="<?php echo $order['id'];?>" />
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                <input min="1" type="number" id="qty[]" name="qty[]" value="<?php echo $order['quantity'];?>" style="width:50px;" />
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4"><?php echo $order['medicine_name'];?></div>
              <div class="col-md-2 col-sm-1 col-xs-1 med-type"><?php echo $order['medicine_type'];?></div>
              <div class="col-md-1 col-sm-1 col-xs-1 med-type"><?php echo $sp_per_tab;?></div>
              <div class="col-md-1 col-sm-1 col-xs-1 med-type" style="text-align:right;"><?php echo number_format($total,2);?></div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                <button type="submit" class="btn btn-default next" id ="deleteid" value ="Remove" onClick="removeMedicine('<?php echo $order_id;?>','med')">Remove</button>
              </div>
            </div>
            <?php
            }
            ?>
            <div class="row">
              <div class="col-md-1 col-sm-1 col-xs-1"></div>
              <div class="col-md-1 col-sm-1 col-xs-1" id="quan">
                <button type="submit" class="btn btn-default next" name="btnUpdate" id ="updateid" value ="Update">Update</button>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4"></div>
              <div class="col-md-3 col-sm-2 col-xs-2 med-type" align="right">Grand Total</div>
              <div class="col-md-1 col-sm-1 col-xs-1 med-type" style="text-align:right;"><?php echo number_format($gtotal,2);?></div>
              <div class="col-md-1 col-sm-1 col-xs-1">&nbsp;</div>
            </div>
            <hr/>
          </div>
        </form>
        <div class="row clearfix" style="margin-bottom: 20px; margin-right: 70px; text-align: right;">
          <button type="submit" name="checkout" id="checkout" class="btn btn-dsefault" <?php if($count==0) echo "disabled";?>>Submit</button>
          <button type="submit" name="reset_button" id="reset" class="btn btn-dsefault" onClick="removeMedicine('<?php echo $user_id_session;?>','clear')" <?php if($count==0) echo "disabled";?>>Clear All</button>
        </div>
      </div>
    </div>
    <div class="pbox" id="div2">
      <div class="border-box">
        <form class="upload" enctype="multipart/form-data" action="" method="POST" id="prescription_uplaod">
          <div class="form-group">
            <div class="form-group">
              <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;">User</label>
              <select class="form-control clearfix validate[required]" name="user_select" style="float:right; margin-right:300px; width:60%;" data-errormessage-value-missing="Please Select an user">
                <option value="">Select User</option>
                <?php
                $result = $mydb->getQuery('*','users','1=1 ORDER BY name');
                while($rasMember = $mydb->fetch_array($result)){
                $user_id = $rasMember['id'];
                $user_name = $rasMember['name'];
                ?>
                <option value ="<?php echo $user_id;?>"><?php echo $user_name; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Date</label>
              <input type="text" class="form-control clearfix validate[required] text-input datepicker" data-errormessage-value-missing="Please Enter Order Date"  data-errormessage-type-mismatch="Please enter valid format"name="order_date" id="datepicker" style="float:right;margin-right:300px;width:60%;margin-bottom:40px;">
            </div>
            <input type="file" name="photo_image" id="exampleInputFile">
            <p class="help-block">(Your Prescription)</p>
          </div>
          <button type="submit" class="btn btn-default" id="fsubmit" name="fsubmit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<script>
    $(document).ready(function () {
        $('#menu').on('click', 'a', function () {
            $('.current').not($(this).closest('li').addClass('current')).removeClass('current');
            // fade out all open subcontents
            $('.pbox:visible').hide(600);
            // fade in new selected subcontent
            $('.pbox[id=' + $(this).attr('data-id') + ']').show(600);
        });

        var client_id = $("#user_id").val();
        if(client_id=="1087")
        {
            value = 'counter_sales';
            $("input[name=sales_type][value=" + value + "]").attr('checked', 'checked');
        }
           //alert(client_id);
    });
</script>