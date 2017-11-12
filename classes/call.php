<?php
include("connection.php");
include("dbCls.php");
include("configuration.php");
include("mailCls.php");
//echo 'sklfhslkdf';exit();
$mydb=new mydb();
$mydb->opendb();
include("general.class.php");
$mailObj = new Mail();
include("checkpage.php");
include("nepalicalendar.class.php");
$nepalicalendar=new Nepali_Calendar();



?>
