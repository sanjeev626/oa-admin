<?php
//echo $mydb->getQuery('ts.*, tm.id med_id','tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id','ts.medicine_id="0"','1');
//SELECT tm.id med_id, ts.* FROM tbl_stock ts INNER JOIN tbl_medicine tm ON tm.id=ts.medicine_id WHERE ts.medicine_id = 8490
  // $resUpdate = $mydb->getQuery('ts.*, tm.id med_id','tbl_stock ts INNER JOIN tbl_medicine tm ON tm.medicine_name = ts.item_description','ts.medicine_id="0"');
  // while($rasUpdate = $mydb->fetch_array($resUpdate))
  // {

  //   $stock_id = $rasUpdate['id'];
  //   $medicine_id = $rasUpdate['med_id'];

  //   $data='';
  //   $data['medicine_id'] = $medicine_id;
  //   $mydb->updateQuery('tbl_stock',$data,'id='.$stock_id);

  //   echo $rasUpdate['item_description'].' --- '.$rasUpdate['med_id'].' --- '.$stock_id;
  //   if($rasUpdate['med_id']<1) echo "NOT FOUND";
  //   echo "<br>";
  // }


/*  $resStock = $mydb->getQuery('id,item_description','tbl_stock');
  while($rasStock = $mydb->fetch_array($resStock))
  {
    $stock_id = $rasStock['id'];
    $item_description = str_replace('"','',$rasStock['item_description']);
    $data0 = '';
    $data0['item_description'] = $item_description;
    $mydb->updateQuery('tbl_stock',$data0,'id='.$stock_id);
    //echo "<br>".$mydb->updateQuery('tbl_stock',$data0,'id='.$stock_id,'1');
  }*/
  //echo $mydb->getQuery('id,item_description','tbl_stock','medicine_id=0 GROUP BY item_description ORDER BY item_description','1');
  /*$resStock = $mydb->getQuery('id,item_description','tbl_stock','medicine_id=0 GROUP BY item_description ORDER BY item_description');
  while($rasStock = $mydb->fetch_array($resStock))
  {
    $stock_id = $rasStock['id'];
    $medicine_name = $rasStock['item_description'];
    $medicine_id = $mydb->getValue('id','tbl_medicine','medicine_name="'.$medicine_name.'"');
    $data = '';
    $data['medicine_id'] = $medicine_id;
    $mydb->updateQuery('tbl_stock',$data,'id='.$stock_id);
    echo "<br>".$mydb->updateQuery('tbl_stock',$data,'id='.$stock_id,'1').' --- '.$medicine_name." stock_id=".$stock_id." medicine_id=".$medicine_id;
  }*/

  echo $mydb->getQuery('id,medicine_name','tbl_order','medicine_id=0 ORDER BY medicine_name','1');
  $resOrder = $mydb->getQuery('id,medicine_name','tbl_order','medicine_id=0 ORDER BY medicine_name');
  while($rasOrder = $mydb->fetch_array($resOrder))
  {
    $order_id = $rasOrder['id'];
    $medicine_name = trim($rasOrder['medicine_name']);
    $medicine_id = $mydb->getValue('id','tbl_medicine','medicine_name="'.$medicine_name.'"');
    $correct_medicine_name = $mydb->getValue('medicine_name','tbl_medicine','medicine_name="'.$medicine_name.'"');
    //if(!empty($correct_medicine_name))
    {
      $data = '';
      $data['medicine_id'] = $medicine_id;
      //$data['medicine_name'] = $correct_medicine_name;
      $mydb->updateQuery('tbl_order',$data,'id='.$order_id);
      echo "<br>".$mydb->updateQuery('tbl_order',$data,'id='.$order_id,'1').' --- '.$medicine_name." stock_id=".$stock_id." medicine_id=".$medicine_id;
    }
  }

  // DELETE  FROM `tbl_order` WHERE `sales_id` = 1397;
  // UPDATE tbl_order SET medicine_name='Crepe Bandage 4' WHERE medicine_name='Crepe Bandage 4"';
  // UPDATE tbl_order SET medicine_name='Roller bandage 3' WHERE medicine_name='Roller bandage 3"';
  // UPDATE tbl_order SET medicine_name='Crepe Bandage simple 3' WHERE medicine_name='Crepe Bandage simple 3"';
  // UPDATE tbl_order SET medicine_name='Adhesive Tape 1' WHERE medicine_name='Adhesive Tape 1"';
  // UPDATE tbl_order SET medicine_name='Scissors 4' WHERE medicine_name='Scissors 4"';
  // UPDATE tbl_order SET medicine_name='Adhesive Tape 1/2' WHERE medicine_name='Adhesive Tape 1/2"';
  // UPDATE tbl_order SET medicine_name='Crepe Bandage 3' WHERE medicine_name='Crepe Bandage 3"';
  // UPDATE tbl_order SET medicine_name='ROLLER BANDAGE 4' WHERE medicine_name='ROLLER BANDAGE 4"'; 



  // UPDATE tbl_order SET medicine_name='PRAZOPRESS XL 5' WHERE medicine_name='PRAZOPRESS-XL 5';
  // UPDATE tbl_order SET medicine_name='SIPTIN M 1000' WHERE medicine_name='Siptin-M 1000';
  // UPDATE tbl_order SET medicine_name='SIPTIN M 850' WHERE medicine_name='Siptin-M 850';
  // UPDATE tbl_order SET medicine_name='SIPTIN 100MG' WHERE medicine_name='Siptin100mg';
  // UPDATE tbl_order SET medicine_name='SIPTIN M 850' WHERE medicine_name='SiptinM850';
  // UPDATE tbl_order SET medicine_name='ZIDE 80MG' WHERE medicine_name='Zide80mg';


  // UPDATE tbl_order SET medicine_name='AASMA 150 XR TABLET' WHERE medicine_name='AASMA-150XR Tablet';
  // UPDATE tbl_order SET medicine_name='AMLODAC 5MG' WHERE medicine_name='Amlod5mg';
  // UPDATE tbl_order SET medicine_name='AMLOD AT' WHERE medicine_name='AmlodAT';
  // UPDATE tbl_order SET medicine_name='ASTAT 10' WHERE medicine_name='Astat10';
  // UPDATE tbl_order SET medicine_name='ATORTIN 10MG' WHERE medicine_name='Atortin10mg';
  // UPDATE tbl_order SET medicine_name='DECOLD TAB' WHERE medicine_name='DE-COLD TAB';
  // UPDATE tbl_order SET medicine_name='GLUBOSE 50MG' WHERE medicine_name='Glubose50mg';
  // UPDATE tbl_order SET medicine_name='ECOSPRIN 75MG' WHERE medicine_name='ECOSPRIN75MG';


  // UPDATE tbl_order SET medicine_name='AASMA 300 XR TABLET' WHERE medicine_name='AASMA-300XR Tablet';
  // UPDATE tbl_order SET medicine_name='HYPOLIP 10 TABLET' WHERE medicine_name='HYPOLIP-10 TABLET';
  // UPDATE tbl_order SET medicine_name='HYTIDE 12.5MG' WHERE medicine_name='Hytide125mg';
  // UPDATE tbl_order SET medicine_name='LOTAN 25 TABLET' WHERE medicine_name='LOTAN-25 TABLET';
  // UPDATE tbl_order SET medicine_name='LOTANH TABLET' WHERE medicine_name='LOTAN-H TABLET';
  // UPDATE tbl_order SET medicine_name='LOTAN50 TABLET' WHERE medicine_name='LOTAN50TABLET';
  // UPDATE tbl_order SET medicine_name='MYLOD 5' WHERE medicine_name='Mylod5';


  // UPDATE tbl_order SET medicine_name='LODOZ 2.5' WHERE medicine_name='LODOZ25';
  // UPDATE tbl_order SET medicine_name='MYLOD L' WHERE medicine_name='MylodL';
  // UPDATE tbl_order SET medicine_name='OSTOCALD' WHERE medicine_name='Ostocal-D';
  // UPDATE tbl_order SET medicine_name='REPACE 50' WHERE medicine_name='REPACE50';
  // UPDATE tbl_order SET medicine_name='REPACE H' WHERE medicine_name='REPACEH';
  // UPDATE tbl_order SET medicine_name='RESERT 50' WHERE medicine_name='Resert50';
  // UPDATE tbl_order SET medicine_name='SHADOW SPF30+ CREAM' WHERE medicine_name='SHADOW SPF30  CREAM';

  // UPDATE tbl_order SET medicine_name='DIAPRIDE M 2' WHERE medicine_name='DIAPRIDEM2';


  // UPDATE tbl_order SET medicine_name='FIRST AID BOX M' WHERE medicine_name='First Aid Box (M)';
  // UPDATE tbl_order SET medicine_name='FIRST AID BAG RED COTTON' WHERE medicine_name='First Aid Bag (Red Cotton)';

 

///////////////////////////////////   tbl_stock     //////////////////////////////////////////////////////////

// UPDATE tbl_stock SET item_description='Crepe Bandage 4' WHERE item_description='Crepe Bandage 4"';
//   UPDATE tbl_stock SET item_description='Roller bandage 3' WHERE item_description='Roller bandage 3"';
//   UPDATE tbl_stock SET item_description='Crepe Bandage simple 3' WHERE item_description='Crepe Bandage simple 3"';
//   UPDATE tbl_stock SET item_description='Adhesive Tape 1' WHERE item_description='Adhesive Tape 1"';
//   UPDATE tbl_stock SET item_description='Scissors 4' WHERE item_description='Scissors 4"';
//   UPDATE tbl_stock SET item_description='Adhesive Tape 1/2' WHERE item_description='Adhesive Tape 1/2"';
//   UPDATE tbl_stock SET item_description='Crepe Bandage 3' WHERE item_description='Crepe Bandage 3"';
//   UPDATE tbl_stock SET item_description='ROLLER BANDAGE 4' WHERE item_description='ROLLER BANDAGE 4"'; 



//   UPDATE tbl_stock SET item_description='PRAZOPRESS XL 5' WHERE item_description='PRAZOPRESS-XL 5';
//   UPDATE tbl_stock SET item_description='SIPTIN M 1000' WHERE item_description='Siptin-M 1000';
//   UPDATE tbl_stock SET item_description='SIPTIN M 850' WHERE item_description='Siptin-M 850';
//   UPDATE tbl_stock SET item_description='SIPTIN 100MG' WHERE item_description='Siptin100mg';
//   UPDATE tbl_stock SET item_description='SIPTIN M 850' WHERE item_description='SiptinM850';
//   UPDATE tbl_stock SET item_description='ZIDE 80MG' WHERE item_description='Zide80mg';


//   UPDATE tbl_stock SET item_description='AASMA 150 XR TABLET' WHERE item_description='AASMA-150XR Tablet';
//   UPDATE tbl_stock SET item_description='AMLODAC 5MG' WHERE item_description='Amlod5mg';
//   UPDATE tbl_stock SET item_description='AMLOD AT' WHERE item_description='AmlodAT';
//   UPDATE tbl_stock SET item_description='ASTAT 10' WHERE item_description='Astat10';
//   UPDATE tbl_stock SET item_description='ATORTIN 10MG' WHERE item_description='Atortin10mg';
//   UPDATE tbl_stock SET item_description='DECOLD TAB' WHERE item_description='DE-COLD TAB';
//   UPDATE tbl_stock SET item_description='GLUBOSE 50MG' WHERE item_description='Glubose50mg';
//   UPDATE tbl_stock SET item_description='ECOSPRIN 75MG' WHERE item_description='ECOSPRIN75MG';


//   UPDATE tbl_stock SET item_description='AASMA 300 XR TABLET' WHERE item_description='AASMA-300XR Tablet';
//   UPDATE tbl_stock SET item_description='HYPOLIP 10 TABLET' WHERE item_description='HYPOLIP-10 TABLET';
//   UPDATE tbl_stock SET item_description='HYTIDE 12.5MG' WHERE item_description='Hytide125mg';
//   UPDATE tbl_stock SET item_description='LOTAN 25 TABLET' WHERE item_description='LOTAN-25 TABLET';
//   UPDATE tbl_stock SET item_description='LOTANH TABLET' WHERE item_description='LOTAN-H TABLET';
//   UPDATE tbl_stock SET item_description='LOTAN50 TABLET' WHERE item_description='LOTAN50TABLET';
//   UPDATE tbl_stock SET item_description='MYLOD 5' WHERE item_description='Mylod5';


//   UPDATE tbl_stock SET item_description='LODOZ 2.5' WHERE item_description='LODOZ25';
//   UPDATE tbl_stock SET item_description='MYLOD L' WHERE item_description='MylodL';
//   UPDATE tbl_stock SET item_description='OSTOCALD' WHERE item_description='Ostocal-D';
//   UPDATE tbl_stock SET item_description='REPACE 50' WHERE item_description='REPACE50';
//   UPDATE tbl_stock SET item_description='REPACE H' WHERE item_description='REPACEH';
//   UPDATE tbl_stock SET item_description='RESERT 50' WHERE item_description='Resert50';
//   UPDATE tbl_stock SET item_description='SHADOW SPF30+ CREAM' WHERE item_description='SHADOW SPF30  CREAM';

//   UPDATE tbl_stock SET item_description='DIAPRIDE M 2' WHERE item_description='DIAPRIDEM2';


//   UPDATE tbl_stock SET item_description='FIRST AID BOX M' WHERE item_description='First Aid Box (M)';
//   UPDATE tbl_stock SET item_description='FIRST AID BAG RED COTTON' WHERE item_description='First Aid Bag (Red Cotton)';


  // UPDATE tbl_stock SET   item_description ="MAMY POKO PANTS L 48S" WHERE item_description="MAMY POKO PANTS (L) 48'S";
  // UPDATE tbl_stock SET item_description="MAMY POKO PANTS M 56S" WHERE item_description="MAMY POKO PANTS (M) 56'S";
  // UPDATE tbl_stock SET item_description="MAMY POKO PANTS L 38S" WHERE item_description="MAMY POKO PANTS (L) 38'S";
  // UPDATE tbl_stock SET item_description="MAMY POKO PANTS XL 46S" WHERE item_description="MAMY POKO PANTS (XL) 46'S";
  // UPDATE tbl_stock SET item_description="MAMY POKO PANTS S 60S" WHERE item_description="MAMY POKO PANTS (S) 60'S";



  // UPDATE tbl_order SET medicine_name="MAMY POKO PANTS L 48S" WHERE medicine_name="Mamy Poko Pants (L) 48's";
  // UPDATE tbl_order SET medicine_name="MAMY POKO PANTS M 56S" WHERE medicine_name="Mamy Poko Pants (M) 56's";
  // UPDATE tbl_order SET medicine_name="AVAS 10MG" WHERE medicine_name="Avas10";
  // UPDATE tbl_order SET medicine_name="ALPHADOL 0.25MG" WHERE medicine_name="Alphadol 2.5mg";
  // UPDATE tbl_order SET medicine_name="DUZELA 60" WHERE medicine_name="DUZELA60";
  // UPDATE tbl_order SET medicine_name="ENTALIV 0.5MG" WHERE medicine_name="ENTALIV05MG";
  // UPDATE tbl_order SET medicine_name="ESAM 2.5MG" WHERE medicine_name="ESAM25MG";
  // UPDATE tbl_order SET medicine_name="FIRST AID BOX (L)" WHERE medicine_name="FIRST AID BOX L";
  // UPDATE tbl_order SET medicine_name="IROVEL 300MG" WHERE medicine_name="IROVEL-300";
  // UPDATE tbl_order SET medicine_name="LATOCOM EYE DROPS" WHERE medicine_name="LATOCOMEYEDROPS";
  // UPDATE tbl_order SET medicine_name="MAMY POKO PANTS L 38S" WHERE medicine_name="Mamy Poko Pants (L) 38's";
  // UPDATE tbl_order SET medicine_name="MAMY POKO PANTS M 56S" WHERE medicine_name="Mamy Poko Pants (M) 56's";
  // UPDATE tbl_order SET medicine_name="MAMY POKO PANTS S 60S" WHERE medicine_name="Mamy Poko Pants (S) 60's";
  // UPDATE tbl_order SET medicine_name="OLEANZ 5" WHERE medicine_name="OLEANZ5";
  // UPDATE tbl_order SET medicine_name="PROLOMET XL 25" WHERE medicine_name="PROLOMETXL25";
  // UPDATE tbl_order SET medicine_name="S-AMLONG 5MG" WHERE medicine_name="S-Amlong 5 mg";
  // UPDATE tbl_order SET medicine_name="URSOCOL-300" WHERE medicine_name="URSOCOL300";

?>