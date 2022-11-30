<?php
include("is_logged.php");//Archivo comprueba si el usuario esta logueado
include("../../libraries/inventory.php");

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../../libraries/password_compatibility_library.php");
}	

	require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos

	/*Inicio carga de datos*/
		$user_id = $_SESSION['user_id'];
		$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
		$nombre_sucursal = get_id('branch_offices','name','id',$id_sucursal);//Obtengo el nombre de la sucursal
		$id_sucursal=intval($id_sucursal );
		$cashbox_id=get_id('cashbox','id','user_id',$user_id);	
		$opening_balance=get_id('cashbox','opening_balance','id',$cashbox_id);
		$date_initial=get_id('cashbox','last_close','id',$cashbox_id);	
		$date_final=date("Y-m-d H:i:s");	
		$total_ingresos=total_ingresos($date_initial,$date_final,$user_id);
		$total_cobros=total_cobros($date_initial,$date_final,$cashbox_id);
		
	/*Fin carga de datos*/
	
	if (empty($_POST['note2'])){
			$errors[] = "Ingresa el concepto del egreso.";
		}else if (empty($_POST['total2'])){
			$errors[] = "Ingresa el total del egreso.";
		} else if (empty($_POST['mod_id'])){
			$errors[] = "ID vacío.";
		} else if ($id_sucursal==0){
			$errors[] = "Usuario no autorizado para realizar egresos de caja.";
		} else if ($cashbox_id==0){
			$errors[] = "Usuario no autorizado para realizar egresos de caja.";
		} elseif (
				!empty($_POST['note2'])
				&& !empty($_POST['total2'])
				&& !empty($_POST['mod_id'])
				
				){
			
			// escaping, additionally removing everything that could be (html/javascript-) code
            $note = mysqli_real_escape_string($con,(strip_tags($_POST["note2"],ENT_QUOTES)));
			$total=floatval($_POST['total2']);
			$id=intval($_POST['mod_id']);
			//Write register in to database 
			$sql = "UPDATE cash_outflows SET note='$note', total='$total' WHERE id='$id' ";
			$query_new = mysqli_query($con,$sql);
            // if has been added successfully
            if ($query_new) {
                $messages[] = "Egreso ha sido actualizado con éxito.";
				save_log('Egresos','Actualización de datos',$_SESSION['user_id']);
            } else {
                $errors[] = "Lo sentimos, actualización falló. Por favor, vuelva a intentarlo.";
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