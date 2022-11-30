<?php

include("is_logged.php");//Archivo comprueba si el usuario esta logueado
$adjustment_id= $_SESSION['adjustment_id'];
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['id_sucursal'])){$id_sucursal=intval($_POST['id_sucursal']);}


	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../currency.php");//Archivo que obtiene los datos de la moneda
if (!empty($id) and !empty($qty) and !empty($unit_price))
{
add_inventory_tweaks_product($adjustment_id,$id,$qty,$unit_price,$id_sucursal);//Agrego un registro  a la tabla
$get_type=get_id('inventory_tweaks','type','id',$adjustment_id);//Obtengo el tipo de transaccion
if ($get_type==1){
	add_inventory($id,$qty,$id_sucursal);//Agrego producto al inventario
}elseif ($get_type==2){
	remove_inventory($id,$qty,$id_sucursal);//Elimino producto del inventario
} 


}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$id_detail=intval($_GET['id']);	
$product_id_remove=get_id('inventory_tweaks_product','product_id','id',$id_detail);
$qty_remove=get_id('inventory_tweaks_product','qty','id',$id_detail);
$id_sucursal=get_id('inventory_tweaks_product','branch_id','id',$id_detail);
$get_type=get_id('inventory_tweaks','type','id',$adjustment_id);//Obtengo el tipo de transaccion
$delete=mysqli_query($con, "DELETE FROM inventory_tweaks_product WHERE id='".$id_detail."'");
if ($get_type==1){
	remove_inventory($product_id_remove,$qty_remove,$id_sucursal );//Disminuye la cantidad en el inventario;	
} elseif ($get_type==2){
	add_inventory($product_id_remove,$qty_remove,$id_sucursal);//Agrego producto al inventario
}

}
if (isset($_POST['value'])){
	
	$campo=intval($_POST['campo']);
	if ($campo==1){
		$value=mysqli_real_escape_string($con,(strip_tags($_POST["value"],ENT_QUOTES)));
		$str_update="note='$value'";
	}else if ($campo==2){
		$value=mysqli_real_escape_string($con,(strip_tags($_POST["value"],ENT_QUOTES)));
		$str_update="number_reference='$value'";
	} 	
	$update_purchase=mysqli_query($con,"update inventory_tweaks set $str_update where id='$adjustment_id'");
}
	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
	/*Fin datos empresa*/

	
	
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
	$sql=mysqli_query($con, "select * from products, inventory_tweaks_product where products.product_id=inventory_tweaks_product.product_id and inventory_tweaks_product.inventory_tweak_id='$adjustment_id'");
	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$purchase_product_id=$row["id"];
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
			<td ><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $purchase_product_id ?>','<?php echo $branch_id;?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
		</tr>		
		<?php
		
	}
	$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
	$total_neto=$total_parcial;
	$total_neto=number_format($total_neto,$precision_moneda,'.','');
	$total_iva=($total_neto*$tax) / 100;
	$total_iva=number_format($total_iva,$precision_moneda,'.','');
	$total_compra=$total_neto+$total_iva;
	$total_compra=number_format($total_compra,$precision_moneda,'.','');
	$update=mysqli_query($con,"update inventory_tweaks set subtotal='$total_neto', tax='$total_iva', total='$total_compra' where id='$adjustment_id'");
?>


<tr>
	<td colspan=5><span class="pull-right">NETO <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right"><?php echo strtoupper(tax_txt);?> <?php echo "$tax% $moneda";?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">TOTAL <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
</table>


