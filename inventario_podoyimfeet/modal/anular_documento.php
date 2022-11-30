
<form name="anular_documento" id="anular_documento">
<div class="modal fade" id="anularModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Anular documento</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="motivo_anular" class="control-label">Motivo:</label>
            <select name="motivo_anular" id="motivo_anular" class="form-control" required>
				<option value="">-- Selecciona --</option>
				<option value="Mala Impresión"> Mala Impresión</option>
				<option value="Datos incorrectos"> Datos incorrectos</option>
				<option value="Mala Coordinación"> Mala Coordinación</option>
			</select>
			<input type="hidden" id="id_anular" name="id_anular">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Anular</button>
      </div>
    </div>
  </div>
</div>

</form>