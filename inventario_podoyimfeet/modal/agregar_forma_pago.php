 
<form class="form-horizontal" method="post" id="new_register" name="new_register">
<!-- Modal -->
<div class="modal fade" id="modal_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nueva forma de pago</h4>
      </div>
      <div class="modal-body">
	  
      <div class="form-group">
		<label for="label" class="col-sm-3 control-label">Descripción </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="label" name="label" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="days" class="col-sm-3 control-label">Días </label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="days" name="days" required>
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