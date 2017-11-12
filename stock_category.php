<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css"/>
<script type="text/javascript">
 function callDelete(id)
 {
	if(confirm('Are you sure to delete ?'))
	{
		window.location='?manager=stock&did='+id;
	}
 }

 function callDeletestock(id)
 {
	if(confirm('Are you sure to delete ?'))
	{
		window.location='?manager=stock&sid='+id;
	}
 }

  function callReturn(id)
 {
	if(confirm('Are you sure to return stock ?'))
	{
		window.location='?manager=stock_return&stock_return_id='+id;
	}
 }
</script>
<script type="text/javascript">
   $(document).ready(function(){
        $(".inputBox").autocomplete({
            source:'stock_search.php',
            minLength:1,
            select:function(e,ui) {
            	location.href = ui.item.the_link;            	
            }
            
        });
    });
</script>
<?php 
//print_r($_POST);
if(isset($_POST['btnUpdate']))
{
	$count = count($_POST['medicine_id']);
	for($i=0;$i<$count;$i++)
	{
		$medicine_id = $_POST['medicine_id'][$i];
		$category = $_POST['category'][$i];
		if($category!="medicine")
		{
			$medicine_name = $mydb->getValue('medicine_name','tbl_medicine','id='.$medicine_id);
			$data='';
			$data['category'] = $category;				
			$mydb->updateQuery('tbl_medicine',$data,'id='.$medicine_id);
			$query = $mydb->updateQuery('tbl_medicine',$data,'id='.$medicine_id,'1');
			echo $medicine_name." --- ".$query."<br>";
		}
	}
}
?>

<form>
  	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
<?php if(isset($_GET['message'])){?>
		<tr>
		  	<td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
		</tr>
<?php } ?>
	
	    <tr class="TitleBar">
	      <td class="TtlBarHeading" width="90%">Stock details</td>
	      <td class="TtlBarHeading"><input name="btnAdd" type="button" value="Add" class="button" onClick="window.location='<?php echo ADMINURLPATH;?>stock_manage'" /></td>
	    </tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
		<tr>	    	
	    	<td align="right" class="TitleBarA" width="20%"><strong>Search:</strong></td>
	    	<td class="TitleBarA" style="width:50px;"><input type="text" class="inputBox" id="searchid" placeholder="Search for medicine or invoice no or distributor" style="width:50%" value="<?php if(isset($_GET['addtolist'])){echo $mydb->getValue('medicine_name','tbl_medicine','id='.$_GET['addtolist']);} ?>"></td>
		</tr>

	</table>
</form>

 <?php 
 //if(isset($_GET['addtolist']))
 {
	$medicine_id=$_GET['addtolist'];
	//$medicine_id = $mydb->getValue('medicine_id','tbl_stock','id='.$tbl_stock_id);
    //$description = $mydb->getValue('description','tbl_stock','id='.$tbl_stock_id);
	

	//echo $mydb->getQuery('*','tbl_stock','medicine_id="'.$medicine_id.'" ORDER BY id DESC','1');
	$result = $mydb->getQuery('ts.*,tm.category','tbl_stock ts INNER JOIN tbl_creditmemo tc ON ts.creditmemo_id=tc.id INNER JOIN tbl_medicine tm ON ts.medicine_id=tm.id','tc.distributor_id=27 OR tc.distributor_id=40 GROUP BY ts.medicine_id ORDER BY tc.distributor_id ASC ');

	?>
<form method="POST">
 <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">     
	<tr>
		<td width="2%" align="center" class="titleBarB"><strong>S.N</strong></td>	
		<td width="13%" align="center" class="titleBarB"><strong>Distributor</strong></td>	  
		<td width="5%" align="center" class="titleBarB"><strong>Invoice No</strong></td>	
		<td width="7%" align="center" class="titleBarB"><strong>Recieved Date</strong></td>
		<td width="10%" align="center" class="titleBarB"><strong>Item description</strong></td>
		<td width="5%" align="center" class="titleBarB"><strong>Batch No</strong></td>
		<td align="center" class="titleBarB"><strong>Expiry Date</strong></td>
		<td align="center" class="titleBarB" ><strong>Quantity</strong></td>
		<td align="center" class="titleBarB"><strong>CC/Rate</strong></td>
		<td align="center" class="titleBarB"><strong>SP</strong></td>
		<td align="center" width="3%" class="titleBarB"><strong>Deal</strong></td>
		<td align="center" class="titleBarB"style="color:green"><strong>Stock</strong></td>
		<td align="center" class="titleBarB"style="color:green"><strong>Sales</strong></td>
		<td align="center" class="titleBarB"><strong>Total price</strong></td>
		<td align="center" class="TitleBarB">Category</td>				 
	</tr>
			<?php 
			$counter = 0;
			while($rasMember5 = $mydb->fetch_array($result))
			{
			    $id=$rasMember5['id'];
				$creditmemo_id=$rasMember5['creditmemo_id'];
				$medicine_name=$rasMember5['item_description'];
							
				//print_r($rasMember5);			
				?>
		
				<tr>
				  <td align="center" class="titleBarA" valign="top"><?php  echo ++$counter;?></td>
				  <td class="titleBarA" valign="top">
				  	<?php  
				  		$distributor_id=$mydb->getValue('distributor_id','tbl_creditmemo','id='.$creditmemo_id);
				  		echo $result1=$mydb->getValue('fullname','tbl_distributor','id='.$distributor_id);
						 $val=$mydb->getValue('invoice_eng_date','tbl_creditmemo','id='.$creditmemo_id);
				  	?>
					</td>
				  <td class="titleBarA" valign="top"><?php  echo $result1=$mydb->getValue('invoice_no','tbl_creditmemo','id='.$creditmemo_id);?></td>
				  <td align="center" class="titleBarA" valign="top"><?php  echo $result1=date('d-m-Y',strtotime($val));?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $medicine_name;?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['batch_number'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo date('d-m-Y',strtotime($rasMember5['expiry_date']));?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['quantity'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['rate'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['sp_per_tab'];?></td>
				  <td align="center" class="titleBarA" valign="top"><?php echo $rasMember5['deal'];?></td>
				  <td align="center" class="titleBarA" valign="top" style="color:green"><?php echo $rasMember5['stock'];?></td>
				  <td align="center" class="titleBarA" valign="top" style="color:green"><?php echo $rasMember5['sales'];?></td>
				  <?php $roudnum=$rasMember5['total_price']; ?>
				  <td align="center" class="titleBarA" valign="top"><?php echo round($roudnum,2);?></td>					 
				  <td class="titleBarA" valign="top" align="center">
				  	<input type="hidden" name="medicine_id[]" id="medicine_id[]" value="<?php echo $rasMember5['medicine_id'];?>">
				  	<select name="category[]" id="category[]">
				  		<option value="none">Select One</option>
				  		<option value="medicine" <?php if($rasMember5['category']=="medicine") echo "selected";?>>Medicine</option>
				  		<option value="surgical" <?php if($rasMember5['category']=="surgical") echo "selected";?>>Surgical</option>
				  		<option value="orthopedic" <?php if($rasMember5['category']=="orthopedic") echo "selected";?>>Orthopedic</option>
				  		<option value="baby_products" <?php if($rasMember5['category']=="baby_products") echo "selected";?>>Baby Products</option>
				  		<option value="cosmetic" <?php if($rasMember5['category']=="cosmetic") echo "selected";?>>Cosmetic</option>
				  		<option value="others" <?php if($rasMember5['category']=="others") echo "selected";?>>Others</option>
				  	</select>				  	
				  </td>
				</tr>
				<?php			
				}
			}
				?>
				<tr>
		<td colspan="14"></td>		
		<td align="center" class="TitleBarB"><input type="submit" name="btnUpdate" id="btnUpdate" value="Update"></td>				 
	</tr>
</table>
</form>