<?php
require_once ("libraries/inventory.php");//Contiene funcion que controla stock en el inventario
$tipo = 'excel';
$extension = '.xls';
header("Content-type: application/vnd.ms-$tipo");
header("Content-Disposition: attachment; filename=reporte_inventario$extension");
header("Pragma: no-cache");
header("Expires: 0");
?>
<h1>Reporte de Inventario</h1>
<table>
    <thead>
        <tr>
            <th style="text-align:center;">C&oacute;digo</th>
            <th style="text-align:left;">Producto</th>
            <th style="text-align:left;">Fabricante</th>
			<?php
			$sucursales=list_branch_offices();
			while($rw_suc=mysqli_fetch_array($sucursales)){
			?>
			<th style="text-align:center;"><?php echo $rw_suc['code']?></th>
			<?php
			}
			?>
			<th style="text-align:center;">Total</th>
			<th style="text-align:right;">Precio</th>
        </tr>
    </thead>
	<?php
		include("currency.php");//Archivo que obtiene los datos de la moneda
		$product_code = mysqli_real_escape_string($con,(strip_tags($_REQUEST['product_code'], ENT_QUOTES)));
		$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
		$manufacturer_id = intval($_REQUEST['manufacturer_id']);
		$tables="products, manufacturers";
		$campos="products.product_id, products.model, products.product_name, products.status, products.image_path, products. product_code, products.selling_price, manufacturers.name";
		$sWhere="products.manufacturer_id=manufacturers.id";
		$sWhere.=" and products.product_name LIKE '%".$query."%'";
		$sWhere.=" and products.product_code LIKE '%".$product_code."%'";
		if ($manufacturer_id>0){
			$sWhere.=" and products.manufacturer_id = '".$manufacturer_id."'";
		}
		$sWhere.=" order by products.product_id desc";
		$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere");
		while($row = mysqli_fetch_array($query)){	
							$product_id=$row['product_id'];
							$product_code=$row['product_code'];
							$model=$row['model'];
							$product_name=$row['product_name'];
							$manufacturer_name=$row['name'];
							$selling_price=$row['selling_price'];
							$image_path=$row['image_path'];
													

						?>	
						<tr>
							<td style="text-align:center;"><?php echo $product_code;?></td>
							<td><?php echo $product_name;?></td>
							<td><?php echo $manufacturer_name;?></td>
						<?php
						$sucursales=list_branch_offices();
						$sum_stock=0;
						while($rw_suc=mysqli_fetch_array($sucursales)){
							$branch_id=$rw_suc['id']
							?>
							<td class='text-center'><?php  echo $stock=get_stock($product_id,$branch_id);?></td>
							<?php
							$stock=str_replace(",","",$stock);
							$stock=floatval($stock);
							$sum_stock+=$stock;
						}
						?>	
							<td style="text-align:center;"><?php echo number_format($sum_stock,2);?></td>
							<td style="text-align:right;"><?php echo number_format($selling_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
						</tr>
						<?php }?>
</table>   