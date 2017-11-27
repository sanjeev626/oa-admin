<?php 
$query=mysql_query("SELECT id,composition FROM tbl_medicine WHERE composition!='' GROUP BY composition ORDER BY composition");
    while($medicine=mysql_fetch_array($query)){        
        $composition = $medicine["composition"];
        $data='';
        $data['composition'] = $composition;
        $mydb->insertQuery('tbl_composition',$data);
    }
    /*while($medicine=mysql_fetch_array($query)){        
        $id = trim($medicine["id"]); 
        $composition = trim($medicine["composition"]);
        $composition = str_replace(' ','',$composition);
        $composition = preg_replace('/\s+/', ' ', $composition);
        $data='';
        $data['composition'] = $composition;
        $mydb->updateQuery('tbl_medicine',$data,'id='.$id);
    }*/
?> 