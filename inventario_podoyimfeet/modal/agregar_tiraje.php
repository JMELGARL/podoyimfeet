 
<form class="form-horizontal" method="post" id="new_register" name="new_register">
<!-- Modal -->
<div class="modal fade" id="modal_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo tiraje de documento</h4>
      </div>
      <div class="modal-body">
	  
      <div class="form-group">
		<label for="code" class="col-sm-3 control-label">Código </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="code" name="code" required>
		</div>
	  </div>
	  <div class="form-group">
		<label for="initial" class="col-sm-3 control-label">Tiraje inicial </label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="initial" name="initial" required>			
		</div>
	  </div>
	  <div class="form-group">
		<label for="final" class="col-sm-3 control-label">Tiraje final </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="final" name="final" required>	
		</div>
	  </div>
	  <div class="form-group">
		<label for="type_document" class="col-sm-3 control-label">Documento </label>
		<div class="col-sm-8">
		  <?php
			$query1=mysqli_query($con,"select id, name_document from type_documents");
		  ?>
		<select name="type_document" id="type_document" class="form-control" required>
			<option value="">Selecciona el documento</option>
		<?php
			while($rw1=mysqli_fetch_array($query1)){
		?>
			<option value="<?php echo $rw1['id'];?>"><?php echo $rw1['name_document'];?></option>	
		<?php
			}
		?>	
		</select>
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="id_suc" class="col-sm-3 control-label">Sucursal </label>
		<div class="col-sm-8">
		  <?php
			$query2=mysqli_query($con,"select id, name from branch_offices");
		  ?>
			<select name="id_suc" id="id_suc" class="form-control" required>
				<option value="">Selecciona sucursal</option>
				<?php
					while($rw2=mysqli_fetch_array($query2)){
				?>
				<option value="<?php echo $rw2['id'];?>"><?php echo $rw2['name'];?></option>	
				<?php
				}
				?>	
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