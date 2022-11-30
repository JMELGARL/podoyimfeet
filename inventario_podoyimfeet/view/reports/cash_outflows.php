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
					
					<div class="col-md-3">
						<select class="form-control" onchange="load(1);" id="cashbox_id">
							<option value="">Selecciona caja</option>
							<?php 
								$sql=mysqli_query($con,"select * from cashbox");
								while ($rw=mysqli_fetch_array($sql)){
							?>
							<option value="<?php echo $rw['id']?>"><?php echo ucfirst($rw['cashbox_name']);?></option>
							<?php
								}
							?>
						</select>
					</div>
					<div class="col-md-3">
					<div class="input-group">
						<select class="form-control" onchange="load(1);" id="branch_id">
							<option value="">Selecciona sucursal</option>
							<?php 
								$sql=mysqli_query($con,"select * from branch_offices");
								while ($rw=mysqli_fetch_array($sql)){
							?>
							<option value="<?php echo $rw['id']?>"><?php echo ucfirst($rw['name']);?></option>
							<?php
								}
							?>
						</select>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
						</span>
					</div><!-- /input-group -->
						
					</div>
					
					
					
					
					
					
					<div class="col-md-3 col-xs-12 ">
						<div class="btn-group pull-right">
							<?php if ($permisos_ver==1){?>
							<button type="button" class="btn btn-default"  onclick="imprimir();"><i class='fa fa-print'></i> Imprimir</button>
							<?php }?>
							
							
							 

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
	<script src="plugins/select2/select2.full.min.js"></script>
	<script src="plugins/daterangepicker/daterangepicker.js"></script>
  </body>
</html>
	<script>
	$(function() {
		
		load(1);
		date_ini();
	});
	function load(page){
		var cashbox_id=$("#cashbox_id").val();
		var range=$("#range").val();
		var branch_id=$("#branch_id").val();
		var per_page=$("#per_page").val();
		var parametros = {"action":"ajax","page":page,'cashbox_id':cashbox_id,'branch_id':branch_id,'per_page':per_page,'range':range};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/reporte_egresos_ajax.php',
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
		function imprimir(){
			var cashbox_id=$("#cashbox_id").val();
			var range=$("#range").val();
			var branch_id=$("#branch_id").val();
			VentanaCentrada('cash_outflows-report-print.php?cashbox_id='+cashbox_id+'&range='+range+'&branch_id='+branch_id,'Reporte','','1024','768','true');
		}
	</script>
	
	
	<script>
		function date_ini(){
			
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
		}
	</script>
	
	
	
