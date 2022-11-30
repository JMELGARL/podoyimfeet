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
		if (empty($_POST['order_id'])){
			$errors[] = "ID de la orden vacía. ";
		} else if (empty($_POST['customer_id'])){
			$errors[] = "Debes seleccionar el cliente. ";
		} else if (empty($_POST['product_description'])){
			$errors[] = "Ingresa la descripción o el nombre del equipo. ";
		} else if (empty($_POST['issue'])) {
            $errors[] = "Ingresa el problema del equipo. ";
        } else if (
			!empty($_POST['order_id'])
			&& !empty($_POST['customer_id'])
			&& !empty($_POST['product_description'])
			&& !empty($_POST['issue'])
		) {
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
                $order_id=intval($_POST['order_id']);
				$customer_id=intval($_POST['customer_id']);
				$status=intval($_POST['status']);
				$employee_id=intval($_POST['employee_id']);
				$model = mysqli_real_escape_string($con,(strip_tags($_POST["model"],ENT_QUOTES)));
				$brand = mysqli_real_escape_string($con,(strip_tags($_POST["brand"],ENT_QUOTES)));
				$serial_number = mysqli_real_escape_string($con,(strip_tags($_POST["serial_number"],ENT_QUOTES)));
				$product_description = mysqli_real_escape_string($con,(strip_tags($_POST["product_description"],ENT_QUOTES)));
				$accessories = mysqli_real_escape_string($con,(strip_tags($_POST["accessories"],ENT_QUOTES))); 
				$issue = mysqli_real_escape_string($con,(strip_tags($_POST["issue"],ENT_QUOTES))); 
				$note = mysqli_real_escape_string($con,(strip_tags($_POST["note"],ENT_QUOTES)));
				$delivery_date = mysqli_real_escape_string($con,(strip_tags($_POST["delivery_date"],ENT_QUOTES)));
               // write new  data into database
                    $sql = "UPDATE orders SET delivery_date='$delivery_date', customer_id='$customer_id', status='$status', employee_id='$employee_id', model='$model', brand='$brand', serial_number='$serial_number', product_description='$product_description', accessories='$accessories', issue='$issue', note='$note'  WHERE order_id='".$order_id."'";
                    $query = mysqli_query($con,$sql);

                    // if  has been update successfully
                    if ($query) {
                        $messages[] = "La orden ha sido actualizada con éxito.";
						save_log('Orden de servicio','Actualización de datos',$_SESSION['user_id']);
						
                    } else {
                        $errors[] = "Lo sentimos , el actualización falló. Por favor, regrese y vuelva a intentarlo.".mysqli_error($con);
                    }
                
			
		}else {
			$errors[] = "Error desconocido";	
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