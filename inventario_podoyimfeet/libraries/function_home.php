<?php
function recently_products(){
	global $con;
	$id_moneda=get_id('business_profile','currency_id','id','1');
	$array_moneda=get_currency($id_moneda);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	
	$sql=mysqli_query($con,"select * from  products, manufacturers where products.manufacturer_id=manufacturers.id order by product_id desc limit 0, 5");
	?>
	<ul class="products-list product-list-in-box">
	<?php
	while ($rw=mysqli_fetch_array($sql)){
		$product_id=$rw['product_id'];
		$product_name= $rw['product_name'];
		$selling_price= $rw['selling_price'];
		$model= $rw['model'];
		$image_path	= $rw['image_path'];
		?>
		<li class="item">
            <div class="product-img">
                <img src="<?php echo $image_path;?>" alt="Product Image">
            </div>
            <div class="product-info">
				<a href="edit_product.php?id=<?php echo $product_id;?>" class="product-title"><?php echo $product_name;?> <span class="label label-info pull-right"><?php echo number_format($selling_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></a>
                <span class="product-description">
                    <?php echo $model;?>
                </span>
            </div>
        </li><!-- /.item -->		
		<?php
	}
	?>
	</ul>
	<?php
	
}
function latest_sales(){
	global $con;
	$id_moneda=get_id('business_profile','currency_id','id','1');
	$array_moneda=get_currency($id_moneda);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	
	$sql=mysqli_query($con,"select * from sales order by sale_id desc limit 0,10");
	while ($rw=mysqli_fetch_array($sql)){
		$sale_id=$rw['sale_id'];
		$sale_number=$rw['sale_number'];
		$customer_id=$rw['customer_id'];
		$sql_customer=mysqli_query($con,"select name from customers where id='".$customer_id."'");
		$rw_customer=mysqli_fetch_array($sql_customer);
		$customer_name=$rw_customer['name'];
		$date_added=$rw['sale_date'];
		list($date,$hora)=explode(" ",$date_added);
		list($Y,$m,$d)=explode("-",$date);
		$fecha=$d."-".$m."-".$Y;
		$total=$rw['total'];
		?>
	<tr>
        <td><a href="edit_sale.php?id=<?php echo $sale_id;?>"><?php echo $sale_number;?></a></td>
        <td><?php echo $customer_name;?></td>
        <td><?php echo $fecha;?></td>
        <td class='text-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
    </tr>
		<?php
		
	}
}

function sum_sales(){
	global $con;
	$id_moneda=get_id('business_profile','currency_id','id','1');
	$array_moneda=get_currency($id_moneda);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	
	$year=date('Y');
	$sql=mysqli_query($con,"select sum(total) as total from sales where year(sale_date) = $year ");
	$rw=mysqli_fetch_array($sql);
	echo $total=number_format($rw['total'],$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);
}
function count_sales(){
	global $con;
	$id_moneda=get_id('business_profile','currency_id','id','1');
	$array_moneda=get_currency($id_moneda);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	
	$year=date('Y');
	$sql=mysqli_query($con,"select COUNT(*) as count from sales where year(sale_date) = $year ");
	$rw=mysqli_fetch_array($sql);
	echo $count=$rw['count'];
}	
function sum_purchases(){
	global $con;
	$id_moneda=get_id('business_profile','currency_id','id','1');
	$array_moneda=get_currency($id_moneda);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	
	$year=date('Y');
	$sql=mysqli_query($con,"select sum(total) as total from purchases where year(purchase_date) = $year ");
	$rw=mysqli_fetch_array($sql);
	echo $total=number_format($rw['total'],$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);
}
function count_purchases(){
	global $con;
	$id_moneda=get_id('business_profile','currency_id','id','1');
	$array_moneda=get_currency($id_moneda);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	
	$year=date('Y');
	$sql=mysqli_query($con,"select COUNT(*) as count from purchases where year(purchase_date) = $year ");
	$rw=mysqli_fetch_array($sql);
	echo $count=$rw['count'];
}
function sum_inventory(){
	global $con;
	$id_moneda=get_id('business_profile','currency_id','id','1');
	$array_moneda=get_currency($id_moneda);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	
	$sql=mysqli_query($con,"select sum(products.buying_price*inventory.product_quantity) as total from products,  inventory where products.product_id=inventory.product_id");
	$rw=mysqli_fetch_array($sql);
	echo $sum=number_format($rw['total'],$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);
}
function count_stock(){
	global $con;
	$sql=mysqli_query($con,"select COUNT(*) as count from inventory where product_quantity >0 ");
	$rw=mysqli_fetch_array($sql);
	echo $count=$rw['count'];
}
function count_customers(){
	global $con;
	$id_moneda=get_id('business_profile','currency_id','id','1');
	$array_moneda=get_currency($id_moneda);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	
	$sql=mysqli_query($con,"select COUNT(*) as count from customers ");
	$rw=mysqli_fetch_array($sql);
	echo $count=number_format($rw['count'],$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);
} 
function new_customers(){
	global $con;
	$year=date('Y');
	$month=date('m');
	$sql=mysqli_query($con,"select COUNT(*) as count from customers where year(created_at) = '$year' and month(created_at)= '$month' ");
	$rw=mysqli_fetch_array($sql);
	echo $count=$rw['count'];
}
function sum_sales_month($month){
	global $con;
	$year=date('Y');
	$sql=mysqli_query($con,"select SUM(total) as total from sales where year(sale_date) = '$year' and month(sale_date)= '$month' ");
	$rw=mysqli_fetch_array($sql);
	echo $total=number_format($rw['total'],2,'.','');
	}
function sum_purchases_month($month){
	global $con;
	$year=date('Y');
	$sql=mysqli_query($con,"select SUM(total) as total from purchases where year(purchase_date) = '$year' and month(purchase_date)= '$month' ");
	$rw=mysqli_fetch_array($sql);
	echo $total=number_format($rw['total'],2,'.','');
	}
	
?>