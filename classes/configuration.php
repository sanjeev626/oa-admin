<?php
// client
define("ACTIONNAME","manager");
define("URLPATH","index.php?".ACTIONNAME."=");
if($_SERVER['HTTP_HOST'] == 'admin.onlineaushadhi.dev' || $_SERVER['HTTP_HOST'] == '127.0.0.1')
{
	define("SITEROOT","http://admin.onlineaushadhi.dev/");
	define("SITEROOTADMIN","http://admin.onlineaushadhi.dev/");
	define("SITEROOTDOC",$_SERVER['DOCUMENT_ROOT']."/");
}
else
{
	define("SITEROOT","http://demo.onlineaushadhi.com/");
	define("SITEROOTADMIN",SITEROOT);
	define("SITEROOTDOC",$_SERVER['DOCUMENT_ROOT']."/");
}

define("FILEPATH","includes/");
define("PAGING","dashboard/");
define("IMAGEPATH","images/");
define("UPLOADPATH","upload/");


define("USERID","sanjeevdbclientuser");
$allowedimageext = array ("jpg", "jpeg", "gif", "png");
$allowedextfile = array ("pdf", "doc", "docx", "txt", "xls");

define("ADMINACTIONNAME","manager");
define("ADMINURLPATH","index.php?".ADMINACTIONNAME."=");
define("ADMINUSER","sanjeevdbdfg546gfddgdfg");
define("ADMINROLE","sabidtryudbdfg546gfddgdfg");
define("SECRETPASSWORD","sanjeevsinghdbementendc");
define("CLIENT","sanjeevsinghdbclientdac");
define("ADMINEMAIL","care.onlineaushadhi@gmail.com");
define("ADMINNAME","Online Aushadhi");
?>