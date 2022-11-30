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
	if (empty($_POST['name_document'])){
			$errors[] = "Ingresa el nombre del documento.";
		}else if (empty($_POST['format'])){
			$errors[] = "Selecciona el formato del documento.";
		} else if (empty($_POST['orientation'])){
			$errors[] = "Selecciona la orientación del documento.";
		}  elseif (
			!empty($_POST['name_document'])
			&& !empty($_POST['format'])
			&& !empty($_POST['orientation'])
			
			){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que conecta a la base de datos
			// escaping, additionally removing everything that could be (html/javascript-) code
            $name_document = mysqli_real_escape_string($con,(strip_tags($_POST["name_document"],ENT_QUOTES)));
			$format = mysqli_real_escape_string($con,(strip_tags($_POST["format"],ENT_QUOTES)));
			$orientation = mysqli_real_escape_string($con,(strip_tags($_POST["orientation"],ENT_QUOTES)));
			$is_taxeable=1;
			$module=intval($_POST['module']);
			
			//Write register in to database 
			$sql ="INSERT INTO  type_documents (id, name_document, format, orientation, is_taxeable, module) VALUES (NULL, '$name_document', '$format', '$orientation', '$is_taxeable','$module');";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Documento ha sido creado con éxito.";
				save_log('Documentos','Registro de documento',$_SESSION['user_id']);
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