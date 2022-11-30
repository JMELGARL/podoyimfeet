<?php
	$_SESSION['sale_id']=intval($_GET['id']);
	$sale_id=$_SESSION['sale_id'];
	$sql_sale=mysqli_query($con,"select * from sales, type_documents where sales.type=type_documents.id and sale_id='".$_SESSION['sale_id']."'");
	$count=mysqli_num_rows($sql_sale);
	$rw_sale=mysqli_fetch_array($sql_sale);
	$sale_number=$rw_sale['sale_number'];
	$customer_id=$rw_sale['customer_id'];
	$name_document=$rw_sale['name_document'];
	$sql_customer=mysqli_query($con,"select * from customers where id='".$customer_id."'");
	$rw_customer=mysqli_fetch_array($sql_customer);
	$customer_name=$rw_customer['name'];
	$customer_address=$rw_customer['address1'];
	$customer_city=$rw_customer['city'];
	$customer_state=$rw_customer['state'];
	$customer_postal_code=$rw_customer['postal_code'];
	$customer_work_phone=$rw_customer['work_phone'];
	$customer_id=$rw_customer['id'];
	
	if (!isset($_GET['id']) or $count!=1){
		echo "<script>window.close();</script>";
		exit;
	}
	
	$sale_date= date('d/m/Y', strtotime($rw_sale['sale_date']));
	
	$sql_contact=mysqli_query($con,"select email from  contacts where client_id='".$customer_id."'");
	$rw_contact=mysqli_fetch_array($sql_contact);
	$customer_email=$rw_contact['email'];
	
	/*Datos de la empresa*/
		$sql_empresa=mysqli_query($con,"SELECT business_profile.name, business_profile.tax, business_profile.address,  currencies.symbol, business_profile.city, business_profile.state, business_profile.postal_code, business_profile.phone, business_profile.email FROM  business_profile, currencies where business_profile.currency_id=currencies.id and business_profile.id=1");
		$rw_empresa=mysqli_fetch_array($sql_empresa);
		$moneda=$rw_empresa["symbol"];
		$tax=$rw_empresa["tax"];
		$bussines_name=$rw_empresa["name"];
		$address=$rw_empresa["address"];
		$city=$rw_empresa["city"];
		$state=$rw_empresa["state"];
		$postal_code=$rw_empresa["postal_code"];
		$phone=$rw_empresa["phone"];
		$email=$rw_empresa["email"];
		
	/*Fin datos empresa*/
?>
<!DOCTYPE html>
<html>
  <head>
  
	<?php include("head.php");?>
  </head>
	
  <body onload="window.print();">
    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <?php echo $bussines_name;?>
              <small class="pull-right">Fecha: <?php echo $sale_date;?></small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-5 invoice-col">
            Proveedor
			<address>
              <strong><?php echo $bussines_name;?></strong><br>
              <?php echo $address.", ". $city;?><br>
              <?php echo $state.", ". $postal_code;?><br>
              Teléfono: <?php echo $phone;?><br>
              Email: <?php echo $email;?>
            </address>
           
          </div><!-- /.col -->
          <div class="col-sm-5 invoice-col">
            Cliente
             <address>
              <strong><?php echo $customer_name;?></strong><br>
             <?php echo $customer_address." ". $customer_city;?><br>
             <?php echo $customer_state." ". $customer_postal_code;?><br>
              Teléfono: <?php echo $customer_work_phone;?><br>
              Email: <?php echo $customer_email;?>
            </address>
          </div><!-- /.col -->
          <div class="col-sm-2 invoice-col">
            <b><?php echo ucfirst($name_document);?> # <?php echo $sale_number;?></b><br>
            <br>
            
            
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>CODIGO</th>
                  <th class='text-center'>CANT.</th>
                  <th>DESCRIPCION</th>
                  <th><span class="pull-right">PRECIO UNIT.</span></th>
				  <th><span class="pull-right">DESCUENTO</span></th>
				  <th><span class="pull-right">PRECIO TOTAL</span></th>
                </tr>
              </thead>
              <tbody>
			  <?php
				$sumador_total=0;
				$sumador_descuento=0;
				$sql=mysqli_query($con, "select * from products, sale_product where products.product_id=sale_product.product_id and sale_product.sale_id='$sale_id'");
				while ($row=mysqli_fetch_array($sql)){
					$product_id=$row['product_id'];
					$sale_product_id=$row["sale_product_id"];
					$product_code=$row['product_code'];
					$qty=$row['qty'];
					$discount=intval($row['discount']);
					$product_name=$row['product_name'];
					$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');

					$precio_total=$unit_price*$qty;
					$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
					$descuento=($precio_total * $discount) / 100;
					$descuento=number_format($descuento,$precision_moneda,'.','');//Descuento Formateado
					$sumador_descuento+=$descuento;//Sumador
					$sumador_total+=$precio_total;//Sumador
					?>
				<tr>
                  <td><?php echo $product_code;?></td>
                  <td class='text-center'><?php echo $qty;?></td>
                  <td><?php echo $product_name;?></td>
                  <td><span class="pull-right"><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
				  <td><span class="pull-right"><?php echo number_format($descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
                  <td><span class="pull-right"><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></span></td>
                </tr>	
					
					<?php
				}
				$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
				$sumador_descuento=number_format($sumador_descuento,$precision_moneda,'.','');
				$total_neto=$total_parcial-$sumador_descuento;
				$total_neto=number_format($total_neto,$precision_moneda,'.','');
				$total_iva=($total_neto*$tax) / 100;
				$total_iva=number_format($total_iva,$precision_moneda,'.','');
				$total_compra=$total_neto+$total_iva;
				$total_compra=number_format($total_compra,$precision_moneda,'.','');
				if ($total_parcial>0){
					$percent=($sumador_descuento / $total_parcial) * 100;
				} else {
					$percent=0;
				}
			  ?>
                
                
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-8">
            
            
          </div><!-- /.col -->
          <div class="col-xs-4">
           
            <div class="table-responsive">
              <table class="table">
			  <?php if ($sumador_descuento>0){?>
				<tr>
					<th style="width:50%" class="text-right">Parcial <?php echo $moneda;?></td>
					<td class="text-right"><?php echo number_format($total_parcial,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					
				</tr>

				<tr>
					<th style="width:50%" class="text-right">Descuento <?php echo number_format($percent,2);?>% <?php echo $moneda;?></span></td>
					<td class="text-right"><?php echo number_format($sumador_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					
				</tr>
				<?php }?>
                <tr>
                  <th style="width:50%" class="text-right">Subtotal <?php echo $moneda;?></th>
                  <td class="text-right"><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
                </tr>
                <tr>
                  <th class="text-right">IGV (<?php echo $tax;?>%) <?php echo $moneda;?></th>
                  <td class="text-right"><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
                </tr>
                
                <tr>
                  <th class="text-right">Total <?php echo $moneda;?></th>
                  <td class="text-right"><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
                </tr>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- ./wrapper -->

    
    
  </body>
</html>
