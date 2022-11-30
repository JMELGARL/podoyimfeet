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
	$sale_id=intval($id);
	$id_sucursal= get_id('sales','branch_id','sale_id',$sale_id);//Obtengo el id de la sucursal
	
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
	$sql=mysqli_query($con, "select * from products, sale_product where products.product_id=sale_product.product_id and sale_product.sale_id='$sale_id'");
	while ($rw=mysqli_fetch_array($sql)){
		$sale_product_id=$rw['sale_product_id'];
		$product_id=$rw['product_id'];
		$qty=$rw['qty'];
		add_inventory($product_id,$qty,$id_sucursal);//Regresa los productos al inventario
		$delete1=mysqli_query($con,"delete from sale_product where sale_product_id='".$sale_product_id."'");//Elimina el item de la tabla sale_product
	}
	if($delete=mysqli_query($con, "DELETE FROM sales WHERE sale_id='".$sale_id."'") ){
				$aviso="Bien hecho!";
				$msj="Datos eliminados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";	
				save_log('Ventas','Eliminación de datos',$_SESSION['user_id']);
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

if (isset($_GET['id_anular'])){
	$id_null=intval($_GET['id_anular']);
	$motivo_anular =mysqli_real_escape_string($con,(strip_tags($_REQUEST['motivo_anular'], ENT_QUOTES)));
	$fecha=date("Y-m-d H:i:s");
	
	
	$id_sucursal= get_id('sales','branch_id','sale_id',$id_null);//Obtengo el id de la sucursal
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
		$sql=mysqli_query($con, "select * from products, sale_product where products.product_id=sale_product.product_id and sale_product.sale_id='$id_null'");
		while ($rw=mysqli_fetch_array($sql)){
			$sale_product_id=$rw['sale_product_id'];
			$product_id=$rw['product_id'];
			$qty=$rw['qty'];
			add_inventory($product_id,$qty,$id_sucursal);//Regresa los productos al inventario
			$update1=mysqli_query($con,"update sale_product set qty='0' where sale_product_id='".$sale_product_id."'");//Elimina el item de la tabla sale_product
		}
		
		
		if($update=mysqli_query($con, "UPDATE sales SET subtotal='0', tax='0', total='0' WHERE sale_id='".$id_null."'") ){
				$aviso="Bien hecho!";
				$msj="Datos anulados satisfactoriamente.";
				$classM="alert alert-success";
				$times="&times;";	
				save_log('Ventas','Anulación de datos',$_SESSION['user_id']);
			}else{
				$aviso="Aviso!";
				$msj="Error al anular los datos ".mysqli_error($con);
				$classM="alert alert-danger";
				$times="&times;";					
			}
		
		
		ingresar_documento($id_null,$motivo_anular,$fecha,'sales');
		
		
	} else {
		$aviso="Acceso denegado!";$msj="No cuentas con los permisos necesario para acceder a este módulo.";	$classM="alert alert-danger";$times="&times;";
	}
}
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$type=intval($_REQUEST['type']);
	$branch_id=intval($_REQUEST['branch_id']);
	$customer_id=intval($_REQUEST['customer_id']);
	$status=intval($_REQUEST['status']);
	$tables="sales, users, type_documents";
	$campos="sales.currency_id, sales.sale_id, sales.sale_number, sales.sale_prefix, sales.customer_id, type_documents.name_document, sales.sale_date, sales.subtotal, users.fullname, sales.tax, sales.total, sales.status, sales.due_date";
	$sWhere="users.user_id=sales.sale_by and sales.type=type_documents.id";
	$sWhere.=" and sales.sale_number LIKE '%".$query."%'";
	if ($type>0){
		$sWhere.=" and sales.type = '".$type."'";
	}
	if ($branch_id>0){
		$sWhere.=" and sales.branch_id = '".$branch_id."'";
	}
	if ($customer_id>0){
		$sWhere.=" and sales.customer_id = '".$customer_id."'";
	}
	if ($status>0){
		$sWhere.=" and sales.status = '".$status."'";
	}
	$sWhere.=" order by sales.sale_id desc";
	
	
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
		
	?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
				<h3 class="box-title">Listado de Ventas</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>#</th>
							<th >Documento</th>
							<th class='text-center'>Fecha </th>
							<th>Cliente</th>
							<th>Vendedor </th>
							<th>Estado </th>
							<th class='text-right'>Total</th>
							<th class='text-right'>Total pend.</th>
							<th class='text-center'>Vence</th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$sale_id=$row['sale_id'];
							$name_document=$row['name_document'];
							$sale_prefix=$row['sale_prefix'];
							$sale_number=$row['sale_number'];
							$customer_id=$row['customer_id'];
							$sql_customer=mysqli_query($con,"select name from customers where id='".$customer_id."'");
							$rw_customer=mysqli_fetch_array($sql_customer);
							$customer_name=$rw_customer['name'];
							
							$date_added=$row['sale_date'];
							$user_fullname=$row['fullname'];
							$subtotal=$row['subtotal'];
							$tax=$row['tax'];
							$total=$row['total'];
							list($date,$hora)=explode(" ",$date_added);
							list($Y,$m,$d)=explode("-",$date);
							$fecha=$d."-".$m."-".$Y;	
							$status=$row['status'];			
							if ($status==1){$text_status="Pagada";$label_class="label-success";}
							else if ($status==2){$text_status="Pendiente";$label_class="label-warning";}
							else if ($status==3){$text_status="Vencida";$label_class="label-danger";}	
							if ($status!=1){
								$sum_payment=sum_charge($sale_id);	
								$pendiente=$total-$sum_payment;
								$due_date=date("d/m/Y", strtotime($row['due_date']));
								 
								
							} else {
								$pendiente=0;
								$due_date="";
								
								
							}
							$currency_id=$row['currency_id'];
							/* datos de la moneda*/
								$array_moneda=get_currency($currency_id);
								$precision_moneda=$array_moneda['currency_precision'];
								$simbolo_moneda=$array_moneda['currency_symbol'];
								$sepador_decimal_moneda=$array_moneda['currency_decimal_separator'];
								$sepador_millar_moneda=$array_moneda['currency_thousand_separator'];
							/*Fin datos moneda*/
							if ($total==0){
								
								$sql_anulados=mysqli_query($con,"SELECT * FROM `documentos_anulados` where id_tabla='$sale_id'");
								//echo mysqli_num_rows($sql_anulados);exit;
							$rw_null=mysqli_fetch_array($sql_anulados);
								$fecha_anulado=date("d/m/Y", strtotime($rw_null['fecha']));
								$motivo=$rw_null['motivo'];
								$nombre_documento='<a href="#" data-toggle="tooltip" title="'.$motivo.'">'.ucfirst($name_document).' anulado</a>';
							} else {
								$nombre_documento=ucfirst($name_document);
							}
							
							$finales++;
						?>	
						<tr>
							<td class='text-center'><?php echo "$sale_prefix $sale_number";?></td>
							<td><?php echo $nombre_documento;?></td>
							<td class='text-center'><?php echo $fecha;?> </td>
							<td><?php echo $customer_name;?></td>
							<td><?php echo $user_fullname;?></td>
							<td><label class='label <?php echo $label_class;?> '><?php echo $text_status;?></label></td>
							<td><?php echo $simbolo_moneda;?><span class='pull-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td><?php echo $simbolo_moneda;?><span class='pull-right'><?php echo number_format($pendiente,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
							<td class='text-center'><?php echo $due_date;?></td>
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="edit_sale.php?id=<?php echo $sale_id;?>"><i class='fa fa-edit'></i> Editar</a></li>
									
									<li><a href="#" data-target="#cobrosModal" data-toggle="modal" data-id="<?php echo $sale_id; ?>"><i class='fa fa-dollar'></i> Cobros</a></li>
									<?php }
									if ($permisos_ver){
										?>
									<li><a href="#" onclick="sale_print('<?php echo $sale_id;?>')"><i class='fa fa-file-pdf-o'></i> Ver PDF</a></li>	
										<?php
									}
									if ($permisos_eliminar==1){
									?>
									<?php 
										if ($total>0){
									?>
									<li><a href="#" data-toggle="modal" data-target="#anularModal" data-sale_id="<?php echo $sale_id?>"><i class='fa fa-window-close'></i> Anular</a></li>
										<?php }?>
									<li><a href="#" onclick="eliminar('<?php echo $sale_id;?>')"><i class='fa fa-trash'></i> Borrar</a></li>
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
		  
