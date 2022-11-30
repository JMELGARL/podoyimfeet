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
	$modulo="Guias";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$referral_guide_id=intval($id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	
	if($delete=mysqli_query($con, "DELETE FROM  referral_guides WHERE id='".$referral_guide_id."'") and $delete2=mysqli_query($con, "DELETE FROM  referral_guide_product WHERE referral_guide_id='".$referral_guide_id."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";
				save_log('Guías de remisión','Eliminación de datos',$_SESSION['user_id']);	
			}else{
				$aviso="Aviso!";
				$msj="Error al eliminar los datos ".mysqli_error($con);
				$classM="alert alert-danger";
				$times="&times;";					
			}
		
		
	} else {//No cuenta con los permisos
		$aviso="Acceso denegado!";
		$msj="No cuentas con los permisos necesario para acceder a este m&oacute;dulo.";
		$classM="alert alert-danger";
		$times="&times;";
	}
}


if (isset($_GET['id_anular'])){
	$id_null=intval($_GET['id_anular']);
	$motivo_anular =mysqli_real_escape_string($con,(strip_tags($_REQUEST['motivo_anular'], ENT_QUOTES)));
	$fecha=date("Y-m-d H:i:s");
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
		
		$sql1="UPDATE referral_guide_product SET qty='0' where referral_guide_id='$id_null'";
		$sql2="UPDATE referral_guides SET status='2' where id='$id_null'";
		if ($update1=mysqli_query($con,$sql1) and $update2=mysqli_query($con,$sql2)){
			$aviso="Bien hecho!";
			$msj="Datos anulados satisfactoriamente.";
			$classM="alert alert-success";
			$times="&times;";
			save_log('Guías de remisión','Anulación de datos',$_SESSION['user_id']);
		} else {
			$aviso="Aviso!";
			$msj="Error al anular los datos ".mysqli_error($con);
			$classM="alert alert-danger";
			$times="&times;";
		}
		
		
		ingresar_documento($id_null,$motivo_anular,$fecha,'referral_guides');
		
	} else {
		$aviso="Acceso denegado!";$msj="No cuentas con los permisos necesario para acceder a este módulo.";	$classM="alert alert-danger";$times="&times;";
	}
}




$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$daterange = mysqli_real_escape_string($con,(strip_tags($_REQUEST['range'], ENT_QUOTES)));
	@$status_guia=intval($_REQUEST['status_guia']);
	$customer_id=mysqli_real_escape_string($con,(strip_tags($_REQUEST['customer_id'], ENT_QUOTES)));
	$tables="referral_guides";
	$campos="*";
	if (!empty($daterange)){
		list ($f_inicio,$f_final)=explode(" - ",$daterange);//Extrae la fecha inicial y la fecha final en formato español
		list ($dia_inicio,$mes_inicio,$anio_inicio)=explode("/",$f_inicio);//Extrae fecha inicial 
		$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio 00:00:00";//Fecha inicial formato ingles
		list($dia_fin,$mes_fin,$anio_fin)=explode("/",$f_final);//Extrae la fecha final
		$fecha_final="$anio_fin-$mes_fin-$dia_fin 23:59:59";
		} else {
			$fecha_inicial=date("Y-m")."-01 00:00:00";
			$fecha_final=date("Y-m-d H:i:s");
		}
	$sWhere = "  referral_guides.created_at between '$fecha_inicial' and '$fecha_final' ";
	
		 if ($customer_id!=""){
			 $sWhere .= " and referral_guides.customer_id='$customer_id'"; 
		 }
	if ($status_guia!=2){
		$sWhere .= " and referral_guides.status='$status_guia'"; 
	}	 
	$sWhere .= " and referral_guides.number like '%$query%'";
	$sWhere .= " and referral_guides.customer_id >0";	 
	$sWhere.=" order by referral_guides.id desc";
	
	
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
	$reload = './quotes.php';
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	
	if (isset($_REQUEST["id"]) or isset($_REQUEST["id_anular"])){
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
				<h3 class="box-title">Listado de Guías de Remisión</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table  table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>#</th>
							<th>Fecha</th>
							<th>Dirección de partida </th>
							<th>Dirección de llegada </th>
							<th>Unidad de transporte </th>
							<th>Transportista</th>
							<th class='text-center'>Comp. #</th>
							<th class='text-center'>Estado</th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$id=$row['id'];
							$created_at=$row['created_at'];
							$fecha=date("d/m/Y", strtotime($created_at));
							
							
							
							$branch_id=$row['branch_id'];
							$customer_id=$row['customer_id'];
							$address1=get_id('customers','address1','id',$customer_id);
							$city=get_id('customers','city','id',$customer_id);
							$state=get_id('customers','state','id',$customer_id);
							$sender_address=get_id('branch_offices','address','id',$branch_id);
							$receiver_address="$address1";
							if (!empty($city)){
								$receiver_address.=", $city";
							} 
							if (!empty($state)){
								$receiver_address.=", $state";
							}
							$sender=get_id('branch_offices','name','id',$branch_id);
							$receiver=get_id('customers','name','id',$customer_id);
							$transport=$row['transport'];
							$carrier=$row['carrier'];
							$estado="NO";
							$number=$row['number'];
							$comprobante=$row['comprobante'];
							$status=$row['status'];
							if ($status==0){
								$txt_status='No facturado';
								$class_label='label-warning';
							} elseif  ($status==1){
								$txt_status='Facturado';
								$class_label='label-success';
							} elseif ($status==2){
								$txt_status='Anulado';
								$class_label='label-danger';
							}
							$finales++;
						?>	
						<tr>
							<td class='text-center'><?php echo $number;?></td>
							<td><?php echo $fecha;?></td>
							<td>
								<i class='fa fa-user'></i> <?php echo $sender;?><br>
								<i class='fa fa-map-marker'></i> <?php echo $sender_address;?>
							</td>
							<td>
								<i class='fa fa-user'></i> <?php echo $receiver;?><br>
								<i class='fa fa-map-marker'></i> <?php echo $receiver_address;?>
							</td>
							<td ><?php echo $transport;?></td>
							<td ><?php echo $carrier;?></td>
							<td class='text-center'><?php echo $comprobante;?></td>
							<td class='text-center'><label  class='label <?php echo $class_label;?>'><?php echo $txt_status;?></label></td>
							
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_referral_guide.php?id=<?php echo $id;?>"><i class='fa fa-edit'></i> Editar</a></li>
									<?php }
									if ($permisos_ver){
										?>
									<li><a href="#" onclick="imprimir('<?php echo $id;?>')"><i class='fa fa-print'></i> Imprimir</a></li>	
										<?php
									}
									if ($permisos_eliminar==1){
									?>
									<?php 
									if($status==0)
									{
									?>
									<li><a href="#" data-toggle="modal" data-target="#anularModal" data-guia_id="<?php echo $id?>"><i class='fa fa-window-close'></i> Anular</a></li>
									<?php }?>
									<li><a href="#" onclick="eliminar('<?php echo $id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
									<?php }?>
								</ul>
							</div><!-- /btn-group -->
                    		</td>
						</tr>
						<?php }?>	
						<tr>
							<td colspan=9> 
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
		  
