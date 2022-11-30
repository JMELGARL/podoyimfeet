 <form class="form-horizontal" method="post" id="update_register" name="update_register">
<!-- Modal -->
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editar egreso de caja</h4>
      </div>
      <div class="modal-body">
		<div class="form-group">
			<label for="note2" class="col-sm-3 control-label">Descripción</label>
			<div class="col-sm-9">
			  <textarea class='form-control' name="note2" id="note2" required></textarea>
			  <input type='hidden' id="mod_id" name="mod_id">
			</div>
		</div>
		<div class="form-group">
			<label for="total2" class="col-sm-3 control-label">Monto total</label>
			<div class="col-sm-9">
			  <input type='text' name='total2' id='total2' required class='form-control'>
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