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
	$modulo="Compras";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$purchase_order_id=intval($id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	
	if($delete=mysqli_query($con, "DELETE FROM  purchases_order WHERE purchase_order_id='".$purchase_order_id."'") and $delete2=mysqli_query($con, "DELETE FROM  purchase_order_product WHERE purchase_order_id='".$purchase_order_id."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";
				save_log('Ordenes de compra','Eliminación de datos',$_SESSION['user_id']);	
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
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$daterange = mysqli_real_escape_string($con,(strip_tags($_REQUEST['range'], ENT_QUOTES)));
	
	$status=mysqli_real_escape_string($con,(strip_tags($_REQUEST['status'], ENT_QUOTES)));
	$tables="purchases_order, suppliers, users";
	$campos="purchases_order.currency_id, purchases_order.purchase_order_id, purchases_order.created_at, suppliers.name,  suppliers.work_phone, users.fullname, purchases_order.status, purchases_order.subtotal, purchases_order.tax";
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
	$sWhere="purchases_order.supplier_id=suppliers.id and purchases_order.employee_id=users.user_id";
	$sWhere .= " and purchases_order.created_at between '$fecha_inicial' and '$fecha_final' ";
	
		 if ($status!=""){
			 $sWhere .= " and purchases_order.status='$status'"; 
		 }
	$sWhere .= " and suppliers.name like '%$query%'";	 
	$sWhere.=" order by purchases_order.purchase_order_id desc";
	
	
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
				<h3 class="box-title">Listado de Ordenes de Compra</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table  table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>#</th>
							<th>Fecha</th>
							<th>Proveedor </th>
							<th>Realizado por </th>
							<th>Estado </th>
							<th class='text-right'>Neto </th>
							<th class='text-right'>IGV</th>
							<th class='text-right'>Total</th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$purchase_order_id=$row['purchase_order_id'];
							$created_at=$row['created_at'];
							$fecha=date("d/m/Y", strtotime($created_at));
							$proveedor=$row['name'];
							$work_phone=$row['work_phone'];
							$fullname=$row['fullname'];
							$status=$row['status'];
							$subtotal=number_format($row['subtotal'],2,'.','');
							$tax=number_format($row['tax'],2,'.','');
							$total=$subtotal+$tax;
							if ($status==0){$estado="Pendiente";$label="label-warning";}
							else if ($status==1) {$estado="Aceptada";$label="label-success";}
							else if ($status==2) {$estado="Rechazada";$label="label-danger";}
							else if ($status==3) {$estado="Compra";$label="label-info";}
							$currency_id=$row['currency_id'];
							/* datos de la moneda*/
								$array_moneda=get_currency($currency_id);
								$precision_moneda=$array_moneda['currency_precision'];
								$simbolo_moneda=$array_moneda['currency_symbol'];
								$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
								$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
							/*Fin datos moneda*/
							$finales++;
						?>	
						<tr>
							<td class='text-center'><?php echo $purchase_order_id;?></td>
							<td><?php echo $fecha;?></td>
							<td>
								<i class='fa fa-user'></i> <?php echo $proveedor;?><br>
								<i class='fa fa-phone'></i> <?php echo $work_phone;?>
							</td>
							<td ><?php echo $fullname;?></td>
							<td >
								<span class="label <?php echo $label;?>"><?php echo $estado;?></span>
							</td>
							<td ><?php echo $simbolo_moneda;?><span class='pull-right'><?php echo number_format($subtotal,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td><?php echo $simbolo_moneda;?><span class='pull-right'><?php echo number_format($tax,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td><?php echo $simbolo_moneda;?><span class='pull-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_purchase_order.php?id=<?php echo $purchase_order_id;?>"><i class='fa fa-edit'></i> Editar</a></li>
									<?php }
									if ($permisos_ver){
										?>
									<li><a href="#" onclick="imprimir('<?php echo $purchase_order_id;?>')"><i class='fa fa-print'></i> Imprimir</a></li>	
										<?php
									}
									if ($permisos_eliminar==1){
									?>
									<li><a href="#" onclick="eliminar('<?php echo $purchase_order_id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
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
		  
