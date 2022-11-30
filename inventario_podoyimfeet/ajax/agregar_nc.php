<?php

include("is_logged.php");//Archivo comprueba si el usuario esta logueado
$note_id= $_SESSION['note_id'];

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../currency.php");//Archivo que obtiene los datos de la moneda



	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
	/*Fin datos empresa*/
	$includes_tax=get_id("credit_notes","includes_tax","id",$note_id);
	$currency_id=get_id("credit_notes","currency_id","id",$note_id);
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
	
</tr>
<?php
	$sumador_total=0;
	$sumador_descuento=0;
	$sql=mysqli_query($con, "select * from products, note_product where products.product_id=note_product.product_id and note_product.id='$note_id'");
	$count_sql=mysqli_num_rows($sql);

	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$sale_product_id=$row["id"];
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
	//$update=mysqli_query($con,"update sales set subtotal='$total_neto', tax='$total_iva', total='$total_compra' where sale_id='$sale_id'");
	if ($total_parcial>0){
		$percent=($sumador_descuento / $total_parcial) * 100;
	} else {
		$percent=0;
	}
	
	?>

<?php if ($sumador_descuento>0){?>
<tr>
	<td colspan=4><span class="pull-right">PARCIAL <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_parcial,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	
</tr>

<tr>
	<td colspan=4><span class="pull-right">DESCUENTO <?php echo number_format($percent,2);?>% <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($sumador_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	
</tr>
<?php }?>
<tr>
	<td colspan=5><span class="pull-right"><?php echo ucfirst(neto_txt);?>  <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	
</tr>
<tr>
	<td colspan=5><span class="pull-right"><?php echo strtoupper(tax_txt);?> <?php echo "$simbolo_moneda";?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	
</tr>
<tr>
	<td colspan=5><span class="pull-right"><?php echo ucfirst(total_txt);?> <?php echo $simbolo_moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
	
</tr>
</table>

<div class="col-xs-12">
	<a href="note-print-pdf.php?id=<?php echo $note_id;?>" target="_blank" class="btn btn-success pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generar PDF</a>
</div>
