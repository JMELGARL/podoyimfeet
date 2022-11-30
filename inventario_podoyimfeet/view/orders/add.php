<?php
	if ($permisos_editar==1){
		$delete=mysqli_query($con,"delete from orders where customer_id=0 and employee_id='$user_id'");
		
		$next=mysqli_query($con," select max(order_number) as max from orders");
		$rw=mysqli_fetch_array($next);
		$order_number= $rw['max']+1;
		
		
		$next="SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND   TABLE_NAME   = 'orders'";
		$query_next=mysqli_query($con,$next);
		$rw_next=mysqli_fetch_array($query_next);
		$order_id=$rw_next['AUTO_INCREMENT'];
		$order_date=date("Y-m-d H:i:s");
		$currency_id=get_id("business_profile","currency_id","id",1);
		$insert=mysqli_query($con,"INSERT INTO orders (order_id, order_number, order_date,status,currency_id, employee_id) VALUES (NULL,'$order_number', '$order_date',1,'$currency_id','$user_id');");
		save_log('Orden de servicio','Registro de orden de servicio',$_SESSION['user_id']);
	}
	
	$sql=mysqli_query($con,"select * from orders where order_id='".$order_id."'");
	$count=mysqli_num_rows($sql);
	$rw=mysqli_fetch_array($sql);
	$customer_id=$rw['customer_id'];
	$sql_customer=mysqli_query($con,"select customers.name, customers.work_phone, contacts.first_name, contacts.last_name, contacts.phone, contacts.email from customers, contacts where customers.id=contacts.client_id and customers.id='$customer_id'");
	$rw_customer=mysqli_fetch_array($sql_customer);
	$name_customer=$rw_customer['name'];
	$work_phone=$rw_customer['work_phone'];
	$contact_name=$rw_customer['first_name']." ".$rw_customer['last_name'];
	$contact_phone=$rw_customer['phone'];
	$contact_email=$rw_customer['email'];
	$status=$rw['status'];
	$serial_number=$rw['serial_number'];
	$product_description=$rw['product_description'];
	$brand=$rw['brand'];
	$model=$rw['model'];
	$issue=$rw['issue'];
	$accessories=$rw['accessories'];
	$note=$rw['note'];
	$employee_id=$rw['employee_id'];
	$order_date= date('d/m/Y', strtotime($rw['order_date']));
	$delivery_date=$rw['delivery_date'];
	
	if (!isset($order_id) or $count!=1){
		header("location: service_orders.php");
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
		  <h1><i class='fa fa-edit'></i> Nueva orden de servicio</h1>
		
		</section>
		<!-- Main content -->
       <section class="content">
	   
	   <form role="form" method="post" name="order" id="order">
      <div class="row">
	  
        <!-- left column -->
        <div class="col-md-4">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detalles del cliente</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Cliente</label>
                  <select class="form-control select2" data-placeholder="<?php echo $name_customer;?>" name="customer_id" id="customer_id">
					
				  </select>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Teléfono</label>
                  <input type="text" class="form-control" id="work_phone" placeholder="" value="<?php echo $work_phone;?>" readonly>
                </div>
				<div class="form-group">
                  <label for="exampleInputPassword1">Nombre contacto</label>
                  <input type="text" class="form-control" id="contact_name" placeholder="" value="<?php echo $contact_name;?>" readonly>
                </div>
				<div class="form-group">
                  <label for="exampleInputPassword1">Teléfono contacto</label>
                  <input type="text" class="form-control" id="contact_phone" placeholder="" value="<?php echo $contact_phone;?>" readonly>
                </div>
				<div class="form-group">
                  <label for="exampleInputPassword1">Email contacto</label>
                  <input type="text" class="form-control" id="contact_email" placeholder="" value="<?php echo $contact_email;?>" readonly>
                </div>
                
                
              </div>
              <!-- /.box-body -->

              
          
          </div>
          <!-- /.box -->

         </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-8">
		  
          <!-- Horizontal Form -->
          <div class="box box-info">
		  
            <div class="box-header with-border">
              <h3 class="box-title">Detalle de la orden</h3>
			  <div class='pull-right col-md-4'>
				 <div class = "input-group">
					 <span class = "input-group-addon"><i class='fa fa-dollar'></i></span>
					 <select class='form-control' onchange="return currency(this.value);" name="currency_id" id="currency_id">
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
            <!-- /.box-header -->
            <!-- form start -->
           
              <div class="box-body">
			  
			  <div class="row">
                <div class="col-md-3">
                    <label>Estado</label>
                    <select class="form-control" name="status" id="status">
						<option value="1" <?php if ($status==1){echo "selected";}?>>En proceso</option>
						<option value="2" <?php if ($status==2){echo "selected";}?>>Presupuesto</option>
						<option value="3" <?php if ($status==3){echo "selected";}?>>Reparado</option>
						<option value="4" <?php if ($status==4){echo "selected";}?>>No reparado</option>
					</select>
					<input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id;?>">
                </div>
				<div class="col-md-3">
                    <label>Técnico</label>
                    <select class="form-control" name="employee_id" id="employee_id" required>
						<option value="">Selecciona</option>
						<?php 
							$sql_user=mysqli_query($con,"select users.user_id, users.fullname from users, repairman where users.user_id=repairman.user_id and users.status=1 order by fullname");
							while ($rw_user=mysqli_fetch_array($sql_user)){
								?>
								<option value="<?php echo $rw_user['user_id'];?>" <?php if ($employee_id==$rw_user['user_id']){echo "selected";}?>><?php echo $rw_user['fullname'];?></option>
								<?php
							}
						?>
					</select>
                </div>
				<div class="col-md-2">
                    <label>Fecha</label>
                    <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $order_date;?>" disabled="">
				</div>
				<div class="col-md-2">
                    <label>F. entrega</label>
                    <input type="text" class="form-control datepicker" name="delivery_date"  value="<?php echo $delivery_date;?>" readonly>
				</div>
				<div class="col-md-2">
					<label>ORDEN Nº</label>
                    <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $order_number;?>" disabled="">
                </div>
               </div> 
               
              </div>
              <!-- /.box-body -->
				
				
            
          </div>
          <!-- /.box -->
          <!-- general form elements disabled -->
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Detalles del equipo</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             
             
                <div class="row">
                <div class="col-md-4">
                    <label>Modelo</label>
                    <input type="text" class="form-control" name="model" id="model" value="<?php echo $model;?>">
                </div>
				<div class="col-md-4">
                    <label>Marca</label>
                    <input type="text" class="form-control" name="brand" id="brand" value="<?php echo $brand;?>">
                </div>
				<div class="col-md-4">
                    <label>Número de serie</label>
                    <input type="text" class="form-control" name="serial_number" id="serial_number" value="<?php echo $serial_number;?>">
                </div>
				
				<div class="col-md-6">
                    <label>Descripción o nombre del equipo</label>
                    <input type="text" class="form-control" name="product_description" id="product_description" value="<?php echo $product_description;?>" required>
                </div>
				<div class="col-md-6">
                    <label>Accesorios</label>
                    <input type="text" class="form-control" name="accessories" id="accessories" value="<?php echo $accessories;?>">
                </div>
				
				<div class="col-md-6">
                    <label>Problema del equipo</label>
                    <textarea class="form-control" name="issue" id="issue" rows="3" required><?php echo $issue;?></textarea>
                </div>
				<div class="col-md-6">
                    <label>Observaciones</label>
                    <textarea class="form-control" name="note" id="note" rows="3"><?php echo $note;?></textarea>
                </div>
				
				
				
               </div> 
              
            </div>
            <!-- /.box-body -->
			<div class="box-footer">
				<div id="resultados_ajax"  style="position:relative;"> </div>
				<button type="submit" class="btn btn-primary pull-right">Guardar datos</button>
			</div>
			
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
		
		<div class="col-md-12">
          <!-- PRESUPUESTO -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">PRESUPUESTO</h3>
			  <div class="box-tools pull-right">
				<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-default btn-box-tool"><i class="fa fa-plus"></i> Crear presupuesto</button>
				<button type="button" class="btn btn-default btn-box-tool" onclick="order_print('<?php echo $order_id;?>');"><i class="fa fa-print"></i> Imprimir</button>
			  
	
			  </div>
			   
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
               <div id="resultados"></div>
                
              </div>
              <!-- /.box-body -->

              
          
          </div>
          <!-- /.box -->

         </div>
		 
      </div>
      <!-- /.row -->
	  </form>
    </section>
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
	<script src="plugins/datepicker/daterangepicker.js"></script>
	<script>
	$(function () {
        //Initialize Select2 Elements
		$(".select2").select2();
		$("#resultados" ).load( "./ajax/agregar_orden.php?order_id=<?php echo $order_id;?>" );
		load(1);
		
		//datepicker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			startDate: '-7d',
			autoclose: true
		});
	});
		function load(page){
			var q= $("#q").val();
			var is_service= $("#is_service").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/productos_ordenes.php?action=ajax&page='+page+'&q='+q+"&is_service="+is_service,
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
        url: "./ajax/agregar_orden.php",
        data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad+"&descuento="+descuento+"&order_id="+<?php echo $order_id;?>,
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
        url: "./ajax/agregar_orden.php",
        data: "id="+id+"&order_id="+<?php echo $order_id;?>,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}

		

		function order_print(order_id){
			VentanaCentrada('order-print-pdf.php?order_id='+order_id,'Orden de servicio','','1024','768','true');
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
	var work_phone = $('#customer_id').select2('data')[0].work_phone;
	var contact_name = $('#customer_id').select2('data')[0].contact_name;
	var contact_phone = $('#customer_id').select2('data')[0].contact_phone;
	var contact_email = $('#customer_id').select2('data')[0].contact_email;
	$('#work_phone').val(work_phone);
	$('#contact_name').val(contact_name);
	$('#contact_phone').val(contact_phone);
	$('#contact_email').val(contact_email);
	
	
    
});
});

$("#order").submit(function( event ) {
	var customer_id=$("#customer_id").val();
	if (customer_id==""){
		alert("Debes seleccionar el cliente");
		return false;
	}
	var parametros = $(this).serialize();
	 $.ajax({
		type: "POST",
		url: "ajax/modificar/orden.php",
		data: parametros,
		beforeSend: function(objeto){
			$("#resultados_ajax").html("Enviando...");
		},
		success: function(datos){
			$("#resultados_ajax").html(datos);
			window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();});}, 5000);
		}
	});	
	event.preventDefault();
});	
</script>
<script>
	function taxes(value, order_id){
		$( "#resultados" ).load( "./ajax/agregar_orden.php?taxes="+value+"&order_id="+order_id );
	}
</script>

<script>
	function currency(value){
		$( "#resultados" ).load( "./ajax/agregar_orden.php?currency_id="+value+"&order_id="+<?php echo $order_id;?> );
	}
	</script>