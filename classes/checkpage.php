<?php
//define Admin
define("SITENAME",$mydb->getValue('title','tbl_admin','id=1'));
define("SITEEMAIL",$mydb->getValue('email','tbl_admin','id=1'));
$pagename = '';
/*$rasHome = $mydb->getArray('metatitle,metakeywords,metadescription','tbl_page','id=3');
$pagepath = 'includes/home.php';*/

if(isset($_GET['urlcode']))
{
	$urlcode = $_GET['urlcode'];
	/*if($mydb->getCount('id','tbl_activity','urlcode="'.$urlcode.'"')>0)
	{
		$pagename = 'activity';
		$rasActivity = $mydb->getArray('id,parent_id,subtitle,title,description,activityimage,pagetitle,metakeywords,metadescription','tbl_activity','urlcode="'.$urlcode.'"');	
		$id = $rasActivity['id'];
		$parent_id = $rasActivity['parent_id'];
		$title = $rasActivity['title'];	
		$subtitle = $rasActivity['subtitle'];	
		$description = $rasActivity['description'];	
		$activityimage = $rasActivity['activityimage'];	
		$metatitle = $rasActivity['pagetitle'];
		$metakeywords = $rasActivity['metakeywords'];
		$metadescription = 	$rasActivity['metadescription'];
		$pagepath = 'includes/template_activity.php';
	}*/
	
}

/*if(empty($metatitle))
	$metatitle = stripslashes($rasHome['metatitle']);

if(empty($metakeywords))
	$metakeywords = stripslashes($rasHome['metakeywords']);

if(empty($metadescription))
	$metadescription = stripslashes($rasHome['metadescription']);*/

?>