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
	$modulo="Cortes";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id_corte=intval($id);
	
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
		$transaction_id=get_id('cashier_closing','transaction_id','id',$id_corte);
		if($delete=mysqli_query($con, "DELETE FROM cashier_closing WHERE id='".$id_corte."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";
				
				balance_null($transaction_id,$user_id);	
				save_log('Cortes de caja','Eliminación de datos',$_SESSION['user_id']);
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
	$cashbox_id=intval($_REQUEST['cashbox_id']);
	$branch_id=intval($_REQUEST['branch_id']);
	
	$tables="cashier_closing, users";
	$campos="*";
	$sWhere="users.user_id=cashier_closing.user_id";
	if ($cashbox_id>0){
		$sWhere.=" and cashier_closing.cashbox_id = '".$cashbox_id."'";
	}
	if ($branch_id>0){
		$sWhere.=" and cashier_closing.branch_id = '".$branch_id."'";
	}
	
	$sWhere.=" order by cashier_closing.id desc";
	
	
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
	$reload = './cashier_closing.php';
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
				<h3 class="box-title">Listado de cortes de caja</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th>Fecha inicial</th>
							<th>Fecha final</th>
							<th>Sucursal </th>
							<th>Caja</th>
							<th>Cajero </th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$id=$row['id'];
							$date_initial=date("d/m/Y H:i:s", strtotime($row['date_initial']));
							$date_final=date("d/m/Y H:i:s", strtotime($row['date_final']));
							$branch_id=$row['branch_id'];
							$branch_office=get_id('branch_offices','name','id',$branch_id);
							$cashbox_id=$row['cashbox_id'];
							$cashbox_name=get_id('cashbox','cashbox_name','id',$cashbox_id);
							$fullname=$row['fullname'];
							$finales++;
						?>	
						<tr>
							<td ><?php echo $date_initial;?></td>
							<td ><?php echo $date_final;?></td>
							<td ><?php echo $branch_office;?></td>
							<td ><?php echo $cashbox_name;?></td>
							<td ><?php echo $fullname;?></td>
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php 
									if ($permisos_ver){
										?>
									<li><a href="#" onclick="ver_corte('<?php echo $id;?>')"><i class='fa fa-file-pdf-o'></i> Ver corte</a></li>	
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
		  
