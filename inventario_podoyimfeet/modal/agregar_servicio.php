 
<form class="form-horizontal" method="post" id="new_register" name="new_register">
<!-- Modal -->
<div class="modal fade" id="modal_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo Servicio</h4>
      </div>
      <div class="modal-body">
	  
      <div class="form-group">
		<label for="cod_service" class="col-sm-3 control-label">Código</label>
		<div class="col-sm-6">
		  <input type="text" class="form-control" id="cod_service" name="cod_service" placeholder="Ingresa el código del servicio" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="name_service" class="col-sm-3 control-label">Servicio</label>
		<div class="col-sm-6">
		  <input type="text" class="form-control" id="name_service" name="name_service" placeholder="Ingresa el nombre del servicio" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="selling_price" class="col-sm-3 control-label">Precio</label>
		<div class="col-sm-6">
		  <input type="text" class="form-control" id="selling_price" name="selling_price" required pattern="\d+(\.\d{2})?" title="precio con 2 decimales" placeholder="Ingresa el precio del servicio">
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