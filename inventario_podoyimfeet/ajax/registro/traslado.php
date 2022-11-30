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
if (empty($_POST['origin'])){
		$errors[] = "Selecciona la sucursal fuente";
	}elseif (empty($_POST['destination'])) {
        $errors[] = "Selecciona la sucursal destino";
    } elseif ($_POST['destination']==$_POST['origin']) {
        $errors[] = "Selecciona la sucursal fuente debe ser distinta a la sucursal destino";
    }elseif (
		!empty($_POST['origin'])
		&& !empty($_POST['destination'])
    ) {
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
            $origin = intval($_POST['origin']);
			$destination = intval($_POST['destination']);
			$created_at= date("Y-m-d H:i:s");
			$next_insert_id=next_insert_id('transfers');
			//Valida que hayan productos agregados
            $count_tmp=count_tmp($user_id);
			if ($count_tmp>0){
				//Almaceno el traslado
				$add_transfer= add_transfer($origin, $destination, $created_at, $user_id);
				if ($add_transfer==1){
						 $messages[] = "El traslado ha sido guardado con éxito.";
						 save_log('Traslados','Registro de traslado',$_SESSION['user_id']);
				} else {
						$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
				}	
			} else {
				$errors[] = "No hay productos agregados.";
			} 	
		} else {
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
				<center>
					<a href="transfers.php"  class="btn btn-default" style="margin-right: 5px;"><i class="fa fa-th"></i> Lista de traslados</a>
					<a href="new_transfer.php"  class="btn btn-info" style="margin-right: 5px;"><i class="fa fa-pencil"></i> Nuevo traslado</a>
					<a href="transfer-print-pdf.php?id=<?php echo $next_insert_id;?>" target="_blank" class="btn btn-success" style="margin-right: 5px;"><i class="fa fa-download"></i> Generar PDF</a>
				</center>
				<?php
			}
?>			