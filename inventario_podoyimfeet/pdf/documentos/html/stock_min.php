<style type="text/css">
<!--
div.zone
{
    border: solid 0.5mm red;
    border-radius: 2mm;
    padding: 1mm;
    background-color: #FFF;
    color: #440000;
}
div.zone_over
{
    width: 30mm;
    height: 20mm;
    
}
.bordeado
{
	border: solid 0.5mm #eee;
	border-radius: 1mm;
	padding: 0mm;
	font-size:12px;
}
.table {
  border-spacing: 0;
  border-collapse: collapse;
}
.table-bordered td, .table-bordered th {
  padding: 3px;
  text-align: left;
  vertical-align: top;
}
.table-bordered {
  border: 0px solid #eee;
  border-collapse: separate;
  
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}
.left{
	border-left: 1px solid #eee;
	
}
.top{
	border-top: 1px solid #eee;
}
.bottom{
	border-bottom: 1px solid #eee;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}

-->
</style>
<page backtop="20mm" backbottom="15mm" backleft="5mm" backright="5mm" style="font-size: 13px; font-family: helvetica" backimg="">
	<?php 
	$title_report='Reporte de stock mínimo';
	include('page_header_footer.php');
	
	?>

	
	

	
	
	
  
    <table class="table-bordered" style="width:100%;font-size:11px" cellspacing=0>
        <tr>
			<th class='top bottom'  style="width: 10%;text-align:center">Código</th>
			<th class='top bottom'  style="width: 10%;text-align:center">Modelo</th>
			<th class='top bottom'  style="width: 20%;text-align:center">Producto</th>
			<th class='top bottom'  style="width: 10%;text-align:center">Fabricante</th>
            <th class='top bottom'  style="width: 10%;text-align:center">Categoría</th>
			<th class='top bottom'  style="width: 10%;text-align:center">Estado</th>
			<th class='top bottom'  style="width: 10%;text-align:center">Stock mínimo</th>
            <th class='top bottom'  style="width: 10%;text-align:center">Stock total</th>
			<th class='top bottom'  style="width: 10%;text-align:center">Precio</th>
        </tr>
		<?php

		while($row=mysqli_fetch_array($query)){
			$product_id=$row['product_id'];
			$product_code=$row['product_code'];
			$model=$row['model'];
			$product_name=$row['product_name'];
			$manufacturer_name=$row['name'];
			$category_id=$row['category_id'];
			$category_name=get_id('categories','name','id',$category_id);
			$status=$row['status'];
			$selling_price=$row['selling_price'];
			$image_path=$row['image_path'];
			if ($status==1){
				$lbl_status="Activo";
				$lbl_class='label label-success';
			}else {
				$lbl_status="Inactivo";
				$lbl_class='label label-danger';
			}		
			$get_all_stock=get_all_stock($product_id);
			$get_all_stock=intval($get_all_stock);	
			$stock_min=get_id('products','stock_min','product_id',$product_id);
			$stock_min=intval($stock_min);
			
			?>
				<tr>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $product_code;?></td>
					<td class='bottom' style="width: 10%;"><?php echo $model;?></td>
					<td class='bottom' style="width: 20%;text-align:left"> <?php echo $product_name;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $manufacturer_name;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $category_name;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $lbl_status;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $stock_min;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $get_all_stock;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo number_format($selling_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					
				</tr>
			<?php 
		}
		
		?>
	
	 </table>
</page>

