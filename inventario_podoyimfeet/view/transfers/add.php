<?php
	if ($permisos_editar==1){

		$sql=mysqli_query($con,"select id from transfers order by id desc limit 0,1");
		$rw=mysqli_fetch_array($sql); 
		$order_number=$rw['id'];
		$next_number=$order_number+1;
	}
?>
<!DOCTYPE html>
<html>
  <head>
  
	<?php include("head.php");?>
	<link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
  	<?php 
		if ($permisos_editar==1){
		include("modal/buscar_productos.php");
		include("modal/guardar_traslado.php");
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
		<?php if ($permisos_editar==1){?>
        
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
		  <form method="post" name="new_transfer" id="new_transfer">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo traslado de mercadería</h3>
              <div  class='pull-right'>
				<button type="submit"  class="btn btn-success pull-right  guardar_datos"><i class="fa fa-floppy-o"></i> Guardar datos</button>
			  </div>
			  
            </div>
            <div class="box-body">
			
              <div class="row">
                <!-- *********************** Transfers ************************** -->
                
				<div class="col-md-12 col-sm-12">
                    <div class="box-background">
                        <div class="box-body">
                            <div class="row">
								<div class="col-md-3">
									<label>Sucursal fuente</label>
									<select class="form-control"  id="origin" name="origin" onchange='load(1);' required>
										<?php 
											$sql=mysqli_query($con,"select * from branch_offices");
											while ($rw=mysqli_fetch_array($sql)){
										?>
										<option value="<?php echo $rw['id']?>"><?php echo ucfirst($rw['name']);?></option>
										<?php
											}
										?>
									</select>
							
									
                                </div>
								<div class="col-md-3">
									<label>Sucursal destino</label>
									<select class="form-control"  id="destination" name="destination" required>
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
							
									
                                </div>
                                <div class="col-md-2">
                                    <label>Fecha</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="create_at"  value="<?php echo date("d/m/Y")?>" readonly="">
										<span class="input-group-btn ">
											<button class="btn btn-default " type="button"><i class="fa fa-calendar "></i></button>
										</span>
                                           
                                    </div>
                                </div>
									
									
									
									<div class="col-md-2">

                                        <label>Correlativo Nº</label>
                                       <input type="text" class="form-control" name="order_number" id="order_number" required disabled value='<?php echo $next_number;?>'>
                                    </div>
									
									<div class="col-md-2">

                                        <label>Agregar productos</label>
                                       <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#myModal"><i class='fa fa-search'></i> Buscar productos</button>
                                    </div>
                            </div>
							</form>
							
							<div class='row'>
								<form id="barcode_form" method>	
								<hr>
									 <div class="col-md-1">
										<input class='form-control' type='text' name='barcode_qty' id='barcode_qty'  value='1' required>
									 </div>	
									 
									 <div class="col-md-6">
										<div class="input-group">
												<input class='form-control' type='text' name='barcode' id='barcode' required>
												<span class="input-group-btn">
													<button class="btn btn-default" type="submit" ><i class='fa fa-barcode'></i> </button>
												</span>
										</div>
									 </div>		
								</form>			
								</div>
						<div class='row'>	
							<div id="resultados_ajax" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
							<div id="resultados" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
                        </div>
						</div><!-- /.box-body -->
                    </div>

                            
                </div>
                        <!--/.col end -->
						


                    </div>
					
					
					
					
					
					

            </div><!-- /.box-body -->
            
          </div><!-- /.box -->	
     
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
<script>
	$(function () {
		$( "#resultados" ).load( "./ajax/agregar_traslado_tmp.php" );
		load(1);
	});
	
		function load(page){
			var q= $("#q").val();
			var origin= $("#origin").val();
			parametros={'action':'ajax','page':page,'q':q,'origin':origin};
			$("#loader").fadeIn('slow');
			$.ajax({
				 url:'./ajax/productos_traslados.php',
				 data: parametros,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif">');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
					
				}
			})
		}
		
		function agregar(id){
			var cantidad=$("#cantidad_"+id).val();
			var parametros={'id':id,'cantidad':cantidad};
			//Inicia validacion
			if (isNaN(cantidad)){
				alert('Esto no es un numero');
				document.getElementById('cantidad_'+id).focus();
				return false;
			}
			//Fin validacion
			
			$.ajax({
				type: "POST",
				url: "./ajax/agregar_traslado_tmp.php",
				data: parametros,
				 beforeSend: function(objeto){
					$("#resultados").html("Mensaje: Cargando...");
				  },
				success: function(datos){
					$("#resultados").html(datos);
				}
			});
			
		}
		
		function eliminar (id){
			$.ajax({
				type: "GET",
				url: "./ajax/agregar_traslado_tmp.php",
				data: "id="+id,
				 beforeSend: function(objeto){
					$("#resultados").html("Mensaje: Cargando...");
				  },
				success: function(datos){
				$("#resultados").html(datos);
				}
			});
		}
		
		$( "#new_transfer" ).submit(function( event ) {
			$('.guardar_datos').attr("disabled", true);
			var origin=$("#origin").val();
			var destination=$("#destination").val();
			//Inicia validacion
			if (origin==""){
				alert('Selecciona sucursal fuente.');
				return false;
			}
			
			if (destination==""){
				alert('Selecciona sucursal destino.');
				return false;
			}
			
			 var parametros = $(this).serialize();
			 
			 $.ajax({
					type: "POST",
					url: "ajax/registro/traslado.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#loading_text").show();
						$(".datos_traslado").html("");
						
					  },
					success: function(datos){
					$(".datos_traslado").html(datos);
					$("#loading_text").hide();
					$('#guardarModal').modal("show");
					$('.guardar_datos').attr("disabled", false);
				  }
			});
	
	
			 event.preventDefault();
		});	
		
</script>

	<script>
		$("#barcode_form" ).submit(function( event ) {
		  var barcode=$("#barcode").val();
		  var barcode_qty=$("#barcode_qty").val();
		  var id_sucursal=$("#branch_id").val();
		  parametros={'barcode':barcode,'id_sucursal':id_sucursal,'barcode_qty':barcode_qty};
		  
		  $.ajax({
			type: "POST",
			url: "./ajax/agregar_traslado_tmp.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados").html(datos);
			$("#barcode").val("");
			$("#barcode").focus();
			}
		});
			
		  event.preventDefault();
		})
	</script>

  </body>
</html>
