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
	$modulo="Cotizaciones";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$quote_id=intval($id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	
	if($delete=mysqli_query($con, "DELETE FROM quotes WHERE quote_id='".$quote_id."'") and $delete2=mysqli_query($con, "DELETE FROM quote_product WHERE quote_id='".$quote_id."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";	
				save_log('Cotizaciones','Eliminación de datos',$_SESSION['user_id']);
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
	$sale_by=intval($_REQUEST['sale_by']);
	$status=mysqli_real_escape_string($con,(strip_tags($_REQUEST['status'], ENT_QUOTES)));
	$tables="quotes, customers, users";
	$campos="quotes.currency_id, quotes.quote_id, quotes.quote_date, quotes.customer_id, quotes.status, quotes.subtotal, quotes.tax, customers.name, customers.work_phone, customers.website, users.fullname ";
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
	$sWhere="quotes.customer_id=customers.id and quotes.employee_id=users.user_id";
	$sWhere .= " and quotes.quote_date between '$fecha_inicial' and '$fecha_final' ";
	if ($sale_by>0){
			$sWhere .= " and quotes.employee_id='$sale_by'"; 
		 }
		 if ($status!=""){
			 $sWhere .= " and quotes.status='$status'"; 
		 }
	$sWhere .= " and customers.name like '%$query%'";	 
	$sWhere.=" order by quotes.quote_id desc";
	
	
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
		
	?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
				<h3 class="box-title">Listado de Cotizaciones</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>#</th>
							<th>Fecha</th>
							<th>Atenci&oacute;n </th>
							<th>Empresa </th>
							<th>Vendedor </th>
							<th>Estado </th>
							<th class='text-right'>Neto </th>
							<th class='text-right'>IGV</th>
							<th class='text-right'>Total</th>
							
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$quote_id=$row['quote_id'];
							$quote_date=$row['quote_date'];
							$fecha=date("d/m/Y", strtotime($quote_date));
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
							if ($status==0){$estado="No autorizado";$label="label-warning";}
							else if ($status==1) {$estado="Aprobada";$label="label-success";}
							else if ($status==2) {$estado="En revisión";$label="label-info";}
							else  {$estado="Rechazada";$label="label-danger";}
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
							<td class='text-center'><?php echo $quote_id;?></td>
							<td><?php echo $fecha;?></td>
							<td>
								<i class='fa fa-user'></i> <?php echo $contact;?><br>
								<?php if (!empty($phone)){?>
								<i class='fa fa-phone'></i> <?php echo $phone;?>
								<?php }?>
								<?php if (!empty($email)){?>
								<br><i class='fa fa-envelope'></i> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a>
								<?php }?>
							</td>
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
							<td >
								<span class="label <?php echo $label;?>"><?php echo $estado;?></span>
							</td>
							<td ><?php echo $simbolo_moneda;?><span class='pull-right'><?php echo number_format($subtotal,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td ><?php echo $simbolo_moneda;?><span class='pull-right'><?php echo number_format($tax,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td ><?php echo $simbolo_moneda;?><span class='pull-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							
							
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_quote.php?id=<?php echo $quote_id;?>"><i class='fa fa-edit'></i> Editar</a></li>
									<li><a href="#mailModal" data-toggle="modal" data-quote_id="<?php echo $quote_id; ?>" data-email="<?php echo $email;?>" data-subject="COTIZACION Nº <?php echo $quote_id; ?>" ><i class='fa fa-envelope'></i> Enviar cotización</a></li>
									<?php }
									if ($permisos_ver){
										?>
									<li><a href="#" onclick="quote_print('<?php echo $quote_id;?>')"><i class='fa fa-print'></i> Imprimir</a></li>	
										<?php
									}
									if ($permisos_eliminar==1){
									?>
									<li><a href="#" onclick="eliminar('<?php echo $quote_id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
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
		  
