<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");
	require_once ("../config/conexion.php");
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	//Inicia Control de Permisos
	include("../config/permisos.php");
	$user_id = $_SESSION['user_id'];
	get_cadena($user_id);
	$modulo="Productos";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
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
	
	
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables where $sWhere ");
	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($con);}
	$total_pages = ceil($numrows/$per_page);
	$reload = './inventory.php';
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	
	
	
	if ($numrows>0){
		include("../currency.php");//Archivo que obtiene los datos de la moneda
	?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
				<h3 class="box-title">Listado de productos en inventario</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>Código</th>
							<th>Producto </th>
							<th>Fabricante </th>
					<?php
						$sucursales=list_branch_offices();
						while($rw_suc=mysqli_fetch_array($sucursales)){
							?>
							<th class='text-center'><?php echo $rw_suc['code']?></th>
							<?php
						}
					?>
							<th class='text-center'>Total</th>
							<th class='text-right'>Precio</th>
							
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$product_id=$row['product_id'];
							$product_code=$row['product_code'];
							$model=$row['model'];
							$product_name=$row['product_name'];
							$manufacturer_name=$row['name'];
							$selling_price=$row['selling_price'];
							$image_path=$row['image_path'];
							$get_all_stock=get_all_stock($product_id);
							$get_all_stock=intval($get_all_stock);	
							$stock_min=get_id('products','stock_min','product_id',$product_id);
							$stock_min=intval($stock_min);	
							if ($stock_min>$get_all_stock){
								$class_txt="danger";
							} else {
								$class_txt="";
							}						
							$finales++;
						?>	
						<tr class='<?php echo $class_txt;?>'>
							<td class='text-center'><?php echo $product_code;?></td>
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
							<td class='text-center'><?php echo number_format($sum_stock,2);?></td>
							<td class='text-right'><?php echo number_format($selling_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
						</tr>
						<?php }?>
									
					</table>
					
				</div>
				<div class='row'>	
					<div class='col-md-6'>
						<?php 
							$inicios=$offset+1;
							$finales+=$inicios -1;
							
							echo "Mostrando $inicios al $finales de $numrows registros";
						?>
					</div>
					<div class='col-md-6'>
						<?php 
							echo paginate($reload, $page, $total_pages, $adjacents);
						?>
					</div>	
				</div>		
				</div><!-- /.box-body -->
				
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->	
	<?php	
	}	
}
?>          
		  
