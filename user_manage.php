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
	$data='';
	foreach($_POST as $key=>$value)
	{
	if($key!="btnDo" && $key!="password" && $key!="confirm_password" && $key!="checkbox") 
	$data[$key]=$value;
	}        
	$data['password'] = md5($_POST['password']);
	$hash = md5(rand(11,1000));        
	//$data['hash']=$hash;
	$data['active']=1;
	$uid = $mydb->insertQuery('users',$data);
	$data1=''; 
	if($uid>0)
	{
		$message = "New User Has been added.";
	}
	else
	{
		$message = "ERROR!! Failed to add User.";
	}
	
	$url = ADMINURLPATH."user_manage&message=".$message;
	$mydb->redirect($url);
}


if(isset($_POST['btnDo']) && $_POST['btnDo']=='Update')
{	
	
	$data='';
	foreach($_POST as $key=>$value)
	{
		 if($key!="btnDo" && $key!="checkbox") 
			    $data[$key]=$value;		
	}
	$message = $mydb->updateQuery('users',$data,'id='.$uid);	
	$url = ADMINURLPATH."user_manage&id=".$uid."&message=".$message;
	$mydb->redirect($url);
}
$rasUser = $mydb->getArray('*','users','id='.$uid);
?>

<html>
	<head>	
	 <link rel="stylesheet" href="jsvalidation/css/validationEngine.jquery.css" type="text/css"/>		
	   <script src="jsvalidation/jquery-1.8.2.min.js" type="text/javascript">
      </script>
      <script src="jsvalidation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
      </script>
      <script src="jsvalidation/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
      </script>
	</head>
	<body>
<script>
  jQuery(document).ready(function(){
      // binds form submission and fields to the validation engine
      jQuery("#MemberInsert").validationEngine();
    });
</script>    
	<form action="" id="MemberInsert" method="post" enctype="multipart/form-data" name="MemberInsert" >
	  <table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl">
		<tr class="TitleBar">
	      <td class="TtlBarHeading" colspan="2"><?php echo $do;?> Member</td>
	    </tr>		
		<?php if(isset($_GET['message'])){?>
		<tr>
		  <td colspan="2" class="message"><?php echo $_GET['message'];?></td>
		</tr>
		<?php } ?>    
		
		<tr>
		  <td align="right" class="TitleBarB"><strong>Full name : </strong></td>
		  <td class="TitleBarA"><input name="name" id="name" type="text" value="<?php echo $rasUser['name'];?>" class="inputBox validate[required] text-input" style="width:50%" /></td>
		</tr>

		<tr>
		  <td align="right" class="TitleBarA"><strong>Email</strong></td>
		  <td class="TitleBarA"><input name="email" id="email" type="email" value="<?php echo $rasUser['email'];?>" class="inputBox validate[required,custom[email]]text-input" style="width:50%"/></td>
	    </tr>
	   <?php if(isset($uid)&&($uid==0))
	   	{
	   ?>
		<tr>
		  <td align="right" class="TitleBarA"><strong>Password</strong></td>
		  <td class="TitleBarA"><input name="password" id="password" type="password" value="12345" readonly class="inputBox validate[required] text-input" style="width:50%"/></td>
	    </tr>
		<tr>
		  <td align="right" class="TitleBarA"><strong>Re-enter Password</strong></td>
		  <td class="TitleBarA"><input name="confirm_password" id="confirm_password" type="password"  readonly value="12345" class="inputBox validate[required,equals[password]] text-input" style="width:50%"/></td>
	    </tr>
		<?php } ?>
		<tr>
		  <td align="right" class="TitleBarA"><strong>Address</strong></td>
		  <td class="TitleBarA"><input name="address" id="address" type="text" value="<?php echo $rasUser['address'];?>" class="inputBox validate[optional] text-input" style="width:50%"/></td>
	    </tr>
	    <tr>
		  <td align="right" class="TitleBarA"><strong>Phone Number</strong></td>
		  <td class="TitleBarA"><input name="phone" id="phone" value="<?php echo $rasUser['phone'];?>" type="text" class="inputBox validate[optional,custom[integer],minSize[7]] text-input" style="width:50%"/></td>
		</tr>
	    <tr>
		  <td align="right" class="TitleBarA"><strong>Additional Number</strong></td>
		  <td class="TitleBarA"><input name="additional_phone" id="additional_phone" value="<?php echo $rasUser['additional_phone'];?>" type="text" class="inputBox validate[optional,custom[integer],minSize[7]] text-input" style="width:50%"/></td>
		</tr>
	    <tr>
		  <td align="right" class="TitleBarA"><strong>Discount Percentage</strong></td>
		  <td class="TitleBarA"><input name="discount_percentage" id="discount_per" value="<?php echo $rasUser['discount_percentage']; ?>" type="text"  class="inputBox" style="width:50%"/></td>
	    </tr>
	    <tr>
		  <td align="right" class="TitleBarB"><strong>Reference </strong></td>
		  <td class="TitleBarA"><input name="reference" id="reference" type="text" value="<?php echo $rasUser['reference'];?>" class="inputBox validate[required]text-input" style="width:50%" />
		  	<span style="font-size: 12px"> Facebook Ad/Google Search/Friends/Relatives/Others</span>
		  </td>
		</tr>
		  <tr>
		  <td align="right" class="TitleBarB"><strong>User Type </strong></td>
		  <td class="TitleBarA">
			  <select name="user_type" id="user_type" class="inputBox validate">

				  <option value="regular_user" <?php if($rasUser['user_type']=='regular_user') echo "selected";?>>Regular Users</option>
				  <option value="one_time_user" <?php if($rasUser['user_type']=='one_time_user') echo "selected";?>>One Time Users</option>
				  <option value="registered_only" <?php if($rasUser['user_type']=='registered_only') echo "selected";?>>Registered Only</option>
			  </select>
		  </td>
		</tr>
		<tr>
		  <td align="right" class="TitleBarB"><strong>Is Active </strong></td>
		  <td class="TitleBarA">
			  <select name="active" id="active" class="inputBox validate">
				  <option value="1" <?php if($rasUser['active']=='1') echo "selected";?>>Active</option>
				  <option value="0" <?php if($rasUser['active']=='0') echo "selected";?>>Not Active</option>
			  </select>
		  </td>
		</tr>
		<tr>
		  <td align="right" class="TitleBarA">&nbsp;</td>
		  <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnDo" type="submit" value="<?php echo $do;?>" class="button" /></td>
		</tr>

	  </table>
	</form>
	</body>
</html>