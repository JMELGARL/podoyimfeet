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
if (empty($_POST['supplier_id'])){
			$errors[] = "Selecciona un proveedor";
		}  elseif (empty($_POST['purchase_date'])) {
            $errors[] = "Selecciona la fecha de la compra";
        } elseif (empty($_POST['payment_method'])) {
            $errors[] = "Selecciona la forma de pago";
        }  elseif (empty($_POST['order_number'])) {
            $errors[] = "Ingresa el número del documento";
        } elseif (
			!empty($_POST['supplier_id'])
			&& !empty($_POST['purchase_date'])
			&& !empty($_POST['order_number'])
		
        ) {
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
                $supplier_id = intval($_POST['supplier_id']);
				$purchase_date	 = mysqli_real_escape_string($con,(strip_tags($_POST["purchase_date"],ENT_QUOTES)));
				$order_number = mysqli_real_escape_string($con,(strip_tags($_POST["order_number"],ENT_QUOTES)));
				$is_taxeable=intval($_POST['is_taxeable']);
				$currency_id=intval($_POST['currency_id']);
				
				
				list($dia,$mes,$anio)=explode("/",$purchase_date);
				$created_at="$anio-$mes-$dia ".date("H:i:s");
				$payment_method=intval($_POST['payment_method']);
				$days=get_id('payment_methods','days','id',$payment_method);//obtengo los dias
				$days=intval($days);
				
				
				$due_date=sumardias($created_at,$days);
				if ($days==0){
					$status=1;
				} else {
					$status=2;
				}
				$count_number=mysqli_query($con,"select count(*) as total from purchases where purchase_order_number='".$order_number."' and supplier_id='".$supplier_id ."'");
				$rw=mysqli_fetch_array($count_number);
				$total_numero=intval($rw['total']);
				if ($total_numero==0){ //valida que no existe el numero de factura en la base de datos
					
				
				//Valida que hayan productos agregados
               	$count_tmp=count_tmp($user_id);
				if ($count_tmp>0){
					//Almaceno la compra
					$add_purchase= add_purchase($order_number, $supplier_id, $user_id,$created_at,$due_date, $payment_method,$status,$is_taxeable,$currency_id);
					
					if ($add_purchase==1){
						 $messages[] = "La compra ha sido creada con éxito.";
						 save_log('Compras','Ingreso de compra',$user_id);
					} else {
						$errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
					}
					
				} else {
					$errors[] = "No hay productos agregados a la compra.";
				}
		}  else {
					$errors[] = "El número de factura ya se encuentra registrado. Intenta con otro número.";
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