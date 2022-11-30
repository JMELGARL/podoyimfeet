 
<form class="form-horizontal" method="post" id="guardar_proveedor" name="guardar_cliente">
<!-- Modal -->
<div class="modal fade" id="proveedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo Proveedor</h4>
      </div>
      <div class="modal-body">
	  
      
 <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#activity" data-toggle="tab">Empresa</a></li>
                  <li><a href="#timeline" data-toggle="tab">Contacto</a></li>
                  <li><a href="#settings" data-toggle="tab">Dirección</a></li>
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <div class="form-group">
                        <label for="bussines_name" class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="bussines_name"  name="bussines_name" required>
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="tax_number" class="col-sm-3 control-label">RUC</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="tax_number" name="tax_number">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="website" class="col-sm-3 control-label">Sitio Web</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="website" name="website">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="work_phone" class="col-sm-3 control-label">Teléfono</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="work_phone" name="work_phone" required>
                        </div>
                      </div>
					 
                      

                   
                    

                   
                  </div><!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline">
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label">Nombres</label>
							<div class="col-sm-9">
							  <input type="text" class="form-control" id="first_name" name="first_name" required>
							</div>
						</div>
						<div class="form-group">
							<label for="last_name" class="col-sm-3 control-label">Apellidos</label>
							<div class="col-sm-9">
							  <input type="text" class="form-control" id="last_name" name="last_name" required>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label">Correo Electrónico</label>
							<div class="col-sm-9">
							  <input type="email" class="form-control" id="email" name="email">
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-sm-3 control-label">Teléfono</label>
							<div class="col-sm-9">
							  <input type="text" class="form-control" id="phone" name="phone" required>
							</div>
						</div>
                  </div><!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                    
                      <div class="form-group">
                        <label for="address1" class="col-sm-3 control-label">Calle</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="address1" name="address1">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="city" class="col-sm-3 control-label">Ciudad</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="city" name="city">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="state" class="col-sm-3 control-label">Región/Provincia</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="state" name="state">
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="postal_code" class="col-sm-3 control-label">Código Postal</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="postal_code" name="postal_code">
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
									<option value="<?php echo $rw_countries['id'];?>" ><?php echo utf8_encode($rw_countries['name']);?></option>
									<?php 
								}
							?>
						</select>
                        </div>
                      </div>
                      
                     
                      
                    
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
	 
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" id="guardar_datos" class="btn btn-primary">Registrar</button>
      </div>
    </div>
  </div>
</div>
</form>