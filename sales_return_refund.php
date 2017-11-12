<script src="//code.jquery.com/jquery-1.11.2.js"></script>
<script src="jquery_ui/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css"/>

<SCRIPT type="text/javascript"> 
  $(document).ready(function()
  {   
    $( "#refund_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
  });
</SCRIPT>
<?php
if(isset($_GET['sales_return_id']))
{
    $sales_return_id = $_GET['sales_return_id'];
    $rasReturn = $mydb->getArray('*','tbl_sales_return','id='.$sales_return_id);
    $client_name = $mydb->getValue('fullname','users','id='.$rasReturn['client_id']);
}

if(isset($_POST['btnRefund']))
{
  //print_r($_POST);
  $message="";
  $data_sales_return="";
  $data_sales_return['return_amount'] = $_POST['return_amount'];
  $data_sales_return['refund_status'] = $_POST['refund_status'];
  $data_sales_return['refund_date'] = $_POST['refund_date'];
  $data_sales_return['refund_sales_id'] = $_POST['refund_sales_id'];
  $data_sales_return['remarks'] = $_POST['remarks'];

  $mydb->updateQuery('tbl_sales_return',$data_sales_return,'id='.$sales_return_id);
  $url = ADMINURLPATH.'sales&salesdetails='.$rasReturn['client_id'];        
  $mydb->redirect($url);
  //echo $totalAmount;
  //echo "<br>";
}

if(isset($_POST['btnupdate']))
{
  
  $salesid=$_POST['salesid'];

   foreach ($_POST as $key => $value)
   {
      if($key!="btnupdate"&&$key!="salesid")  
        $data[$key]=$value;
   }

   $mydb->updateQuery('tbl_sales',$data,'id='.$salesid);
    $url = ADMINURLPATH.'sales';    
    $mydb->redirect($url);
}
?>  
<form action="" method="post" name="companyinsert" onSubmit="return call_validate(this,0,this.length);">
  <table cellpadding="2" cellspacing="0" border="0" width="100%" class="FormTbl">
  <tr class="TitleBar">
      <td class="TtlBarHeading" colspan="2">Refund </td>
    </tr>   
  <?php if(isset($_GET['message'])){?>
  <tr>
    <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
  </tr>
  <?php } ?>    
  <tr>
    <td width="17%" align="right" class="TitleBarA"><strong>Client Name:</strong></td>
    <td><?php echo $client_name;?></td>
  </tr>
  <tr>
    <td width="17%" align="right" class="TitleBarA"><strong>Total Refund Amount : </strong></td>
    <td class="TitleBarA">Nrs. <?php echo $rasReturn['total_sales_return_amount'];?></td>
  </tr>
  <tr>
    <td align="right" class="TitleBarA"><strong>Amount Refunded:</strong></td>
    <td class="TitleBarA"><input name="return_amount" id="return_amount" type="text" value="<?php echo $rasReturn['return_amount'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
  <tr>
    <td align="right" class="TitleBarA"><strong>Refund Date: </strong></td>
    <td class="TitleBarA">
      <!-- <input style="width:100px;" type="text"  class= "inputBox refund_date"  name="refund_date"> -->
      <input name="refund_date" id="refund_date" type="text" value="<?php echo $rasReturn['refund_date'];?>" class="inputBox" style="width:50%"/></td>
    </tr>
  <tr>
    <td align="right" class="TitleBarA"><strong>Refunded for this Sales : </strong></td>
    <td class="TitleBarA">
      <Select name="refund_sales_id" id="refund_sales_id" class="inputBox" style="width:50%"> 
        <option value = 0>Select Sales date</option>  
        <?php
        $result = $mydb->getQuery("id,date", "tbl_sales", "client_id='".$rasReturn['client_id']."' ORDER BY id desc");
        while($rasSales = $mydb->fetch_array($result))
        {          
          $sales_id = $rasSales['id'];
          $dropdown_value = $rasSales['date'].' ('.$rasSales['id'].')';        
        ?>  
        <option value ='<?php echo $sales_id;?>' <?php if($rasReturn['refund_sales_id']==$sales_id) echo "selected";?>><?php echo $dropdown_value;?></option>              
        <?php 
          }
        ?>  
    </select>

    </td>
  </tr> 
  <tr>
    <td align="right" class="TitleBarA"><strong>Remarks : </strong></td>
    <td class="TitleBarA"><textarea name="remarks" id="remarks" style="width:50%"><?php echo $rasReturn['remarks'];?></textarea></td>
  </tr>
  <tr>
    <td align="right" class="TitleBarA"><strong>Refund Status : </strong></td>
    <td class="TitleBarA">
    <Select name="refund_status" id="refund_status" class="inputBox" style="width:50%"> 
        <option value = "0">Not refunded</option>
        <option value = "2" <?php if($rasReturn['refund_status']=="2") echo "selected";?>>Partial refund</option>
        <option value = "1" <?php if($rasReturn['refund_status']=="1") echo "selected";?>>Full refund</option>
    </select>    
    </td>
  </tr>   
  <tr>
    <td align="right" class="TitleBarA">&nbsp;</td>
    <td class="TitleBarA" style="padding-bottom:15px;"><input name="btnRefund" type="submit" value="Submit" class="button" /></td>
  </tr>
  </table>
</form>