<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");
	require_once ("../config/conexion.php");
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../currency.php");//Archivo que obtiene los datos de la moneda
	//Inicia Control de Permisos
	include("../config/permisos.php");
	$user_id = $_SESSION['user_id'];
	get_cadena($user_id);
	$modulo="Ordenes";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$order_id=intval($id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	
	if($delete=mysqli_query($con, "DELETE FROM orders WHERE order_id='".$order_id."'") and $delete2=mysqli_query($con, "DELETE FROM order_product WHERE order_id='".$order_id."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";
				save_log('Ordenes de servicio','Eliminación de datos',$_SESSION['user_id']);	
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
	$customer_id=intval($_REQUEST['customer_id']);
	$status=mysqli_real_escape_string($con,(strip_tags($_REQUEST['status'], ENT_QUOTES)));
	$tables="orders, customers, users";
	$campos="orders.order_number, orders.currency_id, orders.order_id, orders.order_date, orders.customer_id, orders.status, orders.subtotal, orders.tax, orders.product_description, orders.brand, orders.model, customers.name, customers.work_phone, customers.website, users.fullname ";
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
	$sWhere="orders.customer_id=customers.id and orders.employee_id=users.user_id";
	$sWhere .= " and orders.order_date between '$fecha_inicial' and '$fecha_final' ";
	if ($customer_id>0){
			$sWhere .= " and orders.customer_id='$customer_id'"; 
		 }
		 if ($status!=""){
			 $sWhere .= " and orders.status='$status'"; 
		 }
		 if (!empty($query)){
			 $sWhere .= " and orders.order_id = '$query'";
		 }
		 
	$sWhere.=" order by orders.order_id desc";
	
	
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
	$reload = './orders.php';
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

	?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
				<h3 class="box-title">Listado de Ordenes de Servicio</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>#</th>
							<th>Fecha</th>
							<th>Cliente </th>
							<th>Técnico </th>
							<th>Equipo </th>
							<th>Estado </th>
							<th class='text-right'>Neto </th>
							<th class='text-right'>IGV</th>
							<th class='text-right'>Total</th>
							
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$order_id=$row['order_id'];
							$order_number=$row['order_number'];
							$order_date=$row['order_date'];
							$fecha=date("d/m/Y", strtotime($order_date));
							$name=$row['name'];
							$work_phone=$row['work_phone'];
							$website=$row['website'];
							$customer_id=$row['customer_id'];
							$sql_contacto=mysqli_query($con,"select first_name, last_name, phone, email from contacts where client_id='$customer_id'");
							$rw=mysqli_fetch_array($sql_contacto);
							$contact=$rw['first_name']." ".$rw['last_name'];
							$phone=$rw['phone'];
							$email=$rw['email'];
							$fullname=$row['fullname'];
							$status=$row['status'];
							$subtotal=number_format($row['subtotal'],2,'.','');
							$tax=number_format($row['tax'],2,'.','');
							$total=$subtotal+$tax;
							if ($status==1){$estado="En proceso";$label="label-warning";}
							else if ($status==2) {$estado="Presupuesto";$label="label-info";}
							else if ($status==3) {$estado="Reparado";$label="label-success";}
							else if ($status==4) {$estado="No reparado";$label="label-danger";}
							
						
							$product_description=$row['product_description'];
							$brand=$row['brand'];
							$model=$row['model'];
							$replace_http=str_replace("https://","",$website);
							$replace_http=str_replace("http://","",$replace_http);
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
							<td class='text-center'><?php echo $order_number;?></td>
							<td><?php echo $fecha;?></td>
							
							<td>
								<?php echo $name;?><br>
								<?php if (!empty($work_phone)){?>
								<i class='fa fa-phone'></i> <?php echo $work_phone;?>
								<?php } ?>
								<?php if (!empty($website)){?>
								<br><i class='fa fa-globe'></i> <a href="http://<?php echo $replace_http;?>" target="_blank"> <?php echo $website;?></a>
								<?php } ?>
							</td>
							<td ><?php echo $fullname;?></td>
							<td>
								<?php echo $product_description." ".$model;?><br>
								<small class='text-muted'><?php echo $brand;?></small>
							</td>
							<td >
								<span class="label <?php echo $label;?>"><?php echo $estado;?></span>
							</td>
							<td ><?php echo $simbolo_moneda?><span class='pull-right'><?php echo number_format($subtotal,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td ><?php echo $simbolo_moneda?><span class='pull-right'><?php echo number_format($tax,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td ><?php echo $simbolo_moneda?><span class='pull-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							
							
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_order.php?id=<?php echo $order_id;?>"><i class='fa fa-edit'></i> Editar</a></li>
									<?php }
									if ($permisos_eliminar==1){
									?>
									<li><a href="#" onclick="eliminar('<?php echo $order_id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
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
		  
