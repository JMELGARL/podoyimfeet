<form class="form-horizontal" method="post" id="new_register" name="new_register">
<!-- Modal -->
<div class="modal fade" id="modal_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo egreso de caja</h4>
      </div>
      <div class="modal-body">
	  
      <div class="form-group">
		<label for="note" class="col-sm-3 control-label">Descripción</label>
		<div class="col-sm-9">
		  <textarea class='form-control' name="note" id="note" required></textarea>
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="total" class="col-sm-3 control-label">Monto total</label>
		<div class="col-sm-9">
		  <input type='text' name='total' id='total' required class='form-control'>
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