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
		if($key!="btnDo"&&$key!="medicine_description")
		$data[$key]=$mydb->CleanString($value);
	}
	$data['slug']=$mydb->get_slug($_POST['medicine_name']);
	$data['medicine_description']=preg_replace('/[^A-Za-z0-9]/',"",$mydb->CleanString($_POST['medicine_name']) );
	$uid = $mydb->insertQuery('tbl_medicine',$data);	

	if($uid>0)
	{
		$message = "New medicine Has been added.";
	}
	else
	{
		$message = "ERROR!! Failed to add medicine.";
	}	
	$url = ADMINURLPATH."medicine_manage&message=".$message;
	$mydb->redirect($url);
}

if(isset($_POST['btnDo']) && $_POST['btnDo']=='Update')
{
	foreach($_POST as $key=>$value)
	{
		if($key!="btnDo") 
		$data[$key]=$mydb->CleanString($value);
	}	
	$data['slug']=$mydb->get_slug($_POST['medicine_name']);
	$message = $mydb->updateQuery('tbl_medicine',$data,'id='.$uid);	
	$url = ADMINURLPATH."medicine_manage&id=".$uid."&message=".$message;
	$mydb->redirect($url);
}
$rasmedicine = $mydb->getArray('*','tbl_medicine','id='.$uid);

?>
<form action="" method="post" name="medicineinsert" onSubmit="return call_validate(this,0,this.length);">
  <table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl">
	<tr class="TitleBar">
      <td class="TtlBarHeading" colspan="2"><?php echo $do;?> medicine</td>
    </tr>		
	<?php if(isset($_GET['message'])){?>
	<tr>
	  <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
	</tr>
	<?php } ?>   
	<tr>
	  <td align="right" class="TitleBarA"><strong>Company ID : </strong></td>
	  <td class="TitleBarA">
	  	<Select name="company_id" id="company_id" class="inputBox"> 
   			<option value="0">Parent</option>
   			<?php 
   				$companyid=$mydb->getValue('company_id','tbl_medicine','id='.$uid);
   				//$username=$mydb->getValue('fullname','tbl_company','id='.$companyid);
				$result5 = $mydb->getQuery('*','tbl_company','parent_id=0  ORDER BY fullname');
				while($rasmedicine5 = $mydb->fetch_array($result5)){
				$company_id = $rasmedicine5['id'];
				$company_name = $rasmedicine5['fullname']; 
				?>	
				<option value ='<?php echo "$company_id"; ?>'<?php if($companyid==$rasmedicine5['id']) echo "selected";?>><?php echo $company_name; ?></option>	
				<?php

					$result_sub = $mydb->getQuery('*','tbl_company','parent_id='.$company_id);
					while($rasSub = $mydb->fetch_array($result_sub))
					{

					?>	
						<option value='<?php echo $rasSub['id']; ?>'<?php if($companyid==$rasSub['id']) echo "selected";?>>&nbsp;&nbsp;<?php  echo $rasSub['fullname']; ?></option>						
					<?php 
					}
				}
					?>												
		</select>
	  </tr> 
	<tr>
	  <td width="17%" align="right" class="TitleBarA"><strong>Medicine Name : </strong></td>
	  <td class="TitleBarA"><input name="medicine_name" id="medicine_name" type="text" value="<?php echo $rasmedicine['medicine_name'];?>" class="inputBox" style="width:50%"/></td>
	</tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Composition :</strong></td>
	  <td class="TitleBarA"><input name="composition" id="composition" type="text" value="<?php echo $rasmedicine['composition'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Indications : </strong></td>
	  <td class="TitleBarA"><input name="indications" id="indications" type="text" value="<?php echo $rasmedicine['indications'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Side Effects : </strong></td>
	  <td class="TitleBarA"><input name="side_effects" id="side_effects" type="text" value="<?php echo $rasmedicine['side_effects'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
    <tr>
	  <td align="right" class="TitleBarA"><strong>Form : </strong></td>
	  <td class="TitleBarA">	  	
	  	<select name="form" id="form" class="inputBox"> 
   			<option value="0">Select One</option>
   			<?php 
			$form_arr = array('BODY SPRAY','CANDY','Capsule','CREAM','DIAPER','DROP','Dry drop','Dry Syrup','EMULSION','EQUIPMENT','EXPECTORANT','Eye Drop','Eye/Ear Drop','FACE WASH','GARGLE','GEL','GLOVES','Injection','LOTION','MACHINERY','MOUTH WASH','MOUTHWASH','NASAL SPRAY');		
			for($i=0;$i<count($form_arr);$i++)
			{
				$form = strtoupper($form_arr[$i]);
			?>	
			<option value ='<?php echo $form; ?>' <?php if($form==$rasmedicine['form']) echo "selected";?>><?php echo $form; ?></option>	
			<?php
			}
			?>												
		</select>
	  </td>
    </tr>
    <tr>
	  <td align="right" class="TitleBarA"><strong>Packing : </strong></td>
	  <td class="TitleBarA"><input name="packing" id="packing" type="text" value="<?php echo $rasmedicine['packing'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
	<tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Minimum Stock : </strong></td>
	  <td class="TitleBarA"><input name="min_stock" id="min_stock" type="text" value="<?php echo $rasmedicine['min_stock'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
    <tr>
	  <td align="right" class="TitleBarA"><strong>Cost Price : </strong></td>
	  <td class="TitleBarA"><input name="cost_per_piece" id="cost_per_piece" type="text" value="<?php echo $rasmedicine['cost_per_piece'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
    <tr>
	  <td align="right" class="TitleBarA"><strong>Selling Price : </strong></td>
	  <td class="TitleBarA"><input name="sp_per_piece" id="sp_per_piece" type="text" value="<?php echo $rasmedicine['sp_per_piece'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Category : </strong></td>
	  <td class="TitleBarA">
	  	<select name="category" id="category" class="inputBox"> 
   			<option value="0">Select One</option>
   			<?php 
			$category_arr = array('medicine','surgical','orthopedic','cosmetic','baby_products','machinery_and_equipment','others');				
			for($i=0;$i<count($category_arr);$i++)
			{
				$category = ucfirst(str_replace('_',' ',$category_arr[$i]));
			?>	
			<option value ='<?php echo $category_arr[$i]; ?>' <?php if($category_arr[$i]==$rasmedicine['category']) echo "selected";?>><?php echo $category; ?></option>	
			<?php
			}
			?>												
		</select>
	  </tr> 
	<tr>
	<tr>
	  <td align="right" class="TitleBarA"><strong>Admin Remarks : </strong></td>
	  <td class="TitleBarA"><input name="admin_remarks" id="admin_remarks" type="text" value="<?php echo $rasmedicine['admin_remarks'];?>" class="inputBox" style="width:100%"/></td>
    </tr>
	<tr>	
    <tr>
	  <td align="right" class="TitleBarA">&nbsp;</td>
	  <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnDo" type="submit" value="<?php echo $do;?>" class="button" /></td>
	</tr>
  </table>
</form>