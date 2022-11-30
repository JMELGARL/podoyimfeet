<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
	<?php 
		if ($permisos_editar==1){
		include("modal/agregar_caja.php");
		include("modal/editar_caja.php");
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
                    
					
					<div class="col-xs-12 col-md-3">
					<?php
						$query2=mysqli_query($con,"select id, name from branch_offices");
					?>
					<div class="input-group">
						<select name="q" id="q" class="form-control" onchange="load(1);">
							<option value="">Selecciona sucursal</option>
						<?php
							
							while($rw2=mysqli_fetch_array($query2)){
								?>
							<option value="<?php echo $rw2['id'];?>"><?php echo $rw2['name'];?></option>	
								<?php
							}
						?>	
						</select>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick='load(1);'><i class='fa fa-search'></i></button>
						</span>
					</div>
					</div>
						<div class="col-xs-12 col-md-1">
							<div id="loader" class="text-center"></div>
						
						</div>
					<div class="col-xs-12 col-md-8">
						<div class="btn-group pull-right">
							<?php if ($permisos_editar==1){?>
							<button type="button" class="btn btn-default"  data-toggle="modal" data-target="#modal_register"><i class='fa fa-plus'></i> Nuevo</button>
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
  </body>
</html>
	<script>
	$(function() {
		load(1);
	});
	function load(page){
		var query=$("#q").val();
		var branch_id=$("#branch_id").val();
		var per_page=$("#per_page").val();
		var parametros = {"action":"ajax","page":page,'query':query,'branch_id':branch_id,'per_page':per_page};
		$("#loader").fadeIn('slow');
		$.ajax({
			url:'./ajax/cajas_ajax.php',
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
			if(confirm('Esta acción  eliminará de forma permanente el cajero \n\n Desea continuar?')){
				var page=1;
				var query=$("#q").val();
				var per_page=$("#per_page").val();
				var parametros = {"action":"ajax","page":page,"query":query,"per_page":per_page,"id":id};
				
				$.ajax({
					url:'./ajax/cajas_ajax.php',
					data: parametros,
					 beforeSend: function(objeto){
					$("#loader").html("<img src='./img/ajax-loader.gif'>");
				  },
					success:function(data){
						$(".outer_div").html(data).fadeIn('slow');
						$("#loader").html("");
						window.setTimeout(function() {
						$(".alert").fadeTo(500, 0).slideUp(500, function(){
						$(this).remove();});}, 5000);
					}
				})
			}
		}
	</script>
<script>
$( "#new_register" ).submit(function( event ) {
  $('#guardar_datos').attr("disabled", true);
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/registro/agregar_cajero.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax").html("Enviando...");
			  },
			success: function(datos){
			$("#resultados_ajax").html(datos);
			$('#guardar_datos').attr("disabled", false);
			load(1);
			window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();});}, 5000);
			$('#modal_register').modal('hide');
		  }
	});
  event.preventDefault();
})
</script>
<script>
$( "#update_register" ).submit(function( event ) {
  $('#actualizar_datos').attr("disabled", true);
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/modificar/caja.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax").html("Enviando...");
			  },
			success: function(datos){
			$("#resultados_ajax").html(datos);
			$('#actualizar_datos').attr("disabled", false);
			load(1);
			window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();});}, 5000);
			$('#modal_update').modal('hide');
		  }
	});
  event.preventDefault();
});
</script>

<script>
	$('#update_register').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var cashbox_name = button.data('name')
  var user_id = button.data('user_id')
  var branch_id = button.data('branch_id')
  var opening_balance= button.data('opening_balance')
  var id= button.data('id')
  var modal = $(this)

  modal.find('.modal-body #cashbox_name2').val(cashbox_name)
  modal.find('.modal-body #user_id2').val(user_id)
  modal.find('.modal-body #id_suc2').val(branch_id)
  modal.find('.modal-body #opening_balance2').val(opening_balance)
  modal.find('.modal-body #id_suc2').val(branch_id)
  modal.find('.modal-body #mod_id').val(id)
})
</script>