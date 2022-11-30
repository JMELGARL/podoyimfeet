 <form class="form-horizontal" method="post" id="update_register" name="update_register">
<!-- Modal -->
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editar ingreso/egreso</h4>
      </div>
      <div class="modal-body">
		<div class='row'>
			<div class='col-md-12'>
				<label>Descripción</label>
				<textarea  name='description_edit' id='description_edit' class='form-control' required></textarea>
				
				<input type="hidden" name="id_edit" id="id_edit">
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