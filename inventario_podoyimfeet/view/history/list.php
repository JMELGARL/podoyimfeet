<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
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
		<?php 
		if ($permisos_ver==1){
		include('modal/barcode.php');	
		?>
		
        <section class="content-header">
				<div class="row">
                    <div class="col-xs-12 col-md-3">
						<div class="form-group">
						<div class="input-group">
						  <div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						  </div>
						  <input type="text" class="form-control pull-right" value="<?php echo "01".date('/m/Y').' - '.date('d/m/Y');?>" id="range" readonly>
						  <span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
						  </span>
						</div><!-- /.input group -->
					  </div><!-- /.form group -->
						
					</div>
					<div class="col-xs-12 col-md-3">
						<input type="text" class="form-control" placeholder="Buscar por acción realizada" id="q" >
					</div>
					<div class="col-xs-12 col-md-3">
						<select id="user_id" class='form-control' onchange="load(1);">
							<option value="">Selecciona usuario</option>
							<?php 
								$query_user=mysqli_query($con,"select * from log, users where log.user_id=users.user_id group by users.user_id order by users.fullname ");
								while($rw=mysqli_fetch_array($query_user)){
									?>
								<option value="<?php echo $rw['user_id']?>"><?php echo $rw['fullname'];?></option>	
									<?php
								}
							?>
						</select>
					</div>
					
					<div class="col-xs-1">
						<div id="loader" class="text-center"></div>
						
					</div>
					<div class="col-xs-2 ">
						<div class="btn-group pull-right">
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
	<!-- Include Required Prerequisites -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
	<!-- Include Date Range Picker -->
		<script src="plugins/daterangepicker/bootstrap-datepicker.js"></script>
  </body>
</html>
	<script>
	$(function() {
		load(1);
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
		var query=$("#q").val();
		var user_id=$("#user_id").val();
		var daterange=$("#range").val();
		
		var per_page=$("#per_page").val();
		var parametros = {"action":"ajax","page":page,'query':query,'user_id':user_id,'daterange':daterange,'per_page':per_page};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/log_ajax.php',
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
			if(confirm('Esta acción  eliminará de forma permanente el producto \n\n Desea continuar?')){
				var page=1;
				var query=$("#q").val();
				var per_page=$("#per_page").val();
				
				var parametros = {"action":"ajax","page":page,"query":query,"per_page":per_page,"id":id};
				
				$.ajax({
					url:'./ajax/productos_ajax.php',
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
		function barcode_print(product_id, qty, width, height){
			VentanaCentrada('barcode-print-pdf.php?id='+product_id+'&qty='+qty+'&width='+width+'&height='+height,'Producto','','1024','768','true');
		}
	</script>
	
	<script>
		$('#barcodeModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var id = button.data('id') // Extract info from data-* attributes
		  var product_code = button.data('product_code')
		  var product_name = button.data('product_name')

		  var modal = $(this)
		  
		  modal.find('.modal-body #product_code').val(product_code)
		  modal.find('.modal-body #product_name').val(product_name)
		  modal.find('.modal-body #product_id').val(id)
		})
	</script>
	
	<script>
	$( "#barcode_form" ).submit(function( event ) {
	 
	 var product_id=$("#product_id").val();
	 var label_qty=$("#label_qty").val();
	 var label_width=$("#label_width").val();
	 var label_height=$("#label_height").val();
	 
	 barcode_print(product_id, label_qty, label_width, label_height)
	
	$('#barcodeModal').modal('hide');
	  event.preventDefault();
	});
	</script>



