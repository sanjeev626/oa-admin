
<?php

if(isset($_POST['btnlogin'])&& $_POST['btnlogin']=='Login')
{
	
 $getpass 		= $mydb->CleanString($_POST['password']);
 
	$getusername 	= $mydb->CleanString($_POST['username']);
 
	
	if($mydb->count_row($mydb->getQuery("*","tbl_admin","username = '".$getusername."'")) == 1)
	{
		$dbpass = $mydb->md5_decrypt($mydb->getValue("pass","tbl_admin","username = '".$getusername."'"), SECRETPASSWORD);
		
		if($dbpass == $getpass)
		{
			$_SESSION[ADMINUSER] = $mydb->getValue("id","tbl_admin","username = '".$getusername."'");
      $_SESSION[ADMINROLE] = $mydb->getValue("role","tbl_admin","username = '".$getusername."'");
      $mydb->setSession_admin();
      
			
			$redirectUrl = SITEROOT;
			$querystring = $_SERVER['QUERY_STRING'];
			if(!empty($querystring))
				$redirectUrl = $redirectUrl.'?'.$querystring;
			$mydb->redirect($redirectUrl);
		}
		else
		{
				echo "<div class='message' style='width:370px;'>Invalid Username/Password/Usertype Combination</div>";
		}
	}
	else
  {
		echo "<div class='message' style='width:370px;'>Invalid Username/Password/Usertype Combination</div>";

  }
	
}


if(isset($_POST['btnsendpassword'])&& $_POST['btnsendpassword']=='Send Password')
{
  
  $emailval=$mydb->CleanString($_POST['femail']);
  if($mydb->count_row($mydb->getQuery("*","tbl_admin","email = '".$emailval."'")) == 1)
  {
    $dbpass = $mydb->md5_decrypt($mydb->getValue("pass","tbl_admin","email= '".$emailval."'"), SECRETPASSWORD);
    $username=$mydb->getValue("pass","tbl_admin","email = '".$emailval."'");
    // $msg = 'Your login information is  sent to your email';
       $to  = $emailval; // Send email to our user
        $subject = 'Login Information'; // Give the email a subject 
        $message = '
 
                You can login with the following credentials after you have activated your account by pressing the url below.
       
      ------------------------
      Username:'.$username.'

      ------------------------
       
      '; // Our message above including the link
                           
      $headers = 'From:noreply@onlineaushadhi.com' . "\r\n"; // Set from headers
      mail($to, $subject, $message, $headers); // Send our email
      
  }
  
}

?>


<script src="https://code.jquery.com/jquery-1.11.2.js"></script>
<script type="text/javascript">

      $(document).ready(function() {

         $('#forgot_pass').click(function() {
                
                $('#admin_login').hide();
                $('#password_forgot').show();
                
        });

          $('#relogin').click(function() {
               
                 $('#password_forgot').hide();
                 $('#admin_login').show();
         });
          
           jQuery('form#password_forgot').submit(function() {
                      jQuery('form#password_forgot .error').remove();

                        var hasError = false;
                        jQuery('#femail').each(function() {
                          if(jQuery.trim(jQuery(this).val()) == '') {
                           
                            jQuery('#errormessage').empty().append("<p style='color:#FF0000; background:#COCOCO; text-align:center; padding:5px 0px; margin-top:5px; border-radius:4px; '><?php echo 'You forgot to enter your Email'; ?></p>");
                            jQuery('#errormessage').addClass('inputError');
                            hasError = true;
                          } else if(jQuery(this).hasClass('inputEmail')) {
                            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                            if(!emailReg.test(jQuery.trim(jQuery(this).val()))) {
                           
                            jQuery('#errormessage').empty().append("<p style='color:#FF0000; background:#COCOCO; text-align:center; padding:2px 2px; margin-top:5px; border-radius:4px; '><?php echo 'You entered an invalid Email'; ?></p>");
                            jQuery('#errormessage').addClass('inputError');
                            hasError = true;
                          }
                        }
                      });
                    
                     

                      if(!hasError) {
                        var formInput = jQuery(this).serialize();
                      
                        jQuery.post(jQuery(this).attr('action'),formInput, function(data){
                          jQuery('form#password_forgot').slideUp("fast", function() {  

                            jQuery(this).before("<span><p style='color:#D47000; background:#COCOCO; text-align:center; padding:5px 0px; margin-top:5px; border-radius:4px; '><?php echo '<strong> Reset link has sent to your mail!</strong>'; ?></p></span><a href='<?php echo SITEROOTADMIN;?>'><p style='font-size:18px'>Back</p></a>");
                          });

                        });
                         
                      }

                      return false;
                 
                
                
              });

      });



</script>

<div class="ie_width" align="center">
  
  <div id="form_wrapper" class="form_wrapper">
   
                  <!--=================================form for admin login-->
    <form method="post" action="" name="f1" class="login active" id="admin_login">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="adminLoginTbl" align="center">
        <tr>
          <td style="padding:0" colspan="2"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="adminLogForm">
              <tr>
                <td><label>Username:</label></td>
              </tr>
              <tr>
                <td><input type="text" name="username" id="username" value="" class="inputBox"/></td>
              </tr>
              <tr>
                <td align="center"><div id="loading" style="display:none;" align="center">Loading...<img src="../images/loading.gif" height="11" width="16" /></div></td>
              </tr>
              <tr>
                <td><label>Password:</label></td>
              </tr>
              <tr>
                <td><input type="password" name="password" id="password" value="" class="inputBox"  /></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td width="69%" style="padding:10px 0 20px 20px; vertical-align:bottom"><a href="javascript:void(0)" rel="forgot_password" id="forgot_pass" class="forgot_linkform">Forgot Your Password?</a></td>
          <td width="31%" align="right" style="padding:0 20px 10px 0;"><input name="btnlogin" class="button" type="submit" id="btnlogin" value="Login"/>
          </td>
        </tr>
      </table>
    </form>

<!--=============================================================form for password recovery=============================================-->

   
    <form method="post"  role="form" action="" name="f2" style="display: none;" class="forgot_password" id="password_forgot">
       <div id="errormessage"></div>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="adminLoginTbl">
       
        <tr>
          <td style="padding:0"><table width="100%" cellpadding="0" cellspacing="0" border="0" class="adminLogForm">
              <tr>
                <td><label>Enter your Email:</label></td>
              </tr>
              <tr>
                <td><input type="email" name="femail" id="femail" value="" class="inputBox inputEmail"/></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="right" style="padding:10px 20px;"><input name="btnsendpassword" class="button"  type="submit" id="btnsendpassword" value="Send Password" /></td>
        </tr>
        <tr>
          <td class="noacc" align="right" style="padding:10px 30px 10px 0"><a href="javascript:void(0)" rel="login"  id="relogin" class="forgot linkform">Suddenly Remembered.Login Here</a></td>
        </tr>
      </table>
    </form>
   
  </div>
 
  <div class="clear"></div>
</div>
<!--===================================================================================================================================!>