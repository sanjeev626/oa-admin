<?php
error_reporting(E_ALL);
if(isset($_POST['btnUpdate']))
{
	$count = count($_POST['mid']);
	for($i=0;$i<$count;$i++)
	{
		$mid = $_POST['mid'][$i];
		$data='';        
        $data['medicine_name'] = $_POST['medicine_name'][$i];  
        $data['form'] = $_POST['form'][$i]; 
		$data['composition'] = $_POST['composition'][$i];        
        $data['category'] = $_POST['category'][$i];
        $mydb->updateQuery('tbl_medicine',$data,'id='.$mid);
	}
}


$num_rec_per_page=50;
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$start_from = ($page-1) * $num_rec_per_page;
$sql = "SELECT tm.id,tm.medicine_name,tm.composition,tm.category,tm.form FROM tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id WHERE tm.composition = '' AND tm.category!='surgical' AND tm.category!='orthopedic' AND tm.category!='baby_products' GROUP BY ts.medicine_id ORDER BY medicine_name limit ".$start_from.",".$num_rec_per_page;
//echo $sql;
//echo $mydb->getQuery('tm.id,tm.composition,tm.indications,tm.side_effects','tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id','tm.composition !='' OR tm.indications!='' OR tm.side_effects!='' GROUP BY ts.medicine_id ORDER BY medicine_name  limit '.$start_from.','.$num_rec_per_page,'1');

//$result = $mydb->getQuery('tm.id,tm.composition,tm.indications,tm.side_effects','tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id','1=1 GROUP BY ts.medicine_id ORDER BY medicine_name  limit '.$start_from.','.$num_rec_per_page.'');
$result = $mydb->query($sql);

$no_of_records = mysql_num_rows($result);
?>

<script type="text/javascript"
        src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css"
        href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />

<script type="text/javascript">
   $(document).ready(function(){
        $(".composition").autocomplete({
            source:'composition_list_ajax.php',
            minLength:1,          
            select:function(e,ui) {            		
            	/*location.href = ui.item.the_link;*/            	
            }            
        });

        $(".category").autocomplete({
            source:'category_list_ajax.php',
            minLength:1,          
            select:function(e,ui) {                 
                /*location.href = ui.item.the_link;*/               
            }            
        });

        $(".form").autocomplete({
            source:'form_list_ajax.php',
            minLength:1,          
            select:function(e,ui) {                 
                /*location.href = ui.item.the_link;*/               
            }            
        });
    });
</script>

  <div style="background:#fff url(images/containerbg.jpg) repeat-x left top;">
    
        <div class="adminContent">

          <div class="adminContentinner">
<form action="" method="post" name="tbl_medicine">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
    <?php if(isset($_GET['message'])){?>
	<tr>
	  <td colspan="5" class="message"><?php echo $_GET['message']; ?></td>
	</tr>
	<?php } ?>
    <tr class="TitleBar">
      <td colspan="5" class="TtlBarHeading">Update Medicine Details</td>
    </tr>
    <?php
	if($no_of_records != 0)
	{
	?>
    <tr>
	  <!-- <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td> -->
	  <td class="titleBarB"><strong>Medicine Name</strong></td>
	  <td width="25%" class="titleBarB"><strong>Composition</strong></td>
      <td width="20%" class="titleBarB"><strong>Form</strong></td>
      <td width="25%" class="titleBarB"><strong>Category</strong></td>
	</tr>
	<?php
	$counter = 0;
	while($rasMedicine = $mydb->fetch_array($result))
	{
	$gid = $rasMedicine['id'];
	?>
	<tr>
	  <!-- <td class="titleBarA" align="center" valign="top"> <?php echo ++$counter;?></td> -->
	  <td class="titleBarA" valign="top"><input name="mid[]" type="hidden" id="mid[]" value="<?php echo $rasMedicine['id'];?>" /><input type="text" name="medicine_name[]" value="<?php echo stripslashes(ucfirst($rasMedicine['medicine_name']));?>" class="inputBox medicine_name" placeholder="Medicine Name" /></td>
	  <td class="titleBarA" valign="top"><input type="text" name="composition[]" value="<?php echo $rasMedicine['composition'];?>" class="inputBox composition" placeholder="Composition" /></td>
      <td class="titleBarA" valign="top"><input type="text" name="form[]" value="<?php echo $rasMedicine['form'];?>" class="inputBox form" placeholder="Form" /></td>
      <td class="titleBarA" valign="top"><input type="text" name="category[]" value="<?php echo $rasMedicine['category'];?>" class="inputBox category" placeholder="Medicine or Surgical or Orthopedic or Cosmetic or Baby Products or Machinery and Equipment or Others" />
      </td>
	</tr>	
    <?php
    }
    ?>
	<tr>
	  <td class="titleBarA" align="center" valign="top"><input type="submit" name="btnUpdate" id="btnUpdate" value="Save" class="button" /></td>
	  <td class="titleBarA" valign="top">&nbsp;</td>
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
      <td class="message" colspan="4">No medicines has been Added</td>
   	</tr>
    <?php
	}
	?>
    </table>
    <?php
    if(isset($page))
    {
    ?>
    <nav style="text-align:center;">
        <ul class="pagination">
        <?php
        //$rs_result=$mydb->getCount('id','tbl_medicine');
        
        $sql2 = "SELECT tm.id FROM tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id WHERE tm.composition = '' AND tm.category!='surgical' AND tm.category!='orthopedic' AND tm.category!='baby_products' GROUP BY ts.medicine_id";
        $result2 = $mydb->query($sql2);
        $no_of_records2 = mysql_num_rows($result2);
        $total_pages = ceil($no_of_records2/$num_rec_per_page);
        //echo $total_pages;

        if($page<=1) {

        }else
        {
            $j=$page-1;
            echo '<li >
                    <a href="' . ADMINURLPATH . 'medicine_detail_update&page='.$j.'" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>';
        }

        for ($i=1; $i<=$total_pages; $i++)
        {
            if($i<>$page)

            {

                echo '<li><a href ="' . ADMINURLPATH . 'medicine_detail_update&page=' . $i . '" >' . $i . '</a></li>';
            }
            else
            {
                echo '<li >

                        <span aria-hidden="true">'.$i.'</span>
                    </a>
                </li>';
            }
        }

        if($page==$total_pages)
        {

        }else
        {
            $j=$page+1;
            echo "<li>
            <a href='" . ADMINURLPATH . 'medicine_detail_update&page=' . $j . "' aria-label='Next'>
                <span aria-hidden='true'>&raquo;</span>
            </a>
        </li>";
        }
        ?>
        </ul>
    </nav>
    <?php } ?>
</form>
</div>
</div>
</div>