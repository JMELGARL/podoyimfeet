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
		$sale_id=intval($id);
		$charge_id=intval($_GET["charge_id"]);
		$sale_prefix=get_id('sales','sale_prefix','sale_id',$sale_id);//Obtiene el prefijo de documento de venta
		$sale_number=get_id('sales','sale_number','sale_id',$sale_id);//Obtiene el numero de documento de venta
		$sale_date=get_id('sales','sale_date','sale_id',$sale_id);//Obtiene la fecha de venta
		$customer_id=get_id('sales','customer_id','sale_id',$sale_id);//Obtiene el id del cliente
		$cliente=get_id('customers','name','id',$customer_id);//Obtiene el nombre del cliente
		$total=get_id('charges','total','charge_id',$charge_id);//Obtiene el monto
		$note=get_id('charges','note','charge_id',$charge_id);//Obtiene la nota
		$number_reference=get_id('charges','number_reference','charge_id',$charge_id);//Obtiene la referencia
		$payment_type=get_id('charges','payment_type','charge_id',$charge_id);//Obtiene el tipo de pago
		$fecha_venta=date('d/m/Y',strtotime($sale_date));
		?>
		
	<div class="form-group">
		<label for="purchase_order_number" class="col-sm-3 control-label">Nº de documento</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control" id="sale_number" name="sale_number" value="<?php echo "$sale_prefix $sale_number";?>" required disabled>
		  <input type="hidden" name="sale_id" id="sale_id" value="<?php echo $sale_id;?>" >
		  <input type="hidden" name="charge_id" id="charge_id" value="<?php echo $charge_id;?>" >
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
		  <input type="text" class="form-control" id="customer_id" name="customer_id" value="<?php echo $cliente;?>" required disabled>
		</div>
	</div>
	<div class="form-group">
		<label for="total" class="col-sm-3 control-label">Total a cobrar</label>
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


