<?php 
			session_start();
			error_reporting(0);
			include('classes/call.php');
								

			$ids=$_GET['useridentifcation'];
			 if($ids==''||$ids==0)
			 {

			 }else
			 {
					$result=$mydb->getQuery('*','tbl_image','client_id='.$ids);
					$count=mysql_num_rows($result);
					$output=array();
					if($count>0)
					 {
					 	
						$i = 0;
					 while($rasValue=$mydb->fetch_array($result))
					  {

					  	$image_id=$rasValue['id'];
						$image_name=$rasValue['image_name'];
						$image_path=$rasValue['image_path'];
						$_SESSION['image_name'][$i]=$image_name;
						$_SESSION['image_path'][$i]=$image_path;
						$_SESSION['image_ids'][$i]=$image_id;
								
								
							
						 	           $output[]='<label class="radio-inline">
								            <input type="radio" name="image_display" id="image_display" value="'.$_SESSION['image_ids'][$i].'" required>
								             <img style="width:50px;height:60px;" src="../'. $_SESSION['image_path'][$i].'/'. $_SESSION['image_name'][$i].'" alt="">
							 	      </label>';


							 	      $i++;

					}

					 $data=array("value1"=>$output); 
					 echo json_encode($data);
			
			}else
			 {
			 	
				$output='<label for="exampleInputFile" class="user" style="font-size:14px;padding-top:5px;padding-right:70px;">No Prescription is to display</label>';
				 $data=array("value1"=>$output); 
				echo json_encode($data);
			 }
		}

?>