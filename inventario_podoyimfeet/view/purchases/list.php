<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
	<?php 
		if ($permisos_editar==1){
		include("modal/pagos.php");
		include("modal/agregar_pago.php");
		include("modal/editar_pago.php");
		include("modal/nota_credito.php");
		}
	?>	
    <div class="wrapper">
     <header class="main-header">
		<?php include("main-header.php");?>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
		<?php include("main-sidebar.php");?>
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
		<?php if ($permisos_ver==1){?>
        <section class="content-header">
				<div class="row">
					
                    <div class="col-xs-12 col-md-3">
						<div class="input-group">
						  <input type="text" class="form-control" placeholder="Buscar por #" id='q' onkeyup="load(1);">
						  <span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
						  </span>
						</div><!-- /input-group -->
						
						
					</div>
					<div class="col-xs-12 col-md-3">
						<select class="form-control select2" name="supplier_id" id="supplier_id" >
							<option value="">Selecciona Proveedor</option>
					    </select>
					</div>
					<div class="col-xs-12 col-md-2">
						<select class="form-control" name="status" id="status" onchange="load(1)">
							<option value="">Selecciona estado</option>
							<option value="1">Pagada</option>
							<option value="2">Pendiente</option>
							<option value="3">Vencida</option>
					    </select>
					</div>
					
					<div class="col-xs-1">
						<div id="loader" class="text-center"></div>
						
					</div>
					<div class="col-xs-12 col-md-3 ">
						<div class="btn-group pull-right">
							<?php if ($permisos_editar==1){?>
							<a href="new_purchase.php" class="btn btn-default"><i class='fa fa-plus'></i> Nuevo</a>
							<?php }?>
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Mostrar
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-right">
							  <li class='active' onclick='per_page(15);' id='15'><a href="#">15</a></li>
							  <li  onclick='per_page(25);' id='25'><a href="#">25</a></li>
							  <li onclick='per_page(50);' id='50'><a href="#">50</a></li>
							  <li onclick='per_page(100);' id='100'><a href="#">100</a></li>
							  <li onclick='per_page(1000000);' id='1000000'><a href="#">Todos</a></li>
							</ul>
							 

						</div>
                    </div>
					<input type='hidden' id='per_page' value='15'>
					
             </div>
				
			 
        </section>
			
        <!-- Main content -->
        <section class="content">
			<div id="resultados_ajax"></div>
			<div class="outer_div"></div><!-- Datos ajax Final -->         
        </section><!-- /.content -->
		<?php 
		} else{
		?>	
		<section class="content">
			<div class="alert alert-danger">
				<h3>Acceso denegado! </h3>
				<p>No cuentas con los permisos necesario para acceder a este módulo.</p>
			</div>
		</section>		
		<?php
		}
		?>
      </div><!-- /.content-wrapper -->
      <?php include("footer.php");?>
    </div><!-- ./wrapper -->

	<?php include("js.php");?>
	<script src="dist/js/VentanaCentrada.js"></script>
	<script src="plugins/select2/select2.full.min.js"></script>
	
  </body>
</html>
	<script>
	$(function() {
		load(1);
	});
	function load(page){
		var query=$("#q").val();
		var supplier_id=$("#supplier_id").val();
		var status=$("#status").val();
		var type_document=$("#type_document").val();
		var per_page=$("#per_page").val();
		var parametros = {"action":"ajax","page":page,'query':query,'supplier_id':supplier_id,'status':status,'type_document':type_document,'per_page':per_page};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/compras_ajax.php',
			data: parametros,
			 beforeSend: function(objeto){
			$("#loader").html("<img src='./img/ajax-loader.gif'>");
		  },
			success:function(data){
				$(".outer_div").html(data).fadeIn('slow');
				$("#loader").html("");
			}
		})
	}
	
	function per_page(valor){
		$("#per_page").val(valor);
		load(1);
		$('.dropdown-menu li' ).removeClass( "active" );
		$("#"+valor).addClass( "active" );
	}

	
	</script>

		<script>
		function eliminar(id){
			if(confirm('Esta acción  eliminará de forma permanente la compra \n\n Desea continuar?')){
				var page=1;
				var query=$("#q").val();
				var supplier_id=$("#supplier_id").val();
				var status=$("#status").val();
				var type_document=$("#type_document").val();
				var per_page=$("#per_page").val();
				
				var parametros = {"action":"ajax","page":page,"query":query,"supplier_id":supplier_id,"status":status,'type_document':type_document,"per_page":per_page,"id":id};
				
				$.ajax({
					url:'./ajax/compras_ajax.php',
					data: parametros,
					 beforeSend: function(objeto){
					$("#loader").html("<img src='./img/ajax-loader.gif'>");
				  },
					success:function(data){
						$(".outer_div").html(data).fadeIn('slow');
						$("#loader").html("");
						window.setTimeout(function() {
						$(".alert").fadeTo(500, 0).slideUp(500, function(){
						$(this).remove();});}, 5000);
					}
				})
			}
		}
	</script>
	

<script type="text/javascript">
	$(document).ready(function() {
		$( ".select2" ).select2({        
		ajax: {
			url: "ajax/supplier_select2.php",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
				// parse the results into the format expected by Select2.
				// since we are using custom formatting functions we do not need to
				// alter the remote JSON data
				return {
					results: data
				};
			},
			cache: true
			
			
			
		},
		minimumInputLength: 2
		
		}).on('change', function (e) {
			load(1);
		})
		});
</script>

<script>
$('#pagosModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes
  cargar_pagos(id);//Cargas los pagos	
	
})
</script>

<script>
$('#agregarPagoModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes
  var parametros = {"action":"ajax","id":id};
	$.ajax({
		url:'modal/editar/agregar_pago.php',
		data: parametros,
		beforeSend: function(objeto){
			$("#loader3").html("<img src='./img/ajax-loader.gif'>");
		 },
		success:function(data){
			$(".outer_div3").html(data).fadeIn('slow');
			$("#loader3").html("");
		}
	});
})
</script>

<script>
$('#editarPagoModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var id = button.data('id') // Extract info from data-* attributes
  var payment_id = button.data('payment_id') // Extract info from data-* attributes
  var parametros = {"action":"ajax","id":id,"payment_id":payment_id};
	$.ajax({
		url:'modal/editar/editar_pago.php',
		data: parametros,
		beforeSend: function(objeto){
			$("#loader4").html("<img src='./img/ajax-loader.gif'>");
		 },
		success:function(data){
			$(".outer_div4").html(data).fadeIn('slow');
			$("#loader4").html("");
		}
	});
})
</script>

<script>
		function forma_pago(tipo_pago){
			if (tipo_pago==2){
				$(".number_reference").show();
				$(".number_reference label ").html("Cheque Nº");
				$("#number_reference").prop('required',true);
						 
			} else if (tipo_pago==3){
				$(".number_reference").show();
				$(".number_reference label ").html("Referencia Nº");
				$("#number_reference").prop('required',true);
			} 
			else
			{
				$(".number_reference").hide();
				$("#number_reference").prop('required',false);

			}
		} 
	</script>
	<script>
		$("#agregar_pago" ).submit(function(event) {
			var id=$("#purchase_id").val();
			var parametros = $(this).serialize();
			$.ajax({
				type: "POST",
				url:'ajax/registro/agregar_pago.php',
				data: parametros,
				 beforeSend: function(objeto){
					$("#loader_pago").html("<img src='./img/ajax-loader.gif'>");
				  },
				success: function(data){
					removeElement();
					$("#loader_pago").html(data).fadeIn('slow');
					$('#agregarPagoModal').modal('hide');
					cargar_pagos(id);
					
					
			  }
			});
			event.preventDefault();
		});
	</script>

	<script>
		$("#editar_pago" ).submit(function(event) {
			var id=$("#purchase_id").val();
			var parametros = $(this).serialize();
			$.ajax({
				type: "POST",
				url:'ajax/modificar/editar_pago.php',
				data: parametros,
				 beforeSend: function(objeto){
					$("#loader_pago").html("<img src='./img/ajax-loader.gif'>");
				  },
				success: function(data){
					removeElement();
					$("#loader_pago").html(data).fadeIn('slow');
					$('#editarPagoModal').modal('hide');
					cargar_pagos(id);
					
					
			  }
			});
			event.preventDefault();
		});
	</script>
	
	<script>
		function cargar_pagos(id){
			 var parametros = {"action":"ajax","id":id};
			$.ajax({
				url:'modal/editar/pagos.php',
				data: parametros,
				beforeSend: function(objeto){
					$("#loader2").html("<img src='./img/ajax-loader.gif'>");
				 },
				success:function(data){
					$(".outer_div2").html(data).fadeIn('slow');
					$("#loader2").html("");
				}
			});
		}
	</script>
	<script>
		function removeElement(){
			window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();});}, 5000);
		}
	</script>
	
	
	<script>
		function eliminar_pago(purchase_id, payment_id){
			if(confirm('Esta acción  eliminará de forma permanente el pago \n\n Desea continuar?')){
			var parametros = {"action":"ajax","id":purchase_id,"payment_id":payment_id};
				$.ajax({
					url:'modal/editar/pagos.php',
					data: parametros,
					 beforeSend: function(objeto){
					$("#loader2").html("<img src='./img/ajax-loader.gif'>");
				  },
					success:function(data){
						$(".outer_div2").html(data).fadeIn('slow');
						$("#loader2").html("");
						removeElement();
					}
				})
			}
		}
	</script>
	
	