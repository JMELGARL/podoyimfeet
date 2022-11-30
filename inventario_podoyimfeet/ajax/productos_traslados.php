<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	//include inventory functions
	include("../libraries/inventory.php");
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $id_sucursal =intval($_REQUEST['origin']);
		 
		 $aColumns = array('product_code', 'product_name');//Columnas de busqueda
			 $sTable = "products, inventory";
			 $sWhere = "";
			 $sWhere .= " where products.product_id=inventory.product_id";
			 if ( $_GET['q'] != "" )
			{
				$sWhere .= " and (products.product_code LIKE '%".$q."%' OR products.product_name LIKE '%".$q."%')";
			}
			$sWhere .=" and inventory.branch_id='$id_sucursal'";
			$sWhere .=" and products.status=1";
			$sWhere .=" order by products.product_name";
			
		
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './index.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>Código</th>
					<th>Producto</th>
					<th>Fabricante</th>
					<th class='text-center'>Stock</th>
					<th class='text-right'>Precio</th>
					<th class='text-right'>Cant.</th>
					<th style="width: 36px;"></th>
				</tr>
				<?php
				/*Datos de la empresa*/
				$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
				$rw_empresa=mysqli_fetch_array($sql_empresa);
				$moneda=$rw_empresa["symbol"];
				/*Fin datos empresa*/
				while ($row=mysqli_fetch_array($query)){
					$id_producto=$row['product_id'];
					$product_code=$row['product_code'];
					$product_name=$row['product_name'];
					$manufacturer_id=$row['manufacturer_id'];
					$sql_marca=mysqli_query($con, "select name from manufacturers where id='$manufacturer_id'");
					$rw_marca=mysqli_fetch_array($sql_marca);
					$nombre_marca=$rw_marca['name'];
					$selling_price=$row["selling_price"];
					$selling_price=number_format($selling_price,2,'.','');
					$stock= get_stock($id_producto,$id_sucursal); 
					?>
					<tr>
						<td><?php echo $product_code; ?></td>
						<td><?php echo $product_name; ?></td>
						<td ><?php echo $nombre_marca; ?></td>
						<td class='text-center'><?php echo $stock; ?></td>
						<td class='text-right'><?php echo $selling_price;?>	</td>
						<td class='col-xs-1'>
							<div class="pull-right">
								<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $id_producto; ?>"  value="1" >
							</div>
						</td>
						
						
						<td ><span class="pull-right"><a href="#" onclick="agregar('<?php echo $id_producto ?>')"><i class="glyphicon glyphicon-shopping-cart " style="font-size:24px;color: #5CB85C;"></i></a></span></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=7><span class="pull-right"><?php
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span>
					</td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>