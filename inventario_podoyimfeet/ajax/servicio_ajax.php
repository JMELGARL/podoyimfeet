<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");
	require_once ("../config/conexion.php");
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$order_id = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$phone = mysqli_real_escape_string($con,(strip_tags($_REQUEST['phone'], ENT_QUOTES)));

	
	$sql=mysqli_query($con,"select * from orders, customers where orders.customer_id=customers.id and order_id='".$order_id."' and customers.work_phone='".$phone."'" );
	$count=mysqli_num_rows($sql);
	$rw=mysqli_fetch_array($sql);
	$customer_id=$rw['customer_id'];
	$sql_customer=mysqli_query($con,"select customers.name, customers.work_phone, contacts.first_name, contacts.last_name, contacts.phone, contacts.email from customers, contacts where customers.id=contacts.client_id and customers.id='$customer_id'");
	$rw_customer=mysqli_fetch_array($sql_customer);
	$name_customer=$rw_customer['name'];
	$work_phone=$rw_customer['work_phone'];
	$contact_name=$rw_customer['first_name']." ".$rw_customer['last_name'];
	$contact_phone=$rw_customer['phone'];
	$contact_email=$rw_customer['email'];
	$status=$rw['status'];
	$serial_number=$rw['serial_number'];
	$product_description=$rw['product_description'];
	$brand=$rw['brand'];
	$model=$rw['model'];
	$issue=$rw['issue'];
	$accessories=$rw['accessories'];
	$note=$rw['note'];
	$employee_id=$rw['employee_id'];
	$order_date= date('d/m/Y H:i:s', strtotime($rw['order_date']));
	

	if ($count>0){

	?>
		<br>
<div class="row">
	  
        <!-- left column -->
        <div class="col-md-4">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detalles del cliente</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              
			              <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="1" class="col-sm-2 control-label">Nombre</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control " id="1" value="<?php echo $name_customer;?>" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="2" class="col-sm-2 control-label">Teléfono</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control " id="2" value="<?php echo $work_phone;?>" readonly>
                  </div>
                </div>
				<div class="form-group">
                  <label for="3" class="col-sm-2 control-label">Contacto</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control " id="3" value="<?php echo $contact_name;?>" readonly>
                  </div>
                </div>
				<div class="form-group">
                  <label for="4" class="col-sm-2 control-label">Teléfono</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control " id="4" value="<?php echo $contact_phone;?>" readonly>
                  </div>
                </div>
				<div class="form-group">
                  <label for="5" class="col-sm-2 control-label">Email</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control " id="5" value="<?php echo $contact_email;?>" readonly>
                  </div>
                </div>
               
              </div>

            </form>
                
				
                
                
             

              
          
          </div>
          <!-- /.box -->

         </div>



		 <div class="col-md-5">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detalles del equipo</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              
			              <form class="form-horizontal">
              <div class="box-body">
               <div class="row">
                <div class="col-md-4">
                    <label>Modelo</label>
                    <input type="text" class="form-control" name="model" id="model" value="<?php echo $model;?>">
                </div>
				<div class="col-md-4">
                    <label>Marca</label>
                    <input type="text" class="form-control" name="brand" id="brand" value="<?php echo $brand;?>">
                </div>
				<div class="col-md-4">
                    <label>Número de serie</label>
                    <input type="text" class="form-control" name="serial_number" id="serial_number" value="<?php echo $serial_number;?>">
                </div>
				
				<div class="col-md-6">
                    <label>Descripción o nombre del equipo</label>
                    <input type="text" class="form-control" name="product_description" id="product_description" value="<?php echo $product_description;?>" required>
                </div>
				<div class="col-md-6">
                    <label>Accesorios</label>
                    <input type="text" class="form-control" name="accessories" id="accessories" value="<?php echo $accessories;?>">
                </div>
				
				<div class="col-md-12">
                    <label>Problema del equipo</label>
                    <textarea class="form-control" name="issue" id="issue" rows="3" required><?php echo $issue;?></textarea>
                </div>
				
				
				
				
               </div>
               
              </div>
          
            </form>
                
				
                
                
             

              
          
          </div>
          <!-- /.box -->

         </div>


		 <div class="col-md-3">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detalles de la orden</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              
			              <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="1" class="col-sm-2 control-label">Nº</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control " id="1" value="<?php echo $order_id;?>" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label for="2" class="col-sm-2 control-label">Fecha</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control " id="2" value="<?php echo $order_date;?>" readonly>
                  </div>
                </div>
				<div class="form-group">
                  <label for="3" class="col-sm-2 control-label">Estado</label>

                  <div class="col-sm-10">
                     <select class="form-control" name="status" id="status" disabled>
						<option value="1" <?php if ($status==1){echo "selected";}?>>En proceso</option>
						<option value="2" <?php if ($status==2){echo "selected";}?>>Presupuesto</option>
						<option value="3" <?php if ($status==3){echo "selected";}?>>Reparado</option>
						<option value="4" <?php if ($status==4){echo "selected";}?>>No reparado</option>
					</select>
                  </div>
                </div>
				<div class="form-group">
                  <label for="4" class="col-sm-2 control-label">Técnico</label>

                  <div class="col-sm-10">
                    <select class="form-control" name="employee_id" id="employee_id" disabled>
						<?php 
							$sql_user=mysqli_query($con,"select user_id, fullname from users where user_id='$employee_id' order by fullname");
							while ($rw_user=mysqli_fetch_array($sql_user)){
								?>
								<option value="<?php echo $rw_user['user_id'];?>" <?php if ($employee_id==$rw_user['user_id']){echo "selected";}?>><?php echo $rw_user['fullname'];?></option>
								<?php
							}
						?>
					</select>
                  </div>
                </div>
				
               
              </div>
              
            </form>
                
				
                
                
             

              
          
          </div>
          <!-- /.box -->

         </div>
		 
		 

		
		<div class="col-md-12">
          <!-- PRESUPUESTO -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">PRESUPUESTO</h3>
			  <div class="box-tools pull-right">
				<button type="button" class="btn btn-default " onclick="order_print('<?php echo $order_id;?>');"><i class="fa fa-print"></i> Imprimir</button>
			   </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
			  <?php
				/*Datos de la empresa*/
				$sql_empresa=mysqli_query($con,"SELECT * FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
				$rw_empresa=mysqli_fetch_array($sql_empresa);
				$moneda=$rw_empresa["symbol"];
				$tax=$rw_empresa["tax"];
				/*Fin datos empresa*/	
			  ?>
               <div class="table-responsive">
<table class="table">
<tr>
	<th>CODIGO</th>
	<th class='text-center'>CANT.</th>
	<th>DESCRIPCION</th>
	<th><span class="pull-right">PRECIO UNIT.</span></th>
	<th><span class="pull-right">DESCUENTO</span></th>
	<th><span class="pull-right">PRECIO TOTAL</span></th>
</tr>
<?php
	$sumador_total=0;
	$sumador_descuento=0;
	$sql=mysqli_query($con, "select * from products, order_product where products.product_id=order_product.product_id and order_product.order_id='$order_id'");
	while ($row=mysqli_fetch_array($sql))
	{
	$product_id=$row['product_id'];
	$order_product_id=$row["order_product_id"];
	$product_code=$row['product_code'];
	$qty=$row['qty'];
	$product_name=$row['product_name'];
	$unit_price=number_format($row['unit_price'],2,'.','');
	$porcentaje=$row['discount'] / 100;
	$precio_total=$unit_price*$qty;
	$total_descuento=$precio_total*$porcentaje;//Total descuento
	$total_descuento=number_format($total_descuento,2,'.','');//Formateo de numeros sin separador de miles (,)
	$precio_total=number_format($precio_total,2,'.','');//Precio total formateado
	$sumador_descuento+=$total_descuento;//Sumador descuento
	$sumador_total+=$precio_total;//Sumador
	
		?>
		<tr>
			<td><?php echo $product_code;?></td>
			<td class='text-center'><?php echo $qty;?></td>
			<td><?php echo $product_name;?></td>
			<td><span class="pull-right"><?php echo number_format($unit_price,2);?></span></td>
			<td><span class="pull-right"><?php echo number_format($total_descuento,2);?></span></td>
			<td><span class="pull-right"><?php echo number_format($precio_total,2);?></span></td>
		</tr>		
		<?php
		
	}
	
	$total_parcial=number_format($sumador_total,2,'.','');
	$sumador_descuento=number_format($sumador_descuento,2,'.','');
	$total_neto=$total_parcial-$sumador_descuento;
	$total_neto=number_format($total_neto,2,'.','');
	
	


	
	
	
		$total_iva=($total_neto*$tax) / 100;
		$total_iva=number_format($total_iva,2,'.','');
		$total_cotizacion=$total_neto+$total_iva;
		$update=mysqli_query($con,"update orders set subtotal='$total_neto', tax='$total_iva', total='$total_cotizacion' where order_id='$order_id'");
	
	
?>


<tr>
	<td colspan=5><span class="pull-right">PARCIAL <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_parcial,2);?></span></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">DESCUENTO <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($sumador_descuento,2);?></span></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">NETO <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_neto,2);?></span></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">IGV <?php echo "$tax% $moneda";?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_iva,2);?></span></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">TOTAL <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_cotizacion,2);?></span></td>

</tr>
</table>
</div>
                
				</div>
              <!-- /.box-body -->

              
          
          </div>
          <!-- /.box -->

         </div>
		 
      </div>

	<?php	
	}	
	else {
		?>
		<br>
		<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <h3>Nada Encontrado!</h3>No hemos encontrado resultados con los términos de búsqueda  Por favor, inténtelo de nuevo con su (RFC ó su IFE) y su número de orden de servicio asignado. </div>
		<?php
	}
}
?>          
		  
