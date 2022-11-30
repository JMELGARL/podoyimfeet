<?php
session_start();
$user_id=$_SESSION['user_id'];
$includes_tax=intval($_SESSION['includes_tax']);
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../../libraries/password_compatibility_library.php");
}		
	if (empty($_POST['apply_to'])) {
            $errors[] = "Selecciona el documento a aplicar";
        } elseif(empty($_POST['branch_id'])){
			$errors[] = "Selecciona la sucursal";
		} elseif(empty($_POST['prefix'])){
			$errors[] = "Ingresa el tiraje del documento";
		} elseif(empty($_POST['number_document'])){
			$errors[] = "Ingresa el número del documento";
		} elseif(empty($_POST['seller_id'])){
			$errors[] = "Selecciona el vendedor";
		}  elseif (
			!empty($_POST['apply_to'])
			&& !empty($_POST['number_document'])
        ) {
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
                $apply_to = intval($_POST['apply_to']);
				$supplier_id = get_id("purchases","supplier_id","purchase_id",$apply_to);
				$number_document = intval($_POST['number_document']);
				$sale_date	 = date("Y-m-d H:i:s");
				$sale_prefix= mysqli_real_escape_string($con,(strip_tags($_POST["prefix"],ENT_QUOTES)));
				
				$seller_id=intval($_POST['seller_id']);
				$branch_id=intval($_POST['branch_id']);
				$currency_id=intval($_POST['currency_id']);
				
				$cashbox_id=get_id('cashbox','id','	user_id',$user_id);
				$transaction_type=1;
					
				$next_insert_id=next_insert_id("credit_notes");
				
				//Valida que hayan productos agregados
               	$count_tmp=count_tmp($user_id);
				if ($count_tmp>0){
					//Almaceno la nota de credito
					$add_note= add_note($number_document, $sale_prefix, $supplier_id, $user_id,$sale_date, $branch_id,$seller_id,$cashbox_id,$includes_tax,$apply_to,$transaction_type,$currency_id);
					if ($add_note==1){
						 $messages[] = "La nota de crédito ha sido guardada con éxito.";
						 save_log('Notas de crédito','Registro de nota de crédito compra',$_SESSION['user_id']);
						 
					} else {
						$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
					}
					
				} else {
					$errors[] = "No hay productos agregados a la venta.";
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