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
		if($key!="btnDo") 
		$data[$key]=$value;
	}

	$uid = $mydb->insertQuery('tbl_company',$data);	

	if($uid>0)
	{
		$message = "New company Has been added.";
	}
	else
	{
		$message = "ERROR!! Failed to add company.";
	}
	
	$url = ADMINURLPATH."company_manage&message=".$message;
	$mydb->redirect($url);
}

if(isset($_POST['btnDo']) && $_POST['btnDo']=='Update')
{	
	$data='';
	foreach($_POST as $key=>$value)
	{
		if($key!="btnDo") 
		$data[$key]=$value;
	}
    
	$message = $mydb->updateQuery('tbl_company',$data,'id='.$uid);
	
	$url = ADMINURLPATH."company_manage&id=".$uid."&message=".$message;
	$mydb->redirect($url);
}
$rascompany = $mydb->getArray('*','tbl_company','id='.$uid);
$rascompany5 = $mydb->getArray('*','tbl_company','id='.$uid);
?>


<form action="" method="post" name="companyinsert" onSubmit="return call_validate(this,0,this.length);">
  <table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl">
	<tr class="TitleBar">
      <td class="TtlBarHeading" colspan="2"><?php echo $do;?> company</td>
    </tr>		
	<?php if(isset($_GET['message'])){?>
	<tr>
	  <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
	</tr>
	<?php } ?>    
	<tr>
	  <td width="17%" align="right" class="TitleBarA"><strong>Parent Company:</strong></td>
	  <td>
	  <Select name="parent_id" id="parent_id" class="inputBox"> 
   			<option value = 0>Main Division</option>	
   			<?php 
   				$parentvalue=$mydb->getValue('parent_id','tbl_company','id='.$uid);
				$result = $mydb->getQuery('*','tbl_company','parent_id=0');
				while($rascompany5 = $mydb->fetch_array($result))
				{
					
					$sub_company_id = $rascompany5['id'];
					$parent_name = $rascompany5['fullname']; 
				
				?>	
				<option value ='<?php echo $sub_company_id;?>'<?php if($parentvalue==$sub_company_id) echo "selected";?>><?php echo $parent_name;?></option>								
				<?php 
					}
				?>	
		</select>
		</td>
	  </tr>
	<tr>
	  <td width="17%" align="right" class="TitleBarA"><strong>Full name : </strong></td>
	  <td class="TitleBarA"><input name="fullname" id="fullname" type="text" value="<?php echo $rascompany['fullname'];?>" class="inputBox" style="width:50%"/></td>
	</tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Address :</strong></td>
	  <td class="TitleBarA"><input name="address" id="address" type="text" value="<?php echo $rascompany['address'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Landline: </strong></td>
	  <td class="TitleBarA"><input name="landline" id="landline" type="text" value="<?php echo $rascompany['landline'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Mobile : </strong></td>
	  <td class="TitleBarA"><input name="mobile" id="mobile" type="text" value="<?php echo $rascompany['mobile'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
    
	<tr>
	  <td align="right" class="TitleBarA">&nbsp;</td>
	  <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnDo" type="submit" value="<?php echo $do;?>" class="button" /></td>
	</tr>
  </table>
</form>