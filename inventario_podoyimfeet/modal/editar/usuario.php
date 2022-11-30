<?php

	session_start();

	/* Connect To Database*/
	require_once ("../../config/db.php");
	require_once ("../../config/conexion.php");
	if (isset($_GET["id"])){
	$id=$_GET["id"];
	$id=intval($id);
	$sql="select * from users where user_id='$id'";
	$query=mysqli_query($con,$sql);
	$num=mysqli_num_rows($query);
	if ($num==1){
	$rw=mysqli_fetch_array($query);
	$fullname=$rw['fullname'];
	$user_name=$rw['user_name'];
	$user_email=$rw['user_email'];
	$user_group_id=$rw['user_group_id'];
	$status=$rw['status'];
	
	}
	}	
	else {exit;}	
?>
     

	  <div class="form-group">
		<label for="fullname" class="col-sm-3 control-label">Nombre completo</label>
		<div class="col-sm-6">
		  <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Ingresa el nombre completo del usuario" value="<?php echo $fullname;?>" required>
			<input type="hidden" value="<?php echo $id;?>" name="user_id" id="user_id">
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="user_name" class="col-sm-3 control-label">Usuario</label>
		<div class="col-sm-6">
		  <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Ingresa el usuario" value="<?php echo $user_name;?>" pattern="[a-zA-Z0-9]{4,64}" required>
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="user_email" class="col-sm-3 control-label">Email</label>
		<div class="col-sm-6">
		  <input type="text" class="form-control" id="user_email" name="user_email" placeholder="example@gmail.com" value="<?php echo $user_email;?>" required>
		</div>
	  </div>

	  <div class="form-group">
		<label for="user_group_id" class="col-sm-3 control-label">Grupo de permisos</label>
		<div class="col-sm-6">
			<select class="form-control" name="user_group_id" id="user_group_id">
				<?php
				$sql_grupos="select * from user_group";
				$query_grupos=mysqli_query($con,$sql_grupos);
				while ($rw_grupos=mysqli_fetch_array($query_grupos)){
					?>
					<option value="<?php echo $rw_grupos['user_group_id'];?>" <?php if ($user_group_id==$rw_grupos['user_group_id']){echo "selected";}else{echo"";}?>><?php echo $rw_grupos['name'];?></option>	
					<?php
				}
				?>
			</select> 
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="status" class="col-sm-3 control-label">Estado</label>
		<div class="col-sm-6">
		  <select class="form-control" name="status" id="status">
			<option value="1" <?php if ($status==1){echo "selected";}?>>Activo</option>
			<option value="2" <?php if ($status==2){echo "selected";}?>>Inactivo</option>
		  </select>
		</div>
	  </div>

