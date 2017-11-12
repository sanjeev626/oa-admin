<?php
if(isset($_POST['btnMerge']))
{
	//These tables must be altered
  //tbl_order -> medicine_id
  //tbl_orderreview -> medicine_id
  //tbl_stock -> medicine_id
  //tbl_temporder -> medicine_id
  $correct_medicine = $_POST['correct_medicine'];
	$wrong_medicine = $_POST['wrong_medicine'];
  $correct_medicine_id = $mydb->getValue('id','tbl_medicine','medicine_name="'.$correct_medicine.'"');
  $wrong_medicine_id = $mydb->getValue('id','tbl_medicine','medicine_name="'.$wrong_medicine.'"');

  $data='';
  $data['medicine_id'] = $correct_medicine_id;

  $data3='';
  $data3['medicine_id'] = $correct_medicine_id;
  $data3['medicine_name'] =  $correct_medicine;
  $mydb->updateQuery('tbl_order',$data3,'medicine_id='.$wrong_medicine_id);
  $mydb->updateQuery('tbl_orderreview',$data3,'medicine_id='.$wrong_medicine_id);
  $mydb->updateQuery('tbl_temporder',$data3,'medicine_id='.$wrong_medicine_id);

  $data2='';
  $data2['medicine_id'] = $correct_medicine_id;
  $data2['item_description'] = $correct_medicine;
  $mydb->updateQuery('tbl_stock',$data2,'medicine_id='.$wrong_medicine_id);

  $mydb->deleteQuery('tbl_medicine','id='.$wrong_medicine_id);

  //echo "correct_medicine_id = ".$correct_medicine_id;
  //echo "wrong_medicine_id = ".$wrong_medicine_id;
}
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
<script type="text/javascript">
   $(document).ready(function(){
		
	$("#correct_medicine").autocomplete({
		source:'medicine_store.php',
		minLength:1,	  
		select:function(e,ui) {				
			//location.href = ui.item.the_link;			
		}		
	});
	
	$("#wrong_medicine").autocomplete({
		source:'medicine_store.php',
		minLength:1,	  
		select:function(e,ui) {				
			//location.href = ui.item.the_link;			
		}		
	});
});
</script>

<form action="" method="post" name="tbl_medicine">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <?php if(isset($_GET['message'])){?>
    <tr>
      <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
    </tr>
    <?php } ?>
    <tr class="TitleBar">
      <td colspan="3" class="TtlBarHeading">Merge Medicine Name</td>
    </tr>
    <tr>
      <td width="2%" valign="top" class="titleBarB" align="center">&nbsp;</td>
      <td width="20%" class="titleBarB"><strong>Correct Medicine Name</strong></td>
      <td class="titleBarB"><input name="correct_medicine" type="text" id="correct_medicine" Placeholder="correct medicine name" class="inputBox"/></td>
    </tr>
    <tr>
      <td valign="top" class="titleBarB" align="center">&nbsp;</td>
      <td class="titleBarB"><strong>Wrong Medicine Name</strong></td>
      <td class="titleBarB"><input name="wrong_medicine" type="text" id="wrong_medicine" Placeholder="wrong medicine name" class="inputBox" /></td>
    </tr>
    <tr>
      <td class="titleBarB">&nbsp;</td>
      <td class="titleBarB">&nbsp;</td>
      <td class="titleBarB"><input name="btnMerge" id="btnMerge" type="submit" value="Merge" class="button" /></td>
    </tr>
  </table>
</form>
