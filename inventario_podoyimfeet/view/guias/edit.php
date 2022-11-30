
<?php

	$referral_guide_id=intval($_GET['id']);
	$sql=mysqli_query($con,"select * from referral_guides where id='".$referral_guide_id."'");
	$count=mysqli_num_rows($sql);
	$rw=mysqli_fetch_array($sql);
	$reason=$rw['reason'];
	$prefix=$rw['prefix'];
	$created_at=$rw['created_at'];
	$customer_id=$rw['customer_id'];
	$branch_id=$rw['branch_id'];
	$_SESSION['includes_tax']=$rw['includes_tax'];
	$currency_id=$rw['currency_id'];
	$name_customer=get_id('customers','name','id',$rw['customer_id']);
	$branch_name=get_id('branch_offices','name','id',$branch_id);
	if (!isset($_GET['id']) or $count!=1){
		header("location: referral_guides.php");
	}
	//save_log('Guías de remisión','Actualización de datos',$_SESSION['user_id']);
	
	
?>
<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
  	<?php 
		if ($permisos_ver==1){
		include("modal/facturacion.php");
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
		  <h1><i class='fa fa-edit'></i> Editar guía de remisión</h1>
			
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
		  <form method="post" name="guardar" id="guardar">
            <div class="box-header with-border">
              <h3 class="box-title">Guía de remisión</h3>
              <div class='pull-right col-md-2'>
					<button  type="submit" class="btn btn-success pull-right"><i class="fa fa-refresh"></i> Actualizar datos</button>	
				</div>
            </div>
            <div class="box-body">
              <div class="row">
                    <!-- *********************** Guia ************************** -->
                        <div class="col-md-12 col-sm-12">
                          
                            <div class="box box-info">
                                <div class="box-header box-header-background-light with-border">
                                    <h3 class="box-title  ">Detalles de la guía de remisión</h3>
									
                                </div>

                                <div class="box-background">
                                <div class="box-body">
                                    <div class="row">

                                    <div class="col-md-3">
										<label>Dirección de partida</label>
                                        <select class="form-control" id="branch_id" name="branch_id" required>
											<option value="<?php echo $branch_id;?>"><?php echo $branch_name;?></option>
											
											
										</select>
										<input  type='hidden' value="<?php echo $referral_guide_id?>" name="referral_guide_id" id="referral_guide_id" >
								   </div>
									
									<div class="col-md-3">
										<label>Dirección de llegada</label>
                                        <select class="form-control select2"  name="customer_id" id="customer_id" required>
											<option value='<?php echo $customer_id;?>' selected><?php echo $name_customer;?></option>
										</select>
                                    </div>
									<div class="col-md-2">
										<label>Serie de documento</label>
                                       <input type="text" class="form-control" name="prefix" id="prefix" value="<?php echo $prefix;?>" readonly="">
                                    </div>
									
									<div class="col-md-2">
										<label>Nº de guía</label>
                                       <input type="text" class="form-control" name="number" id="number"   value='<?php echo $rw['number'];?>' readonly="" required>
                                    </div>
									
									<div class="col-md-2">
                                       <label>Comprobante de pago Nº</label>
                                       <input type="text" class="form-control" name="comprobante" id="comprobante"  value='<?php echo $rw['comprobante'];?>'>
                                    </div>
										
									
                                    </div>
									
									<div class="row">

                                    
									
									<div class="col-md-3">
                                       <label>Unidad de transporte</label>
										<input type="text" class="form-control" name="envio" id="envio"  value='<?php echo $rw['transport'];?>' required>
                                    </div>
                                    <div class="col-md-3">
										<label>Transportista</label>
										<input type="text" class="form-control" name="transportista" id="transportista"  value='<?php echo $rw['carrier'];?>' required>
                                    </div>
									
									<div class="col-md-2">
										<label>Motivo del traslado</label>
										<select name="motivo" id="motivo" name="motivo" class='form-control'  required>
											<?php 
											$sql=mysqli_query($con,"select * from motivos_traslado");
											while ($rws=mysqli_fetch_array($sql)){
												?>
											<option value="<?php echo $rws['id'];?>" <?php if ($rw['reason']==$rws['id']){echo 'selected';}?>><?php echo $rws['descripcion'];?></option>	
												<?php
											}
											?>
										</select>
								   </div>
									
									
									<div class="col-md-2">
											<label>Incuye <?php echo strtoupper(tax_txt);?></label>
											<select name="is_taxeable" id="is_taxeable" class='form-control' onchange=" taxes(this.value);" required>
												<option value="1" <?php if ($rw['includes_tax']==1){echo 'selected';}?>>Si </option>
												<option value="0" <?php if ($rw['includes_tax']==0){echo 'selected';}?>>No</option>
											</select>
												
										</div>
										<div class='col-md-2'>
											<label>Moneda</label>
											<select class='form-control'  name="currency_id" id="currency_id" required>
												<?php 
													$sql_currency=mysqli_query($con,"select id, name from currencies");
													while($rw=mysqli_fetch_array($sql_currency)){
														?>
												<option value='<?php echo $rw['id'];?>' <?php if ($currency_id== $rw['id']){echo "selected";}?>><?php echo $rw['name'];?></option>				
														<?php
													}
												?>
											 </select>
										</div>
									
									
									
                                    </div>
									<br>
									<div class='row'>
										<div  class='col-md-10'>
										</div>
										<div class="col-md-2">
											<div class="input-group">
												<input type="text" class="form-control" name="created_at"  value="<?php echo date("d/m/Y",strtotime($created_at));?>" disabled="">

												<div class="input-group-addon">
													<a href="#"><i class="fa fa-calendar"></i></a>
												</div>
											</div>
										</div>
									</div>
								
									
									

                                </div><!-- /.box-body -->
                                    </div>


                                

                                


                            </div>
                            <!-- /.box -->
                            </form>
                        </div>
                        <!--/.col end -->
						<div class="col-md-12 text-right">
						<?php
							if ($reason==13){
						?>		
							<button type="button" onclick="guiaToInvoice('<?php echo $referral_guide_id;?>');" class="btn btn-default"><i class="fa fa-dollar"></i> Facturar</button>
							<?php }?>	
							<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-info "><i class="fa fa-plus"></i> Agregar productos</button>
							<button type="button" onclick="quote_print('<?php echo $referral_guide_id;?>')" class="btn btn-default" style="margin-right: 5px;"><i class="fa fa-print"></i> Imprimir</button>
						</div>


                    </div>
					
					<div id="resultados" class='col-md-12' style="margin-top:15px"></div><!-- Carga los datos ajax -->
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
	<!-- Select2 -->
	
    <script src="plugins/select2/select2.full.min.js"></script>
	<script src="dist/js/VentanaCentrada.js"></script>
	
	<script>
	$(function () {
		var currency_id=document.getElementById('currency_id').value;
        //Initialize Select2 Elements
		$(".select2").select2();
		$("#resultados" ).load( "./ajax/agregar_guia.php?referral_guide_id=<?php echo $referral_guide_id;?>"+'&currency_id='+currency_id );
		load(1);
	});
		function load(page){
			var q= $("#q").val();
			var is_service= $("#is_service").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/productos_cotizaciones.php?action=ajax&page='+page+'&q='+q+"&is_service="+is_service,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
					
				}
			})
		}

	function agregar (id)
		{
			var precio_venta=document.getElementById('precio_venta_'+id).value;
			var cantidad=document.getElementById('cantidad_'+id).value;
			var descuento=document.getElementById('descuento_'+id).value;
			var currency_id=document.getElementById('currency_id').value;
			//Inicia validacion
			if (isNaN(cantidad))
			{
			alert('Esto no es un numero');
			document.getElementById('cantidad_'+id).focus();
			return false;
			}
			if (isNaN(precio_venta))
			{
			alert('Esto no es un numero');
			document.getElementById('precio_venta_'+id).focus();
			return false;
			}
			//Fin validacion
			
			$.ajax({
        type: "POST",
        url: "./ajax/agregar_guia.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&descuento="+descuento+"&referral_guide_id="+<?php echo $referral_guide_id;?>+'&currency_id='+currency_id,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});
		}
		
			function eliminar (id)
		{
			var currency_id=document.getElementById('currency_id').value;
			$.ajax({
        type: "GET",
        url: "./ajax/agregar_guia.php",
        data: "id="+id+"&referral_guide_id="+<?php echo $referral_guide_id;?>+'&currency_id='+currency_id,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}
		function referral_guides_update(value,campo){
		$.ajax({
        type: "POST",
        url: "./ajax/agregar_guia.php",
        data: "value="+value+"&campo="+campo+"&referral_guide_id="+<?php echo $referral_guide_id; ?>,
			 success: function(datos){
			$("#resultados").html(datos);
			}
		});
		}
		

		function quote_print(referral_guide_id){
			VentanaCentrada('guia-print-pdf.php?referral_guide_id='+referral_guide_id,'Guia de Remision','','1024','768','true');
		}
					
				

	</script>
	
  </body>
</html>
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
	var value=this.value;
	
    
});
});
</script>

<script>
	function taxes(value){
		var currency_id=document.getElementById('currency_id').value;
		$( "#resultados" ).load( "./ajax/agregar_guia.php?taxes="+value+"&referral_guide_id="+<?php echo $referral_guide_id; ?>+'&currency_id='+currency_id );
	}
</script>
<script>
	function guiaToInvoice(referral_guide_id){
		if (confirm('Realmente desea convetir la guía de remisión en factura?'))
		{
			location.replace("new_sale.php?referral_guide_id="+referral_guide_id);//Redirecciono al modulo de facturacion
		}
	} 
</script>

	<script>
	$( "#guardar" ).submit(function( event ) {
		  var parametros = $(this).serialize();
		  
		$.ajax({
				type: "POST",
				url: "ajax/modificar/guia.php",
				data: parametros,
				 beforeSend: function(objeto){
					$("#resultados").html("Enviando...");
				  },
				success: function(datos){
					$("#resultados").html(datos);
				}
		});
		  
		  event.preventDefault();
		 
		});
	</script>