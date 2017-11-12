<?php
include('classes/call.php');
include('simple_html_dom.php');
    $url=$_POST['url'];
    $comname=$_POST['comname1'];
    $data='';
    $data['name']=$comname;
    $id=$mydb->insertQuery('tbl_company',$data);
    $stat=$mydb->getValue('status',tbl_company,'id='.$id);
if($stat==0)
{
    $output = $mydb->curl($url);
    $spaninfo = $mydb->getValueByTagName($output,'<span id="lblmanufact">', '</span>');
    $document = new simple_html_dom();
    $document->load($spaninfo);
    foreach ($document->find('div.panel-body a') as $element) {
        $val1 = $element->plaintext;
        $data1 = '';
        $data1['company_id'] = $id;
        $data1['medicine_name'] = $val1;
        $mydb->insertQuery('tbl_medicine', $data1);

    }
    $data2='';
    $data2['status']='1';
    $data2['flag']='curl';
    $mydb->updateQuery('tbl_company',$data2,'id='.$id);

}
else{
    echo "already happen";
}

