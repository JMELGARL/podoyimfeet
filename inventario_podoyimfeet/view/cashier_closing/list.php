<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
	<?php 
		if ($permisos_editar==1){
		include("modal/cobros.php");
		include("modal/agregar_cobro.php");
		include("modal/editar_cobro.php");
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
				<div class="row">

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
					
					
					
					
					
					
					<div class="col-md-6 col-xs-12 ">
						<div class="btn-group pull-right">
							<?php if ($permisos_editar==1){?>
							<a href="new_cash_register.php" class="btn btn-default"><i class='fa fa-plus'></i> Nuevo</a>
							<?php }?>
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
				<p>No cuentas con los permisos necesario para acceder a este m??dulo.</p>
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
  </body>
</html>
	<script>
	$(function() {
		
		load(1);
	});
	function load(page){
		var cashbox_id=$("#cashbox_id").val();
		var branch_id=$("#branch_id").val();
		var per_page=$("#per_page").val();
		var parametros = {"action":"ajax","page":page,'cashbox_id':cashbox_id,'branch_id':branch_id,'per_page':per_page};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/cortes_ajax.php',
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
			
			if(confirm('Esta acci??n  eliminar?? de forma permanente el corte de caja \n\n Desea continuar?')){
				var page=1;
				var cashbox_id=$("#cashbox_id").val();
				var branch_id=$("#branch_id").val();
				var per_page=$("#per_page").val();
				var parametros = {"action":"ajax","page":page,'branch_id':branch_id,'cashbox_id':cashbox_id,"per_page":per_page,"id":id};
				
				$.ajax({
					url:'./ajax/cortes_ajax.php',
					data: parametros,
					 beforeSend: function(objeto){
					$("#loader").html("<img src='./img/ajax-loader.gif'>");
				  },
					success:function(data){
						$(".outer_div").html(data).fadeIn('slow');
						$("#loader").html("");
						removeElement();
					}
				})
			}
		}
	</script>
	<script>
		function ver_corte(id){
			VentanaCentrada('cashier-closing-print.php?id='+id,'Corte','','1024','768','true');
		}
	</script>
	</script>
	<script>
		function removeElement(){
			window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();});}, 5000);
		}
	</script>


	
