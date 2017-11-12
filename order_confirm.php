<?php
//print_r($_POST);
if(isset($_GET['sales_id']))
{
	$sales_id=$_GET['sales_id'];
	
	//Update tb_sales table for first aid box and other items as specified STARTS HERE
	if(isset($_POST['item_details']) && !empty($_POST['item_details']))
	{
		$data = '';
		$data['item_details'] = $_POST['item_details'];
		$mydb->updateQuery('tbl_sales',$data,'id='.$sales_id);
		//echo '<br>'.$mydb->updateQuery('tbl_sales',$data,'id='.$sales_id,'1');
	}
	//Update tb_sales table for first aid box and other items as specified ENDS HERE

	$result=$mydb->getQuery('*','tbl_orderreview','sales_id='.$sales_id);//extract order data from tbl_orderreview
	//echo $mydb->getQuery('*','tbl_orderreview','sales_id='.$sales_id,'1');

	while($rasOrder =$mydb->fetch_array($result))
	{		
		$ordered_quantity=$rasOrder['quantity'];
		$quantity=$rasOrder['quantity'];
		$medicine_id=$rasOrder['medicine_id'];
		$medicine=$rasOrder['medicine_name'];
		//$medicine=preg_replace('/[^A-Za-z0-9]/',"", $med);
		$type=$rasOrder['medicine_type'];
		$refill_day = $rasOrder['refill_day'];

		$order_total_amount = 0;

		// Insert into tbl_order and update tbl_stock
		if($ordered_quantity>0)
		{
			//Copy from tbl_orderreview to tbl_order STARTS HERE
			//echo "<br>".$quantity;
			$rasStock = $mydb->getArray('id,sales,stock,pack,rate,sp_per_tab','tbl_stock','medicine_id="'.$medicine_id.'" AND stock>sales ORDER BY expiry_date ASC LIMIT 1');

			$stock_sales_id1 = $rasStock['sales'];
			if($stock_sales_id1<0)
			{
				//display error message
				$url = ADMINURLPATH.'order_review&error&sales_id='.$sales_id.'&message=ERROR !! please consult with your system administrator';
				$mydb->redirect($url);
			}

			$stock_sales=$rasStock['sales'];//0
			$available_in_stock1 = $rasStock['stock']-$rasStock['sales'];//120-0
			$pack = $rasStock['pack'];//10
			$rate_per_strip = $rasStock['rate'];//224.46
			$rate = $rate_per_strip/$pack;
			$sp_per_tab = $rasStock['sp_per_tab'];//$rate+$rate*0.16;
			
			if($available_in_stock1>=$ordered_quantity)
			{
				//echo "<br><br>Available in single stock.";				
				$data1 = '';
				$data1['medicine_id'] = $medicine_id;
				$data1['medicine_name'] = $medicine;
				$data1['medicine_type']=$type;
				$data1['refill_day']=$refill_day;
				$data1['quantity'] =$ordered_quantity;//
				$sales_quantity=$quantity;
				$quantity=0;
				/////////////////////////////////////////////////////////////////
				$data1['sales_id']=$sales_id;
				$data1['stock_id']=$rasStock['id'];

				$data1['Rate']=round($sp_per_tab,2);
				$data1['total_amount']=round($data1['Rate']*$data1['quantity'],2);

				//print_r($data1); die();
				$mydb->insertQuery('tbl_order',$data1);
				//echo "<br>".$mydb->insertQuery('tbl_order',$data1,'1');
				//Copy from tbl_orderreview to tbl_order ENDS HERE

				//update tbl_stock with sales quantity STARTS HERE
				$stock_val=$stock_sales+$sales_quantity;
				$id=$rasStock['id'];
				$data='';
				$data['sales']=$stock_val;
				$mydb->updateQuery('tbl_stock',$data,'id='.$id);
				//echo "<br>".$mydb->updateQuery('tbl_stock',$data,'id='.$id,'1');
				//update tbl_stock with sales quantity ENDS HERE
			}
			else
			{
				$balance_quantity = $ordered_quantity-$available_in_stock1;
				//echo "<br><br>Not Available in single stock.";
				//Get all the stock IDs from where stock to be deducted
				$stock_id1 = $rasStock['id'];
				$stock_ids = '';
				$sales_qty = '';
				$sales_qty[] = $available_in_stock1;			
				$stock_ids[] = $stock_id1;

				$rasStock2 = $mydb->getArray('id,sales,stock,pack,rate,sp_per_tab','tbl_stock','medicine_id="'.$medicine_id.'" AND stock>sales AND id!='.$stock_id1.' ORDER BY expiry_date,id ASC LIMIT 1');
				$available_in_stock2 = $rasStock2['stock']-$rasStock2['sales'];//120-0
				$stock_id2 = $rasStock2['id'];									
				$sales_qty[] = $balance_quantity;			
				$stock_ids[] = $stock_id2;				

				$stock_sales_id2 = $rasStock2['sales'];
				if($stock_sales_id2<0)
				{
					//display error message
					$url = ADMINURLPATH.'order_review&error&sales_id='.$sales_id.'&message=ERROR in Stock/sales !! please consult with your system administrator.';
					$mydb->redirect($url);
				}			

				//print_r($sales_qty);
				//print_r($stock_ids);

				for($i=0;$i<count($stock_ids);$i++)
				{					
					$stk_id = $stock_ids[$i];
					$rasData = $mydb->getArray('sp_per_tab,sales','tbl_stock','id='.$stk_id);
					$sp_per_tab = $rasData['sp_per_tab'];
					$prev_sales = $rasData['sales'];
					$data1 = '';
					$data1['sales_id']=$sales_id;
					$data1['medicine_id'] = $medicine_id;
					$data1['medicine_name'] = $medicine;
					$data1['medicine_type']=$type;
					$data1['refill_day']=$refill_day;
					$data1['quantity'] = $sales_qty[$i];//120
					$data1['stock_id']=$stock_ids[$i];

					$data1['Rate']=round($sp_per_tab,2);
					$data1['total_amount']=round($data1['Rate']*$data1['quantity'],2);

					//print_r($data1); die();
					$mydb->insertQuery('tbl_order',$data1);
					//echo "<br>".$mydb->insertQuery('tbl_order',$data1,'1');

					//update tbl_stock with sales quantity STARTS HERE
					$stock_val=$prev_sales+$sales_qty[$i];
					$data='';
					$data['sales']=$stock_val;
					$mydb->updateQuery('tbl_stock',$data,'id='.$id);
					//echo "<br>".$mydb->updateQuery('tbl_stock',$data,'id='.$stk_id,'1');
					//update tbl_stock with sales quantity ENDS HERE
				}
				//Copy from tbl_orderreview to tbl_order ENDS HERE
			}
		}
	}

	//update tbl_sales with status and delete from tbl_orderreview STARTS HERE
	$data2='';
	$data2['review_status'] = '1';
	$data2['order_status'] = '0';
	if($refill_day!=''){
		$data2['refill_day']=$refill_day;
	}
	$mydb->updateQuery('tbl_sales',$data2,'id='.$sales_id);
	//echo "<br><br>".$mydb->updateQuery('tbl_sales',$data2,'id='.$sales_id,'1');
	$mydb->deleteQuery('tbl_orderreview','sales_id='.$sales_id);
	//echo "<br>".$mydb->deleteQuery('tbl_orderreview','sales_id='.$sales_id,'1');
	//update tbl_sales with status and delete from tbl_orderreview STARTS HERE

	$url = ADMINURLPATH.'sales';
	$mydb->redirect($url);
}
?>