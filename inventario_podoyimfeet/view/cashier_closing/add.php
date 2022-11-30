<?php
	/*Inicio carga de datos*/
		$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
		$nombre_sucursal = get_id('branch_offices','name','id',$id_sucursal);//Obtengo el nombre de la sucursal
		$id_sucursal=intval($id_sucursal );
		$cashbox_id=get_id('cashbox','id','user_id',$user_id);	
		$opening_balance=get_id('cashbox','opening_balance','id',$cashbox_id);
		$date_initial=get_id('cashbox','last_close','id',$cashbox_id);	
		$date_final=date("Y-m-d H:i:s");	
		$total_ingresos=total_ingresos($date_initial,$date_final,$user_id);
		$total_cobros=total_cobros($date_initial,$date_final,$cashbox_id);
		include("currency.php");//Archivo que obtiene los datos de la moneda
	/*Fin carga de datos*/
?>
<!DOCTYPE html>
<html>
  <head>
  
	<?php include("head.php");?>
  </head>
  <body class="hold-transition <?php echo $skin;?> sidebar-mini">
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
		<?php if ($permisos_editar==1 and $id_sucursal>0){?>
        
		<!-- Main content -->
        <section class="content">
          <!-- Default box -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Agregar nuevo corte de caja</h3>
              
            </div>
            <div class="box-body">
              <div class="row">
                <!-- *********************** Cortes ************************** -->
                <div class="col-md-12 col-sm-12">
					<table class="table table-bordered">
						<tr>
							<th>CONCEPTO</th>
							<th class='text-right'>CONTADO	</th>
							<th class='text-right'>CREDITO</th>
							<th class='text-right'>SALDO</th>
						</tr>
						<tr>
							<td>Fondo fijo</td>
							<td></td>
							<td></td>
							<td class='text-right'><strong><?php echo number_format($opening_balance,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
						</tr>
						<tr>
							<td>Ingresos</td>
							<td></td>
							<td></td>
							<td class='text-right'><strong><?php echo number_format($total_ingresos,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
						</tr>
					<?php 
						$query1=mysqli_query($con,"select id, name_document from type_documents");
						$sumador_contado=0;
						$sumador_credito=0;
						while($rw1=mysqli_fetch_array($query1)){
							$type=$rw1['id'];
							$total_contado=total_contado($date_initial,$date_final,$cashbox_id,$type);
							$sumador_contado+=$total_contado;
							$total_credito=total_credito($date_initial,$date_final,$cashbox_id,$type);
							$sumador_credito+=$total_credito;
							?>
						<tr>
							<td>Ventas con <?php echo $rw1['name_document'];?></td>
							<td class='text-right'><?php echo number_format($total_contado,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
							<td class='text-right'><?php echo number_format($total_credito,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
							<td class='text-right'><strong></strong></td>
						</tr>	
							<?php
						}
						
						$subtotal_contado=$total_cobros+$sumador_contado;
						$subtotal_credito=$sumador_credito;
						$fondo_fijo_ingresos=$opening_balance+$subtotal_contado;
						$total_egresos=total_egresos($date_initial,$date_final,$cashbox_id);
						$valor_entregar=$fondo_fijo_ingresos-$total_egresos;
					?>
						
						<tr>
							<td>INGRESOS (Cuentas por cobrar)</td>
							<td class='text-right'><?php echo number_format($total_cobros,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
							<td></td>
							<td class='text-right'><strong></strong></td>
						</tr>
						<tr>
							<th>SUB TOTAL</th>
							<th class='text-right'><?php echo number_format($subtotal_contado,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></th>
							<th class='text-right'><?php echo number_format($subtotal_credito,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></th>
							<th class='text-right'><strong></strong></th>
						</tr>
						<tr>
							<td>TOTAL FONDO FIJO MAS INGRESOS</td>
							<td></td>
							<td></td>
							<td class='text-right'><strong><?php echo number_format($fondo_fijo_ingresos,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
						</tr>
						<tr>
							<td>MENOS EGRESOS</td>
							<td></td>
							<td></td>
							<td class='text-right'><strong><?php echo number_format($total_egresos,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
						</tr>
						<tr>
							<td>MENOS FONDO FIJO</td>
							<td></td>
							<td></td>
							<td class='text-right col-md-2'>
							<div class="input-group input-group-sm">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" ><i class='fa fa-dollar'></i></button>
								</span>
								<input type='text' class='form-control input-sm' style='text-align:right;font-weight:bold' name='fondo_fijo' id='fondo_fijo' onkeyup='corte();' value='0.00' autofocus>
							</div>
							</td>
						</tr>
						<tr>
							<td>VALOR A ENTREGAR</td>
							<td></td>
							<td></td>
							<td class='text-right col-md-2'>
							<div class="input-group input-group-sm">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" ><i class='fa fa-dollar'></i></button>
								</span>
								<input type='text' class='form-control input-sm' readonly value="<?php echo number_format($valor_entregar,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?>" style='text-align:right;font-weight:bold' name='valor_entregar' id='valor_entregar'>
								<input type='hidden' value="<?php echo number_format($valor_entregar,$precision_moneda,'.','');?>" style='text-align:right;font-weight:bold' name='hidden_entrega' id='hidden_entrega'>
								
							</div>	
							</td>
						</tr>
					</table>	
					<div class='pull-right'>
						<button type="button" class="btn btn-default" onclick="imprimir_corte();"><i class="fa fa-print"></i> Imprimir</button>
					</div>
                </div>
                <!--/.col end -->		
						


              </div>
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
	<script src="dist/js/VentanaCentrada.js"></script>
	<script>
		function corte(){
			var fondo_fijo= $("#fondo_fijo").val();
			var valor_entregar= $("#valor_entregar").val();
			var hidden_entrega=$("#hidden_entrega").val();
			
			fondo_fijo=parseFloat(fondo_fijo);
			hidden_entrega=parseFloat(hidden_entrega);
			
			total=hidden_entrega-fondo_fijo;
			
			$("#valor_entregar").val(total);
				if(isNaN(fondo_fijo)){
				alert('Esto no en un numero');
				$("#fondo_fijo").focus();
				$("#valor_entregar").val(hidden_entrega);
				$("#efectivo").val(hidden_efectivo);
				return false;
			}
			
			var total= total.toFixed(2);
			$("#valor_entregar").val(total);
			
		}
		function imprimir_corte()
		{
			var fondo_fijo=$("#fondo_fijo").val();
			VentanaCentrada('cashier-closing-print-pdf.php?fondo_fijo='+fondo_fijo,'Corte','','1024','768','true');
			location.reload();
		}
	</script>
	
  
  </body>
</html>
