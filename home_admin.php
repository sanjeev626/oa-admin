<script>
    $(document).ready(function(){

        $.ajax({
            method:'get',
            url:'notification.php',
            cache:false,

            success:function(response){


                var obj=$.parseJSON(response);

                $('#totalnoti').html(obj.value1);
                $('#result').html(obj.value2);
                $('#new_order').html(obj.value3);
                $('#orderdelivery_count').html(obj.value4);
                $('#prescription_image').html(obj.value5);
                $('#registeruser_count').html(obj.value6);
            	$('#refilldate_count').html(obj.value7);
                $('#stock_alert').html(obj.value8);
                $('#product_count').html(obj.value9);
                $('#total_sales').html(obj.value10);
                $('#total_purchase').html(obj.value11);
                $('#total_Profit').html(obj.value12);
                 $('#medicine_expiry').html(obj.value13);

            }


        });

    });

</script>

<div class="container">
	<div class="row home">
		<div class="col-md-3 order">
			<div class="parts">
				<h2 id="new_order"></h2>
				<p>New Order</p>
			</div>
			<div class="more">
				<a href="<?php echo SITEROOTADMIN; ?>index.php?manager=order_review">View Details</a>
			</div>
		</div>
		<div class="col-md-3 upload">
			<div class="parts">
				<h2 id="prescription_image"></h2>
				<p>Prescription Uploaded</p>
			</div>
			<div class="more">
				<a href="<?php echo SITEROOTADMIN; ?>index.php?manager=details&action=prescription_upload">View Details</a>
			</div>
		</div>
		<div class="col-md-3 refill">
			<div class="parts">
				<h2 id="refilldate_count"></h2>
				<p>Refill Call Alert</p>
			</div>
			<div class="more">
				<a href="<?php echo SITEROOTADMIN; ?>index.php?manager=details&action=refill">View Details</a>
			</div>
		</div>

		<div class="col-md-3 stock">
			<div class="parts">
				<h2 id="stock_alert"></h2>
				<p>Stock Alert</p>
			</div>
			<div class="more">
				<a href="<?php echo SITEROOTADMIN; ?>index.php?manager=details&action=stock_alert">View Details</a>
			</div>
		</div>

		<div class="col-md-3 med_alert">
            <div class="parts">
                <h2 id="medicine_expiry"></h2>
                <p>Medicine Expiry</p>


            </div>
            <div class="more">
                <a href="<?php echo SITEROOTADMIN; ?>index.php?manager=details&action=medicine_alert">View Details</a>
            </div>
        </div>
		<div class="col-md-3 delivery">
			<div class="parts">
				<h2 id="orderdelivery_count"></h2>
				<p>Pending Delivery</p>
			</div>
			<div class="more">
				<a href="<?php echo SITEROOTADMIN; ?>index.php?manager=order">View Details</a>
			</div>
		</div>
		<div class="col-md-3 user">
			<div class="parts">
				<h2 id="registeruser_count"></h2>
				<p>New User Registered</p>
			</div>
			<div class="more">
				<a href="<?php echo SITEROOTADMIN; ?>index.php?manager=details&action=user_reg">View Details</a>
			</div>
		</div>
		<div class="col-md-3 in_stock">
			<div class="parts">
				<h2 id="product_count"></h2>
				<p>Products in Stock </p>
			</div>
			<div class="more">
				<a href="<?php echo SITEROOTADMIN; ?>index.php?manager=details&action=product_list">View Details</a>
			</div>
		</div>		
	</div>
</div>