<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");
	require_once ("../config/conexion.php");
	require_once ("../libraries/inventory.php");
	//Inicia Control de Permisos
	include("../config/permisos.php");
	$user_id = $_SESSION['user_id'];
	get_cadena($user_id);
	$modulo="Finanzas";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
		
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
		$nulled = balance_null($id,$user_id);
			if($nulled){
				$aviso="Bien hecho!";
				$msj="Transacción anulada satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";	
				change_status($id);
				save_log('Finanzas','Anulación de datos',$_SESSION['user_id']);
			}else{
				$aviso="Aviso!";
				$msj="Error al anular los datos ".mysqli_error($con);
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
	$daterange = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$tables="finances";
	$campos="*";
	$sWhere="";
	if (!empty($daterange)){
		list ($f_inicio,$f_final)=explode(" - ",$daterange);//Extrae la fecha inicial y la fecha final en formato espa?ol
		list ($dia_inicio,$mes_inicio,$anio_inicio)=explode("/",$f_inicio);//Extrae fecha inicial 
		$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio 00:00:00";//Fecha inicial formato ingles
		list($dia_fin,$mes_fin,$anio_fin)=explode("/",$f_final);//Extrae la fecha final
		$fecha_final="$anio_fin-$mes_fin-$dia_fin 23:59:59";
		
		$sWhere .= " created_at between '$fecha_inicial' and '$fecha_final' ";
	}
	
	
	$sWhere.=" order by id asc";
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
	$reload = './finances.php';
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
				<h3 class="box-title">Listado de movimientos de efectivo</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<tr>
							<th>ID </th>
							<th class='text-center'>Fecha </th>
							<th class='text-center'>Hora </th>
							<th>Descripción</th>
							<th class='text-right'>Egresos</th>
							<th class='text-right'>Ingresos</th>
							<th class='text-right'>Saldo</th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){
							$id=$row['id'];	
							$fecha=date("d/m/Y", strtotime($row['created_at']));
							$hora=date("H:i:s", strtotime($row['created_at']));
							$description=$row['description'];
							$type=$row['type'];
							$amount=$row['amount'];
							$status=$row['status'];
							if ($type==1){
								$txt_type='Ingresos';
								$abono=number_format($amount,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);
							} else {
								$abono="";
							} 
							
							if ($type==2){
								$txt_type='Egresos';
								$cargo=number_format($amount,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);
							} else {
								$cargo="";
							}
							
							$balance=$row['balance'];
							$finales++;
						?>	
						<tr>
							<td><?php echo $id;?></td>
							<td class='text-center'><?php echo $fecha;?></td>
							<td class='text-center'><?php echo $hora;?></td>
							<td><?php echo $description;?></td>
							<td><span class='pull-right'><?php echo $cargo;?></span></td>
							<td><span class='pull-right'><?php echo $abono;?></span></td>
							<td><span class='pull-right'><?php echo number_format($balance,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td class='text-right'>
							<?php 
								if ($status==1){
							?>		
							
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="#" data-toggle="modal" data-target="#modal_update" data-id='<?php echo $id;?>' data-description='<?php echo $description;?>'><i class='fa fa-edit'></i> Editar</a></li>
									<?php }
									if ($permisos_eliminar==1){
									?>
									<li><a href="#" onclick="eliminar('<?php echo $id;?>')"><i class='fa fa-trash'></i> Anular</a></li>
									<?php }?>
								</ul>
							</div><!-- /btn-group -->
								<?php } else {
									echo "Anulado";
								}?>
                    		</td>
						</tr>
						<?php }?>	
						<tr>
							<td colspan='8'> 
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
		  
