<?php

include("is_logged.php");//Archivo comprueba si el usuario esta logueado
$sale_id= $_SESSION['sale_id'];
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['descuento'])){floatval($descuento=$_POST['descuento']);}
if (isset($_POST['id_sucursal'])){$id_sucursal=intval($_POST['id_sucursal']);}

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	
if (!empty($id) and !empty($qty) and !empty($unit_price))
{
	$default_currency=get_id("business_profile","currency_id","id",1); 
	$default_currency_code=get_id("currencies","code","id",$default_currency); 
	$currency_id=get_id("sales","currency_id","sale_id",$sale_id); 
	$actual_currency_code=get_id("currencies","code","id",$currency_id);
	$converter_price= currencyConverter($default_currency_code, $actual_currency_code,$unit_price);
	$unit_price=$converter_price;
	
add_sale_product($sale_id,$id,$qty,$descuento,$unit_price);//Guardo los datos en la tabla sale_product
$is_service= is_service($id);
if ($is_service==0){//SINO es un servicio
	remove_inventory($id,$qty,$id_sucursal );//Disminuye la cantidad en el inventario;
}
}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$sale_product_id=intval($_GET['id']);	
$sql_sale=mysqli_query($con,"select product_id, qty from  sale_product where sale_product_id='".$sale_product_id."'");
$rw_sale=mysqli_fetch_array($sql_sale);
$product_id_remove=$rw_sale['product_id'];
$qty_remove=$rw_sale['qty'];
$delete=mysqli_query($con, "DELETE FROM sale_product WHERE sale_product_id='".$sale_product_id."'");
$is_service= is_service($product_id_remove);
$id_sucursal= get_id('sales','branch_id','sale_id',$sale_id);//Obtengo el id de la sucursal

if ($is_service==0){//SINO es un servicio
	add_inventory($product_id_remove, $qty_remove,$id_sucursal);//Agrego producto al inventario
}	
}
if (isset($_POST['key'])){
	$key=intval($_POST['key']);
	if ($key==1){
		$value=intval($_POST['value']);
		$str="type='$value'";
	} else if ($key==2){
		$value=intval($_POST['value']);
		$str="customer_id='$value'";
	} else if ($key==3){
		$value=intval($_POST['value']);
		$days=get_id('payment_methods','days','id',$value);//obtengo los dias
		$created_at=get_id('sales','sale_date','sale_id',$sale_id);//obtengo los dias
		$days=intval($days);
		$due_date=sumardias($created_at,$days);
		if ($days==0){
			$status=1;
		} else {
			$status=2;
		}
		$str="payment_method='$value', due_date='$due_date', status='$status'";
		
	} else if ($key==4){
		$value=intval($_POST['value']);
		$str="seller_id='$value'";
	} else if ($key==5){
		$value=intval($_POST['value']);
		$str="includes_tax='$value'";
	} else if ($key==6){
		$value=intval($_POST['value']);
		$str="currency_id='$value'";
	} else if ($key==7){
		$value=mysqli_real_escape_string($con,(strip_tags($_POST["value"],ENT_QUOTES)));
		$str="guia_number='$value'";
	}
	
	$update_sale=mysqli_query($con,"update sales set $str where sale_id='$sale_id'");
}
	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
	/*Fin datos empresa*/
	$includes_tax=get_id("sales","includes_tax","sale_id",$sale_id);
	$currency_id=get_id("sales","currency_id","sale_id",$sale_id); 
	
	/* datos de la moneda*/
	$array_moneda=get_currency($currency_id);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	$currency_name=$array_moneda['currency_name'];
	/*Fin datos moneda*/
	
?>
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
	$sql=mysqli_query($con, "select * from products, sale_product where products.product_id=sale_product.product_id and sale_product.sale_id='$sale_id'");
	$count_sql=mysqli_num_rows($sql);

	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$sale_product_id=$row["sale_product_id"];
	$product_code=$row['product_code'];
	$qty=$row['qty'];
	$discount=intval($row['discount']);
	$product_name=$row['product_name'];
	$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');
	$precio_total=$unit_price*$qty;
	$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
	$descuento=($precio_total * $discount) / 100;
	$descuento=number_format($descuento,$precision_moneda,'.','');//Descuento Formateado
	$sumador_descuento+=$descuento;//Sumador
	$sumador_total+=$precio_total;//Sumador
	
		?>
		<tr>
			<td><?php echo $product_code;?></td>
			<td class='text-center'><?php echo $qty;?></td>
			<td><?php echo $product_name;?></td>
			<td><span class="pull-right"><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td><span class="pull-right"><?php echo number_format($descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td><span class="pull-right"><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td ><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $sale_product_id ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
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
	
	$total_compra=$total_neto+$total_iva;
	$total_compra=number_format($total_compra,$precision_moneda,'.','');
	$update=mysqli_query($con,"update sales set subtotal='$total_neto', tax='$total_iva', total='$total_compra' where sale_id='$sale_id'");
	if ($total_parcial>0){
		$percent=($sumador_descuento / $total_parcial) * 100;
	} else {
		$percent=0;
	}
	
	?>

<?php if ($sumador_descuento>0){?>
<tr>
	<td colspan=5><span class="pull-right">PARCIAL <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_parcial,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>

<tr>
	<td colspan=5><span class="pull-right">DESCUENTO <?php echo number_format($percent,2);?>% <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($sumador_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<?php }?>
<tr>
	<td colspan=5><span class="pull-right"><?php echo ucfirst(neto_txt);?>  <?php echo $simbolo_moneda;?></span></td>
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
	<td><span class="pull-right"><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
</table>

<div class="col-xs-12">
	<a href="sale-print-pdf.php?id=<?php echo $sale_id;?>" target="_blank" class="btn btn-success pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generar PDF</a>
</div>
