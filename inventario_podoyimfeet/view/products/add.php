<?php
	
	
	$product_code=time()."-".$_SESSION['user_id'];
	$created_at=date("Y-m-d H:i:s");
	$target_dir="img/productos/product.png";
	$inser=mysqli_query($con,"INSERT INTO products (product_id, product_code, model, product_name, note, status, manufacturer_id, buying_price, selling_price, created_at, image_path) VALUES (NULL, '$product_code', '', '', '', '1', '', '0', '0', '$created_at', '$target_dir'); ");
	$sql_product=mysqli_query($con,"select * from products where  product_code='$product_code'");
	$rw_product=mysqli_fetch_array($sql_product);
	$product_id=$rw_product['product_id'];
	
	$count=mysqli_query($con,"select count(*) as total from products where manufacturer_id>0");
	$rw=mysqli_fetch_array($count);
	$product_codes=$rw['total']+1;
	save_log('Productos','Registro de producto',$_SESSION['user_id']);
	
	
?>
<!DOCTYPE html>
<html>
  <head>
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
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
		  <h1><i class='fa fa-edit'></i> Agregar nuevo producto</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
		<div class="row">
		
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
			<div id="load_img">
              <img class=" img-responsive" src="img/productos/product.png" alt="Bussines profile picture">
			  </div>

              <h3 class="profile-username text-center"></h3>

              <p class="text-muted text-center mail-text"></p>

              

              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
		<form name="update_register" id="update_register" class="form-horizontal" method="post" enctype="multipart/form-data">
		
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#details" data-toggle="tab" aria-expanded="false">Detalles del producto</a></li>
             
			  
            </ul>
            <div class="tab-content">
              <div id="resultados_ajax"></div>
           
             

              <div class="tab-pane active" id="details">
                
                  <div class="form-group ">
                    <label for="product_code" class="col-sm-2 control-label">Código</label>

                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="product_code" name="product_code"  value="<?php echo $product_codes;?>"required >
					   <input type="hidden"  id="product_id" name="product_id"  value="<?php echo $product_id;?>" >
                    </div>
					<label for="model" class="col-sm-2 control-label">Modelo</label>

                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="model" name="model" >
                    </div>
                  </div>
				 
				  <div class="form-group">
                    <label for="product_name" class="col-sm-2 control-label">Nombre</label>

                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="product_name" name="product_name" required >
                    </div>
					<label for="presentation" class="col-sm-2 control-label">Presentación</label>

                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="presentation" name="presentation" maxlength="100" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="note" class="col-sm-2 control-label">Descripción</label>
                    <div class="col-sm-10">
						<textarea class="form-control" name="note" id="note"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="manufacturer_id" class="col-sm-2 control-label">Fabricante</label>

                    <div class="col-sm-4">
                      <select class="form-control" name="manufacturer_id" id="manufacturer_id" required>
						<option value="">Selecciona</option>
						<?php 
							$sql=mysqli_query($con,"select *  from manufacturers where status=1 order by name");
							while ($rw=mysqli_fetch_array($sql)){
								$id=$rw['id'];
								$name=$rw['name'];
							?>
							<option value="<?php echo $id;?>"><?php echo $name;?></option>
							<?php
							}
						?>
					  </select>
                    </div>
					
					<label for="category_id" class="col-sm-2 control-label">Categoría</label>

                    <div class="col-sm-4">
                      <select class="form-control" name="category_id" id="category_id" required>
						<option value="">Selecciona</option>
						<?php 
							$sql=mysqli_query($con,"select *  from categories where status=1 order by name");
							while ($rw=mysqli_fetch_array($sql)){
								$id=$rw['id'];
								$name=$rw['name'];
							?>
							<option value="<?php echo $id;?>"><?php echo $name;?></option>
							<?php
							}
						?>
					  </select>
                    </div>
					
					
					
					
                  </div>

                   <div class="form-group">
                    <label for="buying_price" class="col-sm-2 control-label">Costo</label>

                    <div class="col-sm-4">
						<div class="input-group">
						  <div class="input-group-addon">
							<i class="fa fa-usd"></i>
						  </div>
						  <input type="text" class="form-control" id="buying_price" name="buying_price" required pattern="\d+(\.\d{2})?" title="precio con 2 decimales" onkeyup="precio_venta();">
						</div>
                    </div>
					 <label for="profit" class="col-sm-2 control-label">Utilidad</label>

                    <div class="col-sm-4">
						<div class="input-group">
						  <div class="input-group-addon">
							<strong>%</strong>
						  </div>
						  <input type="text" class="form-control" id="profit" name="profit" required pattern="\d{1,4}"  maxlength="4" onkeyup="precio_venta();" >
						</div>
                    </div>
					
					
                  </div>
				  
				  <div class="form-group">
                    <label for="selling_price" class="col-sm-2 control-label">Precio de venta</label>

                    <div class="col-sm-4">
						<div class="input-group">
						  <div class="input-group-addon">
							<i class="fa fa-usd"></i>
						  </div>
						  <input type="text" class="form-control" id="selling_price" name="selling_price" required pattern="\d+(\.\d{2})?" title="precio con 2 decimales">
						</div>
                    </div>
					
					
					<label for="status" class="col-sm-2 control-label">Estado</label>

                    <div class="col-sm-4">
                      <select class="form-control" name="status" id="status">
						<option value="1">Activo</option>
						<option value="0">Inactivo</option>
						</select>
                    </div>
					
					
					
					
                  </div>
                	  
				  
				  <div class="form-group">
				  
				  <label for="stock_min" class="col-sm-2 control-label">Stock mínimo</label>
                   <div class="col-sm-4">
                      <input type="text" class='form-control' name="stock_min" id="stock_min" required >
                    </div>
					
					<label for="image" class="col-sm-2 control-label">Imagen</label>
                    <div class="col-sm-4">
                      <input type="file" class='form-control' name="imagefile" id="imagefile" onchange="upload_image(<?php echo $product_id; ?>);">
                    </div>
				  </div>
				  
				  
                  
                  
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                      <button type="submit" class="btn btn-primary actualizar_datos">Guardar datos</button>
                    </div>
                  </div>
               
              </div>
              <!-- /.tab-pane -->
			  
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
		  </form>
        </div>
        <!-- /.col -->
      </div>
     
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
		function upload_image(product_id){
				$("#load_img").text('Cargando...');
				var inputFileImage = document.getElementById("imagefile");
				var file = inputFileImage.files[0];
				var data = new FormData();
				data.append('imagefile',file);
				data.append('product_id',product_id);
				
				
				$.ajax({
					url: "ajax/imagen_product_ajax.php",        // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data: data, 			  // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					success: function(data)   // A function to be called if request succeeds
					{
						$("#load_img").html(data);
						
					}
				});
				
			}
    </script>
		<script>
		$( "#update_register" ).submit(function( event ) {
		  $('.actualizar_datos').attr("disabled", true);
		  var parametros = $(this).serialize();
		  $.ajax({
				type: "POST",
				url: "./ajax/modificar/producto.php",
				data: parametros,
				 beforeSend: function(objeto){
					$("#resultados_ajax").html("Mensaje: Cargando...");
				  },
				success: function(datos){
				$("#resultados_ajax").html(datos);
				$('.actualizar_datos').attr("disabled", false);
				window.setTimeout(function() {
				$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();});}, 5000);
				
			  }
		});		
		  event.preventDefault();
		});
	</script>
	
	<script>
	function precio_venta(){
		var profit = $("#profit").val();
		var buying_price = $("#buying_price").val();
		
		var parametros = {"profit":profit,"buying_price":buying_price};
		$.ajax({
				dataType: "json",
				type:"POST",
				url:'./ajax/precio.php',
				data: parametros,
				 success:function(data){
					//$("#datos").html(data).fadeIn('slow');
				 $.each(data, function(index, element) {
					var precio= element.precio;
					$("#selling_price").val(precio);
                });
    
					
				}
			})
	}
	
	</script>
  </body>
</html>
