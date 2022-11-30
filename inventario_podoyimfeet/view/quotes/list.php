<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
	<!-- daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
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
		<?php if ($permisos_ver==1){
			include("modal/enviar_email.php");
		?>
        <section class="content-header">
				<div class="row">
					<div class="col-md-3 col-xs-12">
						<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						 </div>
						  <input type="text" class="form-control pull-right" value="<?php echo "01".date('/m/Y').' - '.date('d/m/Y');?>" id="range" readonly>
						  
						</div><!-- /input-group -->
					</div>
					<div class="col-md-2 col-xs-12">
						<select id="sale_by" class='form-control' onchange="load(1);">
								<option value="">Vendedor </option>
								<?php
								$sql1=mysqli_query($con,"select * from users where status=1");
								while ($rw1=mysqli_fetch_array($sql1)){
									?>
									<option value="<?php echo $rw1['user_id']?>"><?php echo $rw1['fullname'];?></option>	
									<?php
								}
								?>
						</select>
								
					</div>
					<div class="col-md-2 col-xs-12">
						<select id="status" class='form-control' onchange="load(1);">
							<option value="">Estado </option>
							<option value="0">No autorizado</option>
							<option value="1">Aprobada </option>
							<option value="2">En revisión </option>
						</select>
								
					</div>
                    <div class="col-md-3 col-xs-12">
						<div class="input-group">
						  <input type="text" class="form-control" placeholder="Buscar por nombre del cliente " id='q' onkeyup="load(1);">
						  <span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
						  </span>
						</div><!-- /input-group -->
						
					</div>
					
					
					<div class="col-md-2 col-xs-12 ">
						<div class="btn-group pull-right">
							<?php if ($permisos_ver==1){?>
							<a href="new_quote.php" class="btn btn-default"><i class='fa fa-plus'></i> Nueva</a>
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
		var sale_by=$("#sale_by").val();
		var status=$("#status").val();
		var query=$("#q").val();
		var per_page=$("#per_page").val();
		var parametros = {"action":"ajax","page":page,'range':range,'query':query,'sale_by':sale_by,'status':status,'per_page':per_page};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/cotizaciones_ajax.php',
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
			if(confirm('Esta acción  eliminará de forma permanente la cotización \n\n Desea continuar?')){
				var page=1;
				var range=$("#range").val();
				var sale_by=$("#sale_by").val();
				var status=$("#status").val();
				var query=$("#q").val();
				var per_page=$("#per_page").val();
				
				var parametros = {"action":"ajax","page":page,'range':range,'query':query,'sale_by':sale_by,'status':status,'per_page':per_page,"id":id};
				
				$.ajax({
					url:'./ajax/cotizaciones_ajax.php',
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
		$('#mailModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var email = button.data('email') // Extract info from data-* attributes
		  var quote_id = button.data('quote_id') // Extract info from data-* attributes
		  var subject = button.data('subject') // Extract info from data-* attributes
		  var modal = $(this)
		  modal.find('.modal-body #sendto').val(email)
		  modal.find('.modal-body #quote_id').val(quote_id)
		  modal.find('.modal-body #subject').val(subject)
		  $( ".linkEmail" ).append("<a href='quote-print-pdf.php?quote_id="+quote_id+"' target='_blank'> Archivo adjunto</a>" );
		})
	</script>

	<script>
		$( "#enviar_cotizacion" ).submit(function( event ) {
		  $('#guardar_datos').attr("disabled", true);
		  
		 var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "mail_cotizacion.php",
					data: parametros,
					 beforeSend: function(objeto){
						$(".resultados_ajax").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$(".resultados_ajax").html(datos);
					$('#guardar_datos').attr("disabled", false);
					
				  }
			});
		  event.preventDefault();
		})
	</script>
	
	<script>
		function quote_print(quote_id){
			VentanaCentrada('quote-print-pdf.php?quote_id='+quote_id,'Cotizacion','','1024','768','true');
		}
	</script>
