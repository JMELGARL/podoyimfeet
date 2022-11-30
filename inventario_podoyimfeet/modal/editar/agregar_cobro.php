<?php

session_start();
if (isset($_SESSION['user_id'])){
	/* Connect To Database*/
	require_once ("../../config/db.php");
	require_once ("../../config/conexion.php");
	require_once ("../../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../../currency.php");//Archivo que obtiene los datos de la moneda
	if (isset($_GET["id"])){
		$user_id=$_SESSION['user_id'];
		$id=$_GET["id"];
		$sale_id=intval($id);
		$sale_prefix=get_id('sales','sale_prefix','sale_id',$sale_id);//Obtiene el prefijo de documento de venta
		$sale_number=get_id('sales','sale_number','sale_id',$sale_id);//Obtiene el numero de documento de venta
		$sale_date=get_id('sales','sale_date','sale_id',$sale_id);//Obtiene la fecha de venta
		$customer_id=get_id('sales','customer_id','sale_id',$sale_id);//Obtiene el id del cliente
		$cliente=get_id('customers','name','id',$customer_id);//Obtiene el nombre del cliente
		$fecha_venta=date('d/m/Y',strtotime($sale_date));

	$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
	$nombre_sucursal = get_id('branch_offices','name','id',$id_sucursal);//Obtengo el nombre de la sucursal
	$id_sucursal=intval($id_sucursal );

	if ($id_sucursal>0){
		?>
		
	<div class="form-group">
		<label for="purchase_order_number" class="col-sm-3 control-label">Nº de documento</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="sale_number" name="sale_number" value="<?php echo "$sale_prefix $sale_number";?>" required disabled>
		  <input type="hidden" name="sale_id" id="sale_id" value="<?php echo $sale_id;?>" >
		</div>
	</div>
	<div class="form-group">
		<label for="purchase_date" class="col-sm-3 control-label">Fecha de venta</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="sale_date" name="sale_date" value="<?php echo $fecha_venta;?>" required disabled>
		</div>
	</div>	
	
	<div class="form-group">
		<label for="supplier_id" class="col-sm-3 control-label">Cliente</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="supplier_id" name="supplier_id" value="<?php echo $cliente;?>" required disabled>
		</div>
	</div>
	<div class="form-group">
		<label for="total" class="col-sm-3 control-label">Total a cobrar</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="total" name="total" required>
		</div>
	</div>
	<div class="form-group">
		<label for="payment_type" class="col-sm-3 control-label">Forma de pago</label>
		<div class="col-sm-9">
		  <select class="form-control" name="payment_type" id="payment_type" onchange="forma_pago(this.value)"> 	
				<option value="1">Efectivo</option>
				<option value="2">Cheque</option>
				<option value="3">Transferencia bancaria</option>
		  </select>
		</div>
	</div>	
	<div class="form-group number_reference" style="display:none">
		<label for="number_reference" class="col-sm-3 control-label">Cheque Nº</label>
		<div class="col-sm-9">
		   <input type="text" class="form-control" id="number_reference" name="number_reference"  maxlength="50">
		</div>
	</div>
	<div class="form-group">
		<label for="note" class="col-sm-3 control-label">Notas</label>
		<div class="col-sm-9">
		  <textarea name="note" id="note" class="form-control" maxlength="255"></textarea>
		</div>
	</div>

	<?php 
		} else{
		?>	
		<section class="content">
			<div class="alert alert-danger">
				<h3>Acceso denegado! </h3>
				<p>No cuentas con los permisos necesario para acceder a este módulo.</p>
			</div>
		</section>		
		<?php
		}
		?>


		<?php
	
	}
}

?>


