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
		} else if (empty($_POST['cashbox_name2'])){
			$errors[] = "Ingresa el nombre de la caja.";
		} else if (empty($_POST['user_id2'])){
			$errors[] = "Selecciona el cajero.";
		} else if (empty($_POST['id_suc2'])){
			$errors[] = "Selecciona la sucursal.";
		} else if (empty($_POST['opening_balance2'])){
			$errors[] = "Selecciona el fondo inicial.";
		}   elseif (
			!empty($_POST['mod_id'])
			&& !empty($_POST['cashbox_name2'])
			&& !empty($_POST['user_id2'])
			&& !empty($_POST['id_suc2'])
			&& !empty($_POST['opening_balance2'])
			
		){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
			$cashbox_name = mysqli_real_escape_string($con,(strip_tags($_POST["cashbox_name2"],ENT_QUOTES)));
			$user_id=intval($_POST['user_id2']);
			$id_suc=intval($_POST['id_suc2']);
			$opening_balance=floatval($_POST['opening_balance2']);
			$mod_id=intval($_POST['mod_id']);
			//Write register in to database 
			$sql ="UPDATE cashbox SET cashbox_name='$cashbox_name', user_id='$user_id', branch_id='$id_suc', opening_balance='$opening_balance'  WHERE id='$mod_id'";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Caja ha sido actualizada con éxito.";
				save_log('Cajas','Actualización de datos',$_SESSION['user_id']);
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