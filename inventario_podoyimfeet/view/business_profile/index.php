<?php

/*Datos de la empresa*/
	$sql1=mysqli_query($con,"SELECT * FROM business_profile where id=1");
	$rw1=mysqli_fetch_array($sql1);
	$name=$rw1["name"];
	$industry=$rw1["industry"];
	$number_id=$rw1['number_id'];
	$email=$rw1['email'];
	$phone=$rw1['phone'];
	$tax=$rw1['tax'];
	$currency_id=$rw1['currency_id'];
	$timezone_id=$rw1['timezone_id'];
	$address=$rw1['address'];
	$city=$rw1['city'];
	$postal_code=$rw1['postal_code'];
	$state=$rw1['state'];
	$country_id=$rw1['country_id'];
	$logo_url=$rw1['logo_url'];
	$skin_id=$rw1['skin_id'];
	/*Fin datos empresa*/
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
		<?php if ($permisos_editar==1){?>
        <section class="content-header">
		  <h1>PERFIL DE LA EMPRESA</h1>
		
		</section>
		<!-- Main content -->
        <section class="content">
		<div class="row">
		
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
			<div id="load_img">
              <img class="img-responsive" src="<?php echo $logo_url;?>" alt="Bussines profile picture">
			  </div>

              <h3 class="profile-username text-center"><?php echo $name;?></h3>

              <p class="text-muted text-center mail-text"><?php echo $email;?></p>

              

              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
		<form class="form-horizontal" method="post" enctype="multipart/form-data" name="profi">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#details" data-toggle="tab" aria-expanded="false">Detalles</a></li>
              <li class=""><a href="#address" data-toggle="tab" aria-expanded="false">Dirección</a></li>
            </ul>
            <div class="tab-content">
              <div id="resultados_ajax"></div>
           
             

              <div class="tab-pane active" id="details">
                
                  <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">Razon Social</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="business_name" name="business_name" placeholder="Nombre de la empresa" value="<?php echo $name;?>">
                    </div>
                  </div>
				  <div class="form-group">
                    <label for="industry" class="col-sm-3 control-label">Actividad económica</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="industry" name="industry" placeholder="Actividad económica" value="<?php echo $industry;?>">
                    </div>
                  </div>
				  <div class="form-group">
                    <label for="number_id" class="col-sm-3 control-label">RUC</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="number_id" name="number_id" placeholder="Número de Ruc" required maxlength="11" value="<?php echo $number_id;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-3 control-label">Correo electrónico</label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com" value="<?php echo $email;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="phone" class="col-sm-3 control-label">Teléfono</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="phone" name="phone" placeholder="Teléfono" value="<?php echo $phone;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="tax" class="col-sm-3 control-label">IGV %</label>
                    <div class="col-sm-9">
						<input type="text" class="form-control" id="tax" name="tax" placeholder="Impuesto" value="<?php echo $tax;?>" maxlength=2>
                    </div>
                  </div>
				  <div class="form-group">
                    <label for="currency" class="col-sm-3 control-label">Moneda</label>

                    <div class="col-sm-9">
					<?php 
					$query_currencies=mysqli_query($con,"select * from currencies");
					?>
						<select class='form-control select2' name="currency" id="currency">
						<?php
							while ($rw_currencies=mysqli_fetch_array($query_currencies)){
								?>
								<option value="<?php echo $rw_currencies['id'];?>" <?php if ($rw_currencies['id']==$currency_id){echo "selected";}else {echo "";}?>><?php echo $rw_currencies['name'];?></option>
								<?php 
							}
						?>
							
						</select>
                    </div>
                  </div>
				  <div class="form-group">
                    <label for="timezone" class="col-sm-3 control-label">Zona horaria</label>

                    <div class="col-sm-9">
						<?php 
					$query_timezones=mysqli_query($con,"select * from timezones order by name");
					?>
						<select class='form-control select2' name="timezone" id="timezone">
						<?php
							while ($rw_timezones=mysqli_fetch_array($query_timezones)){
								?>
								<option value="<?php echo $rw_timezones['id'];?>" <?php if ($timezone_id==$rw_timezones['id']){echo "selected";}else {echo "";}?>><?php echo $rw_timezones['name'];?></option>
								<?php 
							}
						?>
							
						</select>
                    </div>
                  </div>
				  <div class="form-group">
                    <label for="timezone" class="col-sm-3 control-label">Tema</label>

                    <div class="col-sm-9">
					<?php 
					$query_skin=mysqli_query($con,"select * from skins order by name");
					?>
						<select class='form-control' name="theme" id="theme">
						<?php
							while ($rw_sk=mysqli_fetch_array($query_skin)){
								?>
								<option value="<?php echo $rw_sk['id'];?>" <?php if ($skin_id==$rw_sk['id']){echo "selected";}else {echo "";}?>><?php echo $rw_sk['name'];?></option>
								<?php 
							}
						?>
							
						</select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="image" class="col-sm-3 control-label">Logo</label>

                    <div class="col-sm-9">
                      <input type="file" name="imagefile" id="imagefile" onchange="upload_image();">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                      <button type="button" class="btn btn-primary" onclick="return updateProfile();">Guardar datos</button>
                    </div>
                  </div>
                
              </div>
              <!-- /.tab-pane -->
			   <div class="tab-pane" id="address">
               
                  <div class="form-group">
                    <label for="address1" class="col-sm-3 control-label">Direccion Fiscal</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="address1" name="address1" placeholder="Calle" value="<?php echo $address;?>">
                    </div>
                  </div>
				  <div class="form-group">
                    <label for="city" class="col-sm-3 control-label">Provincia</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="city" name="city" placeholder="Ciudad" value="<?php echo $city;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="state" class="col-sm-3 control-label">Distrito</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="state" name="state" placeholder="Región/Provincia" value="<?php echo $state;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="postal_code" class="col-sm-3 control-label">Código Postal</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Código Postal" value="<?php echo $postal_code;?>">
                    </div>
                  </div>
                  <div class="form-group">
				  
                    <label for="country_id" class="col-sm-3 control-label">País</label>

                    <div class="col-sm-9">
						<?php 
						$query_countries=mysqli_query($con,"select * from countries order by name");
						?>
						
						<select class="form-control select2" name="country_id" id="country_id" style='width:100%'>
							<?php
								while ($rw_countries=mysqli_fetch_array($query_countries)){
									?>
									<option value="<?php echo $rw_countries['id'];?>" <?php if ($country_id==$rw_countries['id']){echo "selected";}else {echo "";}?>><?php echo utf8_encode($rw_countries['name']);?></option>
									<?php 
								}
							?>
						</select>
                    </div>
                  </div>
				  
				  
                  
                  
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                      <button type="button" class="btn btn-primary" name="update" onclick="return updateProfile();">Guardar datos</button>
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
	 <script src="plugins/select2/select2.full.min.js"></script>
	<script>
		$(function () {
        //Initialize Select2 Elements
		$(".select2").select2();
		
	});
		function updateProfile(){
			var business_name=$("#business_name").val();
			var industry=$("#industry").val();
			var number_id=$("#number_id").val();
			var email=$("#email").val();
			var phone=$("#phone").val();
			var tax=$("#tax").val();
			var currency=$("#currency").val();
			var timezone=$("#timezone").val();
			var address1=$("#address1").val();
			var city=$("#city").val();
			var state=$("#state").val();
			var postal_code=$("#postal_code").val();
			var country_id=$("#country_id").val();
			var theme=$("#theme").val();
			
			var parametros = {"business_name":business_name,"number_id":number_id,"email":email,"phone":phone,"tax":tax,
			"currency":currency, "timezone":timezone,"address1":address1,"city":city,"state":state,"postal_code":postal_code,"country_id":country_id,"industry":industry,"theme":theme };
			 $.ajax({
				type: "POST",
				url: "./ajax/modificar/perfil.php",
				data: parametros,
				 beforeSend: function(objeto){
					$("#resultados_ajax").html("Mensaje: Cargando...");
				  },
				success: function(datos){
				$("#resultados_ajax").html(datos);
				$(".profile-username").html(business_name);
				$(".mail-text").html(email);
				
			  }
			});
			
			
		}
	</script>
	<script>
		function upload_image(){
				
				var inputFileImage = document.getElementById("imagefile");
				var file = inputFileImage.files[0];
				if( (typeof file === "object") && (file !== null) )
				{
					$("#load_img").text('Cargando...');	
					var data = new FormData();
					data.append('imagefile',file);
					
					
					$.ajax({
						url: "ajax/imagen_ajax.php",        // Url to which the request is send
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
				
				
			}
    </script>
  </body>
</html>
