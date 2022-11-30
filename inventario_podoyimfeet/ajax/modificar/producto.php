<?php
session_start();
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../../libraries/password_compatibility_library.php");
}
		$pattern = '/^\d+(?:\.\d{2})?$/';
		
		if (empty($_POST['product_id'])){
			$errors[] = "ID del producto está vacío";
		} else if (empty($_POST['product_code'])){
			$errors[] = "Código del producto está vacío";
		}else if (empty($_POST['product_name'])){
			$errors[] = "Nombre del producto está vacío";
		} else if (empty($_POST['manufacturer_id'])){
			$errors[] = "Fabricante está vacío";
		} else if (empty($_POST['buying_price'])){
			$errors[] = "Precio de compra está vacío";
		} else if (empty($_POST['selling_price'])){
			$errors[] = "Precio de venta está vacío";
		} else if(preg_match($pattern, $_POST['buying_price']) == '0'){
			$errors[] = "Precio de compra tiene un formato inválido. Asegurate que sea un número con 2 decimales";
		} else if(preg_match($pattern, $_POST['selling_price']) == '0'){
			$errors[] = "Precio de venta tiene un formato inválido. Asegurate que sea un número con 2 decimales";
		}  elseif (
			!empty($_POST['product_code'])
			&& !empty($_POST['product_name'])
			&& !empty($_POST['manufacturer_id'])
			&& !empty($_POST['buying_price'])
			&& !empty($_POST['selling_price'])
			) {
		
			require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
			// escaping, additionally removing everything that could be (html/javascript-) code
				$product_id=intval($_POST['product_id']);
                $product_code = mysqli_real_escape_string($con,(strip_tags($_POST["product_code"],ENT_QUOTES)));
				$product_name = mysqli_real_escape_string($con,(strip_tags($_POST["product_name"],ENT_QUOTES)));
				$model= mysqli_real_escape_string($con,(strip_tags($_POST["model"],ENT_QUOTES)));
				$note= mysqli_real_escape_string($con,$_POST["note"]);
				$status= mysqli_real_escape_string($con,(strip_tags($_POST["status"],ENT_QUOTES)));
				$manufacturer_id= mysqli_real_escape_string($con,(strip_tags($_POST["manufacturer_id"],ENT_QUOTES)));
				$buying_price= mysqli_real_escape_string($con,(strip_tags($_POST["buying_price"],ENT_QUOTES)));
				$category_id=intval($_POST['category_id']);
				$selling_price= mysqli_real_escape_string($con,(strip_tags($_POST["selling_price"],ENT_QUOTES)));
				$presentation= mysqli_real_escape_string($con,(strip_tags($_POST["presentation"],ENT_QUOTES)));
				$profit= intval($_POST["profit"]);
				$stock_min= intval($_POST["stock_min"]);
				
				// update data
                    $sql = "UPDATE products SET product_code='".$product_code."',model='".$model."',product_name='".$product_name."', presentation='".$presentation."',
					note='".$note."',status='".$status."', manufacturer_id='".$manufacturer_id."', category_id='".$category_id."', buying_price='".$buying_price."', 
					selling_price='".$selling_price."', profit='".$profit."', stock_min='".$stock_min."' WHERE product_id='$product_id' ";
                    $query = mysqli_query($con,$sql);

                    // if user has been update successfully
                    if ($query) {
                        $messages[] = "Los datos han sido procesados exitosamente.";
						save_log('Productos','Actualización de datos',$_SESSION['user_id']);
                    } else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo. ".mysqli_error($con);
                    }
                
			
		} else {
			$errors[] = " Desconocido";	
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