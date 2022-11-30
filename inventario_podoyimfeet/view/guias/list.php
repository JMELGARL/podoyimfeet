<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
	<!-- daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
	<?php 
		if ($permisos_editar==1){
			include("modal/anular_documento.php");
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
					<div class="col-xs-3">
						<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						 </div>
						  <input type="text" class="form-control pull-right" value="<?php echo "01".date('/m/Y').' - '.date('d/m/Y');?>" id="range" readonly>
						  
						</div><!-- /input-group -->
					</div>
					
					<div class="col-xs-3">
						<select class="form-control select2" data-placeholder="Selecciona el cliente" name="customer_id" id="customer_id" onchange="load(1)">
						</select>
								
					</div>
                    <div class="col-xs-2">
						<div class="input-group">
						  <input type="text" class="form-control" placeholder="# de comp. " id='q' onkeyup="load(1);">
						  <span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
						  </span>
						</div><!-- /input-group -->
						
					</div>
					
					<div class="col-xs-2">
						<select class='form-control' name='status_guia' id='status_guia' onchange='load(1)'>
							<option value='2'>Selecciona</option>
							<option value='0'>No Facturado</option>
							<option value='1'>Facturado</option>
						</select>
					</div>
					
					
					<div class="col-xs-2 ">
						<div class="btn-group pull-right">
							<?php if ($permisos_ver==1){?>
							<a href="new_referral_guide.php" class="btn btn-default"><i class='fa fa-plus'></i> Nueva</a>
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
					<div class="col-xs-12">
						<div id="loader" class="text-center"></div>
						
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
	<!-- Include Date Range Picker -->
	<script src="plugins/daterangepicker/daterangepicker.js"></script>
	<script src="plugins/select2/select2.full.min.js"></script>
  </body>
</html>
	<script>
	$(function() {
		
		load(1);
				        //Date range picker
       // $('#range').daterangepicker();
		
		 $('#range').daterangepicker({
		"locale": {
        "format": "MM/DD/YYYY",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "Desde",
        "toLabel": "Hasta",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
        "firstDay": 1
    },
    "linkedCalendars": false,
    "autoUpdateInput": false,
    "opens": "right"
});
	});
	function load(page){
		var range=$("#range").val();
		var customer_id=$("#customer_id").val();
		var query=$("#q").val();
		var status_guia=$("#status_guia").val();
		var per_page=$("#per_page").val();
		var parametros = {"action":"ajax","page":page,'range':range,'query':query,'customer_id':customer_id,'per_page':per_page,'status_guia':status_guia};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/guias_ajax.php',
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
			if(confirm('Esta acción  eliminará de forma permanente la guía de remisión \n\n Desea continuar?')){
				var page=1;
				var range=$("#range").val();
				var customer_id=$("#customer_id").val();
				var query=$("#q").val();
				var per_page=$("#per_page").val();
				
				var parametros = {"action":"ajax","page":page,'range':range,'query':query,'customer_id':customer_id,'per_page':per_page,"id":id};
				
				$.ajax({
					url:'./ajax/guias_ajax.php',
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
	<script>
	//Anular documentos
	$('#anularModal').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var guia_id = button.data('guia_id') // Extract info from data-* attributes
	  var modal = $(this)
	  modal.find('.modal-body #id_anular').val(guia_id)
	})

	$("#anular_documento" ).submit(function(event) {
				var page=1;
				var range=$("#range").val();
				var customer_id=$("#customer_id").val();
				var query=$("#q").val();
				var per_page=$("#per_page").val();
				var motivo_anular=$("#motivo_anular").val();
				var id_anular=$("#id_anular").val();
				var status_guia=2;
				var parametros = {"action":"ajax","page":page,'range':range,'query':query,'customer_id':customer_id,'per_page':per_page,"motivo_anular":motivo_anular,"id_anular":id_anular,'status_guia':status_guia};
			$.ajax({
				type: "GET",
				url:'./ajax/guias_ajax.php',
				data: parametros,
				 beforeSend: function(objeto){
					$("#loader").html("<img src='./img/ajax-loader.gif'>");
				  },
				success: function(data){
						$(".outer_div").html(data).fadeIn('slow');
						$("#loader").html("");
						$("#anularModal").modal("hide");
						window.setTimeout(function() {
						$(".alert").fadeTo(500, 0).slideUp(500, function(){
						$(this).remove();});}, 5000);
					
					
			  }
			});
			event.preventDefault();
		});
	</script>
	
	
	
	<script>
		function imprimir(referral_guide_id){
			VentanaCentrada('guia-print-pdf.php?referral_guide_id='+referral_guide_id,'Guia de Remision','','1024','768','true');
		}
	</script>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$( ".select2" ).select2({        
			ajax: {
				url: "ajax/customers_select2.php",
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
			
		});
	});
</script>

