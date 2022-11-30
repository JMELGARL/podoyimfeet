 
 <form class="form-horizontal" method="post" id="update_register" name="update_register">
<!-- Modal -->
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editar tiraje de documento</h4>
      </div>
      <div class="modal-body">
		      <div class="form-group">
		<label for="code2" class="col-sm-3 control-label">Código </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="code2" name="code2" required>
		  <input type="hidden" class="form-control" id="mod_id" name="mod_id">
		</div>
	  </div>
	  <div class="form-group">
		<label for="initial2" class="col-sm-3 control-label">Tiraje inicial </label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="initial2" name="initial2" required>			
		</div>
	  </div>
	  <div class="form-group">
		<label for="final2" class="col-sm-3 control-label">Tiraje final </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="final2" name="final2" required>	
		</div>
	  </div>
	  <div class="form-group">
		<label for="type_document2" class="col-sm-3 control-label">Documento </label>
		<div class="col-sm-8">
		  <?php
			$query1=mysqli_query($con,"select id, name_document from type_documents");
		  ?>
		<select name="type_document2" id="type_document2" class="form-control" required>
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
		<label for="id_suc2" class="col-sm-3 control-label">Sucursal </label>
		<div class="col-sm-8">
		  <?php
			$query2=mysqli_query($con,"select id, name from branch_offices");
		  ?>
			<select name="id_suc2" id="id_suc2" class="form-control" required>
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
         <button type="submit" id="actualizar_datos" class="btn btn-primary">Actualizar datos</button>
      </div>
    </div>
  </div>
</div>
</form>