<?php
session_start();
$user_id=$_SESSION['user_id'];
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../../libraries/password_compatibility_library.php");
}		
if (empty($_POST['type'])){
			$errors[] = "Selecciona el tipo de ajuste";
		}  elseif (empty($_POST['number_reference'])) {
            $errors[] = "Ingresa el número de referencia";
        }  elseif (
			!empty($_POST['type'])
			&& !empty($_POST['number_reference'])
        ) {
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
                $type = intval($_POST['type']);
				$number_reference	 = mysqli_real_escape_string($con,(strip_tags($_POST["number_reference"],ENT_QUOTES)));
				$note	 = mysqli_real_escape_string($con,(strip_tags($_POST["note"],ENT_QUOTES)));
				$created_at=date("Y-m-d H:i:s");
				//Valida que hayan productos agregados
               	$count_tmp=count_tmp($user_id);
				if ($count_tmp>0){
					//Almaceno la ajuste
					$add_purchase= add_adjustment($type, $number_reference, $note, $user_id,$created_at);
					
					if ($add_purchase==1){
						 $messages[] = "La compra ha sido creada con éxito.";
						 save_log('Ajuste inventario','Registro de ajuste de inventario',$_SESSION['user_id']);
					} else {
						$errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
					}
					
				} else {
					$errors[] = "No hay productos agregados.";
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