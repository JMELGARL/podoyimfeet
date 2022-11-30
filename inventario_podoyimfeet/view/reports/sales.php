<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
	<!-- daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-collapse sidebar-mini">
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
                    <div class="col-md-2  col-xs-12">
						<input type="text" class="form-control pull-right" value="<?php echo "01".date('/m/Y').' - '.date('d/m/Y');?>" id="range" readonly>
					</div>
					<div class="col-md-2 col-xs-12">
					
						<select class="form-control" onchange="load(1);" id="type">
							<option value="">Tipo de documento</option>
							<?php 
								$sql=mysqli_query($con,"select * from type_documents");
								while ($rw=mysqli_fetch_array($sql)){
							?>
							<option value="<?php echo $rw['id']?>"><?php echo ucfirst($rw['name_document']);?></option>
							<?php
								}
							?>
						</select>
						
					</div>
					<div class="col-md-3 col-xs-12">
						<select class="form-control select2" data-placeholder="Selecciona el cliente" name="customer_id" id="customer_id">	
						
						</select>
					</div>
					
					<div class="col-md-2">
					<div class="input-group">
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
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
						  </span>
					</div>	
					</div>
					
					<div class="col-xs-12 col-md-2">
						<select class="form-control" name="status" id="status" onchange="load(1)">
							<option value="">Selecciona estado</option>
							<option value="1">Pagada</option>
							<option value="2">Pendiente</option>
							<option value="3">Vencida</option>
					    </select>
					</div>
					
					<div class="col-md-1 col-xs-12 ">
						<div class="btn-group pull-right">
							<?php if ($permisos_ver==1){?>
							<button type="button"  onclick="reporte();" class="btn btn-default"><i class='fa fa-print'></i> Imprimir</button>
							<?php }?>
						</div>
                    </div>
					<div class="col-xs-12">
						<div id="loader" class="text-center"></div>
					</div>
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
		<script src="plugins/select2/select2.full.min.js"></script>
  </body>
</html>
<script>
	$(function() {
		load(1);
		select2_init();
		
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
		var type=$("#type").val();
		var customer_id=$("#customer_id").val();
		var sale_by=$("#sale_by").val();
		var status=$("#status").val();
		var parametros = {"action":"ajax","page":page,'range':range,'customer_id':customer_id,'sale_by':sale_by,'type':type,'status':status};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/reporte_ventas_ajax.php',
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
</script>
<script>
	function reporte(){
		var daterange=$("#range").val();
		var sale_by=$("#sale_by").val();
		var type=$("#type").val();
		var customer_id=$("#customer_id").val();
		var status=$("#status").val();
		 VentanaCentrada('sales-report-print.php?daterange='+daterange+"&sale_by="+sale_by+"&type="+type+"&customer_id="+customer_id+"&status="+status,'Reporte ventas','','1024','768','true');
	}
</script>
<script>
	function select2_init(){
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
		minimumInputLength: 2,
		allowClear: true
	}).on('change', function (e) {
		load(1);
	});
	}

</script>	



