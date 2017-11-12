<!DOCTYPE html>
<html lang="en">
  <head>
    

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="css/style.css" rel="stylesheet" type="text/css" >

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://code.jquery.com/jquery-1.11.2.js"></script>

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
          <li class="current"><a href="#" data-id="div1">Upload Your Prescription</a></li>
          <li><a href="#" data-id="div2">You can also order here</a></li>
        </ul><br><br>
        <div class="pbox" id="div1">
          <div class="border-box">
            <form class="upload">
              <div class="form-group">
              <div class="form-group">
                <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;">User</label>
                <select class="form-control clearfix" style="float:right; margin-right:300px; width:60%;">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
                </div>
                   <div class="form-group">
                    <label for="exampleInputEmail1">Date</label>
                    <input type="email" class="form-control clearfix" id="exampleInputEmail1" style="float:right;margin-right:300px;width:60%;margin-bottom:40px;">
                  </div>
                <input type="file" id="exampleInputFile">
                <p class="help-block">(Your Prescription)</p>
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>
          </div>  
        </div>
        <div class="pbox" id="div2">
         <div class="border-box">
         	<div class="form-group">
                <label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;">User</label>
                <select class="form-control clearfix" style="float:right; margin-right:300px; width:60%;">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                </select>
                </div>
                   <div class="form-group">
                    <label for="exampleInputEmail1">Date</label>
                    <input type="email" class="form-control clearfix" id="exampleInputEmail1" style="float:right;margin-right:300px;width:60%;margin-bottom:40px;">
                  </div>
            <div class="row">
              <div class="col-md-1 col-sm-1 col-xs-1" style="margin-right:61px;">
                <label>S.N. </label> 
              </div> 
              <div class="col-md-4 col-sm-4 col-xs-4">
                  <label for="exampleInputEmail1">Product/Medicine Name</label>
              </div>
              <div class="col-md-1 col-sm-1 col-xs-1">
                <label for="quantity">Qty: </label> 
              </div> 
              <div class="col-md-1 col-sm-1 col-xs-1 med-type">
                <label for="type">Medicine Type:</label>
            </div>
          </div>
         	<div class="row">
              <div class="col-md-1 col-sm-1 col-xs-1" style="margin-right:61px;">
                <label for="number">1. </label> 
              </div> 
           		<div class="col-md-4 col-sm-4 col-xs-4">
              		<div class="form-group">
                		<input type="email" class="form-control" id="exampleInputEmail1" >
              		</div>
           		</div>
           		<div class="col-md-1 col-sm-1 col-xs-1">
              		<input min="1" type="number" id="quantity" name="quantity" value="1" />
           		</div> 
           		<div class="col-md-1 col-sm-1 col-xs-1 med-type">
         			<label class="radio-inline">
            			<input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> One-time
          			</label>
          			<label class="radio-inline">
            			<input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Regular
          			</label>
        		</div>
	           <div class="col-md-3">
	             	<button type="submit" class="btn btn-default next">Next</button>
	           </div>
        	</div>
        	<button type="submit" class="btn btn-default">Submit</button>
                
        </div>

        </div>  
        </div>
        </div>
      
      




        

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
    <script src="jsript/jquery1.11.2.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="jscript/bootstrap.min.js"></script>
    <script src="jscript/owl.carousel.min.js"></script>
    
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
 
      // "singleItem:true" is a shortcut for:
      // items : 1, 
      // itemsDesktop : false,
      // itemsDesktopSmall : false,
      // itemsTablet: false,
      // itemsMobile : false
 
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


  </body>
</html>

