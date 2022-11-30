<?php
session_start();
$user_id=$_SESSION['user_id'];
	if (empty($_POST['referral_guide_id'])){
		$errors[] = "ID vacío.";
	} else if (empty($_POST['branch_id'])){
		$errors[] = "Selecciona la dirección de partida";
	} else if (empty($_POST['customer_id'])) {
		$errors[] = "Selecciona la dirección de llegada";
	} else if (empty($_POST['number'])) {
		$errors[] = "Ingresa el número de guía";
	} else if (empty($_POST['envio'])) {
		$errors[] = "Ingresa la unidad de transporte";
	} else if (empty($_POST['envio'])) {
		$errors[] = "Ingresa la unidad de transporte";
	} else if (empty($_POST['transportista'])) {
		$errors[] = "Ingresa el nombre del transportista";
	} else if (empty($_POST['motivo'])) {
		$errors[] = "Selecciona el motivo del traslado";
	} else if (empty($_POST['currency_id'])) {
		$errors[] = "Selecciona la moneda";
	} else if (empty($_POST['prefix'])) {
		$errors[] = "Tiraje de documento no ingresado";
	} else {
		require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
		require_once ("../../libraries/inventory.php");
		$branch_id=mysqli_real_escape_string($con,(strip_tags($_POST["branch_id"],ENT_QUOTES)));
		$customer_id=mysqli_real_escape_string($con,(strip_tags($_POST["customer_id"],ENT_QUOTES)));
		$prefix=mysqli_real_escape_string($con,(strip_tags($_POST["prefix"],ENT_QUOTES)));
		$number=mysqli_real_escape_string($con,(strip_tags($_POST["number"],ENT_QUOTES)));
		$comprobante=mysqli_real_escape_string($con,(strip_tags($_POST["comprobante"],ENT_QUOTES)));
		$envio=mysqli_real_escape_string($con,(strip_tags($_POST["envio"],ENT_QUOTES)));
		$transportista=mysqli_real_escape_string($con,(strip_tags($_POST["transportista"],ENT_QUOTES)));
		$motivo=mysqli_real_escape_string($con,(strip_tags($_POST["motivo"],ENT_QUOTES)));
		$is_taxeable=mysqli_real_escape_string($con,(strip_tags($_POST["is_taxeable"],ENT_QUOTES)));
		$currency_id=mysqli_real_escape_string($con,(strip_tags($_POST["currency_id"],ENT_QUOTES)));
		$referral_guide_id=intval($_POST['referral_guide_id']);
		
	
	
	
				
				$sql="update referral_guides set customer_id='$customer_id', comprobante='$comprobante', transport='$envio', carrier='$transportista', reason='$motivo', currency_id='$currency_id', includes_tax='$is_taxeable' where id='$referral_guide_id'";
				$query = mysqli_query($con,$sql);
				
				 // if has been added successfully
                    if ($query) {
                        $messages[] = "Los datos han sido actualizados con éxito.";
						save_log('Guías de remisión','Actualización de datos',$_SESSION['user_id']);
						
						
						

				} else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
                    }
				
			
		
	
	
		 
	}	


if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
						
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
						
				</div>
				
			
				<?php
			}