<?php
require_once("classes/call.php");

$typecheck=$_GET['type'];
////=============================for user search from tl_client=============================================
if($typecheck=="user")
{
    $q=$_GET['term'];
    $sql = "SELECT * from users WHERE name LIKE '$q%' OR phone LIKE '$q%' ORDER BY reference LIMIT 5";
    //echo $sql;
    $query3=mysql_query($sql);

    $json=array();

    while($user=mysql_fetch_array($query3)){
        $json[]=array(
            'value'=> $user["name"].' <'.$user["phone"].'>',
            'label'=> $user["name"].' <'.$user["phone"].'>',
            'the_link'=> "index.php?manager=users&username=".$user["id"]
        );
    }
    echo json_encode($json);
}
elseif($typecheck=="company")
{


        $q=$_GET['term'];
        $query1=mysql_query("SELECT * from tbl_company WHERE fullname LIKE '$q%' ORDER BY fullname LIMIT 5");

        $json=array();

        while($company=mysql_fetch_array($query1)){
            $json[]=array(
                'value'=> $company["fullname"],
                'label'=> $company["fullname"],
                'the_link'=>"index.php?manager=company&companyname=".$company["id"]
            );
        }
        echo json_encode($json);

}
elseif($typecheck=="distributor")
{
    $q=$_GET['term'];
    $query1=mysql_query("SELECT * from tbl_distributor WHERE fullname LIKE '$q%' ORDER BY fullname LIMIT 5");

    $json=array();

    while($distributor=mysql_fetch_array($query1)){
        $json[]=array(
            'value'=> $distributor["fullname"],
            'label'=> $distributor["fullname"],
            'the_link'=>"index.php?manager=distributor&distributorname=".$distributor["id"]
        );
    }
    echo json_encode($json);

}
elseif($typecheck=="ordersearch")
{
    $q=$_GET['term'];
    $query1=mysql_query("SELECT * from users WHERE name LIKE '$q%' OR phone LIKE '$q%' ORDER BY name LIMIT 5");

    $json=array();

    while($salesdetails=mysql_fetch_array($query1))
    {
        $json[]=array(
            'value'=> $salesdetails["name"].' <'.$salesdetails["phone"].'>',
            'label'=> $salesdetails["name"].' <'.$salesdetails["phone"].'>',
            'the_link'=>"index.php?manager=sales&salesdetails=".$salesdetails["id"]
        );
    }
    echo json_encode($json);

}
?>