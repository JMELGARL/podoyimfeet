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
	if (empty($_POST['cod_service'])){
			$errors[] = "El código del servicio está vacío.";
		}
	else if (empty($_POST['name_service'])){
			$errors[] = "El nombre del servicio está vacío.";
		}
	else if (empty($_POST['selling_price'])){
			$errors[] = "El precio del servicio está vacío.";
		}
	else if (
			!empty($_POST['cod_service']) && 
			!empty($_POST['name_service']) && 
			!empty($_POST['selling_price']) 
			
			)
		{
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que conecta a la base de datos
			// escaping, additionally removing everything that could be (html/javascript-) code
            $cod_service= mysqli_real_escape_string($con,(strip_tags($_POST["cod_service"],ENT_QUOTES)));
			$name_service= mysqli_real_escape_string($con,(strip_tags($_POST["name_service"],ENT_QUOTES)));
			$selling_price= floatval($_POST["selling_price"]);
			$status=1;
			$manufacturer_id=0;
			$buying_price=0;
			$is_service=1;
			$date_added=date("Y-m-d H:i:s");
			//Write register in to database 
			$sql = "INSERT INTO products (product_code, product_name, status, manufacturer_id, 	buying_price, selling_price, created_at, is_service) 
			VALUES('".$cod_service."','".$name_service."','".$status."','".$manufacturer_id."', '".$buying_price."', '".$selling_price."', '".$date_added."','".$is_service."');";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Servicio ha sido creado con éxito.";
				save_log('Servicios','Registro de servicio',$_SESSION['user_id']);
            } else {
                $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
            }
		} 
		else 
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