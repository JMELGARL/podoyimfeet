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
	if (empty($_POST['sale_id'])){
			$errors[] = "ID está vacío.";
		} elseif (empty($_POST['total'])){
			$errors[] = "Total vacío.";
		} elseif (
			!empty($_POST['sale_id']) &&
			!empty($_POST['total'])
		
		){
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
			require_once ("../../currency.php");//Contiene funcion que controla stock en el inventario
			// escaping, additionally removing everything that could be (html/javascript-) code
            $sale_id=intval($_POST['sale_id']);
			$payment_type=intval($_POST['payment_type']);
			$total=floatval($_POST['total']);
			$number_reference = mysqli_real_escape_string($con,(strip_tags($_POST["number_reference"],ENT_QUOTES)));
			$note = mysqli_real_escape_string($con,(strip_tags($_POST["note"],ENT_QUOTES)));
			$payment_date=date("Y-m-d H:i:s");
			$user_id=$_SESSION['user_id'];
			
			// Valido cuanto cobro
			$total_pagado=sum_charge($sale_id);
			$total_deuda=sum_sale($sale_id);
			$saldo= $total_deuda - $total_pagado;
			$saldo=number_format($saldo,$precision_moneda,'.','');
			if ($saldo>=$total){
				//Write register in to database 
				$sql = "INSERT INTO charges (charge_id, sale_id, total, payment_date, payment_type, number_reference, note, user_id) VALUES
				(NULL, '".$sale_id."','".$total."', '".$payment_date."','".$payment_type."','".$number_reference."','".$note."','".$user_id."');";
				$query_new = mysqli_query($con,$sql);
				// if has been added successfully
				if ($query_new) {
					$messages[] = "Cobro ha sido creado con éxito.";
					save_log('Cobros','Registro de cobros',$_SESSION['user_id']);
				} else {
					$errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
				}
			}
			else {
				$errors[] = "El monto a cobrar debe ser menor al saldo total.";
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