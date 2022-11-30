 
 <form class="form-horizontal" method="post" id="update_register" name="update_register">
<!-- Modal -->
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editar sucursal</h4>
      </div>
      <div class="modal-body">
	  
      <div class="form-group">
		<label for="code2" class="col-sm-4 control-label">Código de sucursal </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="code2" name="code2" required>
		  <input type="hidden" class="form-control" id="mod_id" name="mod_id" >
		</div>
	  </div>
	  <div class="form-group">
		<label for="name2" class="col-sm-4 control-label">Nombre de sucursal </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="name2" name="name2" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="address2" class="col-sm-4 control-label">Dirección </label>
		<div class="col-sm-8">
		  <textarea class='form-control' name="address2" id='address2' required></textarea>
		</div>
	  </div>
	  <div class="form-group">
		<label for="phone2" class="col-sm-4 control-label">Teléfono </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="phone2" name="phone2" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="user_id2" class="col-sm-4 control-label">Contacto </label>
		<div class="col-sm-8">
		  <select name="user_id2" id="user_id2" class='form-control' required>
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
		<label for="status2" class="col-sm-4 control-label">Estado </label>
		<div class="col-sm-8">
		  <select name="status2" id="status2" class='form-control' required>
			<option value="1" >Activo</option>
			<option value="0">Inactivo</option>
		  </select>
		</div>
	  </div>
	  

	 
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
         <button type="submit" id="actualizar_datos" class="btn btn-primary">Actualizar datos</button>
      </div>
    </div>
  </div>
</div>
</form>