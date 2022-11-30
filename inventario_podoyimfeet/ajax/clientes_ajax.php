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
	$modulo="Clientes";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	$query_validate=mysqli_query($con,"select customer_id from sales where customer_id='".$id."'");
	$count=mysqli_num_rows($query_validate);
		if ($count==0)
		{
			if($delete=mysqli_query($con, "DELETE FROM customers WHERE id='$id'") and $delete2=mysqli_query($con,"DELETE FROM contacts WHERE client_id='$id' ")){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";	
				save_log('Clientes','Eliminación de datos',$_SESSION['user_id']);
			}else{
				$aviso="Aviso!";
				$msj="Error al eliminar los datos ".mysqli_error($con);
				$classM="alert alert-danger";
				$times="&times;";					
			}
		}
		else
		{
			$aviso="Aviso!";
			$msj="Error al eliminar los datos. El cliente se encuentra vinculado al modulo de ventas";
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
	$tables="customers";
	$campos="*";
	$sWhere=" name LIKE '%".$query."%'";
	$sWhere.=" order by id desc";
	
	
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
	$reload = './permisos.php';
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
				<h3 class="box-title">Listado de Clientes</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				  <div class="table-responsive">	
					<table class="table table-condensed table-hover table-striped">
						<tr>
							<th>ID</th>
							<th>RUC </th>
							<th>Cliente </th>
							<th>Direcci&oacute;n </th>
							<th>Contacto </th>
							<th>Agregado</th>
							
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$id=$row['id'];
							$tax_number=$row['tax_number'];
							$name=$row['name'];
							$work_phone=$row['work_phone'];
							$website=$row['website'];
							$date_added=$row['created_at'];
							list($date,$hora)=explode(" ",$date_added);
							list($Y,$m,$d)=explode("-",$date);
							$fecha=$d."-".$m."-".$Y;
							$sql_contacto=mysqli_query($con,"select first_name, last_name, phone, email from contacts where client_id='$id'");
							$rw=mysqli_fetch_array($sql_contacto);
							$contact=$rw['first_name']." ".$rw['last_name'];
							$phone=$rw['phone'];
							$email=$rw['email'];
							
							
							$address1=$row['address1'];
							$city=$row['city'];
							$state=$row['state'];
							$country_id=$row['country_id'];
							
							$sql_country=mysqli_query($con,"select name from countries  where id='$country_id'");
							$rw_country=mysqli_fetch_array($sql_country);
							$country_name=utf8_encode($rw_country['name']);
							if (!empty($country_name) and !empty($state)){	$signo_coma=",";}
							else {$signo_coma=" ";}
							
							$replace_http=str_replace("https://","",$website);
							$replace_http=str_replace("http://","",$replace_http);
							$finales++;
						?>	
						<tr>
							<td><?php echo $id;?></td>
							<td><?php echo $tax_number;?></td>
							<td>
								<?php echo $name;?><br>
								<?php if (!empty($work_phone)){?>
								<i class='fa fa-phone'></i> <?php echo $work_phone;?>
								<?php } ?>
								<?php if (!empty($website)){?>
								<br><i class='fa fa-globe'></i> <a href="http://<?php echo $replace_http;?>" target="_blank"> <?php echo $website;?></a>
								<?php } ?>
							</td>
							<td>
								<?php
								echo $address1." ".$city." <br>";
								echo $state."$signo_coma ".$country_name;
								?>
							</td>
							<td>
								<i class='fa fa-user'></i> <?php echo $contact;?><br>
								<?php if (!empty($phone)){?>
								<i class='fa fa-phone'></i> <?php echo $phone;?>
								<?php }?>
								<?php if (!empty($email)){?>
								<br><i class='fa fa-envelope'></i> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a>
								<?php }?>
							</td>
							
							<td><?php echo $fecha;?></td>
							
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="#" data-toggle="modal" data-target="#cliente_edit" onclick="editar('<?php echo $id;?>');"><i class='fa fa-edit'></i> Editar</a></li>
									<?php }
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
		  
