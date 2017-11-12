<?php
    include('simple_html_dom.php');
    $html = file_get_contents('incomlist.htm');
    $companylist=explode('.',$html);
    foreach($companylist as $value)
    {
        $companylist1[]=preg_replace('/\s+/S',"",$value);
    }

    ////=============To store company======================================
    $url='http://www.netmeds.com/medicine/manufacturers';
    $output=$mydb->curl($url);
    $spaninfo= $mydb->getValueByTagName($output,'<span id="lblmanufact">','</span>');
    $druginfo= $mydb->getValueByTagName($spaninfo,'<div class="drug-list-col">','</div>');
    $ulinfo= $mydb->getValueByTagName($druginfo,'<ul class="alpha-drug-list">','</ul>');
    $document = new simple_html_dom();
    $document->load($ulinfo);
    $comname=array();
    foreach($document->find('li') as $element)
    {
        $href= $element->first_child()->href;
        $datalink = "http://www.netmeds.com" . $href;
        $comname=preg_replace("/\(\d+\)/","",$element->plaintext);
        $comname1=preg_replace('/\s+/S',"",$comname);


        if(in_array($comname1,$companylist1))
        {?>

            <button onclick="myAjax('<?php echo $datalink;?>','<?php echo $comname;?>')"><?php echo $comname;?></button>
    <?php
            echo "<br>";
        }

    }
    ?>
<script>
    function myAjax(comlink,comname) {

        var datastring='url='+comlink+'&comname1='+comname;

        $.ajax({
            type: "POST",
            url: 'ajaxcurl.php',
            data:datastring,
            success:function(html) {

            }

        });
    }
</script>
