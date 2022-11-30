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
		  <h1><i class='fa fa-edit'></i> Agregar nuevo ajuste de inventario</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Nuevo Ajuste</h3>
              
            </div>
            <div class="box-body">
              <div class="row">
                        

                        <!-- *********************** New adjustment ************************** -->
                        <div class="col-md-12 col-sm-12">
                            <form method="post" name="new_adjustment" id="new_adjustment">
                            <div class="box box-info">
                                <div class="box-header box-header-background-light with-border">
                                    <h3 class="box-title  ">Detalles del ajuste</h3>
                                </div>

                                <div class="box-background">
                                <div class="box-body">
                                    <div class="row">

                                   
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

                                       <label>Tipo de ajuste</label>
                                       <select name="type" id="type" class='form-control' required>
											<option value="">Selecciona</option>
											<option value="1">Ingreso</option>
											<option value="2">Salida</option>
										</select>
                                    </div>
									<div class="col-md-4">
                                        <label>Nota</label>
                                       <input type="text" class="form-control" name="note" id="note" maxlength='255'  >
                                    </div>
									
									<div class="col-md-2">

                                        <label>Nº de referencia</label>
                                       <input type="text" class="form-control" name="number_reference" id="number_reference" required maxlength='30' >
                                    </div>
									
									<div class="col-md-2">
                                        <label>Agregar productos</label>
                                       <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#myModal"><i class='fa fa-search'></i> Buscar productos</button>
                                    </div>
                                    </div>

                                </div><!-- /.box-body -->
                                    </div>


                                

                                


                            </div>
                            <!-- /.box -->
                            
                        </div>
                        <!--/.col end -->
						


                    </div>
					<div id="resultados_ajax" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
					<div id="resultados" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
					
					<div class="box-footer">
						<button type="submit"  class="btn btn-success pull-right "><i class="fa fa-floppy-o"></i> Guardar datos</button>
                    </div>
					</form>
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
		$( "#resultados" ).load( "./ajax/agregar_ajuste_tmp.php" );
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
			var type=document.getElementById('type').value;
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
			if (type==""){
				alert('Selecciona el tipo de transacción');
				return false;
			}
			
			//Fin validacion
			
			$.ajax({
				type: "POST",
				url: "./ajax/agregar_ajuste_tmp.php",
				data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&id_sucursal="+id_sucursal+"&type="+type,
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
					url: "./ajax/agregar_ajuste_tmp.php",
					data: "id="+id,
					 beforeSend: function(objeto){
						$("#resultados").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					}
				});
		   }
	$("#new_adjustment" ).submit(function( event ) {
			var supplier_id=$("#supplier_id").val();
			var order_number=$("#order_number").val();
			//Inicia validacion
			if (order_number=="")
			{
			alert('Ingresa en número de documento.');
			$("#order_number").val("");
			$("#order_number").focus();
			return false;
			}
			 var parametros = $(this).serialize();
			 
			 $.ajax({
					type: "POST",
					url: "ajax/registro/ajuste.php",
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
  </body>
</html>
