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
	
	
	$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
	$nombre_sucursal = get_id('branch_offices','name','id',$id_sucursal);//Obtengo el nombre de la sucursal
	$id_sucursal=intval($id_sucursal );
	
	
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
          <!-- Default box -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Agregar nueva nota de crédito</h3>
              
            </div>
            <div class="box-body">
              <div class="row">
                    <div class="num"></div>

                    <!-- *********************** Sales ************************** -->
                    <div class="col-md-12 col-sm-12">
						<form method="post" name="venta" id="venta">
                            <div class="row">
									<div class="col-md-3">
										<label>Sucursal</label>
										<select class="form-control" id="branch_id" name="branch_id">
											<option value="<?php echo $id_sucursal;?>"><?php echo $nombre_sucursal;?></option>
										</select>
									</div>
									<div class="col-md-3">
										<label>Aplica a documento</label>
										<select class="form-control select2" id="apply_to" name="apply_to" onchange="add_tmp(this.value);">
											<option value="">-- Selecciona el documento --</option>
											<?php 
												$sql=mysqli_query($con,"select purchases.purchase_id, purchases.purchase_order_number, suppliers.name from purchases, suppliers where purchases.supplier_id=suppliers.id and purchases.status!=1 order by purchases.purchase_id desc");
												while ($rw=mysqli_fetch_array($sql)){
											?>
											<option value="<?php echo $rw['purchase_id']?>" ><?php echo $rw['purchase_order_number']." | Proveedor: ".$rw['name'];?></option>
											<?php
												}
											?>
										</select>
									</div>
									<div class="col-md-3">
                                       <label>Serie de documento</label>
                                       <input type="text" class="form-control" name="prefix" id="prefix" value="" >
                                    </div>
									<div class="col-md-3">
                                       <label>Documento Nº</label>
                                       <input type="number" class="form-control" name="number_document" id="number_document"  value=""  >
                                    </div>
									
								</div>
								<div class="row">
									
									
									<div class="col-md-3">
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
									
									<div class="col-md-3">
										<label>Incuye <?php echo strtoupper(tax_txt);?></label>
										<select name="is_taxeable" id="is_taxeable" class='form-control' onchange="return taxes(this.value);">
											<option value="1" <?php if ($_SESSION['includes_tax']==1){echo "selected";}?>>Si </option>
											<option value="0" <?php if ($_SESSION['includes_tax']==0){echo "selected";}?>>No</option>
										</select>
									</div>
                                    
									<div class="col-md-3">
                                        <label>Fecha de documento</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo date("d/m/Y")?>" disabled="">

                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
									
									<div class="col-md-3">
                                        <label>Selecciona Moneda</label>
                                        <div class="input-group">
											<div class="input-group-addon">
                                                <a href="#"><i class='fa fa-dollar'></i></a>
                                            </div>
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
									
									
                                    </div>

                                

                                


                            
                            </form>
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
		$( "#resultados" ).load( "./ajax/agregar_nc_tmp.php" );
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
        url: "./ajax/agregar_nc_tmp.php",
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
        url: "./ajax/agregar_nc_tmp.php",
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
	
		

<script>
$( ".guardar" ).click(function() {
   var customer_id=$("#customer_id").val();	
   var payment_method=$("#payment_method").val();
	payment_method=parseInt(payment_method);
  if (payment_method>1 &&  customer_id==""){
	  alert('Selecciona el proveedor');
	  return false;
  } 
	
   var parametros = $( "#venta" ).serialize();
   $.ajax({
        type: "POST",
        url: "./ajax/registro/nota_credito_buy.php",
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
	function taxes(value){
		$( "#resultados" ).load( "./ajax/agregar_nc_tmp.php?taxes="+value );
	}
</script>
<script>
	function currency(value){
		$( "#resultados" ).load( "./ajax/agregar_nc_tmp.php?currency_id="+value );
	}
	</script>
	
	<script>
	function add_tmp(value){
		$( "#resultados" ).load( "./ajax/agregar_nc_tmp.php?apply_to="+value );
		
	}
	</script>
	
	<script>
	function update_tmp(id, campo, valor){
		$( "#resultados" ).load( "./ajax/agregar_nc_tmp.php?id_tmp="+id+"&campo="+campo+"&valor="+valor );
		//alert(value);
		
	}
	</script>
  </body>
</html>
