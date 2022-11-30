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
	if (empty($_POST['user_id'])){
			$errors[] = "Selecciona el cajero.";
		}else if (empty($_POST['cashbox_name'])){
			$errors[] = "Ingresa el nombre de la caja.";
		} else if (empty($_POST['id_suc'])){
			$errors[] = "Selecciona la sucursal.";
		} else if (empty($_POST['opening_balance'])){
			$errors[] = "Ingresa el balance inicial.";
		}  elseif (
			!empty($_POST['user_id'])
			&& !empty($_POST['cashbox_name'])
			&& !empty($_POST['id_suc'])
			&& !empty($_POST['opening_balance'])
			){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que conecta a la base de datos
			// escaping, additionally removing everything that could be (html/javascript-) code
            $user_id=intval($_POST['user_id']);
			$cashbox_name=mysqli_real_escape_string($con,(strip_tags($_POST["cashbox_name"],ENT_QUOTES)));
			$branch_id=intval($_POST['id_suc']);
			$opening_balance=floatval($_POST['opening_balance']);
			$created_at=date("Y-m-d H:i:s");
			//Write register in to database 
			$sql ="INSERT INTO  cashbox (id, cashbox_name, user_id,branch_id, opening_balance, last_close, created_at) VALUES (NULL, '$cashbox_name', '$user_id', '$branch_id', '$opening_balance', '$created_at','$created_at');";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Caja ha sido creado con éxito.";
				save_log('Cajeros','Registro de cajero',$_SESSION['user_id']);
            } else {
                $errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
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