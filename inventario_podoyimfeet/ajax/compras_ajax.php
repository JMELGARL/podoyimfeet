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
	$purchase_id=intval($id);
	$type_document=1;
	$total_purchase=get_id('purchases','total','purchase_id',$purchase_id);
	$payment_method=get_id('purchases','payment_method','purchase_id',$purchase_id);
	//$apply_to=get_id('purchases','apply_to','purchase_id',$purchase_id);
	
	$date_added=date("Y-m-d H:i:s");
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	$sql=mysqli_query($con, "select * from products, purchase_product where products.product_id=purchase_product.product_id and purchase_product.purchase_id='$purchase_id'");
	while ($rw=mysqli_fetch_array($sql)){
		$purchase_product_id=$rw['purchase_product_id'];
		$product_id=$rw['product_id'];
		$qty=$rw['qty'];
		$branch_id=$rw['branch_id'];
		if ($type_document==1){
			remove_inventory($product_id,$qty,$branch_id);//Regresa los productos al inventario
		} else if ($type_document==2){
			add_inventory($product_id,$qty,$branch_id);//Agrega los productos al inventario
			
		}
		if ($type_document==2){
			$insert=mysqli_query($con,"INSERT INTO payments (payment_id, purchase_id, total, payment_date, payment_type, number_reference, note, user_id) VALUES (NULL, '$apply_to', '-$total_purchase', '$date_added', '$payment_method', '', '', '$user_id ');");
		}
		$delete1=mysqli_query($con,"delete from purchase_product where purchase_product_id='".$purchase_product_id."'");//Elimina el item de la tabla purchase_product
	}
	$sql2=mysqli_query($con,"select * from payments where purchase_id='".$purchase_id."'");
	while($rw2=mysqli_fetch_array($sql2)){
		$payment_id=$rw2['payment_id'];
		$transaction_id=$rw2['transaction_id'];
		$del=mysqli_query($con,"delete from payments where payment_id='$payment_id'");
		balance_null($transaction_id,$user_id);
		change_status($transaction_id);
	}
	if($delete=mysqli_query($con, "DELETE FROM purchases WHERE purchase_id='".$purchase_id."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";
			save_log('Compras','Eliminación de datos',$_SESSION['user_id']);		
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
	$supplier_id=intval($_REQUEST['supplier_id']);
	$status=intval($_REQUEST['status']);
	
	$tables="purchases,  users";
	$campos="purchases.currency_id, purchases.purchase_date, purchases.purchase_id, purchases.purchase_order_number, users.fullname, purchases.status, purchases.subtotal, purchases.tax, purchases.total, purchases.supplier_id, purchases.due_date";
	$sWhere="users.user_id=purchases.purchase_by";
	$sWhere.=" and purchases.purchase_order_number LIKE '%".$query."%'";
	if ($supplier_id>0){
		$sWhere.=" and purchases.supplier_id = '".$supplier_id."'";
	}
	if ($status>0){
		$sWhere.=" and purchases.status = '".$status."'";
	}
	
	$sWhere.=" order by purchases.purchase_id desc";
	
	
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
	$reload = './purchase_list.php';
	//main query to fetch the data
	$update=mysqli_query($con,"UPDATE purchases SET status=3  WHERE due_date<now() and status!=1");
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
				<h3 class="box-title">Listado de Compras</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>Doc. Nº</th>
							<th class='text-center'>Fecha </th>
							<th>Proveedor</th>
							<th>Usuario </th>
							<th>Estado</th>
							<th class='text-right'>Monto </th>
							<th class='text-right'>Monto pendiente</th>
							<th class='text-center'>Vence</th>
							
							<th></th>
						</tr>
						<?php 
						$finales=0;
						$today_date=date('Ymd');
						$today_date=intval($today_date);
						while($row = mysqli_fetch_array($query)){	
							$purchase_id=$row['purchase_id'];
							$purchase_order_number=$row['purchase_order_number'];
							$currency_id=$row['currency_id'];
							/* datos de la moneda*/
								$array_moneda=get_currency($currency_id);
								$precision_moneda=$array_moneda['currency_precision'];
								$simbolo_moneda=$array_moneda['currency_symbol'];
								$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
								$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
							/*Fin datos moneda*/
							$date_added=$row['purchase_date'];
							$user_fullname=$row['fullname'];
							$subtotal=$row['subtotal'];
							$tax=$row['tax'];
							$total=$row['total'];
							$supplier_id=$row['supplier_id'];
							$sql_supplier=mysqli_query($con,"select name from suppliers where id='".$supplier_id."'");
							$rw_supplier=mysqli_fetch_array($sql_supplier);
							$supplier_name=$rw_supplier['name'];
							list($date,$hora)=explode(" ",$date_added);
							list($Y,$m,$d)=explode("-",$date);
							$fecha=$d."-".$m."-".$Y;	
							$status=$row['status'];
							if ($status==1){$text_status="Pagada";$label_class="label-success";}
							else if ($status==2){$text_status="Pendiente";$label_class="label-warning";}
							else if ($status==3){$text_status="Vencida";$label_class="label-danger";}
							
							if ($status!=1){
								$sum_payment=sum_payment($purchase_id);	
								$pendiente=$total-$sum_payment;
								$due_date=date("d/m/Y", strtotime($row['due_date']));
								 
								
							} else {
								$pendiente=0;
								$due_date="";
								
								
							} 
							
							
							
							$finales++;
						?>	
						<tr>
							<td class='text-center'><?php echo $purchase_order_number;?></td>
							<td class='text-center'><?php echo $fecha;?></td>
							<td><?php echo $supplier_name;?></td>
							<td><?php echo $user_fullname;?></td>
							<td><label class='label <?php echo $label_class;?> '><?php echo $text_status;?></label></td>
							<td ><?php echo $simbolo_moneda; ?><span class='pull-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td ><?php echo $simbolo_moneda; ?><span class='pull-right'><?php echo number_format($pendiente,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td class='text-center'><?php echo $due_date;?></td>
							
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_purchase.php?id=<?php echo $purchase_id;?>" ><i class='fa fa-edit'></i> Editar</a></li>
									<li><a href="#" data-target="#pagosModal" data-toggle="modal" data-id="<?php echo $purchase_id; ?>"><i class='fa fa-dollar'></i> Pagos</a></li>
									<?php } if ($permisos_ver==1){?>
									<li><a href="purchase-print.php?id=<?php echo $purchase_id;?>" target='_blank'><i class='fa fa-print'></i> Imprimir</a></li>
									<?php }?>
									<?php 
									if ($permisos_eliminar==1){
									?>
									<li><a href="#" onclick="eliminar('<?php echo $purchase_id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
									<?php }?>
								</ul>
							</div><!-- /btn-group -->
                    		</td>
						</tr>
						<?php }?>
						<tr>
							<td colspan='9'>
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
		  
