<?php
require_once("classes/call.php");
/*=====================medicine store search in nepal================================ */
$q=$_GET["term"];
 
$query=mysql_query("SELECT id,form FROM tbl_medicine WHERE form LIKE '$q%' and form!='' GROUP BY form ORDER BY form");
//$query3=mysql_query("SELECT id,name FROM tbl_company WHERE )
$json=array();
    while($medicine=mysql_fetch_array($query)){
        $json[]=array(
            'value'=> $medicine["form"],
            'label'=> $medicine["form"]
        );
    }
echo json_encode($json);
?> 