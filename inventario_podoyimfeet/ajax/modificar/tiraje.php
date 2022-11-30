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
			$errors[] = "Ingresa el código.";
		} else if (empty($_POST['initial2'])){
			$errors[] = "Ingresa el tiraje inicial.";
		} else if (empty($_POST['final2'])){
			$errors[] = "Ingresa el tiraje final.";
		} else if (empty($_POST['type_document2'])){
			$errors[] = "Selecciona el tipo de documento.";
		} else if (empty($_POST['id_suc2'])){
			$errors[] = "Selecciona la sucursal.";
		}  elseif (
			!empty($_POST['code2'])
			&& !empty($_POST['initial2'])
			&& !empty($_POST['final2'])
			&& !empty($_POST['type_document2'])
			&& !empty($_POST['id_suc2'])
			
		){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
			$code = mysqli_real_escape_string($con,(strip_tags($_POST["code2"],ENT_QUOTES)));
			$initial=intval($_POST['initial2']);
			$final=intval($_POST['final2']);
			$type_document=intval($_POST['type_document2']);
			$branch_id=intval($_POST['id_suc2']);
			$mod_id=intval($_POST['mod_id']);
			//Write register in to database 
			$sql ="UPDATE document_printing SET code='$code',initial='$initial',final='$final',type_document='$type_document', branch_id='$branch_id' WHERE id='$mod_id'";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Tiraje ha sido actualizado con éxito.";
				save_log('Tirajes','Actualización de datos',$_SESSION['user_id']);
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