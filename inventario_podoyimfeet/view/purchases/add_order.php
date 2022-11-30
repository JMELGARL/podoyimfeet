<?php
	if ($permisos_ver==1){
	$next="SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND   TABLE_NAME   = 'purchases_order'";
	$query_next=mysqli_query($con,$next);
	$rw_next=mysqli_fetch_array($query_next);
	$next_insert=$rw_next['AUTO_INCREMENT'];
	
	$purchase_order_id=$next_insert;
	$employee_id=$_SESSION['user_id'];
	$created_at=date("Y-m-d H:i:s");
	$_SESSION['purchase_order_id']=$purchase_order_id;
	$currency_id=get_id("business_profile","currency_id","id",1);
	$insert=mysqli_query($con,"INSERT INTO purchases_order (purchase_order_id, created_at,employee_id,includes_tax,currency_id) VALUES ('$purchase_order_id', '$created_at','$employee_id',0,'$currency_id');");
	$_SESSION['currency_id']=$currency_id;
	save_log('Ordenes de compra','Registro de orden de compra',$_SESSION['user_id']);
	}
	$_SESSION['includes_tax']=0;
	 
?>
<!DOCTYPE html>
<html>
  <head>
  
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
  	<?php 
		if ($permisos_ver==1){
		include("modal/buscar_productos.php");
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
		  <h1><i class='fa fa-edit'></i> Agregar nueva orden de compra</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Nueva Orden de Compra</h3>
              <div class='pull-right col-md-2'>
				 <div class = "input-group">
					 <span class = "input-group-addon"><i class='fa fa-dollar'></i></span>
					 <select class='form-control' onchange="actualizar_campos(this.value,7)" name="currency_id" id="currency_id">
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
                        

                        <!-- *********************** Purchase ************************** -->
                        <div class="col-md-12 col-sm-12">
                            <form method="post">
                            <div class="box box-info">
                                <div class="box-header box-header-background-light with-border">
                                    <h3 class="box-title  ">Detalles de la orden de compra</h3>
                                </div>

                                <div class="box-background">
                                <div class="box-body">
                                    <div class="row">

                                    <div class="col-md-4">

                                        <label>Proveedor</label>
                                        <select class="form-control select2" name="supplier_id" >
											<option value="">Selecciona Proveedor</option>
											
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Fecha</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo date("d/m/Y")?>" disabled="">

                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-md-2">

                                        <label>Orden de Compra Nº</label>
                                       <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $purchase_order_id;?>" disabled="">
                                    </div>
									
									<div class="col-md-2">
										<label>Estado</label>
										<select class="form-control" disabled onchange="actualizar_campos(this.value,2)">
											<option value="0" selected="">Pendiente</option>
											<option value="1">Aceptada</option>
											<option value="2">Rechazada</option>
										</select>

                                        
                                    </div>
									
									<div class="col-md-2">
										<label>Incuye <?php echo strtoupper(tax_txt);?></label>
										<select name="is_taxeable" id="is_taxeable" class='form-control' onchange="actualizar_campos(this.value,6);">
											<option value="1" <?php if ($_SESSION['includes_tax']==1){echo "selected";}?>>Si </option>
											<option value="0" <?php if ($_SESSION['includes_tax']==0){echo "selected";}?>>No</option>
										</select>
									</div>
									
                                    </div>
									
									
									<div class="row">

                                    <div class="col-md-4">
										<label>Condiciones de pago</label>
										<input type="text" class="form-control" name="condiciones" onblur="actualizar_campos(this.value,3)">
                                    </div>
                                    <div class="col-md-3">
                                       <label>Enviar por</label>
										<input type="text" class="form-control" name="envio" onblur="actualizar_campos(this.value,4)" >
                                    </div>
									<div class="col-md-5">
										<label>Comentarios o intrucciones especiales</label>
										<input type="text" class="form-control" name="comentarios" onblur="actualizar_campos(this.value,5)" >
                                    </div>
									
									
                                    </div>
									

                                </div><!-- /.box-body -->
                                    </div>


                                <div class="box-footer pull-right">
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class='fa fa-plus'></i> Agregar productos</button>
									<button type="button" onclick="imprimir('<?php echo $purchase_order_id;?>')" class="btn btn-default" style="margin-right: 5px;"><i class="fa fa-print"></i> Imprimir</button>
                                </div>

                                


                            </div>
                            <!-- /.box -->
                            </form>
                        </div>
                        <!--/.col end -->
						


                    </div>
					<div id="resultados" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
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
	});
	
		$(document).ready(function(){
			load(1);
			
		});

		function load(page){
			var q= $("#q").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/productos_compras.php?action=ajax&page='+page+'&q='+q,
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
			var id_sucursal=document.getElementById('id_sucursal_'+id).value;
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
			if (id_sucursal==""){
				alert('Selecciona una sucursal');
				return false;
			}
			//Fin validacion
			
			$.ajax({
        type: "POST",
        url: "./ajax/agregar_orden_compra.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&id_sucursal="+id_sucursal,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});
		}
		
		
		function actualizar_campos(valor, campo){
		parametros={"valor":valor,"campo":campo};
		$.ajax({
        url: "./ajax/agregar_orden_compra.php",
        data: parametros,
			 success: function(data){
			$("#resultados").html(data);
			}
		});
		}
		function actualizar_item(valor, item, product_item){
		parametros={"valor":valor,"item":item,"product_item":product_item};
		$.ajax({
        url: "./ajax/agregar_orden_compra.php",
        data: parametros,
			 success: function(data){
			$("#resultados").html(data);
			}
		});
		}
		
		
		function eliminar(id){
			parametros={"id":id};
			$.ajax({
			url: "./ajax/agregar_orden_compra.php",
			data: parametros,
				 success: function(data){
				$("#resultados").html(data);
				}
			});
			
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
			var value=this.value;
			actualizar_campos(value,1);
			
		});
		});
</script>
	<script>
		function imprimir(purchase_order_id){
			VentanaCentrada('purchase-order-print-pdf.php?purchase_order_id='+purchase_order_id,'Orden de Compra','','1024','768','true');
		}
	</script>

	
  </body>
</html>
