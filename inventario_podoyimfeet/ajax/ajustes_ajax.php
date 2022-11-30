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
	$modulo="Ajustes";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	$sql=mysqli_query($con, "select * from products, inventory_tweaks_product where products.product_id=inventory_tweaks_product.product_id and inventory_tweaks_product.inventory_tweak_id='$id'");
	while ($rw=mysqli_fetch_array($sql)){
		$id_detail=$rw['id'];
		$product_id=$rw['product_id'];
		$qty=$rw['qty'];
		$branch_id=$rw['branch_id'];
		$get_type=get_id('inventory_tweaks','type','id',$id);
		if ($get_type==1){
			remove_inventory($product_id,$qty,$branch_id);//Elimina los productos del inventario
		} elseif ($get_type==2){
			add_inventory($product_id,$qty,$branch_id);//Ingresa los productos al inventario
		}
		$delete1=mysqli_query($con,"delete from inventory_tweaks_product where id='".$id_detail."'");//Elimina el item de la tabla 
	}
	
	if($delete=mysqli_query($con, "DELETE FROM inventory_tweaks WHERE id='".$id."'") ){
		$aviso="Bien hecho!";
		$msj="Datos eliminados satisfactoriamente.";
		$classM="alert alert-success";
		$times="&times;";
		save_log('Ajustes','Eliminaci&oacute;n de datos',$_SESSION['user_id']);	
	}else{
		$aviso="Aviso!";
		$msj="Error al eliminar los datos ".mysqli_error($con);
		$classM="alert alert-danger";
		$times="&times;";					
	}
		
		
	} else {//No cuenta con los permisos
		$aviso="Acceso denegado!";
		$msj="No cuentas con los permisos necesario para acceder a este m?dulo.";
		$classM="alert alert-danger";
		$times="&times;";
	}
}
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$daterange = mysqli_real_escape_string($con,(strip_tags($_REQUEST['range'], ENT_QUOTES)));
	$type=intval($_REQUEST['type']);
	$tables="inventory_tweaks,  users";
	$campos="inventory_tweaks.note, inventory_tweaks.created_at, inventory_tweaks.id, inventory_tweaks.number_reference, users.fullname,inventory_tweaks.type, inventory_tweaks.subtotal, inventory_tweaks.tax, inventory_tweaks.total";
	$sWhere="users.user_id=inventory_tweaks.user_id";
	$sWhere.=" and inventory_tweaks.number_reference LIKE '%".$query."%'";
	if ($type>0){
		$sWhere.=" and inventory_tweaks.type = '".$type."'";
	}
	
	if (!empty($daterange)){
		list ($f_inicio,$f_final)=explode(" - ",$daterange);//Extrae la fecha inicial y la fecha final en formato espa?ol
		list ($dia_inicio,$mes_inicio,$anio_inicio)=explode("/",$f_inicio);//Extrae fecha inicial 
		$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio 00:00:00";//Fecha inicial formato ingles
		list($dia_fin,$mes_fin,$anio_fin)=explode("/",$f_final);//Extrae la fecha final
		$fecha_final="$anio_fin-$mes_fin-$dia_fin 23:59:59";
		} else {
			$fecha_inicial=date("Y-m")."-01 00:00:00";
			$fecha_final=date("Y-m-d H:i:s");
		}
		$sWhere .= " and inventory_tweaks.created_at between '$fecha_inicial' and '$fecha_final' ";
	$sWhere.=" order by inventory_tweaks.id desc";
	
	
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
	$reload = './inventory_tweaks.php';
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
				<h3 class="box-title">Listado de ajustes de inventario</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>Nº de referencia</th>
							<th class='text-center'>Fecha </th>
							<th>Usuario </th>
							<th>Nota</th>
							<th>Tipo</th>
							<th class='text-right'>Total </th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						$today_date=date('Ymd');
						$today_date=intval($today_date);
						while($row = mysqli_fetch_array($query)){	
							$id=$row['id'];
							$number_reference=$row['number_reference'];
							$note=$row['note'];
							
							$date_added=$row['created_at'];
							$user_fullname=$row['fullname'];
							$subtotal=$row['subtotal'];
							$tax=$row['tax'];
							$total=$row['total'];
							$fecha=date("d/m/Y", strtotime($date_added));	
							$type=$row['type'];
							if ($type==1){$text_status="Ingreso";$label_class="label-success";}
							else if ($type==2){$text_status="Salida";$label_class="label-warning";}
							$finales++;
						?>	
						<tr>
							<td class='text-center'><?php echo $number_reference;?></td>
							<td class='text-center'><?php echo $fecha;?></td>
							<td><?php echo $user_fullname;?></td>
							<td><?php echo $note;?></td>
							<td><label class='label <?php echo $label_class;?> '><?php echo $text_status;?></label></td>
							<td class='text-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
								
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_adjustment.php?id=<?php echo $id;?>"><i class='fa fa-edit'></i> Editar</a></li>
									<?php } 
									if ($permisos_ver){
										?>
									<li><a href="#" onclick="imprimir('<?php echo $id;?>')"><i class='fa fa-print'></i> Imprimir</a></li>	
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
							<td colspan='7'>
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
		  
