<?php
	/*Inicio carga de datos*/
		$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
		$nombre_sucursal = get_id('branch_offices','name','id',$id_sucursal);//Obtengo el nombre de la sucursal
		$id_sucursal=intval($id_sucursal );
		$get_document_printing=get_document_printing(1,$id_sucursal);
		$prefix=$get_document_printing['code'];
		$_SESSION['includes_tax']=0;
		$currency_id=get_id("business_profile","currency_id","id",1);
		$_SESSION['currency_id']=$currency_id;
	/*Fin carga de datos*/

	
	
	$sql=mysqli_query($con,"select id from type_documents where module=2");
	$rw=mysqli_fetch_array($sql);
	$id_type=$rw['id'];
	$get_document_printing=get_document_printing($id_type,$id_sucursal);
	$prefix=$get_document_printing['code'];
	
	$str=mysqli_query($con,"select number from referral_guides where branch_id='$id_sucursal' order by number desc");
	$rw=mysqli_fetch_array($str);
	$next_number=$rw['number']+1;

	$time=time();
	
	$_SESSION['referral_guide_id']=$time;
	$referral_guide_id=$_SESSION['referral_guide_id'];
	
	
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
		<?php if ($permisos_editar==1 and $id_sucursal>0){?>
        <section class="content-header">
		  <h1><i class='fa fa-edit'></i> Agregar nueva guía de remisión</h1>
			
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
		  <form method="post" name="guardar" id="guardar">
            <div class="box-header with-border">
              <h3 class="box-title">Nueva guía de remisión</h3>
             	
				<div class='pull-right col-md-2'>
					<button  type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Guardar datos</button>	
				</div>
				
            </div>
            <div class="box-body">
              <div class="row">
                        
				
                        <!-- *********************** Quote ************************** -->
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
											<option value="">Selecciona sucursal</option>
											<?php 
												$sql=mysqli_query($con,"select * from branch_offices");
												while ($rw_branch=mysqli_fetch_array($sql)){
											?>
											<option value="<?php echo $rw_branch['id']?>" <?php if ($id_sucursal==$rw_branch['id']){echo "selected";}?>><?php echo ucfirst($rw_branch['name']);?></option>
											<?php
												}
											?>
										</select>
								   </div>
									
									<div class="col-md-3">
										<label>Dirección de llegada</label>
                                        <select class="form-control select2" data-placeholder="Selecciona" name="customer_id" id="customer_id" required>
											<option value="">-- Selecciona --</option>
										</select>
                                    </div>
									<div class="col-md-2">
										<label>Serie de documento</label>
                                       <input type="text" class="form-control" name="prefix" id="prefix" value="<?php echo $prefix;?>" readonly="">
                                    </div>
									
									<div class="col-md-2">
										<label>Nº de guía</label>
                                       <input type="text" class="form-control" name="number" id="number"   value='<?php echo $next_number;?>' required>
                                    </div>
									
									<div class="col-md-2">
                                       <label>Comprobante de pago Nº</label>
                                       <input type="text" class="form-control" name="comprobante" id="comprobante"  value=''>
                                    </div>
									
									
                                    </div>
									
									
									<div class="row">

                                    
									
									<div class="col-md-3">
                                       <label>Unidad de transporte</label>
										<input type="text" class="form-control" name="envio" id="envio" value='' required>
                                    </div>
                                    <div class="col-md-3">
										<label>Transportista</label>
										<input type="text" class="form-control" name="transportista" id="transportista" value='' required>
                                    </div>
									<div class="col-md-2">
										<label>Motivo del traslado</label>
										<select name="motivo" id="motivo" class='form-control' required >
											<option value="">-- Selecciona --</option> 
											<?php 
											$sql=mysqli_query($con,"select * from motivos_traslado");
											while ($rws=mysqli_fetch_array($sql)){
												?>
											<option value="<?php echo $rws['id'];?>" ><?php echo $rws['descripcion'];?></option>	
												<?php
											}
											?>
										</select>
                                    </div>
									
									<div class="col-md-2">
											<label>Incuye <?php echo strtoupper(tax_txt);?></label>
											<select name="is_taxeable" id="is_taxeable" class='form-control' onchange="taxes(this.value);" >
												<option value="0" >No</option>
												<option value="1" >Si </option>
											</select>
												
										</div>
										<div class='col-md-2'>
											<label>Moneda</label>
											<select class='form-control' name="currency_id" id="currency_id">
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
									
									
</form>
                                </div><!-- /.box-body -->
                                    </div>


                                

                                


                            </div>
                            <!-- /.box -->
                            </form>
							<div class='row'>
								<form id="barcode_form" method>	
								
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
								<div class="col-md-3">
									<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-info "><i class="fa fa-plus"></i> Agregar productos</button>
								</div>
								<div class="col-md-2">
									<div class="input-group">
												<input type="text" class="form-control" name="created_at"  value="<?php echo date("d/m/Y");?>" disabled="">

												<div class="input-group-addon">
													<a href="#"><i class="fa fa-calendar"></i></a>
												</div>
											</div>
								</div>	
							</div>
								
                        </div>
                        <!--/.col end -->
						<div class="col-md-12 text-right" style='margin-top:10px'>
							 
						
							
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
				url:'./ajax/productos_ventas.php?action=ajax&page='+page+'&q='+q+"&is_service="+is_service,
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
		$("#barcode_form" ).submit(function( event ) {
		  var barcode=$("#barcode").val();
		  var barcode_qty=$("#barcode_qty").val();
		  var id_sucursal=$("#branch_id").val();
		  var referral_guide_id="<?php echo $referral_guide_id;?>";
		  var currency_id=document.getElementById('currency_id').value;
		  parametros={'barcode':barcode,'id_sucursal':id_sucursal,'barcode_qty':barcode_qty,'referral_guide_id':referral_guide_id,'currency_id':currency_id};
		  
		  $.ajax({
			type: "POST",
			url: "./ajax/agregar_guia.php",
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
	
	<script>
	$( "#guardar" ).submit(function( event ) {
		  var parametros = $(this).serialize();
		  
		$.ajax({
				type: "POST",
				url: "ajax/registro/guia.php",
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