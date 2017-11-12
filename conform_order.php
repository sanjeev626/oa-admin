		

		<script type="text/javascript">
			 function callDelivery(gid)
			 {
				if(confirm('Are you sure medicine is delivered?'))
				{
					var ab=gid;
					alert(ab);
					window.location='?manager=order&uid='+gid;
				}
			 } 
		</script>
		<script type="text/javascript">
			 function callDelete(gid)
			 {
				if(confirm('Are you sure to cancel order ?'))
				{
					window.location='?manager=order&did='+gid;
				}
			 } 
		</script>






		<?php 

				if(isset($_GET['did']))
				{
					$delId = $_GET['did'];
					$mess = $mydb->deleteQuery('tbl_order','id='.$delId);
				}

				if(isset($_GET['uid']))
				{
					$updateId = $_GET['uid'];
					$data='';
					$data['status']=1;
					$mess = $mydb->updateQuery('tbl_order',$data,'id='.$updateId);
				}




				$result = $mydb->getQuery('*','tbl_order');
				$count = mysql_num_rows($result);
		?>	


			<form action="" method="post" name="tbl_conform_order">
			  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
			   
			    <tr class="TitleBar">
			      <td colspan="6" class="TtlBarHeading">Order Confirmation
			        <div style="float:right"></div></td>
			     
			    </tr>
			    <?php
				if($count != 0)
				{
				?>
			    <tr>
				  <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
				  <td class="titleBarB" align="center"><strong>Medicine Name</strong></td>
				  <td width="20%" class="titleBarB" align="center"><strong>Quantity</strong></td>
				  <td width="30%"  class="titleBarB" align="center"><strong>Option</strong></td>
				

				</tr>
				<?php
					$counter = 0;
					while($rasMember = $mydb->fetch_array($result))
					{
					$gid = $rasMember['id'];
					echo "abcd";
					?>
				<tr>
				  <td class="titleBarA" align="center" valign="top"> <input name="eid[]" type="hidden" id="eid[]" value="<?php echo $rasMember['id'];?>" /><?php echo ++$counter;?></td>
				  <td class="titleBarA" valign="top"><?php echo stripslashes(ucfirst($rasMember['medicine_name']));?></td>
				  <td class="titleBarA" align="center" valign="top"><?php echo ucfirst($rasMember['quantity']);?></td>

				  
				 <td class="titleBarA" align="center" valign="top"> 
				 <?php 	
				 	if($rasMember['status']==0)
				 	{	?>
				  <input type="submit" name="btndeliver" id="btndeliver" value="Delivered " class="button" OnClick="callDelivery('<?php echo $gid;?>')" />
				<input type="submit" name="btndelay" id="btncancel" value="Cancel " class="button" OnClick="callDelete('<?php echo $gid; ?>')" />

				 <?php	}else{
				 		echo "delivered";}
				 	?>
				
				</td>
				
				 	

				</tr>
				
			<?php
			}
			?>
				<tr>
				  
				  <td class="titleBarA" valign="top">&nbsp;</td>
				  <td class="titleBarA" valign="top">&nbsp;</td>
				  <td class="titleBarA" valign="top">&nbsp;</td>
				 
			    </tr>
			<?php
					}
					else
					{
					?>
			    <tr>

			      <td class="message" colspan="4">No medicines  has been queued for confirmation</td>
			   	</tr>
			    <?php
					}
					?>
			  </table>
			</form>