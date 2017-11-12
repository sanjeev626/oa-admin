<?php
/*
derive data from sales_details to reorder medicine.
*/

if(isset($_GET['reorder_id']))
{
$id=$_GET['reorder_id'];
$client_id=$mydb->getValue('client_id','tbl_sales','id='.$id);
$result1 =$mydb->getQuery('*','tbl_order','sales_id='.$id);      
while ($checkoutorder = $mydb->fetch_array($result1))
{		
	$quantity_by_user=$checkoutorder['quantity'];
	// while($quantity_by_user>0)
	 {
		$data1='';			
		$data1['session_id'] = $client_id; 
		$med = $checkoutorder['medicine_name'];
		//echo $med.'<br>';
		$compmed_sales1=preg_replace('/[^A-Za-z0-9]/',"", $med);
		
		//echo $mydb->getArray('id,pack,rate,stock,sales','tbl_stock','description="'.$compmed_sales1.'" AND sales<>stock','1');
		$rasStock = $mydb->getArray('id,pack,rate,stock,sales','tbl_stock','description="'.$compmed_sales1.'" AND sales<>stock');
		//echo '<br> Print---<br>';
		//print_r($rasStock);
		//echo '<br><br>';
		$data1['medicine_name'] = $med;		
		$data1['medicine_type']=$checkoutorder['medicine_type'];
		
		$stock_id = $rasStock['id'];
		$stock_sales=$rasStock['sales'];
		$available = $rasStock['stock']-$rasStock['sales'];
		$pack = $rasStock['pack'];
		$rate_per_strip = $rasStock['rate'];
		$rate = $rate_per_strip/$pack;
		$result=$rate+$rate*0.16;
		if($result>0)
		{		
			if($available>$quantity_by_user)
			{
					$data1['quantity'] =$quantity_by_user;
					$sales_quantity=$quantity_by_user;
					$quantity_by_user=0;
			}
			else
			{
					$data1['quantity'] = $available;
					$sales_quantity=$available;
					$quantity_by_user=$quantity_by_user-$available;
				
			}	

			$data1['stock_id']=$rasStock['id'];
		
			$data1['Rate']=round($result,2);
			$data1['total_amount']=round($data1['Rate']*$data1['quantity']);
			
		}
		else
		{
			$data1['quantity']=$_POST['quantity'];
			$data1['Rate']=0.00;
			
			$data1['total_amount']=0.00;
			$quantity_by_user=0;
				
		}
		//print_r($data1);	
		$mydb->insertQuery('tbl_temporder',$data1);			
		$stock_val=$stock_sales+$sales_quantity;
		

		$data='';
		$data['sales']=$stock_val;
		
		$mydb->updateQuery('tbl_stock',$data,'id='.$rasStock['id']);
		
		//echo $mydb->updateQuery('tbl_stock',$data,'id='.$stock_id,'1');
 	 }
} 



            $_SESSION['user_id']=$client_id;

            $user_id_session=$_SESSION['user_id'];

		//$mydb->redirect(ADMINURLPATH."order_manage");

}

  

  elseif(isset($_SESSION['user_id']))

  {

  

	 $user_id_session = $_SESSION['user_id'];

  }



  else

  {

	 $user_id_session=0;

  }	



  

//for image uplaod section

if(isset($_POST['fsubmit'])&&isset($_FILES['photo_image']['name']))

{



                

                  $img_name=$_FILES['photo_image']['name'];

                  

                  $path_info=pathinfo($img_name);

                

             if ($path_info['extension'] == 'jpg' || $path_info['extension'] == 'jpeg' || $path_info['extension'] == 'png' || $path_info['extension'] == 'gif')        

                   

                {

                   $temp_name=$_FILES['photo_image']['tmp_name'];

                  

                  $file=$mydb->Uploadfile($img_name,$temp_name);

                  

                  $date_order=$_POST['order_date'];



                  $newDate = date("Y-m-d", strtotime($date_order));

                 



                  $data = '';

                  $data['client_id']=$_POST['user_select'];

                  

                  $data['date'] = $newDate;

                 

                  $sales_id = $mydb->insertQuery('tbl_sales',$data);



                  $data1='';

                  $data1['sales_id']=$sales_id;

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

                  $url = ADMINURLPATH."order_manage#prescription&message=".$message;

                    $mydb->redirect($url);

}



// if(isset($_SESSION['discount'])) 

// 	$discount = $_SESSION['discount']; 

// else 

// 	$discount = '0';

?>
<html>
<head>
<script src="https://code.jquery.com/jquery-1.11.2.js"></script>
<script src="jscript/bootstrap.min.js"></script>
<script src="jscript/owl.carousel.min.js"></script>
<script src="jquery_ui/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css"/>

<!-- Bootstrap -->

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/owl.carousel.css" rel="stylesheet">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href="css/style.css" rel="stylesheet" type="text/css" >

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

<!--[if lt IE 9]>

      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->

</style>
<script type="text/javascript">

       $(document).ready(function()



       {

                 

                  $( "#date_order" ).datepicker({ dateFormat: 'dd-mm-yy' });

                    $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });



                    $("#searchid").autocomplete

                    ({

                        source:'search.php',

                        minLength:1,

                        success:function(data){

                        

                        $('#result').html(data);

                      }

                           

                    });



                 //for insert data in tempory table ;

                  $("#nextid").click(function()

                  {

                    var medicinename = $("#searchid").val();

                   

                    var quantityquantity = $("#quantity").val();

                 

                    var dose=$("#medicine_type:checked").val();

                    var date_order=$("#date_order").val();

					          var discount=$("#discount").val();

                     

                    var user_ids=$("#user_id option:selected").val();

                    

                    $("#display").empty();

                   

                   

                                    

                    var dataString = 'medicinename='+ medicinename + '&quantity='+ quantityquantity+'&dose='+dose+'&user_id='+user_ids+'&date_order='+date_order+'&discount='+discount;//+'&total_amt='+total_amount;

                        if(searchid!='' && quantity!='')

                        {

                            $.ajax

                            ({ 

                                url: "addorder.php",

                                data: dataString,

                                type: "POST",

                                cache: false, 

                                // success: function(response){

                                //     //var obj=$.parseJSON(response); // now obj is a json object

                                //       //alert(obj.value1); // will alert "1"

                                //       //alert(obj.value2); // will alert "This is some content"

                                //     //document.getElementById('searchid').value='';

                                //    // document.getElementById('quantity').value=1;

                                   

                                

                                //     //$('#display').html(jQuery(obj.value1)); 

                                //     //$('#net_amount').val(obj.value2);

                                  

                                   

                                // }



                            });

                          window.location.href="index.php?manager=order_manage";



                                                 

                        } return false;   



                });

                //for checkout

                $("#checkout").click(function()

                  {

                         var user_ids=$("#user_id option:selected").val();

                         //alert(user_ids);

                         var date_value=$("#date_order").val();

                          var discounts=$("#discount_percentage").val();

                         // alert(discounts);

                           // var el = $("#val");

                           // var total_amounts = parseFloat(el.text());

                           // alert(total_amounts);

                         // var net_amounts=$("#").text;

                         var discount_amount=$("#discount").val();

                        var net_amounts=$("#grand_amount").val();

                          //alert(net_amounts);



                     

                         var dataString='user_id='+ user_ids+ '&datedate='+ date_value+'&discount_val='+discounts+'&discount_amount='+discount_amount+'&net_amounts_val='+net_amounts;



                            $.ajax

                            ({ 



                                url: "order_submit.php",

                                data: dataString,

                                type: "POST",

                                cache:false,

                                success: function(data) {

                                      console.log(data);

                                    }

                                                  



                            });

                              window.location.href="index.php?manager=order_manage";      



                  });





                  

      });

                //to remove data

                function removeMedicine(val)

                {

                  var order_val= val;

                    

                  var dataString='order_id='+order_val;

                    

                    $.ajax

                    ({

                       type:"POST",

                       url:"order_delete.php",

                       data:dataString,

                       cache:false,

                        success: function(response){

                                                  

                                                 // var obj1=$.parseJSON(response); 

                                                 //  $('#display').html(jQuery(obj1.value1)); 

                                                 //  $('#net_amount').val(obj1.value2);

                                                 // // $('#amount_value').val(obj1.value2);



                                              }

                      });

                     window.location.href="index.php?manager=order_manage";





                }

        

    

</script>

<!-- <link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" /> -->

</head>

<body>
<div class="container upload">
  <div class="row">
    <div class="col-md-12">
      <h2><span>Order Form</span></h2>
    </div>
  </div>
  <div class="row form">
    <ul id="menu">
      <li class="current"><a href="#" data-id="div1">You can also order here</a></li>
      <li><a href="#" data-id="div2">Upload Your Prescription</a></li>
    </ul>
    <br>
    <br>
    <div class="pbox" id="div1">
      <div class="border-box form-horizontal">
        <div class="form-group">
          <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;">User</label>
          <select class="form-control clearfix"  name="user_id" style="float:right; margin-right:300px; width:60%;" id="user_id">
            <option value="0">Select User</option>
            <?php 

                        $result = $mydb->getQuery('*','users');

                        while($rasMember = $mydb->fetch_array($result))

                        {

                          $user_id = $rasMember['id'];

                          $user_name = $rasMember['fullname']; 

                      ?>
            <option value ="<?php echo $user_id;?>" <?php if($user_id_session==$user_id) echo 'selected';?>><?php echo $user_name; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;">Date</label>
          <input type="text" name="date_order" id="date_order" class="form-control clearfix" id="dateval" style="float:right;margin-right:300px;width:60%;" value="<?php if(isset($_SESSION['date_order'])) echo $_SESSION['date_order'];?>">
        </div>
        
        <!-- <div class="form-group prescription">

          <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;padding-right:70px;">Prescription</label>

          <label class="radio-inline">

            <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> <img style="width:50px;height:60px;" src="../upload/2015/07/9265prescription1.jpg" alt="">

          </label>

          <label class="radio-inline">

            <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> <img style="width:50px;height:60px;" src="../upload/2015/07/9265prescription1.jpg" alt="">

          </label>

          <label class="radio-inline">

            <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> <img style="width:50px;height:60px;" src="../upload/2015/07/9265prescription1.jpg" alt="">

          </label>

        </div> -->
        
        <div class="row">
          <div class="col-md-1 col-sm-1 col-xs-1">
            <label for="sn">S.N. </label>
          </div>
          <div class="col-md-1 col-sm-1 col-xs-1">
            <label for="quantity">Qty: </label>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-4">
            <label for="exampleInputEmail1">Product/Medicine Name</label>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2">
            <label for="type">Medicine Type:</label>
          </div>
          <div class="col-md-1 col-sm-1 col-xs-1">
            <label for="blank">Rate</label>
          </div>
          <div class="col-md-2 col-sm-2 col-xs-2">
            <label for="blank">Total Amount</label>
          </div>
        </div>
        
        <!-- display result using ajax  -->
        
        <div class="row">
          <div class="col-md-1 col-sm-1 col-xs-1">
            <label for="number">&nbsp;</label>
          </div>
          <div class="col-md-1 col-sm-1 col-xs-1">
            <input min="1" type="number" id="quantity" name="quantity" value="1" />
          </div>
          <div class="col-md-4 col-sm-4 col-xs-4">
            <input type="text" class="form-control" id="searchid" placeholder="Search for medicine" value="" required>
            <div id="result"> </div>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-4 med-type">
            <label class="radio-inline">
              <input type="radio" name="inlineRadioOptions" id="medicine_type" value="one_time" checked>
              One-time </label>
            <label class="radio-inline">
              <input type="radio" name="inlineRadioOptions" id="medicine_type" value="regular">
              Regular </label>
          </div>
          <div class="col-md-1 col-sm-1 col-xs-1">
            <button type="submit" class="btn btn-default next" id="nextid">Next</button>
          </div>
        </div>
        <div id="display">
          <?php

          $output = "";

					$order_total=0;

					$count = 1; 

						$result1 = $mydb->getQuery('*','tbl_temporder','session_id='.$user_id_session); 

							while($order = $mydb->fetch_array($result1))

        {

								

							$order_id = $order['id'];

							$order_amt_result=$order['total_amount'];





    					?>
          <div class="row">
            <div class="col-md-1 col-sm-1 col-xs-1"><?php echo $count;?></div>
            <div class="col-md-1 col-sm-1 col-xs-1" id="quan"><?php echo $order['quantity'];?></div>
            <div class="col-md-4 col-sm-4 col-xs-4"><?php echo $order['medicine_name'];?></div>
            <div class="col-md-2 col-sm-1 col-xs-1 med-type"><?php echo $order['medicine_type'];?></div>
            <div class="col-md-1 col-sm-1 col-xs-1 med-type"><?php echo $order['Rate'];?></div>
            <div class="col-md-1 col-sm-1 col-xs-1 med-type" style="text-align:right;"><?php echo number_format(round($order['total_amount'],2),2);?></div>
            <div class="col-md-1 col-sm-1 col-xs-1">
              <button type="submit" class="btn btn-default next" id ="deleteid" value ="Remove" onClick="removeMedicine('<?php echo $order_id;?>')">Remove</button>
            </div>
          </div>
          <?php

    					

    			             

      				++$count;

      				$order_total=$order_total+$order_amt_result;

      				$order_val=$order_total;

           

				

			 }

				?>
          <hr/>
          <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-9 med-type" style="text-align:right;">Total : </div>
            <div class="col-md-1 col-sm-1 col-xs-1 med-type" id="val" style="text-align:right;"><?php echo round($order_val);?></div>
            <div class="col-md-1 col-sm-1 col-xs-1"></div>
          </div>
          <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-9 med-type" style="text-align:right;">Discount Percentage</div>
            <div class="col-md-1 col-sm-1 col-xs-1 med-type" id="vals" style="text-align:right;">
              <input type="text" name="discount" class="form-control clearfix" id="discount_percentage" onkeyup=mul() value="<?php echo $discount_amount;?>">
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1"></div>
          </div>
          <?php            

				

					?>
          <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-9" style="text-align:right;">Discount : </div>
            <div class="col-md-1 col-sm-1 col-xs-1 med-type" style="text-align:right;">
              <input type="text" onkeyup=sub() name="discount" id="discount" value="">
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1"></div>
          </div>
          <?php            

			

				?>
          <div class="row">
            <div class="col-md-9 col-sm-9 col-xs-9 med-type" style="text-align:right;">Grand Total : </div>
            <div class="col-md-1 col-sm-1 col-xs-1 med-type" style="text-align:right;">
              <input type="text" name="grand_amount" id="grand_amount" value="">
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1"></div>
          </div>
        </div>
        <div class="row">
          <button type="submit" name="checkout" id="checkout" class="btn btn-dsefault">Submit</button>
        </div>
      </div>
    </div>
    <div class="pbox" id="div2">
      <div class="border-box">
        <form class="upload" enctype="multipart/form-data" action="" method="POST">
          <div class="form-group">
            <div class="form-group">
              <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;">User</label>
              <select class="form-control clearfix" name="user_select" style="float:right; margin-right:300px; width:60%;" >
                <option value="0">Select User</option>
                <?php 

                        $result = $mydb->getQuery('*','users');

                        while($rasMember = $mydb->fetch_array($result))

                        {

                          $user_id = $rasMember['id'];

                          $user_name = $rasMember['fullname']; 

                      ?>
                <option value ="<?php echo $user_id;?>"><?php echo $user_name; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Date</label>
              <input type="text" class="form-control clearfix" name="order_date" id="datepicker" style="float:right;margin-right:300px;width:60%;margin-bottom:40px;">
            </div>
            <input type="file" name="photo_image" id="exampleInputFile" required>
            <p class="help-block">(Your Prescription)</p>
          </div>
          <button type="submit" class="btn btn-default" id="fsubmit" name="fsubmit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">



//function for display net amount with display of discount

    function mul()

    {

          var txtdisper=document.getElementById('discount_percentage').value;

         

          var el = $("#val");

         var txttotalamount = parseFloat(el.text());

         

     

     

           if (txtdisper ==""){ txtdisper = 0.00;}

          

           if (txttotalamount ==""){txttotalamount = 0.00;}

            var discount_amount=(parseFloat(txtdisper)*parseFloat(txttotalamount)/100);

            //alert(discount_amount);

            //var discount_amount=discount_amt.tofixed(2);

           

           //parseFloat(Math.round(num2 * 100) / 100).toFixed(2);

           var grand_amount=parseFloat(txttotalamount)-parseFloat(discount_amount);

           // var grand_amount=rem_amount.tofixed(2);

           //alert(grand_amount);

           if (!isNaN(discount_amount)) {

                  document.getElementById('discount').value = parseFloat(Math.round(discount_amount* 100) / 100).toFixed(2);

       

               }

        

            if (!isNaN(grand_amount)) {

                  document.getElementById('grand_amount').value = parseFloat(Math.round(grand_amount* 100) / 100).toFixed(2);



               }

     }

  </script> 
<script type="text/javascript">

      function sub()

      {

        var txtdiscountamount=document.getElementById('discount').value;

       // alert(txtdiscountamount);

        var el = $("#val");

        var txttotalamount = parseFloat(el.text());

        if(txtdiscountamount ==""){txtdiscount = 0.00;}

        var grand_amount=parseFloat(txttotalamount)-parseFloat(txtdiscountamount);

         if (!isNaN(grand_amount)) {

                  document.getElementById('grand_amount').value = parseFloat(Math.round(grand_amount* 100) / 100).toFixed(2);



               }



      }







  </script>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>--> 

<!-- <script src="jsript/jquery1.11.2.js"></script>  --> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 

<script type="text/javascript">

$(document).ready(function() {

 



$('.carousel').carousel({

  interval: false

})







$(document).ready(function() {

 

  $("#slider0").owlCarousel({

 

      navigation : true, // Show next and prev buttons

      slideSpeed : 300,

      paginationSpeed : 400,

      singleItem:true,

      navigationText:["",""]

 

      

  });

 

});





  var owl = $("#slider");

 

  owl.owlCarousel({

     

      itemsCustom : [

        [0,1],

        [400, 2],

        [676, 3]

     

      ],

      navigation : true,

      navigationText : ["",""]

 

  });

 

});

    </script> 
<script type="text/javascript">

    $(document).ready(function() {

 

  $("#slider1").owlCarousel({

 

      navigation : true, // Show next and prev buttons

      slideSpeed : 300,

      paginationSpeed : 400,

      singleItem:true,

      navigationText : ["",""]

 

      // "singleItem:true" is a shortcut for:

      // items : 1, 

      // itemsDesktop : false,

      // itemsDesktopSmall : false,

      // itemsTablet: false,

      // itemsMobile : false

 

  });

 

});

  </script> 
<script>

$(document).ready(function () {

    $('#menu').on('click', 'a', function () {

        $('.current').not($(this).closest('li').addClass('current')).removeClass('current');

        // fade out all open subcontents

        $('.pbox:visible').hide(600);

        // fade in new selected subcontent

        $('.pbox[id=' + $(this).attr('data-id') + ']').show(600);

    });

});

</script> 

<!-- <script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>

 -->
</body>
</html>
