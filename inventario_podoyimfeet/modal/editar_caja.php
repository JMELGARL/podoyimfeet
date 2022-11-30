 
 <form class="form-horizontal" method="post" id="update_register" name="update_register">
<!-- Modal -->
<div class="modal fade" id="modal_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editar caja</h4>
      </div>
      <div class="modal-body">
		<div class="form-group">
			<label for="cashbox_name2" class="col-sm-3 control-label">Nombre caja </label>
			<div class="col-sm-8">
				<input type="text" name="cashbox_name2" id="cashbox_name2" class="form-control" required>
				<input type="hidden" name="mod_id" id="mod_id">
			</div>
		 </div>
		 <div class="form-group">
		<label for="user_id2" class="col-sm-3 control-label">Cajero </label>
		<div class="col-sm-8">
		<?php
			$query1=mysqli_query($con,"select user_id, fullname from users");
		?>
			<select name="user_id2" id="user_id2" class="form-control" required>
				<option value="">Selecciona cajero</option>
				<?php
					while($rw1=mysqli_fetch_array($query1)){
				?>
				<option value="<?php echo $rw1['user_id'];?>"><?php echo $rw1['fullname'];?></option>	
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
	  
	  
	 
	 <div class="form-group">
		<label for="opening_balance2" class="col-sm-3 control-label">Fondo inicial </label>
		<div class="col-sm-8">
		  <input type="text" class="form-control" id="opening_balance2" name="opening_balance2" required>
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