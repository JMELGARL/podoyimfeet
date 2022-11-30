<?php

	$note_id=intval($_GET['id']);
	$_SESSION['note_id']=$note_id;
	$sql=mysqli_query($con,"select * from credit_notes where id='".$note_id."'");
	$count=mysqli_num_rows($sql);
	$rw=mysqli_fetch_array($sql);
	$note_number=$rw['note_number'];
	$customer_id=$rw['customer_id'];
	$branch_id=$rw['branch_id'];
	$prefix=$rw['note_prefix'];
	$seller_id=$rw['seller_id'];
	$apply_to=$rw['apply_to'];
	$currency_id=$rw['currency_id'];

	$nombre_sucursal = get_id('branch_offices','name','id',$branch_id);//Obtengo el nombre de la sucursal
	$nombre_cliente = get_id('customers','name','id',$customer_id);//Obtengo el nombre del cliente
	$note_date= date('d/m/Y', strtotime($rw['created_at']));
	$includes_tax=$rw['includes_tax'];
	
	if (!isset($_GET['id']) or $count!=1){
		//header("location: manage_invoice.php");
	}
	
	
?>
<!DOCTYPE html>
<html>
  <head>
  
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?>  sidebar-mini">
  	<?php 
		if ($permisos_editar==1){
		include("modal/facturacion.php");
		include("modal/agregar_cliente.php");
		}
	?>
    <div class="wrapper">
      <header class="main-header">
		<?php include("main-header.php");?>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
		<?php include("main-sidebar.php");?>
      </aside>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
		<?php if ($permisos_editar==1){?>
        
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
           <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Detalle de nota de crédito</h3>
              
            </div>
            <div class="box-body">
              <div class="row">
                <!-- *********************** Sale ************************** -->
                <div class="col-md-12 col-sm-12">
                    <form method="post">
                    <div class="row">
						<div class="col-md-3">
							<label>Sucursal</label>
							<select class="form-control" id="branch_id" name="branch_id" disabled>
								<option value="<?php echo $branch_id;?>"><?php echo $nombre_sucursal;?></option>
							</select>
						</div>
						<div class="col-md-3">
							<label>Aplica a documento</label>
							<select class="form-control" id="apply_to" name="apply_to" disabled>
								<option value="">-- Selecciona el documento --</option>
								<?php 
									$sql=mysqli_query($con,"select sales.sale_id, sales.sale_number, sales.sale_prefix, customers.name from sales, customers where sales.customer_id=customers.id and status!=1 order by sale_id desc");
									while ($rw=mysqli_fetch_array($sql)){
								?>
								<option value="<?php echo $rw['sale_id']?>" <?php if ($apply_to==$rw['sale_id']){echo "selected";} ?> ><?php echo $rw['sale_prefix']." ".$rw['sale_number']." | Cliente: ".$rw['name'];?></option>
								<?php
									}
								?>
							</select>
						</div>
						<div class="col-md-3">
                            <label>Serie de documento</label>
                            <input type="text" class="form-control" name="prefix" id="prefix" value="<?php echo $prefix;?>" readonly>
                        </div>
						<div class="col-md-3">
                            <label>Documento Nº</label>
                            <input type="number" class="form-control" name="number_document"  value="<?php echo $note_number;?>" disabled="">
                        </div>
						
					</div>
					<div class="row">
						<div class="col-md-3">
							<label>Cliente</label>
                            	<select class="form-control select2" name="customer_id" id="customer_id" disabled>
												<?php if (isset($quote_id) or isset($order_id)){?>
												<option value="<?php echo $customer_id;?>" selected><?php echo $customer_name;?></option>
												<?php }  
												 else { ?>
												<option value="<?php echo $customer_id;?>"><?php if (!empty($nombre_cliente)){echo $nombre_cliente;}else {"Selecciona el cliente";}?></option>
												<?php }?>
								</select>
							
                        </div>
									
					
									
						<div class="col-md-3">
										<label>Vendedor</label>
										<select name="seller_id" id="seller_id" class="form-control" onchange="return update_sale(4,this.value);" disabled>
										<?php
											$query_seller=mysqli_query($con,"select user_id, fullname from users where status=1");
											while ($rw=mysqli_fetch_array($query_seller)){
										?>
											<option value="<?php echo $rw['user_id'];?>" <?php if ($rw['user_id']==$seller_id){echo "selected";}?>><?php echo $rw['fullname'];?></option>		
										<?php
											}
										?>
										</select>
						</div>
						
						<div class="col-md-2">
							<label>Incuye <?php echo strtoupper(tax_txt);?></label>
							<select name="is_taxeable" id="is_taxeable" class='form-control' onchange="return update_sale(5,this.value);" disabled>
								<option value="1" <?php if ($includes_tax==1){echo "selected";}?>>Si </option>
								<option value="0" <?php if ($includes_tax==0){echo "selected";}?>>No</option>
							</select>
						</div>						
						
						<div class="col-md-2">
                            <label>Fecha</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" name="purchase_date"  value="<?php echo $note_date;?>" disabled="">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
						
						<div class="col-md-2">
                                        <label>Selecciona Moneda</label>
                                        <div class="input-group">
											<div class="input-group-addon">
                                                <a href="#"><i class='fa fa-dollar'></i></a>
                                            </div>
                                            <select class='form-control' disabled  name="currency_id" id="currency_id">
												<?php 
													$sql_currency=mysqli_query($con,"select id, name from currencies");
													while($rw=mysqli_fetch_array($sql_currency)){
														?>
												<option value='<?php echo $rw['id'];?>' <?php if ($currency_id== $rw['id']){echo "selected";}?>><?php echo $rw['name'];?></option>				
														<?php
													}
												?>
											</select>
                                           
                                        </div>
                        </div>
									
						
                    </div>
									
                </form>
            </div>
                        <!--/.col end -->
						


                    </div>
					<div id="resultados_ajax" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
					<div id="resultados" class='col-md-12' style="margin-top:4px"></div><!-- Carga los datos ajax -->
            </div><!-- /.box-body -->
            
          </div><!-- /.box -->	
     
        </section><!-- /.content -->
		<?php 
		} else{
		?>	
		<section class="content">
			<div class="alert alert-danger">
				<h3>Acceso denegado! </h3>
				<p>No cuentas con los permisos necesario para acceder a este módulo.</p>
			</div>
		</section>		
		<?php
		}
		?>
      </div><!-- /.content-wrapper -->
      <?php include("footer.php");?>
    </div><!-- ./wrapper -->
	<?php include("js.php");?>
	<!-- Select2 -->
	
    <script src="plugins/select2/select2.full.min.js"></script>
	<script>
	$(function () {
        //Initialize Select2 Elements
		$(".select2").select2();
		$("#resultados" ).load( "./ajax/agregar_nc.php" );
		
	});
	</script>
	

<script type="text/javascript">
$(document).ready(function() {
    $( ".select2" ).select2({        
    ajax: {
        url: "ajax/customers_select2.php",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term // search term
            };
        },
        processResults: function (data) {
            // parse the results into the format expected by Select2.
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data
            return {
                results: data
            };
        },
        cache: true
		
		
		
    },
    minimumInputLength: 2
	
}).on('change', function (e) {
	var customer_id=this.value;
	update_sale(2,customer_id);
});

});



</script>
	
</script>
  </body>
</html>
