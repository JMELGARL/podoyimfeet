			<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Buscar productos o servicios</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					<div class="row">
					
						<div class="col-sm-6">
							<div class="input-group">
								<input type="text" class="form-control" id="q" placeholder="Buscar productos o servicios" onkeyup="load(1)">
								 <div class="input-group-addon btn" onclick="load(1);"><i class='fa fa-search'></i> Buscar</div>
							</div>	
						</div>
					   <div class="col-md-3">
							<select class="form-control" id="is_service" onchange="load(1);">
								<option value="0">Productos</option>
								<option value="1">Servicios</option>
							</select>
                        </div>
					  
					</div> 
					</form>
					<hr>
					<div id="loader" style="position: absolute;	text-align: center;	top: 55px;	width: 100%;display:none;"></div><!-- Carga gif animado -->
					<div class="outer_div" ></div><!-- Datos ajax Final -->
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					
				  </div>
				</div>
			  </div>
			</div>