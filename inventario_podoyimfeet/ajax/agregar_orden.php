<?php

include("is_logged.php");//Archivo comprueba si el usuario esta logueado
$order_id= intval($_REQUEST['order_id']);
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['descuento'])){$discount=$_POST['descuento'];}	

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../currency.php");//Archivo que obtiene los datos de la moneda
if (!empty($id) and !empty($qty) and !empty($unit_price))
{
	
	$default_currency=get_id("business_profile","currency_id","id",1); 
	$default_currency_code=get_id("currencies","code","id",$default_currency); 
	$currency_id=get_id("orders","currency_id","order_id",$order_id);
	$actual_currency_code=get_id("currencies","code","id",$currency_id);
	$converter_price= currencyConverter($default_currency_code, $actual_currency_code,$unit_price);
	$unit_price=$converter_price;
$insert=mysqli_query($con, "INSERT INTO order_product (order_id,product_id,qty,discount,unit_price) VALUES ('$order_id','$id','$qty','$discount','$unit_price')");

}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$order_product_id=intval($_GET['id']);	
$delete=mysqli_query($con, "DELETE FROM order_product WHERE order_product_id='".$order_product_id."'");
}

if (isset($_GET['taxes'])){
	$taxes=intval($_GET['taxes']);
	$update=mysqli_query($con,"update orders set includes_tax='$taxes' where order_id='$order_id'");
}
if (isset($_GET['currency_id'])){
	$value=intval($_GET['currency_id']);
	$update=mysqli_query($con,"update orders set currency_id='$value' where order_id='$order_id'");
}
	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
	/*Fin datos empresa*/

	$includes_tax=get_id("orders","includes_tax","order_id",$order_id);
	$currency_id=get_id("orders","currency_id","order_id",$order_id);
	
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
	$sql=mysqli_query($con, "select * from products, order_product where products.product_id=order_product.product_id and order_product.order_id='$order_id'");
	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$order_product_id=$row["order_product_id"];
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
			<td ><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $order_product_id ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
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
	} else {
		$tax_value=$tax/100 + 1;
		$tax_value=number_format($tax_value,$precision_moneda,'.','');
		$neto=$total_neto/$tax_value;
		$neto=number_format($neto,$precision_moneda,'.','');
		$total_iva=$total_neto-$neto;
		$total_neto=number_format($neto,$precision_moneda,'.','');
		$total_iva=number_format($total_iva,$precision_moneda,'.','');
	}
		
		$total_cotizacion=$total_neto+$total_iva;
		$update=mysqli_query($con,"update orders set subtotal='$total_neto', tax='$total_iva', total='$total_cotizacion' where order_id='$order_id'");
	
	
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
	<td colspan=5 class='text-right'>
	<select name="is_taxeable" id="is_taxeable" onchange="taxes(this.value,'<?php echo $order_id;?>');">
		<option value="1" <?php if ($includes_tax==1){echo "selected";}?>>Incluye <?php echo strtoupper(tax_txt);?></option>
		<option value="0" <?php if ($includes_tax==0){echo "selected";}?>>No incluye <?php echo strtoupper(tax_txt);?></option>
	</select>
	 <span class="pull-right">&nbsp; <?php echo "$simbolo_moneda";?></span></td>
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

