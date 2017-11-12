<?php
//=================================To display message if refill date is 7 days away=====================================
$result=mysql_query("SELECT * from tbl_sales WHERE `Refill_date` ='".date("Y-m-d")."'");
$msg="";
while($alert=$mydb->fetch_array($result))
{
  $clientid[]=$alert['client_id'];
}
if(count($clientid)>0)
{
  $toName = "Admin";
  $toEmail = ADMINEMAIL;
  $fromEmail = ADMINEMAIL;
  $fromName = ADMINNAME;
  $msg .= ' Dear '.$toName.',<br>

                        Some of your users whose medicine will finished within 7 days<br>

              <html>
               <body>
                  <table width="100%" cellspacing=\"4\" cellpadding=\"4\" border=\"1\" align=\"center\">
                  <tr>
                    <td align="center"><strong>User</strong></td>
                    <td align="center"><strong>Contac</strong>t</td>
                    <td align="center"><strong>Last Order</strong></td>
                   <tr>
                  ';

  foreach ($clientid as $value) {
    $userlist=$mydb->getQuery('*','users','id='.$value);
    while($userlist1=$mydb->fetch_array($userlist))
    {
      $msg.='<tr>';
      $msg .= '<td align="center">'.$userlist1['name'] . '</td>';
      $msg.='<td align="center">' .$userlist1['phone'] . '</td>';
      $msg.='<td align="center">' .$mydb->getValue('delivery_date','tbl_sales','client_id="'.$userlist1['id'].'"'). '</td>';
      $msg.='</tr>';
    }


  };
  $msg.='</table></body></html>';
  $msg .= 'Best Regards,<br>
                              ' . $fromName . ' Support Team ';
  $stat = $mydb->sendMessage($subject, $toEmail, $toName, $fromEmail, $fromName, $msg);
  if($stat)
  {
    echo '<table width="100%"><tr><td class="message" align="center">Mail has sent to Admin</td></tr></table>';
  }
  else{ echo '<table width="100%"><tr><td class="message" align="center">Couldnot send mail</td></tr></table>';;}

}
//=====================================================================================================================//

//======================= To display message if refill date is 3 days away============================================

$result=mysql_query("SELECT * from tbl_sales WHERE  DATE_ADD(`Refill_date`,INTERVAL 4 day)='".date("Y-m-d")."'");
$msg="";
while($alert1=$mydb->fetch_array($result))
{

  $clientid1[]=$alert1['client_id'];
}
  if(count($clientid1)>0)
  {
    $toName = "Admin";
    $toEmail = ADMINEMAIL;
    $fromEmail = ADMINEMAIL;
    $fromName = ADMINNAME;
    $msg .= ' Dear '.$toName.',<br>

                        Some of your users whose medicine will finished within 7 days<br>

              <html>
               <body>
                  <table width="100%" cellspacing=\"4\" cellpadding=\"4\" border=\"1\" align=\"center\">
                  <tr>
                    <td align="center"><strong>User</strong></td>
                    <td align="center"><strong>Contac</strong>t</td>
                    <td align="center"><strong>Last Order</strong></td>
                   <tr>
                  ';

    foreach ($clientid1 as $value) {
      $userlist2=$mydb->getQuery('*','users','id='.$value);
      while($userlist3=$mydb->fetch_array($userlist2))
      {
        $msg.='<tr>';
        $msg .= '<td align="center">'.$userlist3['name'] . '</td>';
        $msg.='<td align="center">' .$userlist3['phone'] . '</td>';
        $msg.='<td align="center">' .$mydb->getValue('delivery_date','tbl_sales','client_id="'.$userlist3['id'].'"'). '</td>';
        $msg.='</tr>';
      }


    };
    $msg.='</table>
                </body>
              </html>';
    $msg .= 'Best Regards,<br>
                              ' . $fromName . ' Support Team
                              ';
    $stat = $mydb->sendMessage($subject, $toEmail, $toName, $fromEmail, $fromName, $msg);
    if($stat)
    {
      echo '<table width="100%"><tr><td class="message" align="center">Mail has sent to Admin</td></tr></table>';
    }
    else{ echo '<table width="100%"><tr><td class="message" align="center">Couldnot send mail</td></tr></table>';;}

  }
//======================================================================================================================//
?>