<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");
	require_once ("../config/conexion.php");
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	//Inicia Control de Permisos
	include("../config/permisos.php");
	$user_id = $_SESSION['user_id'];
	 

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$daterange = mysqli_real_escape_string($con,(strip_tags($_REQUEST['range'], ENT_QUOTES)));
	$type=intval($_REQUEST['type']);
	$customer_id=intval($_REQUEST['customer_id']);
	$sale_by=intval($_REQUEST['sale_by']);
	$status=intval($_REQUEST['status']);
	$tables="sales, users, type_documents";
	$campos="sales.sale_id, sales.sale_number, sales.sale_prefix, sales.customer_id, type_documents.name_document, sales.sale_date, sales.subtotal, users.fullname, sales.tax, sales.total, sales.status, sales.due_date";
	$sWhere="users.user_id=sales.sale_by and sales.type=type_documents.id";
	if ($sale_by>0){
		$sWhere.=" and sales.sale_by = '".$sale_by."'";
	}
	if ($type>0){
		$sWhere.=" and sales.type = '".$type."'";
	}
	if (!empty($daterange)){
		list ($f_inicio,$f_final)=explode(" - ",$daterange);//Extrae la fecha inicial y la fecha final en formato espa?ol
		list ($dia_inicio,$mes_inicio,$anio_inicio)=explode("/",$f_inicio);//Extrae fecha inicial 
		$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio 00:00:00";//Fecha inicial formato ingles
		list($dia_fin,$mes_fin,$anio_fin)=explode("/",$f_final);//Extrae la fecha final
		$fecha_final="$anio_fin-$mes_fin-$dia_fin 23:59:59";
		
		$sWhere .= " and sales.sale_date between '$fecha_inicial' and '$fecha_final' ";
	}
	if ($customer_id>0){
		$sWhere .= " and sales.customer_id = '$customer_id' ";
	}
	if ($status>0){
		$sWhere.=" and sales.status = '".$status."'";
	}
	$sWhere.=" order by sales.sale_id";
	
	
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = 10000; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables where $sWhere ");
	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($con);}
	$total_pages = ceil($numrows/$per_page);
	$reload = './sales_report.php';
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	

	
	if ($numrows>0){
		include("../currency.php");//Archivo que obtiene los datos de la moneda
	?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border">
				<h3 class="box-title">Listado de ventas</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th class='text-center'>#</th>
							<th>Documento</th>
							<th class='text-center'>Fecha </th>
							<th>Cliente</th>
							<th>Vendedor </th>
							<th>Estado </th>
							<th class='text-right'>Total</th>
							<th class='text-right'>Total pend.</th>
							<th class='text-center'>Vence</th>
							
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$sale_id=$row['sale_id'];
							$name_document=$row['name_document'];
							$sale_number=$row['sale_number'];
							$sale_prefix=$row['sale_prefix'];
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
							
								
							$finales++;
						?>	
						<tr>
							<td class='text-center'><?php echo "$sale_prefix $sale_number";?></td>
							<td><?php echo ucfirst($name_document);?></td>
							<td class='text-center'><?php echo $fecha;?></td>
							<td><?php echo $customer_name;?></td>
							<td><?php echo $user_fullname;?></td>
							<td><label class='label <?php echo $label_class;?> '><?php echo $text_status;?></label></td>
							<td class='text-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
							<td class='text-right'><?php echo number_format($pendiente,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
							<td class='text-center'><?php echo $due_date;?></td>
						</tr>
						<?php }?>		
					</table>
				</div>	
				</div><!-- /.box-body -->
				<div class="box-footer clearfix">
				
				<?php 
				$inicios=$offset+1;
				$finales+=$inicios -1;
				echo "Mostrando $inicios al $finales de $numrows registros";
				echo paginate($reload, $page, $total_pages, $adjacents);?>
				</div>
			</div><!-- /.box -->
		</div><!-- /.col -->
	</div><!-- /.row -->	
	<?php	
	}	
}
?>          
		  
