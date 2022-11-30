<?php

include("is_logged.php");//Archivo comprueba si el usuario esta logueado
$purchase_order_id= $_SESSION['purchase_order_id'];
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$qty=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($unit_price=$_POST['precio_venta']);}
if (isset($_POST['id_sucursal'])){intval($id_sucursal=$_POST['id_sucursal']);}

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../currency.php");//Archivo que obtiene los datos de la moneda
	
if (!empty($id) and !empty($qty) and !empty($unit_price))
{
	$default_currency=get_id("business_profile","currency_id","id",1); 
	$default_currency_code=get_id("currencies","code","id",$default_currency); 
	$currency_id=get_id("purchases_order","currency_id","purchase_order_id",$purchase_order_id); 
	$actual_currency_code=get_id("currencies","code","id",$currency_id);
	$converter_price= currencyConverter($default_currency_code, $actual_currency_code,$unit_price);
	$unit_price=$converter_price;
	
$insert=mysqli_query($con, "INSERT INTO purchase_order_product (purchase_order_id,product_id,qty,unit_price,branch_id) VALUES ('$purchase_order_id','$id','$qty','$unit_price','$id_sucursal')");
echo mysqli_error($con);
}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$id=intval($_GET['id']);	
$delete=mysqli_query($con, "DELETE FROM purchase_order_product WHERE id='".$id."'");

}
if (isset($_REQUEST['campo'])){
	$campo=intval($_REQUEST['campo']);
	if ($campo==1){
		$valor=intval($_REQUEST['valor']);
		$str_update="supplier_id='".$valor."'";
	} elseif ($campo==2){
		$valor=intval($_REQUEST['valor']);
		$str_update="status='".$valor."'";
	} elseif ($campo==3){
		$valor= mysqli_real_escape_string($con,(strip_tags($_REQUEST['valor'], ENT_QUOTES)));
		$str_update="terms='".$valor."'";
	} elseif ($campo==4){
		$valor= mysqli_real_escape_string($con,(strip_tags($_REQUEST['valor'], ENT_QUOTES)));
		$str_update="ship_via='".$valor."'";
	} elseif ($campo==5){
		$valor= mysqli_real_escape_string($con,(strip_tags($_REQUEST['valor'], ENT_QUOTES)));
		$str_update="note='".$valor."'";
	} elseif ($campo==6){
		$valor= intval($_REQUEST['valor']);
		$str_update="includes_tax='".$valor."'";
	} elseif ($campo==7){
		$valor= intval($_REQUEST['valor']);
		$str_update="currency_id='".$valor."'";
	}
	$update=mysqli_query($con,"UPDATE purchases_order SET $str_update WHERE purchase_order_id='".$purchase_order_id."'");
	
}
if (isset($_REQUEST['item'])){
	$item=intval($_REQUEST['item']);
	$product_item=intval($_REQUEST['product_item']);
	if ($item==1){
		$valor=intval($_REQUEST['valor']);
		$update=mysqli_query($con,"update purchase_order_product set qty='$valor' where id='$product_item'");
	}
	if ($item==2){
		$valor=mysqli_real_escape_string($con,(strip_tags($_REQUEST['valor'], ENT_QUOTES)));
		$valor=str_replace(",","",$valor);
		$update=mysqli_query($con,"update purchase_order_product set unit_price='$valor' where id='$product_item'");
	}
	if ($item==3){
		$valor=intval($_REQUEST['valor']);
		if ($valor==1){
			$new_value=0;
		} else {
			$new_value=1;
		}
		$update=mysqli_query($con,"update purchase_order_product set oc='$new_value' where id='$product_item'");
	}
	 if ($item==4){
		 $new_value=intval($_REQUEST['valor']);
		$update=mysqli_query($con,"update purchase_order_product set qty_rec='$new_value' where id='$product_item'");
	}
	if ($item==5){
		 $new_value=mysqli_real_escape_string($con,(strip_tags($_REQUEST['valor'], ENT_QUOTES)));
		$update=mysqli_query($con,"update purchase_order_product set status_oc='$new_value' where id='$product_item'");
	}
}

	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
	/*Fin datos empresa*/

	$includes_tax=get_id("purchases_order","includes_tax","purchase_order_id",$purchase_order_id); 
	$currency_id=get_id("purchases_order","currency_id","purchase_order_id",$purchase_order_id);

	/* datos de la moneda*/
	$array_moneda=get_currency($currency_id);
	$precision_moneda=$array_moneda['currency_precision'];
	$simbolo_moneda=$array_moneda['currency_symbol'];
	$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
	$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
	$currency_name=$array_moneda['currency_name'];
	/*Fin datos moneda*/	
	
	$status=get_id("purchases_order","status","purchase_order_id",$purchase_order_id);
	
	if ($status==3){
			$disabled="disabled";
		} else {
			$disabled="";
		}
	
	
?>
<table class="table">
<tr>
	<th></th>
	<th>CODIGO</th>
	<th class='text-center'>CANT. REC.</th>
	<th class='text-center'>CANT.</th>
	<th>DESCRIPCION</th>
	<th>SUCURSAL</th>
	<th>STATUS</th>
	<th><span class="pull-right">PRECIO UNIT.</span></th>
	<th><span class="pull-right">PRECIO TOTAL</span></th>
	<th></th>
</tr>
<?php
	$sumador_total=0;
	$sql=mysqli_query($con, "select * from products,  purchase_order_product where products.product_id=purchase_order_product.product_id and purchase_order_product.purchase_order_id='$purchase_order_id'");
	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$id=$row["id"];
	$product_code=$row['product_code'];
	$qty=$row['qty'];
	$product_name=$row['product_name'];
	$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');

	$precio_total=$unit_price*$qty;
	$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
	$sumador_total+=$precio_total;//Sumador
	$branch_id=$row['branch_id'];
	$nombre_sucursal=get_id('branch_offices','name','id',$branch_id);
	$oc=$row['oc'];
	$status_oc=$row['status_oc'];
	$qty_rec=$row['qty_rec'];
		?>
		<tr>
			<td><input type="checkbox"  <?php if ($oc==1){echo "checked";}?> value="<?php echo $oc; ?>" onclick="actualizar_item(this.value,3,'<?php echo $id;?>')"></td>
			<td><?php echo $product_code;?></td>
			<td class='text-center col-md-1'>
				<input type="number" style="text-align:center" value="<?php echo $qty_rec;?>" class="form-control input-sm" onblur="actualizar_item(this.value,4,'<?php echo $id;?>')" <?php echo $disabled;?>>
			</td>
			<td class='text-center col-md-1'>
				<input type="number" style="text-align:center" value="<?php echo $qty;?>" class="form-control input-sm" onblur="actualizar_item(this.value,1,'<?php echo $id;?>')" <?php echo $disabled;?>>
			</td>
			<td><?php echo $product_name;?></td>
			<td><?php echo $nombre_sucursal;?></td>
			<td class="col-md-2">
				<input type="text"  value="<?php echo $status_oc;?>" class="form-control input-sm" onblur="actualizar_item(this.value,5,'<?php echo $id;?>')" >
			</td>
			<td class="col-md-2">
				<input type="text" style="text-align:right" value="<?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?>" class="form-control input-sm" onblur="actualizar_item(this.value,2,'<?php echo $id;?>')" <?php echo $disabled;?>>
			</td>
			<td><span class="pull-right"><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
			<td >
			<?php 
				if($status!=3){
			?>
				<span class="pull-right"><a href="#" onclick="eliminar('<?php echo $id ?>')"><i class="glyphicon glyphicon-trash"></i></a></span>
			<?php 
				}
			?>
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
	$update=mysqli_query($con,"update purchases_order set subtotal='$total_neto', tax='$total_iva', total='$total_compra' where purchase_order_id='$purchase_order_id'");
?>


<tr>
	<td colspan=6><span class="pull-right"><?php echo ucfirst(neto_txt);?> <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=6><span class="pull-right"><?php echo strtoupper(tax_txt);?><?php echo " $simbolo_moneda";?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=6><span class="pull-right"><?php echo ucfirst(total_txt);?> <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	<td></td>
</tr>
</table>


