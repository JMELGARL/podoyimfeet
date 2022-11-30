<?php

session_start();
if (isset($_SESSION['user_id'])){
	/* Connect To Database*/
	require_once ("../../config/db.php");
	require_once ("../../config/conexion.php");
	require_once ("../../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../../currency.php");//Archivo que obtiene los datos de la moneda
	if (isset($_GET["id"])){
		$id=$_GET["id"];
		$purchase_id=intval($id);
		$purchase_order_number=get_id('purchases','purchase_order_number','purchase_id',$purchase_id);//Obtiene el numero de documento de compra
		$purchase_date=get_id('purchases','purchase_date','purchase_id',$purchase_id);//Obtiene la fecha de compra
		$supplier_id=get_id('purchases','supplier_id','purchase_id',$purchase_id);//Obtiene el id del proveedor
		$proveedor=get_id('suppliers','name','id',$supplier_id);//Obtiene el nombre del proveedor
		$fecha_compra=date('d/m/Y',strtotime($purchase_date));
		?>
		
	<div class="form-group">
		<label for="purchase_order_number" class="col-sm-3 control-label">Nº de documento</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="purchase_order_number" name="purchase_order_number" value="<?php echo $purchase_order_number;?>" required disabled>
		  <input type="hidden" name="purchase_id" id="purchase_id" value="<?php echo $purchase_id;?>" >
		</div>
	</div>
	<div class="form-group">
		<label for="purchase_date" class="col-sm-3 control-label">Fecha de compra</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo $fecha_compra;?>" required disabled>
		</div>
	</div>	
	
	<div class="form-group">
		<label for="supplier_id" class="col-sm-3 control-label">Proveedor</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="supplier_id" name="supplier_id" value="<?php echo $proveedor;?>" required disabled>
		</div>
	</div>
	<div class="form-group">
		<label for="total" class="col-sm-3 control-label">Total a pagar</label>
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
	}
}

?>


