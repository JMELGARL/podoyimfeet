<?php
	$_SESSION['purchase_id']=intval($_GET['id']);
	$sql_purchase=mysqli_query($con,"select * from purchases where purchase_id='".$_SESSION['purchase_id']."'");
	$count=mysqli_num_rows($sql_purchase);
	$rw_purchase=mysqli_fetch_array($sql_purchase);
	$purchase_order_number=$rw_purchase['purchase_order_number'];
	$supplier_id=$rw_purchase['supplier_id'];
	$purchase_date=$rw_purchase['purchase_date'];
	$purchase_date= date('d/m/Y', strtotime($rw_purchase['purchase_date']));
	$payment_method=$rw_purchase['payment_method'];
	$includes_tax=$rw_purchase['includes_tax'];
	$currency_id=$rw_purchase['currency_id'];
	
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
		include("modal/agregar_proveedor.php");
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
		  <h1><i class='fa fa-edit'></i> Editar compra</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Editar Compra</h3>
              <div class='pull-right'>
				<button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#myModal"><i class='fa fa-search'></i> Buscar productos</button>
			  </div>
			  <div class='pull-right col-md-2'>
				 <div class = "input-group">
					 <span class = "input-group-addon"><i class='fa fa-dollar'></i></span>
					 <select class='form-control' onchange="return update_purchase(this.value,6);">
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
                                    <h3 class="box-title  ">Detalles de la compra</h3>
                                </div>

                                <div class="box-background">
                                <div class="box-body">
									<div class='row'>
									
									<div class="col-md-4">
                                        <label>Proveedor</label>
										<div class="input-group">
                                        <select class="form-control select2" name="supplier_id" onchange="return update_purchase(this.value,1);">
											<option value="">Selecciona Proveedor</option>
											<?php 
											$sql_supplier=mysqli_query($con,"select id, name from suppliers order by name");
											while ($rw=mysqli_fetch_array($sql_supplier)){
												if ($supplier_id==$rw['id']){$selected="selected";}
												else{$selected="";}
											?>
											<option value="<?php echo $rw['id'];?>" <?php echo $selected;?>><?php echo $rw['name'];?></option>
											<?php
											}
											?>
                                        </select>
											<span class="input-group-btn">
												<button class="btn btn-default" type="button" data-toggle="modal" data-target="#proveedor_modal"><i class='fa fa-plus'></i> Nuevo</button>
											</span>
										  </div>
                                    </div>
									<div class="col-md-2">

                                       <label>Forma de pago</label>
                                       <select name="payment_method" id="payment_method" class='form-control' onchange="return update_purchase(this.value,3);">
										<?php 
											$sql_method=mysqli_query($con,"select label, id from payment_methods order by days");
											while ($rw=mysqli_fetch_array($sql_method)){
												$label_id=$rw['id'];
												$label=$rw['label'];
										?>
											<option value="<?php echo $label_id;?>" <?php if ($payment_method==$label_id){echo "selected";} ?> ><?php echo $label;?></option>
										<?php
											}
										?>
									   </select>
                                    </div>
									
									<div class="col-md-2">

                                        <label>Compra Nº</label>
                                       <input type="text" class="form-control" name="purchase_order_number"  value="<?php echo $purchase_order_number;?>" onchange="return update_purchase(this.value,4);">
                                    </div>
									<div class="col-md-2">
                                        <label>Fecha</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $purchase_date;?>" onchange="return update_purchase(this.value,2);" readonly>

                                            <div class="input-group-addon">
                                                <a href="#" onclick="$('.datepicker').datepicker('show');"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
									
									<div class="col-md-2">
										<label>Incuye <?php echo strtoupper(tax_txt);?></label>
										<select name="is_taxeable" id="is_taxeable" class='form-control' onchange="return update_purchase(this.value,5);">
											<option value="1" <?php if ($includes_tax==1){echo "selected";}?>>Si </option>
											<option value="0" <?php if ($includes_tax==0){echo "selected";}?>>No</option>
										</select>
									</div>
									
									
									 
								</div>
								

                                </div><!-- /.box-body -->
                                    </div>


                                <div class="box-footer">

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
	<script>
	$(function () {
        //Initialize Select2 Elements
		$(".select2").select2();
		$(".datepicker").datepicker({
			 format: 'dd/mm/yyyy',
			 autoclose:true
		});
		$( "#resultados" ).load( "./ajax/agregar_compra.php" );
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
        url: "./ajax/agregar_compra.php",
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
        url: "./ajax/agregar_compra.php",
        data: "id="+id+"&id_sucursal="+id_sucursal,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}
		function update_purchase(value, campo){
		$.ajax({
        type: "POST",
        url: "./ajax/agregar_compra.php",
        data: "value="+value+"&campo="+campo,
			 success: function(datos){
			$("#resultados").html(datos);
			}
		});
		}
		
		

		
					
				

	</script>
	
	
	
  </body>
</html>
