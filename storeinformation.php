<script type="text/javascript"
        src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css"
        href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
<script type="text/javascript">
   $(document).ready(function(){
        $("#medsearch").autocomplete({
            source:'medicine_store.php',
            minLength:1,
          
            select:function(e,ui) {
            		
            	location.href = ui.item.the_link;
            	
            }
            
        });
    });
</script>
<!--==================== for search of distributor and company when medicine is provided ============================= -->
<?php if(isset($_GET['medicinelist']))
 {
 	$medicinename = '';
		$id=$_GET['medicinelist'];//1
		$distributorlist=$mydb->getArray('medicine_name','tbl_medicine','id='.$id);//get medicine name
		$companyid=$mydb->getValue('company_id','tbl_medicine','id='.$id);//4
		$companyinfo=$mydb->getArray('fullname,parent_id,id','tbl_company','id='.$companyid);
	 	/*=============================To Get company name as well as division==========================================*/
		if($companyinfo['parent_id']!=0)
		{
			$maincompany=$mydb->getValue('fullname','tbl_company','id='.$companyinfo['parent_id']);
		}
  		$comid= $companyname['id'];
		/*==============================================================================================================*/
		/*==================================To check distributor list====================================================*/
		$companyarray=$mydb->getQuery('*','tbl_distributor');
		$companyarray2=array();
		$data=array();
		$j = 0;
		while($companyarray1=$mydb->fetch_array($companyarray))
		{
			$companyarray2=explode(',',$companyarray1['companylist']);
			if(in_array($companyid,$companyarray2))
			{

				$companyinfo1=$mydb->getValue('id','tbl_distributor','companylist="'.$companyarray1['companylist'].'"');
				$data[$j] = $companyinfo1;
				$j++;
			}
			
			$companyarray2='';

		}
		
		/*==============================================================================================================*/	
	?>
	<form action="" method="post" name="tbl_medicine">
	  	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
	    <?php if(isset($_GET['message'])){?>
		<tr>
		  <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
		</tr>
		<?php } ?>
	    <tr class="TitleBar">
	      <td colspan="3" class="TtlBarHeading">medicine
	        <div style="float:right"></div></td>
	        <td colspan="2" class="TtlBarHeading" style="width:50px;"><input name="search" type="text" id="medsearch" Placeholder="Search for medicine" class="button"  style="width:250px;"/></td>
	      <td class="TtlBarHeading" style="width:70px;"><input name="btnAdd" type="button" value="Add" class="button" onClick="window.location='<?php echo ADMINURLPATH;?>medicine_manage'" /></td>
	    </tr>
		</table>
	</form>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
		<tr>
	     	<td width="15%" align="right" class="titleBarB" style="font-size:14px"><strong>Medicine Name : </strong></td>
	     	<td class="titleBarA" valign="top" colspan="3"><?php echo $distributorlist['medicine_name'];?> | <a href="<?php echo ADMINURLPATH;?>medicine_manage&id=<?php echo $id;?>">Edit</a> </td>
	    </tr>
	    <tr>
	     	<td width="15%" align="right" class="titleBarB" style="font-size:14px"><strong>Company : </strong></td>
	     	<td class="titleBarA" valign="top" colspan="3"><?php echo $companyinfo['fullname'];if(isset($maincompany)) echo"/".$maincompany;?></td>
	    </tr>
	</table>    
	<br>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">		
		<tr class="TitleBar">
			<td colspan="4" class="TtlBarHeading">Distributor List</td>
		</tr>
		<tr>
			<td width="15%" align="center" class="titleBarB"><strong>Distributor</strong></td>	  
			<td width="15%" align="center" class="titleBarB"><strong>Address</strong></td>	
			<td width="15%" align="center" class="titleBarB"><strong>Landline</strong></td>	
			<td width="15%" align="center" class="titleBarB"><strong>Mobile</strong></td>				 
		</tr>
 
			<?php
			$medicinename = $distributorlist['medicine_name'];
			//for($i=0;$i<count($data);$i++)
				{	
			
		       	 $result = $mydb->getQuery('td.fullname,td.address,td.landline,td.mobile','tbl_stock ts INNER JOIN tbl_creditmemo tc ON tc.id=ts.creditmemo_id INNER JOIN tbl_distributor td ON td.id=tc.distributor_id','ts.medicine_id="'.$id.'" GROUP BY td.id');

			 	while($rasMember5 = $mydb->fetch_array($result))
					{							
						$distributorname=$rasMember5['fullname'];
						$address=$rasMember5['address'];
						$lan_contact=$rasMember5['landline'];
						$mob_contact=$rasMember5['mobile'];
					?>
			
					<tr>
					  
					  <td align="center" class="titleBarA" valign="top"><?php echo $distributorname;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $address;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $lan_contact;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $mob_contact;?></td>
					 
					  </tr>
					<?php			
					}
				}
				?>
</table>
	 <!--=================================Medicine user list===========================================-->
	 <br>
	 <?php
//	 $result45=$mydb->getQuery('cl.fullname,ord.quantity','tbl_medicine med, tbl_order ord, tbl_sales sale,
//	 users cl', 'ord.medicine_name = "'.$medicinename.'"  and sale.id=ord.sales_id and sale.client_id = cl.id');

	$formatMedicine = addslashes($medicinename);
	$reMedicine = preg_replace('/[^A-Za-z0-9]/',"", $medicinename);
	
	$medicine_id=$_GET['medicinelist'];
	
	$result45=$mydb->getQuery('cl.name,ord.quantity,ord.sales_id,sale.date','tbl_medicine med, tbl_order ord,
	tbl_sales sale,users cl', "(ord.medicine_id= '".$medicine_id."') and sale.id=ord.sales_id and sale.client_id = cl.id group by ord.sales_id ORDER BY sale.date DESC");

//	 $rasMember45 = $mydb->fetch_array($result45);
//	echo $result45;
//	 print_r($rasMember45);?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
		<tr class="TitleBar">
			<td colspan="3" class="TtlBarHeading">Purchase History</td>
		</tr>
		<tr>
			<td width="15%" align="center" class="titleBarB">Clients</td>
			<td width="15%" align="center" class="titleBarB">Quantity</td>
			<td width="15%" align="center" class="titleBarB"><strong>Date</strong></td>
		</tr>
		<?php
			$total_sales = 0;
			while($rasMember45 = $mydb->fetch_array($result45))
			{
				$total_sales = $total_sales+$rasMember45['quantity'];
				echo '<tr>
						<td  align="center" class="titleBarA" valign="top">'.$rasMember45['name'].'</td>
						<td  align="center" class="titleBarA" valign="top">'.$rasMember45['quantity'].'</td>
						<td  align="center" class="titleBarA" valign="top">'.$rasMember45['date'].'</td>
					</tr>';
			}
		?>
		<tr>
			<td  align="center" class="titleBarB" valign="top">&nbsp;</td>
			<td  align="center" class="titleBarB" valign="top"><strong><?php echo $total_sales;?></strong></td>
			<td  align="center" class="titleBarB" valign="top">&nbsp;</td>
		</tr>
	</table>
	 <!--=================================Medicine user list===========================================-->
<?php }	 ?>


<!-- ========================================================================================================================== -->

<!-- =================================for search of company when distributor is provided======================================= -->

<?php 
if(isset($_GET['distributorlist']))
 {
		$id=$_GET['distributorlist'];
        $distributorlist= $mydb->getArray('companylist,fullname,address,landline','tbl_distributor','id='.$id);
      ?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
	     	<tr>
		     	<td  width="15%" align="center" class="titleBarB" style="font-size:14px"><strong>Distributor</strong></td>
		     	<td align="center" class="titleBarA" valign="top" colspan="3"><?php echo $distributorlist['fullname'];?></td>
		    </tr>
		    <tr>
		     	<td  width="15%" align="center" class="titleBarB" style="font-size:14px"><strong>Address</strong></td>
		     	<td align="center" class="titleBarA" valign="top" colspan="3"><?php echo $distributorlist['address'];?></td>
		    </tr>
		     <tr>
		     	<td  width="15%" align="center" class="titleBarB" style="font-size:14px"><strong>Contact</strong></td>
		     	<td align="center" class="titleBarA" valign="top" colspan="3"><?php echo $distributorlist['landline'];?></td>
		    </tr>
			<tr>
				  <td align="center" width="15%" valign="top" class="titleBarB"><strong>Company</strong></td>	 
				  <td align="center" width="7%" class="titleBarB"><strong>Address</strong></td>	
				  <td align="center" width="9%" class="titleBarB"><strong>Landline</strong></td>
				  <td align="center" width="10%" class="titleBarB"><strong>Mobile</strong></td>
				  
			</tr>	
	<?php
		$dislist=explode(',',$distributorlist['companylist']);
		for($i=0;$i<count($dislist);$i++)
		{	
	
       	 $result = $mydb->getQuery('*','tbl_company','id="'.$dislist[$i].'"');


		 while($rasMember5 = mysql_fetch_array($result))
						{		

							$companyname=$rasMember5['fullname'];
							$address=$rasMember5['address'];
							$lan_contact=$rasMember5['landline'];
							$mob_contact=$rasMember5['mobile'];
					?>
			
					<tr>
					  <td align="center" class="titleBarA" valign="top"><?php echo $companyname;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $address;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $lan_contact;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $mob_contact;?></td>
					</tr>
					<?php			
					}
				}
		}
					?>
</table>    

<!-- ================================================================================================================================ -->

<!-- =================================for search of distributor when company  is provided======================================= -->

<?php 
if(isset($_GET['companylist']))
{
		$companyid=$_GET['companylist'];
		$companyinfo=$mydb->getArray('fullname,parent_id,id','tbl_company','id='.$companyid);
        $companyarray=$mydb->getQuery('*','tbl_distributor');
        if($companyinfo['parent_id']!=0)
		{
			$maincompany=$mydb->getValue('fullname','tbl_company','id='.$companyinfo['parent_id']);
		}
		$companyarray2=array();
		$data=array();
		$j = 0;
		while($companyarray1=$mydb->fetch_array($companyarray))
		{
			$companyarray2=explode(',',$companyarray1['companylist']);
			if(in_array($companyid,$companyarray2))
			{

				$companyinfo1=$mydb->getValue('id','tbl_distributor','companylist="'.$companyarray1['companylist'].'"');
				$data[$j] = $companyinfo1;
				$j++;
			}
			
			$companyarray2='';

		}
 
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
		
	    <tr>
	     	<td  width="15%" align="center" class="titleBarB" style="font-size:14px"><strong>Company</strong></td>
	     	<td align="center" class="titleBarA" valign="top" colspan="3"><?php echo $companyinfo['fullname'];if(isset($maincompany)) echo"/".$maincompany;?></td>
	    </tr>
		<tr>
			<td width="15%" align="center" class="titleBarB"><strong>Distributor</strong></td>	  
			<td width="15%" align="center" class="titleBarB"><strong>Address</strong></td>	
			<td width="15%" align="center" class="titleBarB"><strong>Landline</strong></td>	
			<td width="15%" align="center" class="titleBarB"><strong>Mobile</strong></td>	
				 
		</tr>
 
			<?php
				
			for($i=0;$i<count($data);$i++)
				{	
			
		       	 $result = $mydb->getQuery('*','tbl_distributor','id="'.$data[$i].'"');

			 	while($rasMember5 = $mydb->fetch_array($result))
						{
							
							$distributorname=$rasMember5['fullname'];
							$address=$rasMember5['address'];
							$lan_contact=$rasMember5['landline'];
							$mob_contact=$rasMember5['mobile'];
							
							
					?>
			
					<tr>
					  
					  <td align="center" class="titleBarA" valign="top"><?php echo $distributorname;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $address;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $lan_contact;?></td>
					  <td align="center" class="titleBarA" valign="top"><?php echo $mob_contact;?></td>
					 
					  </tr>
					<?php			
					}
				}
			}		
			?>
</table>