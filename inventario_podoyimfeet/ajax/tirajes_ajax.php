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
	$modulo="Tirajes";
	permisos($modulo,$cadena_permisos);
	//Finaliza Control de Permisos
	if (isset($_REQUEST["id"])){//codigo para eliminar 
	$id=$_REQUEST["id"];
	$id=intval($id);
	if ($permisos_eliminar==1){//Si cuenta por los permisos bien
		if($delete=mysqli_query($con, "DELETE FROM document_printing WHERE id='$id'")){
			$aviso="Bien hecho!";
			$msj="Datos eliminados satisfactoriamente.";
			$classM="alert alert-success";
			$times="&times;";	
			save_log('Tirajes','Eliminación de datos',$_SESSION['user_id']);
		}else{
			$aviso="Aviso!";
			$msj="Error al eliminar los datos ".mysqli_error($con);
			$classM="alert alert-danger";
			$times="&times;";					
		}
	
		
		
	} else {//No cuenta con los permisos
		$aviso="Acceso denegado!";
		$msj="No cuentas con los permisos necesario para acceder a este módulo.";
		$classM="alert alert-danger";
		$times="&times;";
	}
}
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$query=intval($query);
	$branch_id=intval($_REQUEST['branch_id']);
	$tables="document_printing, type_documents, branch_offices";
	$campos="document_printing.id, document_printing.code, document_printing.initial, document_printing.final, type_documents.name_document, branch_offices.name, document_printing.created_at, document_printing.type_document, document_printing.branch_id";
	$sWhere="type_documents.id=document_printing.type_document and branch_offices.id=document_printing.branch_id";
	if ($query>0){
		$sWhere.=" and document_printing.type_document='$query'";
	}
	if ($branch_id>0){
		$sWhere.=" and document_printing.branch_id='$branch_id'";
	}
	$sWhere.=" order by document_printing.id desc";
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
	$reload = './documents.php';
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
				<h3 class="box-title">Listado de tiraje de documentos</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
					<table class="table table-condensed table-hover table-striped">
						<tr>
							<th>ID </th>
							<th>Código </th>
							<th>Tiraje</th>
							<th>Documento</th>
							<th>Sucursal</th>
							<th>Agregado</th>
							<th></th>
						</tr>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$id=$row['id'];
							$code=$row['code'];
							$initial=$row['initial'];
							$final=$row['final'];
							$type_document=$row['type_document'];
							$branch_id=$row['branch_id'];
							$name_document=$row['name_document'];
							$name=$row['name'];
							$created_at=date("d/m/Y",strtotime($row['created_at']));
							$finales++;
						?>	
						<tr>
							<td><?php echo $id;?></td>
							<td><?php echo $code;?></td>
							<td><?php echo $initial." - ".$final;?></td>
							<td><?php echo $name_document;?></td>
							<td><?php echo $name;?></td>
							<td><?php echo $created_at;?></td>
							
							<td>
							<div class="btn-group pull-right">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones <span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
									<?php if ($permisos_editar==1){?>
									<li><a href="#" data-toggle="modal" data-target="#modal_update" data-code="<?php echo $code;?>" data-initial="<?php echo $initial;?>" data-final="<?php echo $final;?>" data-type_document="<?php echo $type_document;?>"  data-branch_id="<?php echo $branch_id;?>" data-id="<?php echo $id;?>"><i class='fa fa-edit'></i> Editar</a></li>
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
		  
