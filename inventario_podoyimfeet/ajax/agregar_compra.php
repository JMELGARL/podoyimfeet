<?php

include("is_logged.php");//Archivo comprueba si el usuario esta logueado
$purchase_id= $_SESSION['purchase_id'];
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['id_sucursal'])){$id_sucursal=intval($_POST['id_sucursal']);}



	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	
if (!empty($id) and !empty($qty) and !empty($unit_price))
{	
	$default_currency=get_id("business_profile","currency_id","id",1); 
	$default_currency_code=get_id("currencies","code","id",$default_currency); 
	$currency_id=get_id("purchases","currency_id","purchase_id",$purchase_id); 
	$actual_currency_code=get_id("currencies","code","id",$currency_id);
	$converter_price= currencyConverter($default_currency_code, $actual_currency_code,$unit_price);
	$unit_price=$converter_price;
	
$insert=mysqli_query($con, "INSERT INTO purchase_product (purchase_id,product_id,qty,unit_price, branch_id) VALUES ('$purchase_id','$id','$qty','$unit_price','$id_sucursal')");
//add_inventory($id, $qty);//Agrego producto al inventario
add_inventory($id,$qty,$id_sucursal);//Agrego producto al inventario
update_buying_price($id,$unit_price);//Actualizo precio de compra
save_log('Compras','Agregar ítem a  compra',$_SESSION['user_id']);
}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$purchase_product_id=intval($_GET['id']);	
$sql_purchase=mysqli_query($con,"select product_id, qty from  purchase_product where purchase_product_id='".$purchase_product_id."'");
$rw_purchase=mysqli_fetch_array($sql_purchase);
$product_id_remove=$rw_purchase['product_id'];
$qty_remove=$rw_purchase['qty'];
$id_sucursal=intval($_GET['id_sucursal']);
$delete=mysqli_query($con, "DELETE FROM purchase_product WHERE purchase_product_id='".$purchase_product_id."'");
remove_inventory($product_id_remove,$qty_remove,$id_sucursal );//Disminuye la cantidad en el inventario;
save_log('Compras','Elimación de ítem de compra',$_SESSION['user_id']);
}
if (isset($_POST['value'])){
	
	$campo=intval($_POST['campo']);
	if ($campo==1){
		$value=intval($_POST['value']);
		$str_update="supplier_id='$value'";
	}else if ($campo==2){
		$value=mysqli_real_escape_string($con,(strip_tags($_POST["value"],ENT_QUOTES)));
		list ($dia,$mes,$anio)=explode("/",$value);
		$fecha="$anio-$mes-$dia";
		$str_update="purchase_date='$fecha'";
	}else if ($campo==3){
		$value=mysqli_real_escape_string($con,(strip_tags($_POST["value"],ENT_QUOTES)));
		$days=get_id('payment_methods','days','id',$value);//obtengo los dias
		$created_at=get_id('purchases','purchase_date','purchase_id',$purchase_id);//obtengo los dias
		$days=intval($days);
		$due_date=sumardias($created_at,$days);
		if ($days==0){
			$status=1;
		} else {
			$status=2;
		}
				
		$str_update="payment_method='$value', due_date='$due_date', status='$status'";
	} else if ($campo==4){
		$value=mysqli_real_escape_string($con,(strip_tags($_POST["value"],ENT_QUOTES)));
		$str_update="purchase_order_number='$value'";
	}	else if ($campo==5){
		$value=intval($_POST['value']);
		$str_update="includes_tax='$value'";
	} else if ($campo==6){
		$value=intval($_POST['value']);
		$str_update="currency_id='$value'";
	}
	save_log('Compras','Modificación de compra',$_SESSION['user_id']);
	$update_purchase=mysqli_query($con,"update purchases set $str_update where purchase_id='$purchase_id'");
}
	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
	/*Fin datos empresa*/
	
	$includes_tax=get_id("purchases","includes_tax","purchase_id",$purchase_id); 
	$currency_id=get_id("purchases","currency_id","purchase_id",$purchase_id); 
	
	/* datos de la moneda*/
	$array_moneda=get_currency($currency_id);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	$currency_name=$array_moneda['currency_name'];
	/*Fin datos moneda*/
	
	$default_currency=get_id("business_profile","currency_id","id",1); 
	
	
	
	
	
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
	$sql=mysqli_query($con, "select * from products, purchase_product where products.product_id=purchase_product.product_id and purchase_product.purchase_id='$purchase_id'");
	while ($row=mysqli_fetch_array($sql))
	{
	$purchase_product_id=$row["purchase_product_id"];
	$product_id=$row['product_id'];
	
	
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
			<td><span class="pull-right"><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td><span class="pull-right"><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td >
				<span class="pull-right"><a href="#" onclick="eliminar('<?php echo $purchase_product_id ?>','<?php echo $branch_id;?>')"><i class="glyphicon glyphicon-trash"></i></a></span>
			</td>
		</tr>		
		<?php
		
	}
	$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
	$total_neto=$total_parcial;
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
	$update=mysqli_query($con,"update purchases set subtotal='$total_neto', tax='$total_iva', total='$total_compra' where purchase_id='$purchase_id'");
?>


<tr>
	<td colspan=5><span class="pull-right"><?php echo ucfirst(neto_txt);?> <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right"><?php echo strtoupper(tax_txt);?><?php echo " $simbolo_moneda";?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right"><?php echo ucfirst(total_txt);?><?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
</table>

<div class="col-xs-12">
	<a href="purchase-print.php?id=<?php echo $purchase_id;?>" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Imprimir</a>
</div>
