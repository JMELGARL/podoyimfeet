<?php

	$sale_number=intval($_GET['id']);
	$_SESSION['sale_id']=$sale_number;
	$sql_sale=mysqli_query($con,"select * from sales where sale_id='".$_SESSION['sale_id']."'");
	$count=mysqli_num_rows($sql_sale);
	$rw_sale=mysqli_fetch_array($sql_sale);
	$sale_number=$rw_sale['sale_number'];
	$customer_id=$rw_sale['customer_id'];
	$type=$rw_sale['type'];
	$branch_id=$rw_sale['branch_id'];
	$prefix=$rw_sale['sale_prefix'];
	$payment_method=$rw_sale['payment_method'];
	$seller_id=$rw_sale['seller_id'];
	$customer_id=$rw_sale['customer_id'];
	$currency_id=$rw_sale['currency_id'];
	$nombre_sucursal = get_id('branch_offices','name','id',$branch_id);//Obtengo el nombre de la sucursal
	$nombre_cliente = get_id('customers','name','id',$customer_id);//Obtengo el nombre del cliente
	$sale_date= date('d/m/Y', strtotime($rw_sale['sale_date']));
	$includes_tax=$rw_sale['includes_tax'];
	$guia_number=$rw_sale['guia_number'];
	if (!isset($_GET['id']) or $count!=1){
		header("location: manage_invoice.php");
	}
	save_log('Ventas','Actualización de datos',$_SESSION['user_id']);
	
?>
<!DOCTYPE html>
<html>
  <head>
  
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-collapse sidebar-mini">
  	<?php 
		if ($permisos_editar==1){
		include("modal/facturacion.php");
		include("modal/agregar_cliente.php");
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
           <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Editar  venta</h3>
              <div class='pull-right col-md-2'>
				 <div class = "input-group">
					 <span class = "input-group-addon"><i class='fa fa-dollar'></i></span>
					 <select class='form-control' onchange="return update_sale(6,this.value);" name="currency_id" id="currency_id">
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
			  <div class='pull-right col-md-2'>
					<button type="button" class="btn btn-block btn-default" data-toggle="modal" data-target="#myModal"><i class='fa fa-search'></i> Buscar productos</button>
			  </div>
            </div>
            <div class="box-body">
              <div class="row">
                <!-- *********************** Sale ************************** -->
                <div class="col-md-12 col-sm-12">
                    <form method="post">
                    <div class="row">
						<div class="col-md-3">
							<label>Sucursal</label>
							<select class="form-control" id="branch_id" name="branch_id">
								<option value="<?php echo $branch_id;?>"><?php echo $nombre_sucursal;?></option>
							</select>
						</div>
						<div class="col-md-3">
							<label>Tipo de documento</label>
							<select class="form-control" onchange="return update_sale(1,this.value);" disabled>
											<?php 
												$sql=mysqli_query($con,"select * from type_documents");
												while ($rw=mysqli_fetch_array($sql)){
											?>
											<option value="<?php echo $rw['id']?>" <?php if ($rw['id']==$type){echo "selected";} ?>><?php echo ucfirst($rw['name_document']);?></option>
											<?php
												}
											?>
							</select>
						</div>
						<div class="col-md-2">
                            <label>Serie de documento</label>
                            <input type="text" class="form-control" name="prefix" id="prefix" value="<?php echo $prefix;?>" readonly>
                        </div>
						<div class="col-md-2">
                            <label>Documento Nº</label>
                            <input type="number" class="form-control" name="number_document"  value="<?php echo $sale_number;?>" disabled="">
                        </div>
						<div class="col-md-2">
                            <label>Fecha</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $sale_date;?>" disabled="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label>Cliente</label>
                            <div class="input-group">
								<select class="form-control select2" name="customer_id" id="customer_id" >
												<?php if (isset($quote_id) or isset($order_id)){?>
												<option value="<?php echo $customer_id;?>" selected><?php echo $customer_name;?></option>
												<?php }  
												 else { ?>
												<option value="<?php echo $customer_id;?>"><?php if (!empty($nombre_cliente)){echo $nombre_cliente;}else {"Selecciona el cliente";}?></option>
												<?php }?>
								</select>
								
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" data-toggle="modal" data-target="#cliente_modal"><i class='fa fa-plus'></i> </button>
								</span>
							</div>
                        </div>
									
						<div class="col-md-3">
										<label>Forma de pago</label>
										<select name="payment_method" id="payment_method" class='form-control' onchange="return update_sale(3,this.value);">
										<?php 
											$sql_method=mysqli_query($con,"select label, id from payment_methods order by days");
											while ($rw=mysqli_fetch_array($sql_method)){
												$label_id=$rw['id'];
												$label=$rw['label'];
												if ($label_id==$payment_method){$selected="selected";}
												else{$selected="";}
										?>
											<option value="<?php echo $label_id;?>" <?php echo $selected;?>><?php echo $label;?></option>
										<?php
											}
										?>
									   </select>
						</div>
									
						<div class="col-md-2">
										<label>Vendedor</label>
										<select name="seller_id" id="seller_id" class="form-control" onchange="return update_sale(4,this.value);">
										<?php
											$query_seller=mysqli_query($con,"select user_id, fullname from users where status=1");
											while ($rw=mysqli_fetch_array($query_seller)){
										?>
											<option value="<?php echo $rw['user_id'];?>" <?php if ($rw['user_id']==$seller_id){echo "selected";}?>><?php echo $rw['fullname'];?></option>		
										<?php
											}
										?>
										</select>
						</div>
						
						<div class="col-md-2">
							<label>Incuye <?php echo strtoupper(tax_txt);?></label>
							<select name="is_taxeable" id="is_taxeable" class='form-control' onchange="return update_sale(5,this.value);">
								<option value="1" <?php if ($includes_tax==1){echo "selected";}?>>Si </option>
								<option value="0" <?php if ($includes_tax==0){echo "selected";}?>>No</option>
							</select>
						</div>						
									
									
						<div class="col-md-2">

                                        <label>Guía de Remisión</label>
                                       <input type='text' name='guia' id='guia' class='form-control' onblur="return update_sale(7,this.value);" value="<?php echo $guia_number;?>">
                        </div>
                    </div>
									
                </form>
            </div>
                        <!--/.col end -->
						


                    </div>
					<div id="resultados_ajax" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
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
	<script>
	$(function () {
        //Initialize Select2 Elements
		$(".select2").select2();
		$("#resultados" ).load( "./ajax/agregar_venta.php" );
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
			var descuento = $("#descuento_"+id).val();
			var id_sucursal=$("#branch_id").val();
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
        url: "./ajax/agregar_venta.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&descuento="+descuento+"&id_sucursal="+id_sucursal,
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
        url: "./ajax/agregar_venta.php",
        data: "id="+id,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}
		function update_sale(key,value){
		$.ajax({
        type: "POST",
        url: "./ajax/agregar_venta.php",
        data: "key="+key+"&value="+value,
			 success: function(datos){
			$("#resultados").html(datos);
			}
		});
		}
		

		
					
				

	</script>
	
	<script>
$( "#guardar_cliente" ).submit(function( event ) {
  $('#guardar_datos').attr("disabled", true);
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/registro/agregar_cliente.php",
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
			$('#cliente_modal').modal('hide');
		  }
	});
  event.preventDefault();
})
</script>

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
	var customer_id=this.value;
	update_sale(2,customer_id);
});

});



</script>
	
</script>
  </body>
</html>
