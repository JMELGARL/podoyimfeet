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
		} else if (empty($_POST['code2'])){
			$errors[] = "Código está vacío.";
		}else if (empty($_POST['name2'])){
			$errors[] = "Nombre está vacío.";
		} else if (empty($_POST['address2'])){
			$errors[] = "Dirección está vacía.";
		} else if (empty($_POST['phone2'])){
			$errors[] = "Teléfono está vacía.";
		} else if (empty($_POST['user_id2'])){
			$errors[] = "Selecciona contacto.";
		}  elseif (!empty($_POST['name2'])){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
            $code = mysqli_real_escape_string($con,(strip_tags($_POST["code2"],ENT_QUOTES)));
			$name = mysqli_real_escape_string($con,(strip_tags($_POST["name2"],ENT_QUOTES)));
			$address = mysqli_real_escape_string($con,(strip_tags($_POST["address2"],ENT_QUOTES)));
			$phone = mysqli_real_escape_string($con,(strip_tags($_POST["phone2"],ENT_QUOTES)));
			$user_id=intval($_POST['user_id2']);
			$status=intval($_POST['status2']);
			$mod_id=intval($_POST['mod_id']);
			//Write register in to database 
			$sql ="UPDATE branch_offices SET code='$code',name='$name',address='$address',phone='$phone',user_id='$user_id',status='$status' WHERE id='$mod_id'";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Sucursal ha sido actualizada con éxito.";
				save_log('Sucursales','Actualización de datos',$_SESSION['user_id']);
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