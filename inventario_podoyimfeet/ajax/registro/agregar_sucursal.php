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
	if (empty($_POST['code'])){
			$errors[] = "Código está vacío.";
		}else if (empty($_POST['name'])){
			$errors[] = "Nombre está vacío.";
		} else if (empty($_POST['address'])){
			$errors[] = "Dirección está vacía.";
		} else if (empty($_POST['phone'])){
			$errors[] = "Teléfono está vacía.";
		} else if (empty($_POST['user_id'])){
			$errors[] = "Selecciona contacto.";
		} elseif (!empty($_POST['name'])){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que conecta a la base de datos
			// escaping, additionally removing everything that could be (html/javascript-) code
            $code = mysqli_real_escape_string($con,(strip_tags($_POST["code"],ENT_QUOTES)));
			$name = mysqli_real_escape_string($con,(strip_tags($_POST["name"],ENT_QUOTES)));
			$address = mysqli_real_escape_string($con,(strip_tags($_POST["address"],ENT_QUOTES)));
			$phone = mysqli_real_escape_string($con,(strip_tags($_POST["phone"],ENT_QUOTES)));
			$user_id=intval($_POST['user_id']);
			$status=intval($_POST['status']);
			//Write register in to database 
			$sql ="INSERT INTO branch_offices (id, code, name, address, phone, user_id, status) VALUES (NULL, '$code', '$name', '$address', '$phone', '$user_id', '$status');";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Sucursal ha sido creada con éxito.";
				save_log('Sucursales','Registro de sucursal',$_SESSION['user_id']);
            } else {
                $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.".mysqli_error($con);
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