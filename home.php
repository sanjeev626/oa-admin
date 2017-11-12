<?php
//print_r($_SESSION);
if(isset($_SESSION[ADMINROLE]) && $_SESSION[ADMINROLE]=="superadmin")
{
	include("home_superadmin.php");
}
if(isset($_SESSION[ADMINROLE]) && $_SESSION[ADMINROLE]=="admin")
{
	include("home_admin.php");
}
if(isset($_SESSION[ADMINROLE]) && $_SESSION[ADMINROLE]=="user")
{
	include("home_user.php");
}
?>