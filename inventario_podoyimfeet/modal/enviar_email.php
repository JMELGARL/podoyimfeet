	<!-- Modal -->
	<div class="modal fade" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-envelope'></i> Enviar cotización</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="enviar_cotizacion" name="enviar_cotizacion">
			<div class="resultados_ajax text-center"></div>
			  
			  <div class="form-group">
				<label for="sendto" class="col-sm-2 control-label">Destinatario</label>
				<div class="col-sm-10">
				  <input type="email" class="form-control" id="sendto" name="sendto" placeholder="" required>
				  <input type="hidden" class="form-control" id="quote_id" name="quote_id" placeholder="" required>
				</div>
			  </div>
			  <div class="form-group">
				<label for="subject" class="col-sm-2 control-label">Asunto</label>
				<div class="col-sm-10">
				  <input type="text" class="form-control" id="subject" name="subject" placeholder="" required>
				</div>
			  </div>
			  <div class="form-group">
				<label for="message" class="col-sm-2 control-label">Mensaje</label>
				<div class="col-sm-10">
				  <textarea class="form-control" id="message" name="message" rows="4" required>Hola, buen día. Adjunto cotización solicitada</textarea>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label"></label>
				<div class="col-sm-10">
				  <span><i class='glyphicon glyphicon-paperclip'></i><span class="linkEmail"></span></span>
				</div>
			  </div>
		
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary" id="guardar_datos">Enviar cotización</button>
		  </div>
		  </form>
		</div>
	  </div>
	</div>