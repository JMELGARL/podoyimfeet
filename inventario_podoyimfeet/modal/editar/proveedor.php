<?php

	session_start();

	/* Connect To Database*/
	require_once ("../../config/db.php");
	require_once ("../../config/conexion.php");
	if (isset($_GET["id"])){
	$id=$_GET["id"];
	$id=intval($id);
	$sql="select * from suppliers, contacts_supplier where suppliers.id=contacts_supplier.supplier_id and suppliers.id='$id'";
	$query=mysqli_query($con,$sql);
	$num=mysqli_num_rows($query);
	if ($num==1){
	$rw=mysqli_fetch_array($query);
	$name=$rw['name'];
	$tax_number=$rw['tax_number'];
	$website=$rw['website'];
	$work_phone=$rw['work_phone'];
	$first_name=$rw['first_name'];
	$last_name=$rw['last_name'];
	$email=$rw['email'];
	$phone=$rw['phone'];
	$address1=$rw['address1'];
	$city=$rw['city'];
	$state=$rw['state'];
	$postal_code=$rw['postal_code'];
	$country_id=$rw['country_id'];
	
	}
	}	
	else {exit;}	
?>
     

	  <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#activity2" data-toggle="tab">Empresa</a></li>
                  <li><a href="#timeline2" data-toggle="tab">Contacto</a></li>
                  <li><a href="#settings2" data-toggle="tab">Dirección</a></li>
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" id="activity2">
                    <div class="form-group">
                        <label for="bussines_name" class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="bussines_name"  name="bussines_name" value="<?php echo $name;?>" required>
						  <input type="hidden" class="form-control" id="supplier_id"  name="supplier_id" value="<?php echo $id;?>">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="tax_number" class="col-sm-3 control-label">RUC </label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="tax_number" name="tax_number" value="<?php echo $tax_number;?>">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="website" class="col-sm-3 control-label">Sitio Web</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="website" name="website" value="<?php echo $website;?>">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="work_phone" class="col-sm-3 control-label">Teléfono</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="work_phone" name="work_phone" required value="<?php echo $work_phone;?>">
                        </div>
                      </div>
					 
                      

                   
                    

                   
                  </div><!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline2">
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label">Nombres</label>
							<div class="col-sm-9">
							  <input type="text" class="form-control" id="first_name" name="first_name" required value="<?php echo $first_name;?>">
							</div>
						</div>
						<div class="form-group">
							<label for="last_name" class="col-sm-3 control-label">Apellidos</label>
							<div class="col-sm-9">
							  <input type="text" class="form-control" id="last_name" name="last_name" required value="<?php echo $last_name;?>">
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label">Correo Electrónico</label>
							<div class="col-sm-9">
							  <input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>">
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-sm-3 control-label">Teléfono</label>
							<div class="col-sm-9">
							  <input type="text" class="form-control" id="phone" name="phone" required value="<?php echo $phone;?>">
							</div>
						</div>
                  </div><!-- /.tab-pane -->

                  <div class="tab-pane" id="settings2">
                    
                      <div class="form-group">
                        <label for="address1" class="col-sm-3 control-label">Calle</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="address1" name="address1" value="<?php echo $address1;?>">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="city" class="col-sm-3 control-label">Ciudad</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="city" name="city" value="<?php echo $city;?>">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="state" class="col-sm-3 control-label">Región/Provincia</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="state" name="state" value="<?php echo $state?>">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="postal_code" class="col-sm-3 control-label">Código Postal</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo $postal_code;?>">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="address1" class="col-sm-3 control-label">País</label>
                        <div class="col-sm-9">
                         <?php 
						$query_countries=mysqli_query($con,"select * from countries order by name");
						?>
						<select class='form-control' name="country_id" id="country_id">
						<option value="0" >Selecciona</option>
							<?php
								while ($rw_countries=mysqli_fetch_array($query_countries)){
									
									?>
									<option value="<?php echo $rw_countries['id'];?>" <?php if ($country_id==$rw_countries['id']){echo "selected";}else{echo "";}?>><?php echo utf8_encode($rw_countries['name']);?></option>
									<?php 
								}
							?>
						</select>
                        </div>
                      </div>
                      
                     
                      
                    
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->