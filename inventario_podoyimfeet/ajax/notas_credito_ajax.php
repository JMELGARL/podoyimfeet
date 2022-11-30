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
	$modulo="Ventas";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$note_id=intval($id);
	$id_sucursal= get_id('credit_notes','branch_id','id',$note_id);//Obtengo el id de la sucursal
	$apply_to= get_id('credit_notes','apply_to','id',$note_id);
	$total_venta= get_id('credit_notes','total','id',$note_id);
	
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	$sql=mysqli_query($con, "select * from products, note_product where products.product_id=note_product.product_id and note_product.note_id='$note_id'");
	while ($rw=mysqli_fetch_array($sql)){
		$note_product_id=$rw['id'];
		$product_id=$rw['product_id'];
		$qty=$rw['qty'];
		remove_inventory($product_id,$qty,$id_sucursal);//Elimina los productos del inventario
		$delete1=mysqli_query($con,"delete from note_product where id='".$note_product_id."'");//Elimina el item de la tabla sale_product
	}
	if($delete=mysqli_query($con, "DELETE FROM credit_notes WHERE id='".$note_id."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";	
				save_log('Notas de crédito','Eliminación de datos',$_SESSION['user_id']);
			}else{
				$aviso="Aviso!";
				$msj="Error al eliminar los datos ".mysqli_error($con);
				$classM="alert alert-danger";
				$times="&times;";					
			}
		
		//Ahora se debe realizar un abono al la cuenta por cobrar con signo negativo por se elimino la nota de credito
		$payment_date=date("Y-m-d H:i:s");
		$insert=mysqli_query($con,"INSERT INTO charges (charge_id, sale_id, total, payment_date, payment_type, number_reference, note, user_id) VALUES (NULL, '$apply_to', '-$total_venta', '$payment_date', '4', '', '', '$user_id');");
		
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
	
	$branch_id=intval($_REQUEST['branch_id']);
	$customer_id=intval($_REQUEST['customer_id']);
	
	$tables="credit_notes, users";
	$campos="credit_notes.currency_id, credit_notes.id, credit_notes.note_number, credit_notes.note_prefix, credit_notes.customer_id, credit_notes.created_at, credit_notes.subtotal, users.fullname, credit_notes.tax, credit_notes.total";
	$sWhere="users.user_id=credit_notes.sale_by and credit_notes.transaction_type=2";
	$sWhere.=" and credit_notes.note_number LIKE '%".$query."%'";
	
	if ($branch_id>0){
		$sWhere.=" and credit_notes.branch_id = '".$branch_id."'";
	}
	if ($customer_id>0){
		$sWhere.=" and credit_notes.customer_id = '".$customer_id."'";
	}
	
	$sWhere.=" order by credit_notes.id desc";
	
	
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
	$update=mysqli_query($con,"UPDATE sales SET status=3  WHERE due_date<now() and status!=1");
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
				<h3 class="box-title">Listado de notas de crédito</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>#</th>
							<th class='text-center'>Fecha </th>
							<th>Cliente</th>
							<th>Vendedor </th>
							<th class='text-right'>Total</th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$note_id=$row['id'];
							$note_prefix=$row['note_prefix'];
							$note_number=$row['note_number'];
							$customer_id=$row['customer_id'];
							$sql_customer=mysqli_query($con,"select name from customers where id='".$customer_id."'");
							$rw_customer=mysqli_fetch_array($sql_customer);
							$customer_name=$rw_customer['name'];
							
							$date_added=$row['created_at'];
							$user_fullname=$row['fullname'];
							$subtotal=$row['subtotal'];
							$tax=$row['tax'];
							$total=$row['total'];
							list($date,$hora)=explode(" ",$date_added);
							list($Y,$m,$d)=explode("-",$date);
							$fecha=$d."-".$m."-".$Y;	
								
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
							<td class='text-center'><?php echo "$note_prefix $note_number";?></td>
							<td class='text-center'><?php echo $fecha;?></td>
							<td><?php echo $customer_name;?></td>
							<td><?php echo $user_fullname;?></td>
							
							<td ><?php echo $simbolo_moneda?><span class='pull-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							
							
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_note.php?id=<?php echo $note_id;?>"><i class='fa fa-eyes'></i> Ver detalles</a></li>
									<?php }
									if ($permisos_ver){
										?>
									<li><a href="#" onclick="note_print('<?php echo $note_id;?>')"><i class='fa fa-file-pdf-o'></i> Ver PDF</a></li>	
										<?php
									}
									if ($permisos_eliminar==1){
									?>
									<li><a href="#" onclick="eliminar('<?php echo $note_id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
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
		  
