<?php

	session_start();
	/* Connect To Database*/
	require_once ("../../config/db.php");
	require_once ("../../config/conexion.php");
	if (isset($_GET["id"])){
	$id=$_GET["id"];
	$id=intval($id);
	$sql="select * from products where product_id='$id'";
	$query=mysqli_query($con,$sql);
	$num=mysqli_num_rows($query);
	if ($num==1){
	$rw=mysqli_fetch_array($query);
	$product_code=$rw['product_code'];
	$product_name=$rw['product_name'];
	$selling_price=$rw['selling_price'];
	$status=$rw['status'];
	}
	}	
	else {exit;}
?>
<div class="form-group">
	<label for="cod_service" class="col-sm-3 control-label">Código</label>
	<div class="col-sm-6">
		<input type="text" class="form-control"  name="cod_service" placeholder="Ingresa el código del servicio" value="<?php echo $product_code;?>" required>
		<input type="hidden" value="<?php echo $id;?>" name="id" id="id">
	</div>
</div>
 <div class="form-group">
	<label for="name_service" class="col-sm-3 control-label">Servicio</label>
	<div class="col-sm-6">
		 <input type="text" class="form-control" id="name_service" name="name_service" placeholder="Ingresa el nombre del servicio" value="<?php echo $product_name;?>" required>
	</div>
</div>
<div class="form-group">
	<label for="selling_price" class="col-sm-3 control-label">Precio</label>
	<div class="col-sm-6">
	  <input type="text" class="form-control" id="selling_price" name="selling_price" required pattern="\d+(\.\d{2})?" title="precio con 2 decimales" placeholder="Ingresa el precio del servicio" value="<?php echo number_format($selling_price,2,'.','');?>">
	</div>
</div>
<div class="form-group">
	<label for="status" class="col-sm-3 control-label">Estado</label>
	<div class="col-sm-6">
		<select class="form-control" name="status" id="status">
			<option value="1" <?php if ($status==1){echo "selected";}?>>Activo</option>
			<option value="2" <?php if ($status==2){echo "selected";}?>>Inactivo</option>
		</select>
	</div>
</div>