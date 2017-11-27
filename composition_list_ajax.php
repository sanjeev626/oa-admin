<?php
require_once("classes/call.php");
/*=====================medicine store search in nepal================================ */
$q=$_GET["term"];
 
$query=mysql_query("SELECT id,composition FROM tbl_medicine WHERE composition LIKE '$q%' GROUP BY composition ORDER BY composition LIMIT 20");
//$query3=mysql_query("SELECT id,name FROM tbl_company WHERE )
$json=array();
    while($medicine=mysql_fetch_array($query)){
        $json[]=array(
            'value'=> $medicine["composition"],
            'label'=> $medicine["composition"]
        );
    }
echo json_encode($json);
?> 