<?php
if(isset($_GET['confirmed_edit']))
{
    $sales_id = $_GET['sales_id'];

    $resCsales = $mydb->getQuery('*','tbl_order','sales_id='.$sales_id);
    while($rasCsales = $mydb->fetch_array($resCsales))
    {
        $data1='';
        $data1['sales_id'] =$sales_id; 
        $data1['medicine_id'] = $rasCsales['medicine_id'];
        $data1['stock_id'] = $rasCsales['stock_id'];
        $data1['medicine_name'] = $rasCsales['medicine_name'];
        $data1['medicine_type']=$rasCsales['medicine_type'];
        $data1['quantity']=$rasCsales['quantity'];
        $mydb->insertQuery('tbl_orderreview',$data1);
    } 
    $resCsales = $mydb->deleteQuery('tbl_order','sales_id='.$sales_id);
	$url = ADMINURLPATH.'order_confirmedit&sales_id='.$sales_id;    
	$mydb->redirect($url);
}

if(isset($_GET['sales_id']))
{
	$sales_id=$_GET['sales_id'];
	$result=$mydb->getQuery('*','tbl_orderreview','sales_id='.$sales_id);
	$user_id_session=$mydb->getValue('client_id','tbl_sales','id='.$sales_id);
}
if(isset($_POST['btnDo']) && $_POST['btnDo']=='Update')
{
		$salesid=$_POST['salesid'];
		
		$user_id=$_POST['user_id'];
		
		$engdate=$_POST['invoice_eng_date'];
		
		$newDate=date("Y-m-d",strtotime($engdate));
		


		$data='';
		$data['client_id']=$user_id;
		$data['date']=$newDate;
		$mydb->updateQuery('tbl_sales',$data,'id='.$salesid);

		for($j=0;$j<count($_POST['item_description']);$j++)
		{
			$order_reviewid=$_POST['reviewid'][$j];
			$data2='';

			$data2['medicine_name']=$_POST['item_description'][$j];
			$data2['medicine_name'];
			$data2['quantity']=$_POST['quantity'][$j];
			$data2['medicine_id']=$mydb->getValue('id','tbl_medicine','medicine_name="'.$_POST['item_description'][$j].'"');
			

			if($order_reviewid==''){
				$data2['sales_id']=$salesid;
				$mydb->insertQuery('tbl_orderreview',$data2);
			}
			else{
				$data2['id']=$order_reviewid;
				$mydb->updateQuery('tbl_orderreview',$data2,'sales_id="'.$salesid.'"AND id="'.$order_reviewid.'"');

			}
			//$mydb->updateQuery('tbl_orderreview',$data2,'sales_id="'.$salesid.'"AND id="'.$order_reviewid.'"');

		}

		$url = ADMINURLPATH.'order_review&sales_id='.$salesid;    
    	$mydb->redirect($url);
}
?>

 <html>
	<head>
		<script src="//code.jquery.com/jquery-1.11.2.js"></script>

		<script src="jquery_ui/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css"/>

	</head>
<body>

		<SCRIPT type="text/javascript">
		
			
			$(document).ready(function()
			{
				$(document).on("click", ".addButton", function(){
					$(this).closest("tr").remove();
				});
					 $( "#invoice_eng_date" ).datepicker({ dateFormat:'dd-mm-yy' });
					

					$(".inputitem").autocomplete
                    ({
                        source:'search.php',
                        minLength:1,
                        success:function(data){

                        $('#result').html(data);
                      }

                    });

			});
			function queryauto(autoval){
				$(autoval).autocomplete
				({
					source:'search.php',
					minLength:1,
					success:function(data){

						$('#result').html(data);
					}

				});
			}
			function addmedicine(){

			var	t = '<tr><td><input name="reviewid[]" type="hidden" id="salesid" value=""><input class="inputbox inputitem " type="text" name="item_description[]" id="search_id" value="" onkeypress="queryauto(this)"><div id="result"></div></td> <td><input class=" inputbox inputitem " type="integer" name="quantity[]" id="quantity" value=""></td> <td><input type="button" class="btn btn-danger addButton" value="Delete medicine" id="addButton" ></td></tr>';
				/*var wrapper         = $(".FormTbl");*/
				$("#FormTbl> tbody").append(t);

			};

			function deleteMed(id){
				var id = id;
				var dataString='id='+ id;
				$.ajax({
					url:"deletemedicine.php",
					data: dataString,
					type: "POST",
					success: function(data) {
		               $('#trid'+id).hide();
					}
				})
			}
		</SCRIPT>

<form action="" method="post" name="updateconfirm" onSubmit="return call_validate(this,0,this.length);">
	<table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl" id="FormTbl">

		<tr class="TitleBar">
	      <td class="TtlBarHeading" colspan="9">order edit</td> 
		</tr>

		<tr>
			<td align="right" class="TitleBarA"><strong>User</strong></td>
			<td class="TitleBarA" colspan="9">
			  	<select name="user_id" id="user_id" class="inputBox"> 
		   			<option>---</option>
		   			<?php 
						$result1 = $mydb->getQuery('*','users');
						while($rasMember = $mydb->fetch_array($result1))
						{
							$user_id = $rasMember['id'];
                          	$user_name = $rasMember['name']; 
					?>							
					 <option value ="<?php echo $user_id;?>"<?php if($user_id_session==$user_id) echo 'selected';?>><?php echo $user_name; ?></option>
				<?php } ?>	
				</select>
			</td>
				
   		</tr> 

   		<tr>
   			<td align="right" class="TitleBarA"><strong>Order Date</strong></td>
   			<td  class="TitleBarA" colspan="9"><input name="invoice_eng_date" id="invoice_eng_date" type="text" value="<?php
				$odate=$mydb->getValue('date','tbl_sales','id='.$sales_id);echo date("d-m-Y",strtotime($odate));?>" class="inputBox"/></td></td>
   		</tr>

   		<tr>
			
			<td class="TitleBarA" ><strong>Medicine Name<strong></td>
			<td class="TitleBarA" colspan="9"><strong>Requested Quantity</strong></td>
			
		</tr>
		<?php
		 $count = $mydb->getCount('*','tbl_orderreview','sales_id='.$sales_id);
		$i=1;
		while($resMember=$mydb->fetch_array($result))
		{

				$medicine_name=$resMember['medicine_name'];
				$quantity=$resMember['quantity'];
				$reviewid=$resMember['id'];
			//echo $count;
			$i=0;
		?>
			<tr id="trid<?php echo $reviewid; ?>">
				<td><input name="reviewid[]" type="hidden" id="salesid" value="<?php echo $reviewid;?>">
					<input class=" inputbox inputitem " type="text" name="item_description[]" id="search_id" value="<?php if(isset($medicine_name)) echo $medicine_name;?>">
					<div id="result"></div></td>
					<td><input class="inputbox inputitem" type="integer" name="quantity[]" id="quantity" value="<?php if(isset($quantity)) echo $quantity;?>"></td>
					<td>
						<input type='button' class="btn btn-danger" value='Delete medicine' id='addButton'
							   onclick="deleteMed(<?php echo $reviewid;?>)">
					</td>
			</tr>


		<?php $i++;} ?>
		<tfoot>
		<tr>
				  <!--<td align="right" class="TitleBarA">&nbsp;</td>-->
				  <td align="right" class="TitleBarA">
					  <input type='button' class="btn called" value='Add medicine' id='addButton' onclick="addmedicine()">
				  </td>
				  <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnDo" id="addsub" type="submit" value="Update" class="button"/></td>
				  <td class="titleBarA" Valign="top"><input name="salesid" type="hidden" id="salesid" value="<?php echo $sales_id;?>"></td>
		</tr>
		</tfoot>
	</table>
</form>