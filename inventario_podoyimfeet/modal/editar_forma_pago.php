 
 <form class="form-horizontal" method="post" id="update_register" name="update_register">
<!-- Modal -->
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editar forma de pago</h4>
      </div>
      <div class="modal-body">
	   <div class="form-group">
		<label for="label_edit" class="col-sm-3 control-label">Descripción </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="label_edit" name="label_edit" required>
		  <input type="hidden" class="form-control" id="mod_id" name="mod_id" >
		</div>
	  </div>
     
	  <div class="form-group">
		<label for="days_edit" class="col-sm-3 control-label">Días </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="days_edit" name="days_edit" required>
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