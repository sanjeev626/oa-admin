<?php
if($mydb->logout_admin(array(ADMINUSER),1))
{
	$mydb->redirect('index.php');
}
?>