<?php

include("is_logged.php");//Archivo comprueba si el usuario esta logueado
$quote_id= intval($_REQUEST['quote_id']);
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['descuento'])){$discount=intval($_POST['descuento']);}	

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../currency.php");//Archivo que obtiene los datos de la moneda
	
	#descuento del cliente
	$sql_cliente=mysqli_query($con,"select discount_rate from customers, quotes where customers.id=quotes.customer_id and quotes.quote_id='$quote_id'");
	$rw_cliente=mysqli_fetch_array($sql_cliente);
	#fin descuento cliente
	
	
if (!empty($id) and !empty($qty) and !empty($unit_price))
{
	if ($discount<1){
		$discount=$rw_cliente['discount_rate'];//Aplica el descuento al cliente segun corresponda
	}
	
	$default_currency=get_id("business_profile","currency_id","id",1); 
	$default_currency_code=get_id("currencies","code","id",$default_currency); 
	$currency_id=get_id("quotes","currency_id","quote_id",$quote_id); 
	$actual_currency_code=get_id("currencies","code","id",$currency_id);
	$converter_price= currencyConverter($default_currency_code, $actual_currency_code,$unit_price);
	$unit_price=$converter_price;
	
	$insert=mysqli_query($con, "INSERT INTO quote_product (quote_id,product_id,qty,discount,unit_price) VALUES ('$quote_id','$id','$qty','$discount','$unit_price')");

}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$quote_product_id=intval($_GET['id']);	
$delete=mysqli_query($con, "DELETE FROM quote_product WHERE quote_product_id='".$quote_product_id."'");
}
if (isset($_POST['value'])){
	
	$campo=intval($_POST['campo']);
	if ($campo==1){
		$value=intval($_POST['value']);
		$condicion="customer_id='$value'";
	} else if ($campo==2){
		$value=intval($_POST['value']);
		$condicion="status='$value'";
	} else if ($campo==3){
		$value=mysqli_real_escape_string($con,(strip_tags($_REQUEST['value'], ENT_QUOTES)));
		$condicion="terms='$value'";
	} else if ($campo==4){
		$value=mysqli_real_escape_string($con,(strip_tags($_REQUEST['value'], ENT_QUOTES)));
		$condicion="validity='$value'";
	} else if ($campo==5){
		$value=mysqli_real_escape_string($con,(strip_tags($_REQUEST['value'], ENT_QUOTES)));
		$condicion="delivery='$value'";
	} else if ($campo==6){
		$value=mysqli_real_escape_string($con,(strip_tags($_REQUEST['value'], ENT_QUOTES)));
		$condicion="note='$value'";
	} else if ($campo==7){
		$value=intval($_POST['value']);
		$condicion="employee_id='$value'";
	} else if ($campo==8){
		$value=intval($_POST['value']);
		$condicion="includes_tax='$value'";
	} else if ($campo==9){
		$value=intval($_POST['value']);
		$condicion="currency_id='$value'";
	} else if ($campo==10){
		$value=mysqli_real_escape_string($con,(strip_tags($_REQUEST['value'], ENT_QUOTES)));
		$condicion="note_extra='$value'";
	}
	$update_sale=mysqli_query($con,"update quotes set  $condicion where quote_id='$quote_id'");
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
	$currency_id=get_id("quotes","currency_id","quote_id",$quote_id);
	
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
	$sql=mysqli_query($con, "select * from products, quote_product where products.product_id=quote_product.product_id and quote_product.quote_id='$quote_id'");
	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$quote_product_id=$row["quote_product_id"];
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
			<td ><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $quote_product_id ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
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
		$update=mysqli_query($con,"update quotes set subtotal='$total_neto', tax='$total_iva', total='$total_cotizacion' where quote_id='$quote_id'");
	
	
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
<div class='row'>
	<?php 
		$note_extra=get_id('quotes','note_extra','quote_id',$quote_id);
	?>
	<div class="col-md-6">
        <label>Motivos de la aprobación o no aprobación de la cotización</label>
        <input type="text" class="form-control" value="<?php echo $note_extra;?>" onblur="return quote_update(this.value,10);">
     </div>
</div>
