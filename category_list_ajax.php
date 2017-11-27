<?php
require_once("classes/call.php");
/*=====================medicine store search in nepal================================ */
$q=$_GET["term"];
 
$query=mysql_query("SELECT id,category FROM tbl_medicine WHERE category LIKE '$q%' and category!='' GROUP BY category ORDER BY category");
//$query3=mysql_query("SELECT id,name FROM tbl_company WHERE )
$json=array();
    while($medicine=mysql_fetch_array($query)){
        $json[]=array(
            'value'=> $medicine["category"],
            'label'=> $medicine["category"]
        );
    }
echo json_encode($json);
?> 