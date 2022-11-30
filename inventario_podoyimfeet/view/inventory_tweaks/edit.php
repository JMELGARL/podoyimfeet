<?php
	$_SESSION['adjustment_id']=intval($_GET['id']);
	$sql=mysqli_query($con,"select * from  inventory_tweaks where id='".$_SESSION['adjustment_id']."'");
	$count=mysqli_num_rows($sql);
	$rw=mysqli_fetch_array($sql);
	$number_reference=$rw['number_reference'];
	$created_at= date('d/m/Y', strtotime($rw['created_at']));
	$type=intval($rw['type']);
	$note=$rw['note'];
	if (!isset($_GET['id']) or $count!=1){
		header("location: purchase_list.php");
	}
	
	
	
?>
<!DOCTYPE html>
<html>
  <head>
 	<?php include("head.php");?>
	<!-- datepicker CSS -->
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
		  <h1><i class='fa fa-edit'></i> Editar ajuste de inventario</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Editar Ajuste</h3>
              
            </div>
            <div class="box-body">
              <div class="row">
                        

                        <!-- *********************** New adjustment************************** -->
                        <div class="col-md-12 col-sm-12">
                            <form method="post">
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
                                            <input type="text" class="form-control" name="create_at"  value="<?php echo $created_at;?>" readonly="">
											<span class="input-group-btn ">
												<button class="btn btn-default " type="button"><i class="fa fa-calendar "></i></button>
											</span>
                                           
                                        </div>
                                    </div>
									
                                    <div class="col-md-2">
                                       <label>Tipo de ajuste</label>
                                       <select name="type" id="type" class='form-control' required readonly>
											<option value="">Selecciona</option>
											<option value="1" <?php if ($type==1){echo "selected";}?>>Ingreso</option>
											<option value="2" <?php if ($type==2){echo "selected";}?>>Salida</option>
										</select>
                                    </div>
                                    
									<div class="col-md-4">
                                        <label>Nota</label>
                                       <input type="text" class="form-control" name="note" id="note" maxlength='255' value='<?php echo $note;?>' onblur="update_data(this.value,1);">
                                    </div>
									
									<div class="col-md-2">
                                        <label>Nº de referencia</label>
                                       <input type="text" class="form-control" name="number_reference" id="number_reference" required maxlength='30' value='<?php echo $number_reference;?>' onblur="update_data(this.value,2);">
                                    </div>
									
								
									
									<div class="col-md-2">

                                        <label>Agregar productos</label>
                                       <button type="button" class="btn btn-block btn-default" data-toggle="modal" data-target="#myModal"><i class='fa fa-search'></i> Buscar productos</button>
                                    </div>
                                    </div>

                                </div><!-- /.box-body -->
                                    </div>


                                <div class="box-footer pull-right">
									<button type="button" class='btn btn-default' onclick="imprimir('<?php echo intval($_GET['id']);?>')"><i class='fa fa-print'></i> Imprimir </button>
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
	<script src="dist/js/VentanaCentrada.js"></script>
	<script>
	$(function () {
		$( "#resultados" ).load( "./ajax/agregar_ajuste.php" );
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
        url: "./ajax/agregar_ajuste.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&id_sucursal="+id_sucursal,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});
		}
		
			function eliminar (id,id_sucursal)
		{
			
			$.ajax({
        type: "GET",
        url: "./ajax/agregar_ajuste.php",
        data: "id="+id+"&id_sucursal="+id_sucursal,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}
		function update_data(value, campo){
		$.ajax({
        type: "POST",
        url: "./ajax/agregar_ajuste.php",
        data: "value="+value+"&campo="+campo,
			 success: function(datos){
			$("#resultados").html(datos);
			}
		});
		}
		
		

			
					
				

	</script>
	
	<script>
		function imprimir(id){
			VentanaCentrada('inventory-tweaks-print-pdf.php?id='+id,'Ajuste','','1024','768','true');
		}
	</script>
	
  </body>
</html>
