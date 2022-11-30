<?php
session_start();
$referral_guide_id=$_SESSION['referral_guide_id'];
$user_id=$_SESSION['user_id'];
	if (empty($_POST['branch_id'])){
		$errors[] = "Selecciona la dirección de partida";
	} else if (empty($_POST['customer_id'])) {
		$errors[] = "Selecciona la dirección de llegada";
	} else if (empty($_POST['number'])) {
		$errors[] = "Ingresa el número de guía";
	} else if (empty($_POST['envio'])) {
		$errors[] = "Ingresa la unidad de transporte";
	} else if (empty($_POST['envio'])) {
		$errors[] = "Ingresa la unidad de transporte";
	} else if (empty($_POST['transportista'])) {
		$errors[] = "Ingresa el nombre del transportista";
	} else if (empty($_POST['motivo'])) {
		$errors[] = "Selecciona el motivo del traslado";
	} else if (empty($_POST['currency_id'])) {
		$errors[] = "Selecciona la moneda";
	} else if (empty($_POST['prefix'])) {
		$errors[] = "Tiraje de documento no ingresado";
	} else {
		require_once ("../../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../../config/conexion.php");//Contiene funcion que conecta a la base de datos
		require_once ("../../libraries/inventory.php");
		$branch_id=mysqli_real_escape_string($con,(strip_tags($_POST["branch_id"],ENT_QUOTES)));
		$customer_id=mysqli_real_escape_string($con,(strip_tags($_POST["customer_id"],ENT_QUOTES)));
		$prefix=mysqli_real_escape_string($con,(strip_tags($_POST["prefix"],ENT_QUOTES)));
		$number=mysqli_real_escape_string($con,(strip_tags($_POST["number"],ENT_QUOTES)));
		$comprobante=mysqli_real_escape_string($con,(strip_tags($_POST["comprobante"],ENT_QUOTES)));
		$envio=mysqli_real_escape_string($con,(strip_tags($_POST["envio"],ENT_QUOTES)));
		$transportista=mysqli_real_escape_string($con,(strip_tags($_POST["transportista"],ENT_QUOTES)));
		$motivo=mysqli_real_escape_string($con,(strip_tags($_POST["motivo"],ENT_QUOTES)));
		$is_taxeable=mysqli_real_escape_string($con,(strip_tags($_POST["is_taxeable"],ENT_QUOTES)));
		$currency_id=mysqli_real_escape_string($con,(strip_tags($_POST["currency_id"],ENT_QUOTES)));
		
		$count_number=mysqli_query($con,"select count(*) as total from referral_guides where number='".$number."' and branch_id='".$branch_id."'");
		$rw=mysqli_fetch_array($count_number);
		$total_numero=intval($rw['total']);
		
		if ($total_numero==0){ //valida que no existe el numero de factura en la base de datos
			$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
			$sql=mysqli_query($con,"select id from type_documents where module=2");
			$rw=mysqli_fetch_array($sql);
			$id_type=$rw['id'];
			$get_document_printing=get_document_printing($id_type,$id_sucursal);
			$prefix=$get_document_printing['code'];
			$initial=$get_document_printing['initial'];
			$final=$get_document_printing['final'];
		
			$count_guia=count_guia($referral_guide_id);
		if ($count_guia>0){
				
			if ($number>=$initial and $number<=$final){
				
					$next="SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND   TABLE_NAME   = 'referral_guides'";
					$query_next=mysqli_query($con,$next);
					$rw_next=mysqli_fetch_array($query_next);
					$next_insert=$rw_next['AUTO_INCREMENT'];
	
	
				$created_at=date('Y-m-d H:i:s');
				$sql="INSERT INTO referral_guides (id, created_at, branch_id, customer_id, transport, carrier, reason, employee_id, number, comprobante, currency_id, includes_tax, status,prefix) 
				VALUES ('$next_insert', '$created_at', '$branch_id', '$customer_id', '$envio', '$transportista', '$motivo', '$user_id', '$number', '$comprobante', '$currency_id', '$is_taxeable', '0','$prefix');";
				$query = mysqli_query($con,$sql);
				
				 // if has been added successfully
                    if ($query) {
                        $messages[] = "Los datos han sido ingresados con éxito.";
						save_log('Guías de remisión','Registro de guía de remisión',$_SESSION['user_id']);
						
						$update=mysqli_query($con,"update referral_guide_product set referral_guide_id='$next_insert' where referral_guide_id='$referral_guide_id'");
						

				} else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
                    }
				
			} else {
				$errors[] = "Ingresa un número de documento entre $initial y $final ";
			}
		}	
		else {
			$errors[] = "No hay productos agregados";
		}
	
	
		} else {
			$errors[] = "El número de documento ya se encuentra registrado. Intenta con otro número.";
		}
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
				<hr>
				<center><button type="button" class='btn btn-default' onclick="quote_print('<?php echo $next_insert?>');"><i class='fa fa-print'></i> Imprimir</button>	</center>
				<?php
			}