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
	$modulo="Traslados";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$transfer_id=intval($id);
	$origin=get_id('transfers','id_origin','id',$transfer_id);
	$destination=get_id('transfers','id_destination','id',$transfer_id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
		$sql=mysqli_query($con, "select * from products, transfers_product where products.product_id=transfers_product.product_id and transfers_product.transfer_id='$transfer_id'");
	while ($rw=mysqli_fetch_array($sql)){
		$id=$rw['id'];
		$product_id=$rw['product_id'];
		$qty=$rw['qty'];
		add_inventory($product_id,$qty,$origin);//Regresa los productos al inventario
		remove_inventory($product_id,$qty,$destination);//Elimina los productos al inventario
		$delete1=mysqli_query($con,"delete from transfers_product where id='".$id."'");//Elimina el item de la tabla sale_product
	}
	
	if($delete=mysqli_query($con, "DELETE FROM transfers WHERE id='".$transfer_id."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";
			save_log('Traslados','Eliminación de datos',$_SESSION['user_id']);			
		}else{
				$aviso="Aviso!";
				$msj="Error al eliminar los datos ".mysqli_error($con);
				$classM="alert alert-danger";
				$times="&times;";					
		}
		
	} else {//No cuenta con los permisos
		$aviso="Acceso denegado!";
		$msj="No cuentas con los permisos necesario para acceder a este módulo.";
		$classM="alert alert-danger";
		$times="&times;";
	}
}
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$origin=intval($_REQUEST['origin']);
	$destination=intval($_REQUEST['destination']);
	
	$tables="transfers, users";
	$campos="*";
	$sWhere="transfers.user_id=users.user_id";
	if($origin>0){
		$sWhere.=" and transfers.id_origin='$origin'";
	} 
	if($destination>0){
		$sWhere.=" and transfers.id_destination='$destination'";
	} 
	
	$sWhere.=" order by transfers.id desc";
	
	
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
	$reload = './transfers.php';
	//main query to fetch the data
		$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	
	if (isset($_REQUEST["id"])){
	?>
			<div class="<?php echo $classM;?>">
				<button type="button" class="close" data-dismiss="alert"><?php echo $times;?></button>
				<strong><?php echo $aviso?> </strong>
				<?php echo $msj;?>
			</div>	
	<?php
		}
	
	if ($numrows>0){
		include("../currency.php");//Archivo que obtiene los datos de la moneda
	?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
				<h3 class="box-title">Listado de traslados de mercadería</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>Nº</th>
							<th>Sucursal fuente</th>
							<th>Sucursal destino </th>
							<th>Fecha</th>
							<th >Usuario</th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$id=$row['id'];
							$id_origin=$row['id_origin'];
							$sucursal_origen=get_id('branch_offices','name','id',$id_origin);
							$id_destination=$row['id_destination'];
							$sucursal_destino=get_id('branch_offices','name','id',$id_destination);
							$created_at=date("d/m/Y H:i:s", strtotime($row['created_at']));
							$fullname=$row['fullname'];
							$finales++;
						?>	
						<tr>
							<td class='text-center'><?php echo $id;?></td>
							<td ><?php echo $sucursal_origen;?></td>
							<td ><?php echo $sucursal_destino;?></td>
							<td ><?php echo $created_at;?></td>
							<td ><?php echo $fullname;?></td>
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php 
									if ($permisos_ver){
										?>
									<li><a href="#" onclick="transfer_print('<?php echo $id;?>')"><i class='fa fa-print'></i> Imprimir traslado</a></li>	
										<?php
									}
									if ($permisos_eliminar==1){
									?>
									<li><a href="#" onclick="eliminar('<?php echo $id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
									<?php }?>
								</ul>
							</div><!-- /btn-group -->
                    		</td>
						</tr>
						<?php }?>
						<tr>
							<td colspan='10'> 
								<?php 
									$inicios=$offset+1;
									$finales+=$inicios -1;
									echo "Mostrando $inicios al $finales de $numrows registros";
									echo paginate($reload, $page, $total_pages, $adjacents);
								?>
							</td>
						</tr>	
					</table>
				</div>	
				</div><!-- /.box-body -->
				
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->	
	<?php	
	}	
}
?>          
		  
