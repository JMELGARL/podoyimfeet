 <form name='barcode_form' id='barcode_form'>
<div class="modal fade" id="barcodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Generador de etiquetas</h4>
      </div>
      <div class="modal-body">
       
		<div class='row'>
			<div class='col-md-12'>
				<label for="product_code" class="control-label">Código del producto</label>
				<input type="text" class="form-control" id="product_codes" disabled>
				<input type="hidden" class="form-control" id="product_id">
			</div>
		</div>
		
		<div class='row'>
			<div class='col-md-12'>
				<label for="product_name" class="control-label">Nombre del producto</label>
				<input type="text" class="form-control" id="product_names" disabled>
			</div>
		</div>
		
		
		<div class='row'>
			<div class='col-md-4'>
				<label for="label_qty" class="control-label">Cantidad de etiquetas</label>
				<input type="number" class="form-control" id="label_qty" value='1' required>
			</div>
			
			<div class='col-md-4'>
				<label for="label_width" class="control-label">Ancho de etiqueta (mm)</label>
				<input type="number" class="form-control" id="label_width" value='40' required>
			</div>
			
			<div class='col-md-4'>
				<label for="label_height" class="control-label">Altura de etiqueta (mm)</label>
				<input type="number" class="form-control" id="label_height" value='20' required>
			</div>
		</div>
          
          
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Generar</button>
      </div>
    </div>
  </div>
</div>

</form>