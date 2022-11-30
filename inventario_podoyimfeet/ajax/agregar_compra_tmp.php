<?php

session_start();
$user_id=$_SESSION['user_id'];
if (isset($_POST['id'])){$id=intval($_POST['id']);}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['id_sucursal'])){intval($id_sucursal=$_POST['id_sucursal']);}

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../currency.php");//Archivo que obtiene los datos de la moneda
if (!empty($id) and !empty($qty) and !empty($unit_price) and !empty($id_sucursal))
{
	$default_currency=get_id("business_profile","currency_id","id",1); 
	$default_currency_code=get_id("currencies","code","id",$default_currency); 
	$currency_id=$_SESSION['currency_id']; 
	$actual_currency_code=get_id("currencies","code","id",$currency_id);
	$converter_price= currencyConverter($default_currency_code, $actual_currency_code,$unit_price);
	$unit_price=$converter_price;
	
	add_tmp($id, $qty, $unit_price, $user_id,0,$id_sucursal);

//add_inventory($id, $qty);//Agrego producto al inventario
//update_buying_price($id,$unit_price);//Actualizo precio de compra
//update_selling_price($id,$unit_price);//Actualizo precio de venta
}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$id_tmp=intval($_GET['id']);	
remove_tmp($id_tmp);
}
if (isset($_GET['taxes'])){
	$_SESSION['includes_tax']= intval($_GET['taxes']);
}
if (isset($_GET['currency_id'])){
	$_SESSION['currency_id']= intval($_GET['currency_id']);
}
 $includes_tax=$_SESSION['includes_tax'];
 $currency_id=$_SESSION['currency_id'];
	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
	/*Fin datos empresa*/
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
	<th>SUCURSAL</th>
	<th><span class="pull-right">PRECIO UNIT.</span></th>
	<th><span class="pull-right">PRECIO TOTAL</span></th>
	<th></th>
</tr>
<?php
	$sumador_total=0;
	$sql=mysqli_query($con, "select * from products, product_tmp where products.product_id=product_tmp.product_id and product_tmp.user_id='$user_id'");
	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$id_tmp=$row["id_tmp"];
	$product_code=$row['product_code'];
	$qty=$row['qty'];
	$product_name=$row['product_name'];
	$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');

	$precio_total=$unit_price*$qty;
	$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
	$sumador_total+=$precio_total;//Sumador
	$branch_id=$row['branch_id'];
	$nombre_sucursal=get_id('branch_offices','name','id',$branch_id);
	
		?>
		<tr>
			<td><?php echo $product_code;?></td>
			<td class='text-center'><?php echo $qty;?></td>
			<td><?php echo $product_name;?></td>
			<td><?php echo $nombre_sucursal;?></td>
			<td><span class="pull-right"><?php echo number_format($unit_price,$precision_moneda,'.','');?></span></td>
			<td><span class="pull-right"><?php echo number_format($precio_total,$precision_moneda,'.','');?></span></td>
			<td ><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $id_tmp; ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
		</tr>		
		<?php
		
	}
	$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
	$total_neto=$total_parcial;
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
	
	
	$total_compra=$total_neto+$total_iva;
	$total_compra=number_format($total_compra,$precision_moneda,'.','');
	
?>


<tr>
	<td colspan=5><span class="pull-right">NETO <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_neto,$precision_moneda,'.','');?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">IGV <?php echo "$tax% $simbolo_moneda";?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_iva,$precision_moneda,'.','');?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">TOTAL <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_compra,$precision_moneda,'.','');?></span></td>
	<td></td>
</tr>
</table>

