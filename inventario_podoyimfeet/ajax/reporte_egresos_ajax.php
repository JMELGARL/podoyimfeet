<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");
	require_once ("../config/conexion.php");
	require_once ("../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	$user_id = $_SESSION['user_id'];
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$cashbox_id=intval($_REQUEST['cashbox_id']);
	$branch_id=intval($_REQUEST['branch_id']);
	$daterange = mysqli_real_escape_string($con,(strip_tags($_REQUEST['range'], ENT_QUOTES)));
	$tables="cash_outflows, branch_offices";
	$campos="cash_outflows.id, cash_outflows.note, cash_outflows.date_added, branch_offices.name, cash_outflows.cashbox_id, cash_outflows.total";
	$sWhere="cash_outflows.branch_id=branch_offices.id";
	if ($cashbox_id>0){
		$sWhere.=" and cash_outflows.cashbox_id = '".$cashbox_id."'";
	}
	if ($branch_id>0){
		$sWhere.=" and cash_outflows.branch_id = '".$branch_id."'";
	}
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
	$sWhere .= " and cash_outflows.date_added between '$fecha_inicial' and '$fecha_final' ";
	$sWhere.=" order by cash_outflows.id desc";
	
	
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
	$reload = './cashier_closing.php';
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
				<h3 class="box-title">Listado de egresos de caja</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped ">
						<tr>
							<th>Concepto</th>
							<th>Fecha</th>
							<th>Sucursal </th>
							<th>Caja</th>
							<th class='text-right'>Monto</th>
							
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$id=$row['id'];
							$note=$row['note'];
							$date_added=date("d/m/Y ", strtotime($row['date_added']));
							$branch_office=$row['name'];
							$cashbox_id=$row['cashbox_id'];
							$cashbox_name=get_id('cashbox','cashbox_name','id',$cashbox_id);
							$total=$row['total'];
							$finales++;
						?>	
						<tr>
							<td ><?php echo $note;?></td>
							<td ><?php echo $date_added;?></td>
							<td ><?php echo $branch_office;?></td>
							<td ><?php echo $cashbox_name;?></td>
							<td class='text-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
						</tr>
						<?php }?>
						<tr>
							<td colspan='5'> 
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
		  
