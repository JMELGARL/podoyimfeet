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
		$payment_id=intval($_GET["payment_id"]);
		$purchase_order_number=get_id('purchases','purchase_order_number','purchase_id',$purchase_id);//Obtiene el numero de documento de compra
		$purchase_date=get_id('purchases','purchase_date','purchase_id',$purchase_id);//Obtiene la fecha de compra
		$supplier_id=get_id('purchases','supplier_id','purchase_id',$purchase_id);//Obtiene el id del proveedor
		$proveedor=get_id('suppliers','name','id',$supplier_id);//Obtiene el nombre del proveedor
		$total=get_id('payments','total','payment_id',$payment_id);//Obtiene el monto
		$note=get_id('payments','note','payment_id',$payment_id);//Obtiene la nota
		$number_reference=get_id('payments','number_reference','payment_id',$payment_id);//Obtiene la referencia
		$payment_type=get_id('payments','payment_type','payment_id',$payment_id);//Obtiene el tipo de pago
		$fecha_compra=date('d/m/Y',strtotime($purchase_date));
		?>
		
	<div class="form-group">
		<label for="purchase_order_number" class="col-sm-3 control-label">Nº de documento</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="purchase_order_number" name="purchase_order_number" value="<?php echo $purchase_order_number;?>" required disabled>
		  <input type="hidden" name="purchase_id" id="purchase_id" value="<?php echo $purchase_id;?>" >
		  <input type="hidden" name="payment_id" id="payment_id" value="<?php echo $payment_id;?>" >
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
		  <input type="text" class="form-control" id="total" name="total" value="<?php echo number_format($total,$precision_moneda,'.','');?>" required>
		</div>
	</div>
	<div class="form-group">
		<label for="payment_type" class="col-sm-3 control-label">Forma de pago</label>
		<div class="col-sm-9">
		  <select class="form-control" name="payment_type" id="payment_type" onchange="forma_pago(this.value)" <?php if ($payment_type==4){echo "disabled";}?>> 	
				<option value="1" <?php if($payment_type==1){echo "selected";}?>>Efectivo</option>
				<option value="2" <?php if($payment_type==2){echo "selected";}?>>Cheque</option>
				<option value="3" <?php if($payment_type==3){echo "selected";}?>>Transferencia bancaria</option>
				<option value="4" <?php if($payment_type==4){echo "selected";}?>>Nota de crédito</option>
		  </select>
		</div>
	</div>	
	<div class="form-group number_reference" style="<?php if ($payment_type==1 or $payment_type==4){echo "display:none";}?>">
		<label for="number_reference" class="col-sm-3 control-label">Cheque Nº</label>
		<div class="col-sm-9">
		   <input type="text" class="form-control" id="number_reference" name="number_reference" value="<?php echo $number_reference;?>" maxlength="50">
		</div>
	</div>
	<div class="form-group">
		<label for="note" class="col-sm-3 control-label">Notas</label>
		<div class="col-sm-9">
		  <textarea name="note" id="note" class="form-control" maxlength="255"><?php echo $note;?></textarea>
		</div>
	</div>
		<?php
	}
}

?>


