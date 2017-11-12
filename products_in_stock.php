<?php
    $product=mysql_query("SELECT DISTINCT description from tbl_stock");
    $product_count=mysql_num_rows($product);
?>


	<div class="row">
	<div class="col-md-8">
		<div class="table-responsive ">
		<table class="table table-bordered products">
            <?php if($product_count>0)
            { ?>
                <h2 class="in-stock"><span><i class="fa fa-cubes"></i></span>Products In Stock</h2>
                <tr>
                <th width="15%">S.N.</th>
                <th width="60%">Product Name</th>
                <th width="25%">Amount in stock</th>
        	
       	</tr>
                <?php
                $count=0;
                while($result1=$mydb->fetch_array($product))
                {
                    $result2=mysql_query("SELECT item_description, sum(stock-sales)as total from tbl_stock where description='".$result1['description']."'");

                    while($result3=$mydb->fetch_array($result2))
                    {?>

                        <tr>
                            <td><?php echo ++$count;?></td>
                            <td><?php echo $result3['item_description'];?></td>
                            <td><?php echo $result3['total']?></td>
                        </tr>

    <?php
                    }
                }


                ?>


<?php } else{echo '<td>No product in stock</td>';}?>

		</table>
		</div>
	</div>
</div>