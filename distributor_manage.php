<script>
// function validateForm() {
//     var b = document.forms["distributorinsert"]["landline"].value;
//     var c = document.forms["distributorinsert"]["mobile"].value;    
//     var mobileno_check = /^\d{10}$/;  
//     var phoneno_check = /^\d{9}$/; 
//     var d = c.match(mobileno_check);
//     var e = b.match(phoneno_check);

//     if (b == null || b == "" ) {
//     	if (c == null || c == ""){
//         			alert("Both Landline or Mobile No cannot be empty");          			
//         			return false;  	
//     		}else{
//     				if(d == null)  
// 					  {  
// 					      alert("Not a valid Mobile Number");
// 					     return false;  
// 					  }  
// 					  else  
// 					  {       
// 					     return true;  
// 					  }      			
//     		}        
//     }else{
//     	if(e == null)  
// 					  {  
// 					      alert("Not a valid Phone/Mobile Number");  					     
// 					     return false;  
// 					  }  
// 					  else  
// 					  {       
// 					     return true;  
// 					  }
//     }	      
// }
</script>

<?php
if(isset($_GET['id']))
{
	$uid = $_GET['id'];
	$do = "Update";
}
else
{
	$uid = 0;
	$do = "Add";
}

if(isset($_POST['btnDo']) && $_POST['btnDo']=='Add')
{	
	$companylist=implode(",",$_POST['companylist']);
	$data='';
	foreach($_POST as $key=>$value)
	{
		if($key!="btnDo") 
		$data[$key]=$value;
	}
	$data['companylist']=$companylist;
	$uid = $mydb->insertQuery('tbl_distributor',$data);	

	if($uid>0)
	{
		$message = "New distributor Has been added.";
	}
	else
	{
		$message = "ERROR!! Failed to add distributor.";
	}
	
	$url = ADMINURLPATH."distributor_manage&message=".$message;
	$mydb->redirect($url);
}

if(isset($_POST['btnDo']) && $_POST['btnDo']=='Update')
{	
	$companylist=implode(",",$_POST['companylist']);

	$data='';
	foreach($_POST as $key=>$value)
	{
		if($key!="btnDo") 
		$data[$key]=$value;
	}
	$data['companylist']=$companylist;
	
	$message = $mydb->updateQuery('tbl_distributor',$data,'id='.$uid);
	
	$url = ADMINURLPATH."distributor_manage&id=".$uid."&message=".$message;
	$mydb->redirect($url);
}
$rasdistributor = $mydb->getArray('*','tbl_distributor','id='.$uid);

?>


<form action="" method="post" name="distributorinsert" onsubmit="return validateForm()">
  <table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl">
	<tr class="TitleBar">
      <td class="TtlBarHeading" colspan="2"><?php echo $do;?> Distributor</td>
    </tr>		
	<?php if(isset($_GET['message'])){?>
	<tr>
	  <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
	</tr>
	<?php } ?>    
	<tr>
	  <td width="17%" align="right" class="TitleBarA"><strong>Full name : </strong></td>
	  <td class="TitleBarA"><input name="fullname" id="fullname" type="text" value="<?php echo $rasdistributor['fullname'];?>" class="inputBox" style="width:50%" required/></td>
	</tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>PAN NO:</strong></td>
	  <td class="TitleBarA"><input name="pan_number" id="pan_number" type="text" value="<?php echo $rasdistributor['pan_number'];?>" class="inputBox" style="width:50%" required/></td>
    </tr>
    <tr>
	  <td align="right" class="TitleBarA"><strong>DAA REGD :</strong></td>
	  <td class="TitleBarA"><input name="dda_regd" id="dda_regd" type="text" value="<?php echo $rasdistributor['dda_regd'];?>" class="inputBox" style="width:50%" required/></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Address :</strong></td>
	  <td class="TitleBarA"><input name="address" id="address" type="text" value="<?php echo $rasdistributor['address'];?>" class="inputBox" style="width:50%" placeholder="Eg Ombahal, Kathmandu, Nepal" required/></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Landline: </strong></td>
	  <td class="TitleBarA"><input name="landline" id="landline" type="text" value="<?php if(isset($rasdistributor['landline'])){echo $rasdistributor['landline'];}?>" placeholder="01XXXXXXX"class="inputBox" style="width:50%"/><span><?php echo $message1;?></span></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Mobile: </strong></td>
	  <td class="TitleBarA"><input name="mobile" id="mobile" type="text" value="<?php if(isset($rasdistributor['mobile'])){echo $rasdistributor['mobile'];}?>" placeholder="98XXXXXXXX" class="inputBox" style="width:50%"/></td>
    </tr>
    <tr>
	  <td align="right" class="TitleBarA"><strong>Company List</strong></td>
	  <td class="TitleBarA">
	  	<?php 
	  		
	  		$companylist1=$mydb->getValue('companylist','tbl_distributor','id='.$uid);
	  		
	  		$comlist=explode(',',$companylist1);
	  		
	  		$companylist=$mydb->getQuery('*','tbl_company','parent_id=0');
	  		while($list=$mydb->fetch_array($companylist))
	  		{
	  					 $compid=$list['id'];

	  					 if(in_array($compid,$comlist))
	  					 {

	  			?>
	  				 		<input type='checkbox' name='companylist[]' value='<?php echo $compid;?>'checked><?php echo $list['fullname'];?><br>
	  	<?php	
	  					}else
	  					{?>
	  					 	<input type='checkbox' name='companylist[]' value='<?php echo $compid;?>'><?php echo $list['fullname'];?><br>				
	  <?php }
		}	
	  	?>
	  </td>
    </tr>
    		
	<tr>
	  <td align="right" class="TitleBarA">&nbsp;</td>
	  <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnDo" type="submit" value="<?php echo $do;?>" class="button" /></td>
	</tr>
  </table>
</form>