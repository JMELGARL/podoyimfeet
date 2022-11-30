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
	$next_number=next_number(1,$id_sucursal);
	if ($permisos_editar==1){
		if (isset($_GET['quote_id'])){
			$quote_id=intval($_GET['quote_id']);//Id de la cotizacion
			require_once ("libraries/inventory.php");//Contiene funcion que controla stock en el inventario
			quoteToInvoice($quote_id,$user_id,$id_sucursal);//Convierte la cotizacion en Factura
			$customer_id= get_id('quotes','customer_id','quote_id',$quote_id);//Obtengo el id del cliente de la cotizacion
			$user_id= get_id('quotes','employee_id','quote_id',$quote_id);//Obtengo el id del vendedor de la cotizacion
			$customer_name= get_id('customers','name','id',$customer_id);//Obtengo el nombre del cliente
			$_SESSION['includes_tax']= get_id('quotes','includes_tax','quote_id',$quote_id);
		}	
		if (isset($_GET['order_id'])){
			$order_id=intval($_GET['order_id']);//Id de la orden
			require_once ("libraries/inventory.php");//Contiene funcion que controla stock en el inventario
			orderToInvoice($order_id,$user_id);//Convierte la cotizacion en Factura
			$customer_id= get_id('orders','customer_id','order_id',$order_id);//Obtengo el id del cliente de la cotizacion
			$customer_name= get_id('customers','name','id',$customer_id);//Obtengo el nombre del cliente
		}	
		
		if (isset($_GET['referral_guide_id'])){
			$referral_guide_id=intval($_GET['referral_guide_id']);//Id de la guia
			require_once ("libraries/inventory.php");//Contiene funcion que controla stock en el inventario
			guiaToInvoice($referral_guide_id,$user_id, $id_sucursal);//Convierte la guia en Factura
			$customer_id= get_id('referral_guides','customer_id','id',$referral_guide_id);//Obtengo el id del cliente de la cotizacion
			$customer_name= get_id('customers','name','id',$customer_id);//Obtengo el nombre del cliente
			
		}
		
	}
	$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
	$nombre_sucursal = get_id('branch_offices','name','id',$id_sucursal);//Obtengo el nombre de la sucursal
	$id_sucursal=intval($id_sucursal );
	
	$get_document_printing=get_document_printing(1,$id_sucursal);
	$prefix=$get_document_printing['code'];
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
		include("modal/guias.php");
		include("modal/agregar_cliente.php");
		include("modal/guardar_factura.php");
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
        
		<!-- Main content -->
        <section class="content">
			<form method="post" name="venta" id="venta">
          <!-- Default box -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Agregar nueva venta</h3>
              <div class='pull-right col-md-2'>
				 <div class = "input-group">
					 <span class = "input-group-addon"><i class='fa fa-dollar'></i></span>
					 <select class='form-control' onchange="currency(this.value);" name="currency_id" id="currency_id">
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
			  <div class='pull-right col-md-2'>
					 <button type="button" class="btn btn-block btn-default" data-toggle="modal" data-target="#guiaModal"><i class='fa fa-search'></i> Buscar guías de remisión</button>
			  </div>
			  
            </div>
            <div class="box-body">
              <div class="row">
                    <div class="num"></div>

                    <!-- *********************** Sales ************************** -->
                    <div class="col-md-12 col-sm-12">
						
                            <div class="row">
									<div class="col-md-3">
										<label>Sucursal</label>
										<select class="form-control" id="branch_id" name="branch_id">
											<option value="<?php echo $id_sucursal;?>"><?php echo $nombre_sucursal;?></option>
										</select>
									</div>
									<div class="col-md-3">
										<label>Tipo de documento</label>
										<select class="form-control" id="type" name="type" onchange="check_num(this.value,'<?php echo $id_sucursal;?>');">
											<?php 
												$sql=mysqli_query($con,"select * from type_documents where module=1");
												while ($rw=mysqli_fetch_array($sql)){
											?>
											<option value="<?php echo $rw['id']?>" ><?php echo ucfirst($rw['name_document']);?></option>
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
                                       <input type="number" class="form-control" name="number_document" id="number_document"  value="<?php echo $next_number; ?>" >
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
												<option value="">Selecciona Cliente</option>
												<?php }?>
											</select>
											<span class="input-group-btn">
												<button class="btn btn-default" type="button" data-toggle="modal" data-target="#cliente_modal"><i class='fa fa-plus'></i> </button>
											</span>
										</div>
                                    </div>
									
									<div class="col-md-3">
										<label>Forma de pago</label>
										<select name="payment_method" id="payment_method" class='form-control'>
										<?php 
											$sql_method=mysqli_query($con,"select label, id from payment_methods order by days");
											while ($rw=mysqli_fetch_array($sql_method)){
												$label_id=$rw['id'];
												$label=$rw['label'];
										?>
											<option value="<?php echo $label_id;?>"><?php echo $label;?></option>
										<?php
											}
										?>
									   </select>
									</div>
									
									<div class="col-md-2">
										<label>Vendedor</label>
										<select name="seller_id" id="seller_id" class="form-control">
										<?php
											$query_seller=mysqli_query($con,"select user_id, fullname from users where status=1");
											while ($rw=mysqli_fetch_array($query_seller)){
										?>
											<option value="<?php echo $rw['user_id'];?>" <?php if ($rw['user_id']==$user_id){echo "selected";}?>><?php echo $rw['fullname'];?></option>		
										<?php
											}
										?>
										</select>
									</div>
									
									<div class="col-md-2">
										<label>Incuye <?php echo strtoupper(tax_txt);?></label>
										<select name="is_taxeable" id="is_taxeable" class='form-control' onchange="return taxes(this.value);">
											<option value="1" <?php if ($_SESSION['includes_tax']==1){echo "selected";}?>>Si </option>
											<option value="0" <?php if ($_SESSION['includes_tax']==0){echo "selected";}?>>No</option>
										</select>
									</div>
                                    
									
									
									<div class="col-md-2">
										<label>Guía de Remisión</label>
										<input type='text' name='guia_number' id='guia_number' class='form-control' >			
                                    </div>
                                    </div>

                                </form><!-- /.form sale -->
								<div class='row'>
								<form id="barcode_form" method>	
								<hr>
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
								</div>

                                


                            
                           
                        </div>
                        <!--/.col end -->		
						


                    </div>
					<div id="resultados_ajax" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
					<div id="resultados" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
					<div class="col-xs-12">
						<a  href="#" data-target="#guardarModal" data-toggle="modal"  class="btn btn-primary pull-right guardar"><i class="fa fa-floppy-o"></i> Guardar datos</a>
					</div>
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
		$( "#resultados" ).load( "./ajax/agregar_venta_tmp.php" );
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
		
		function load2(page){
			var q= $("#number_ref").val();
			var customer_id= $("#customer_id").val();
			if (customer_id.length==0){
				alert('Selecciona el cliente');
				return false;
			}
			$("#loader2").fadeIn('slow');
			$.ajax({
				url:'./ajax/guias_add.php?action=ajax&page='+page+'&q='+q+"&customer_id="+customer_id,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div2").html(data).fadeIn('slow');
					$('#loader2').html('');
					
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
        url: "./ajax/agregar_venta_tmp.php",
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
        url: "./ajax/agregar_venta_tmp.php",
        data: "id="+id,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}
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
	
})

});

$( "#type" ).change(function() {
	//var number = $(this).find(':selected').data('number');
	// $("#number_document").val(number);

});

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
<script>
$( ".guardar" ).click(function() {
   var customer_id=$("#customer_id").val();	
   var payment_method=$("#payment_method").val();
	payment_method=parseInt(payment_method);
  if (payment_method>1 &&  customer_id==""){
	  alert('Selecciona el cliente');
	  return false;
  } 
	
   var parametros = $( "#venta" ).serialize();
   $.ajax({
        type: "POST",
        url: "./ajax/registro/venta.php",
        data: parametros,
		 beforeSend: function(objeto){
			$("#loading_text").show();
			$(".datos_venta").html("");
		  },
        success: function(datos){
		$(".datos_venta").html(datos);
		$("#loading_text").hide();
			window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();});
			$( "#resultados" ).load( "./ajax/agregar_venta_tmp.php" );
			var tipo=$("#type").val();
			var branch_id=$("#branch_id").val();	
			check_num(tipo,branch_id);
			}, 5000);
			
		}
	});
			
   
});
</script>
<script>
	function check_num(type,branch_id){
		var parametros = {"action":"ajax","type":type,"branch_id":branch_id};
		$.ajax({
				dataType: "json",
				type:"POST",
				url:'./ajax/check_num.php',
				data: parametros,
				 success:function(data){
					//$("#datos").html(data).fadeIn('slow');
				 $.each(data, function(index, element) {
					var number= element.number;
					var prefix= element.prefix;
					$("#number_document").val(number);
					$("#prefix").val(prefix);
                });
    
					
				}
		})
}	
</script>
<script>
	function taxes(value){
		$( "#resultados" ).load( "./ajax/agregar_venta_tmp.php?taxes="+value );
	}
</script>

<script>
	function currency(value){
		$( "#resultados" ).load( "./ajax/agregar_venta_tmp.php?currency_id="+value );
	}
	</script>
	<script>
		function agregar_guia(id,number){
			var branch_id=$('#branch_id').val();
			var guia_number=$('#guia_number').val();
			var number_document=$('#number_document').val();
			var parametros={'id_guia':id,'branch_id':branch_id,'number_document':number_document};
		$.ajax({
				type: "POST",
				url: "./ajax/agregar_venta_tmp.php",
				data: parametros,
				 beforeSend: function(objeto){
				$("#resultados").html("Mensaje: Cargando...");
				},
				success: function(datos){
					$("#resultados").html(datos);
				}
		})
			guia_number+=" "+number;
			$('#guia_number').val(guia_number);
		}
	</script>
	
	
	<script>
		$("#barcode_form" ).submit(function( event ) {
		  var barcode=$("#barcode").val();
		  var barcode_qty=$("#barcode_qty").val();
		  var id_sucursal=$("#branch_id").val();
		  parametros={'barcode':barcode,'id_sucursal':id_sucursal,'barcode_qty':barcode_qty};
		  
		  $.ajax({
			type: "POST",
			url: "./ajax/agregar_venta_tmp.php",
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
  </body>
</html>
