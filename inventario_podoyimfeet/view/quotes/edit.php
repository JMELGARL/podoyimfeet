<?php

	$quote_id=intval($_GET['id']);
	$sql=mysqli_query($con,"select * from quotes where quote_id='".$quote_id."'");
	$count=mysqli_num_rows($sql);
	$rw=mysqli_fetch_array($sql);
	$customer_id=$rw['customer_id'];
	$employee_id=$rw['employee_id'];
	$_SESSION['includes_tax']=$rw['includes_tax'];
	$sql_customer=mysqli_query($con,"select name from customers where id='$customer_id'");
	$rw_customer=mysqli_fetch_array($sql_customer);
	$name_customer=$rw_customer['name'];
	$status=$rw['status'];
	$terms=$rw['terms'];
	$validity=$rw['validity'];
	$delivery=$rw['delivery'];
	$note=$rw['note'];
	$quote_date= date('d/m/Y H:i:s', strtotime($rw['quote_date']));
	$currency_id=$rw['currency_id'];
	save_log('Cotizaciones','Actualización de datos',$_SESSION['user_id']);
	if (!isset($_GET['id']) or $count!=1){
		header("location: quotes.php");
	}
	
	if ($status==1 and $id_grupo!=1){
		header("location: quotes.php");
		exit;
	} else {
		$editable=true;
	}
	
	
?>
<!DOCTYPE html>
<html>
  <head>
  
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
  	<?php 
		if ($permisos_editar==1){
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
		<?php if ($permisos_editar==1){?>
        <section class="content-header">
		  <h1><i class='fa fa-edit'></i> Editar cotización</h1>
		  	
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Editar cotización</h3>
              <div class='pull-right col-md-2'>
				 <div class = "input-group">
					 <span class = "input-group-addon"><i class='fa fa-dollar'></i></span>
					 <select class='form-control' onchange="return quote_update(this.value,9);" name="currency_id" id="currency_id">
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
            </div>
            <div class="box-body">
              <div class="row">
                        

                        <!-- *********************** Quote ************************** -->
                        <div class="col-md-12 col-sm-12">
                            <form method="post">
                            <div class="box box-info">
                                <div class="box-header box-header-background-light with-border">
                                    <h3 class="box-title  ">Detalles de la cotización</h3>
                                </div>

                                <div class="box-background">
                                <div class="box-body">
                                    <div class="row">

                                    <div class="col-md-3">

                                        <label>Cliente</label>
                                        <select class="form-control select2" data-placeholder="<?php echo $name_customer;?>" name="customer_id" onblur="">
											
                                        </select>
                                    </div>
									<div class="col-md-2">

                                        <label>Vendedor</label>
                                        <select name="seller_id" id="seller_id" class="form-control" onchange="return quote_update(this.value,7);">
										<?php
											$query_seller=mysqli_query($con,"select user_id, fullname from users where status=1");
											while ($rw=mysqli_fetch_array($query_seller)){
										?>
											<option value="<?php echo $rw['user_id'];?>" <?php if ($rw['user_id']==$employee_id){echo "selected";}?>><?php echo $rw['fullname'];?></option>		
										<?php
											}
										?>
										</select>
                                    </div>
									
									<?php if ($id_grupo==1){?>
                                    <div class="col-md-2">
                                        <label>Estado de la cotización</label>
                                        <select class="form-control" onchange="return quote_update(this.value,2);">
											<option value="0" <?php if ($status==0){echo "selected";}?>>No autorizado </option>
											<option value="1" <?php if ($status==1){echo "selected";}?>>Aprobada</option>
											<option value="2" <?php if ($status==2){echo "selected";}?>>En revisión</option>
										</select>
									                           
                                    </div>
									<?php }?>
									<div class="col-md-3">
                                        <label>Fecha</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $quote_date;?>" disabled="">

                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-md-2">

                                        <label>Cotización Nº</label>
                                       <input type="text" class="form-control datepicker" name="purchase_date"  value="COT-<?php echo $quote_id;?>" disabled="">
                                    </div>
									
									
                                    </div>
									
									<div class="row">

                                    <div class="col-md-3">
										<label>Condiciones de pago</label>
                                        <input type="text" class="form-control" value="<?php echo $terms;?>" onblur="return quote_update(this.value,3);">
                                    </div>
									<div class="col-md-2">
										<label>Validez de la oferta</label>
                                        <input type="text" class="form-control" value="<?php echo $validity;?>" onblur="return quote_update(this.value,4);">
                                    </div>
                                    
									<div class="col-md-2">
                                       <label>Tiempo de entrega</label>
                                       <input type="text" class="form-control" value="<?php echo $delivery;?>" onblur="return quote_update(this.value,5);">
                                    </div>
									<div class="col-md-2">
										<label>Incuye <?php echo strtoupper(tax_txt);?></label>
										<select name="is_taxeable" id="is_taxeable" class='form-control' onchange=" taxes(this.value); quote_update(this.value,8)">
											<option value="1" <?php if ($_SESSION['includes_tax']==1){echo 'selected';}?>>Si </option>
											<option value="0" <?php if ($_SESSION['includes_tax']==0){echo 'selected';}?>>No</option>
										</select>
									</div>
									<div class="col-md-3">
                                       <label>Notas de la cotización</label>
                                       <input type="text" class="form-control datepicker" value="<?php echo $note;?>" onblur="return quote_update(this.value,6);">
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
							<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-info "><i class="fa fa-plus"></i> Agregar productos</button>
							<?php if ($status==1){?>
								<button type="button" onclick="quoteToInvoice('<?php echo $quote_id;?>');" class="btn btn-default"><i class="fa fa-dollar"></i> Facturar</button>
							<?php }?>	
							<button type="button" onclick="quote_print('<?php echo $quote_id;?>')" class="btn btn-default" style="margin-right: 5px;"><i class="fa fa-print"></i> Imprimir</button>
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
        //Initialize Select2 Elements
		$(".select2").select2();
		$("#resultados" ).load( "./ajax/agregar_cotizacion.php?quote_id=<?php echo $quote_id;?>" );
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
        url: "./ajax/agregar_cotizacion.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&descuento="+descuento+"&quote_id="+<?php echo $quote_id;?>,
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
			
			$.ajax({
        type: "GET",
        url: "./ajax/agregar_cotizacion.php",
        data: "id="+id+"&quote_id="+<?php echo $quote_id;?>,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}
		function quote_update(value,campo){
		$.ajax({
        type: "POST",
        url: "./ajax/agregar_cotizacion.php",
        data: "value="+value+"&campo="+campo+"&quote_id="+<?php echo $quote_id; ?>,
			 success: function(datos){
			$("#resultados").html(datos);
			}
		});
		}
		

		function quote_print(quote_id){
			VentanaCentrada('quote-print-pdf.php?quote_id='+quote_id,'Cotizacion','','1024','768','true');
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
	quote_update(value,1);
    
});
});
</script>
<script>
	function quoteToInvoice(quote_id){
		if (confirm('Realmente desea convetir la cotización Nº '+quote_id+' en factura?'))
		{
			location.replace("new_sale.php?quote_id="+quote_id);//Redirecciono al modulo de facturacion
		}
	} 
</script>

<script>
	function taxes(value){
		$( "#resultados" ).load( "./ajax/agregar_cotizacion.php?taxes="+value+"&quote_id="+<?php echo $quote_id; ?> );
	}
</script>