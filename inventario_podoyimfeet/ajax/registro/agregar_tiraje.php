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
			$errors[] = "Ingresa el código.";
		}else if (empty($_POST['initial'])){
			$errors[] = "Ingresa el tiraje inicial.";
		} else if (empty($_POST['final'])){
			$errors[] = "Ingresa el tiraje final.";
		} else if (empty($_POST['type_document'])){
			$errors[] = "Selecciona el tipo de documento.";
		} else if (empty($_POST['id_suc'])){
			$errors[] = "Selecciona la sucursal.";
		}  elseif (
			!empty($_POST['code'])
			&& !empty($_POST['initial'])
			&& !empty($_POST['final'])
			&& !empty($_POST['type_document'])
			&& !empty($_POST['id_suc'])
			
			){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que conecta a la base de datos
			// escaping, additionally removing everything that could be (html/javascript-) code
            $code = mysqli_real_escape_string($con,(strip_tags($_POST["code"],ENT_QUOTES)));
			$initial=intval($_POST['initial']);
			$final=intval($_POST['final']);
			$type_document=intval($_POST['type_document']);
			$branch_id=intval($_POST['id_suc']);
			$created_at=date("Y-m-d H:i:s");
			//Write register in to database 
			$sql ="INSERT INTO  document_printing (id, code, initial, final, type_document, branch_id, created_at) VALUES (NULL, '$code', '$initial', '$final', '$type_document','$branch_id','$created_at');";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Tiraje ha sido creado con éxito.";
				save_log('Tirajes','Registro de tiraje de documento',$_SESSION['user_id']);
            } else {
                $errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.".mysqli_error($con);
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