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
if (empty($_POST['type'])){
			$errors[] = "Selecciona el tipo de documento";
		}   elseif (empty($_POST['number_document'])) {
            $errors[] = "Ingresa el número de documento";
        } elseif(empty($_POST['branch_id'])){
			$errors[] = "Selecciona la sucursal";
		} elseif(empty($_POST['prefix'])){
			$errors[] = "Ingresa el tiraje del documento";
		} elseif(empty($_POST['seller_id'])){
			$errors[] = "Selecciona el vendedor";
		} elseif(intval($_POST['payment_method'])>1 and empty($_POST['customer_id'])){
			$errors[] = "Selecciona el cliente";
		} elseif(intval($_POST['payment_method'])<1 ){
			$errors[] = "Selecciona la forma de pago";
		} elseif (
			!empty($_POST['type'])
			&& !empty($_POST['number_document'])
        ) {
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once("../../libraries/inventory.php");
			// escaping, additionally removing everything that could be (html/javascript-) code
                $type = intval($_POST['type']);
				$customer_id = intval($_POST['customer_id']);
				$number_document = intval($_POST['number_document']);
				$sale_date	 = date("Y-m-d H:i:s");
				$sale_prefix= mysqli_real_escape_string($con,(strip_tags($_POST["prefix"],ENT_QUOTES)));
				$guia_number= mysqli_real_escape_string($con,(strip_tags($_POST["guia_number"],ENT_QUOTES)));
				$payment_method=intval($_POST['payment_method']);
				$seller_id=intval($_POST['seller_id']);
				$branch_id=intval($_POST['branch_id']);
				$currency_id=intval($_POST['currency_id']);
				
				$days=get_id('payment_methods','days','id',$payment_method);//obtengo los dias
				$cashbox_id=get_id('cashbox','id','	user_id',$user_id);
				$days=intval($days);
				$due_date=sumardias($sale_date,$days);
				if ($days==0){
					$status=1;
				} else {
					$status=2;
				}
				
				$count_number=mysqli_query($con,"select count(*) as total from sales where sale_number='".$number_document."' and type='".$type."' and branch_id='".$branch_id."'");
				$rw=mysqli_fetch_array($count_number);
				$total_numero=intval($rw['total']);
				if ($total_numero==0){ //valida que no existe el numero de factura en la base de datos
					
				$next_insert_id=next_insert_id("sales");
				
				//Valida que hayan productos agregados
               	$count_tmp=count_tmp($user_id);
				if ($count_tmp>0){
					//Actualizo numero de documentos en guias si  hubiere
					get_tmp_guia($number_document,$user_id);
					//Almaceno la venta
					$add_sale= add_sale($number_document, $sale_prefix, $customer_id, $user_id,$sale_date, $due_date, $type,$branch_id, $status,$seller_id,$cashbox_id,$payment_method,$includes_tax,$currency_id,$guia_number);
					if ($add_sale==1){
						 $messages[] = "La venta ha sido guardada con éxito.";
						 save_log('Ventas','Registro de venta',$_SESSION['user_id']);
						 
					} else {
						$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
					}
					
				} else {
					$errors[] = "No hay productos agregados a la venta.";
				}
		}  else {
					$errors[] = "El número de documento ya se encuentra registrado. Intenta con otro número.";
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
				<center>
					<a href="sale-print-pdf.php?id=<?php echo $next_insert_id;?>" target="_blank" class="btn btn-success" style="margin-right: 5px;"><i class="fa fa-download"></i> Generar PDF</a>
				</center>
				<?php
			}
?>			