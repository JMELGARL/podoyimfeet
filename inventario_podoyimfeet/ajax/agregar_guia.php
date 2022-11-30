<?php

include("is_logged.php");//Archivo comprueba si el usuario esta logueado
$_SESSION['currency_id']=intval($_REQUEST['currency_id']);
$referral_guide_id= intval($_REQUEST['referral_guide_id']);
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['descuento'])){$discount=intval($_POST['descuento']);}	

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../currency.php");//Archivo que obtiene los datos de la moneda
	
	
	
	
if (!empty($id) and !empty($qty) and !empty($unit_price))
{
	$user_id=$_SESSION['user_id'];
	$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
	$stock_actual=get_stock($id, $id_sucursal);
	$stock_actual=intval($stock_actual);
	$qty=intval($qty);
	
	$is_service= is_service($id);
	

	if ($stock_actual>=$qty or $is_service==1 ){
	$default_currency=get_id("business_profile","currency_id","id",1); 
	$default_currency_code=get_id("currencies","code","id",$default_currency); 
	$currency_id=intval($_REQUEST['currency_id']);
	$actual_currency_code=get_id("currencies","code","id",$currency_id);
	$converter_price= currencyConverter($default_currency_code, $actual_currency_code,$unit_price);
	$unit_price=$converter_price;
	
	$insert=mysqli_query($con, "INSERT INTO referral_guide_product (referral_guide_id,product_id,qty,discount,unit_price) VALUES ('$referral_guide_id','$id','$qty','$discount','$unit_price')");
	} else {
		echo "<script>alert('Stock insuficiente.')</script>";
	}
	
}
if (isset($_POST['barcode'])){
	$user_id=$_SESSION['user_id'];
	$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
	$barcode=($_POST['barcode']);
	$barcode_qty=intval($_POST['barcode_qty']);
	$product_id=get_id('products','product_id','product_code',$barcode);
	
	if ($product_id>0){
		$stock_actual=get_stock($product_id, $id_sucursal);
		$stock_actual=intval($stock_actual);
		$is_service= is_service($product_id);
		$unit_price=get_id('products','selling_price','product_id',$product_id);
		
		if ($stock_actual>=$barcode_qty or $is_service==1 ){
		$default_currency=get_id("business_profile","currency_id","id",1); 
		$default_currency_code=get_id("currencies","code","id",$default_currency); 
		$currency_id=$_SESSION['currency_id']; 
		$actual_currency_code=get_id("currencies","code","id",$currency_id);
		$converter_price= currencyConverter($default_currency_code, $actual_currency_code,$unit_price);
		$unit_price=$converter_price;
		
		$insert=mysqli_query($con, "INSERT INTO referral_guide_product (referral_guide_id,product_id,qty,discount,unit_price)
		VALUES ('$referral_guide_id','$product_id','$barcode_qty','0','$unit_price')");
	} else {
		echo "<script>alert('Stock insuficiente.')</script>";
	}
	} else {
		echo "<script>alert('Producto no encontrado.')</script>";
	}
	
	
	
}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$referral_guide_product_id=intval($_GET['id']);	
$delete=mysqli_query($con, "DELETE FROM referral_guide_product WHERE id='".$referral_guide_product_id."'");
}


if (isset($_GET['taxes'])){
	$_SESSION['includes_tax']= intval($_GET['taxes']);
}


	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
	/*Fin datos empresa*/

	$includes_tax=$_SESSION['includes_tax'];
	$currency_id=intval($_REQUEST['currency_id']);
	
	/* datos de la moneda*/
		$array_moneda=get_currency($currency_id);
		$precision_moneda=$array_moneda['currency_precision'];
		$simbolo_moneda=$array_moneda['currency_symbol'];
		$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
		$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
		$currency_name=$array_moneda['currency_name'];
	/*Fin datos moneda*/
	
	
?>

<div class="table-responsive">
<table class="table">
<tr>
	<th>CODIGO</th>
	<th class='text-center'>CANT.</th>
	<th>DESCRIPCION</th>
	<th><span class="pull-right">PRECIO UNIT.</span></th>
	<th><span class="pull-right">DESCUENTO</span></th>
	<th><span class="pull-right">PRECIO TOTAL</span></th>
	<th></th>
</tr>
<?php
	$sumador_total=0;
	$sumador_descuento=0;
	$sql=mysqli_query($con, "select * from products, referral_guide_product where products.product_id=referral_guide_product.product_id and  referral_guide_product.referral_guide_id='$referral_guide_id' ");
	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$referral_guide_product_id=$row["id"];
	$product_code=$row['product_code'];
	$qty=$row['qty'];
	$product_name=$row['product_name'];
	$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');
	$porcentaje=$row['discount'] / 100;
	$precio_total=$unit_price*$qty;
	$total_descuento=$precio_total*$porcentaje;//Total descuento
	$total_descuento=number_format($total_descuento,$precision_moneda,'.','');//Formateo de numeros sin separador de miles (,)
	$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
	$sumador_descuento+=$total_descuento;//Sumador descuento
	$sumador_total+=$precio_total;//Sumador
	
		?>
		<tr>
			<td><?php echo $product_code;?></td>
			<td class='text-center'><?php echo $qty;?></td>
			<td><?php echo $product_name;?></td>
			<td><span class="pull-right"><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td><span class="pull-right"><?php echo number_format($total_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td><span class="pull-right"><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td ><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $referral_guide_product_id ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
		</tr>		
		<?php
		
	}
	
	$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
	$sumador_descuento=number_format($sumador_descuento,$precision_moneda,'.','');
	$total_neto=$total_parcial-$sumador_descuento;
	$total_neto=number_format($total_neto,$precision_moneda,'.','');
	
	


	
	if ($includes_tax==0){
		$total_iva=($total_neto*$tax) / 100;
		$total_iva=number_format($total_iva,$precision_moneda,'.','');
	} else if ($includes_tax==1){
		$tax_value=$tax/100 + 1;
		$tax_value=number_format($tax_value,$precision_moneda,'.','');	
		$neto=$total_neto/$tax_value;
		$neto=number_format($neto,$precision_moneda,'.','');
		$total_iva=$total_neto-$neto;
		$total_neto=number_format($neto,$precision_moneda,'.','');
		$total_iva=number_format($total_iva,$precision_moneda,'.','');		
	}	
		$total_cotizacion=$total_neto+$total_iva;
		$update=mysqli_query($con,"update quotes set subtotal='$total_neto', tax='$total_iva', total='$total_cotizacion' where referral_guide_id='$referral_guide_id'");
	
	
?>


<tr>
	<td colspan=5><span class="pull-right">PARCIAL <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_parcial,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">DESCUENTO <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($sumador_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right"><?php echo ucfirst(neto_txt);?> <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right"><?php echo strtoupper(tax_txt);?> <?php echo "$simbolo_moneda";?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right"><?php echo ucfirst(total_txt);?> <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_cotizacion,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
</table>
</div>

