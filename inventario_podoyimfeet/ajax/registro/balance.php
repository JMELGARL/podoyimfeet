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
	if (empty($_POST['description'])){
			$errors[] = "Ingresa la descripción";
		} else if(empty($_POST['amount'])){
			$errors[] = "Ingresa la monto";
		} else if(empty($_POST['type'])){
			$errors[] = "Selecciona el tipo de transacción";
		} elseif (
				!empty($_POST['description']) && 
				!empty($_POST['amount']) && 
				!empty($_POST['type']) 
				){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que conecta a la base de datos
			// escaping, additionally removing everything that could be (html/javascript-) code
            $description = mysqli_real_escape_string($con,(strip_tags($_POST["description"],ENT_QUOTES)));
			$amount=floatval($_POST['amount']);
			$type=intval($_POST['type']);
			$date_added=date("Y-m-d H:i:s");
			
			$ultimo_saldo=get_balance();
			if ($type==1){
				$balance=$ultimo_saldo+$amount;
			} else if ($type==2){
				$balance=$ultimo_saldo-$amount;
			}
			$user_id=$_SESSION['user_id'];
			//Write register in to database 
			$sql = "INSERT INTO finances (id, description, type, amount, balance, created_at, user_id, status)
			VALUES(NULL, '".$description."','".$type."','".$amount."','".$balance."','".$date_added."','".$user_id."','1');";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Fabricante ha sido creado con éxito.";
				save_log('Finanzas','Registro de datos',$_SESSION['user_id']);
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