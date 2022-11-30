 
 <form class="form-horizontal" method="post" id="update_register" name="update_register">
<!-- Modal -->
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editar documento</h4>
      </div>
      <div class="modal-body">
	   <div class="form-group">
		<label for="name_document2" class="col-sm-3 control-label">Nombre </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="name_document2" name="name_document2" required>
		  <input type="hidden" class="form-control" id="mod_id" name="mod_id" >
		</div>
	  </div>
     
	  <div class="form-group">
		<label for="format2" class="col-sm-3 control-label">Tamaño </label>
		<div class="col-sm-8">
		  <select name="format2" id="format2" class="form-control" required>
			<option value="">Selecciona</option>
		<?php
			$query_formats=mysqli_query($con,"select * from formats");
			while ($rw_formats=mysqli_fetch_array($query_formats)){
		?>
			<option value="<?php echo $rw_formats['format']?>"><?php echo $rw_formats['format']?></option>	
		<?php
			}
		?>
		  </select>
		</div>
	  </div>
	  <div class="form-group">
		<label for="orientation2" class="col-sm-3 control-label">Orientación </label>
		<div class="col-sm-8">
		  <select name="orientation2" id="orientation2" class="form-control" required>
			<option value="P">Vertical</option>
			<option value="L">Horizontal</option>
		  </select>
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="module_edit" class="col-sm-3 control-label">Módulo </label>
		<div class="col-sm-8">
		  <select name="module_edit" id="module_edit" class="form-control" required>
			<option value="1">Ventas</option>
			<option value="2">Guías de remisión</option>
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