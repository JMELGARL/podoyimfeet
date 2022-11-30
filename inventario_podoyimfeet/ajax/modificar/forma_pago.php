<?php
include("is_logged.php");//Archivo comprueba si el usuario esta logueado
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../../libraries/password_compatibility_library.php");
}	
	if (empty($_POST['mod_id'])){
			$errors[] = "ID está vacío.";
		} else if (empty($_POST['label_edit'])){
			$errors[] = "Ingresa la descripción.";
		} else if (empty($_POST['days_edit'])){
			$errors[] = "Ingresa los días.";
		}   elseif (
			!empty($_POST['label_edit'])
			&& !empty($_POST['days_edit'])
			
			
		){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
			$label = mysqli_real_escape_string($con,(strip_tags($_POST["label_edit"],ENT_QUOTES)));
			$days = intval($_POST['days_edit']);
			
			$mod_id=intval($_POST['mod_id']);
			//Write register in to database 
			$sql ="UPDATE payment_methods SET label='$label', days='$days'  WHERE id='$mod_id'";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Datos  han sido actualizados con éxito.";
				save_log('Formas de pago','Actualización de datos',$_SESSION['user_id']);
            } else {
                $errors[] = "Lo sentimos, la actualización falló. Por favor, regrese y vuelva a intentarlo.".mysqli_error($con);
            }
		} else 
		{
			$errors[] = "desconocido.";	
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
?>			