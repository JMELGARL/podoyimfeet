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
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
	$customer_id=mysqli_real_escape_string($con,(strip_tags($_REQUEST['customer_id'], ENT_QUOTES)));
	$tables="referral_guides";
	$campos="*";
	
	$sWhere = "  referral_guides.status=0";
	 $sWhere .= " and referral_guides.customer_id='$customer_id'"; 
		 
	$sWhere .= " and referral_guides.number like '%$query%'";
	$sWhere .= " and referral_guides.customer_id >0";	 
	$sWhere.=" order by referral_guides.id desc";
	
	
	include 'pagination2.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = 10; //how much records you want to show
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

				<div class="table-responsive">
					<table class="table  table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>#</th>
							<th>Fecha</th>
							<th>Dirección de partida </th>
							<th>Dirección de llegada </th>
							<th>Unidad de transporte </th>
							<th>Transportista</th>
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
							$finales++;
						?>	
						<tr>
							<td class='text-center'><a href='edit_referral_guide.php?id=<?php echo $id;?>' target='_blank'><?php echo $number;?></a></td>
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
							<td ><span class="pull-right"><a href="#" onclick="agregar_guia('<?php echo $id ?>','<?php echo $number?>'); load2(1);"><i class="glyphicon glyphicon-shopping-cart " style="font-size:24px;color: #5CB85C;"></i></a></span></td>
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

				

	
	
	<?php	
	}	
}
?>          
		  
