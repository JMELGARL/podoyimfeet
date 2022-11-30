 
<form class="form-horizontal" method="post" id="new_register" name="new_register">
<!-- Modal -->
<div class="modal fade" id="modal_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nueva sucursal</h4>
      </div>
      <div class="modal-body">
	  
      <div class="form-group">
		<label for="code" class="col-sm-4 control-label">Código de sucursal </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="code" name="code" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="name" class="col-sm-4 control-label">Nombre de sucursal </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="name" name="name" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="address" class="col-sm-4 control-label">Dirección </label>
		<div class="col-sm-8">
		  <textarea class='form-control' name="address" id='address' required></textarea>
		</div>
	  </div>
	  <div class="form-group">
		<label for="phone" class="col-sm-4 control-label">Teléfono </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="phone" name="phone" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="user_id" class="col-sm-4 control-label">Contacto </label>
		<div class="col-sm-8">
		  <select name="user_id" id="user_id" class='form-control' required>
			<option value="">Selecciona</option>
			<?php 
				$query_u=mysqli_query($con,"select * from users order by fullname");
				while ($rw_u=mysqli_fetch_array($query_u)){
					?>
				<option value="<?php echo $rw_u['user_id'];?>"><?php echo $rw_u['fullname'];?></option>		
					<?php
				}
			?>
		  </select>
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="status" class="col-sm-4 control-label">Estado </label>
		<div class="col-sm-8">
		  <select name="status" id="status" class='form-control' required>
			<option value="1" selected>Activo</option>
			<option value="0">Inactivo</option>
		  </select>
		</div>
	  </div>
	  

	 
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" id="guardar_datos" class="btn btn-primary">Registrar</button>
      </div>
    </div>
  </div>
</div>
</form>