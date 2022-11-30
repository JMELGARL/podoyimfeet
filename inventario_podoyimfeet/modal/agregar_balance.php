 
<form class="form-horizontal" method="post" id="new_register" name="new_register">
<!-- Modal -->
<div class="modal fade" id="modal_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo ingresos/egreso</h4>
      </div>
      <div class="modal-body">
	  
      <div class='row'>
		<div class='col-md-12'>
			<label>Descripción</label>
			<textarea  name='description' id='description' class='form-control' required></textarea>
		</div>
	  </div>
	  
	  <div class='row'>
		<div class='col-md-6'>
			<label>Monto</label>
			<input type='text'  name='amount' id='amount' class='form-control' required>
		</div>
		<div class='col-md-6'>
			<label>Tipo de transacción</label>
			<select   name='type' id='type' class='form-control' required>
				<option value="">-- Selecciona --</option>
				<option value="1">Ingreso</option>
				<option value="2">Egreso</option>
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