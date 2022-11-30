<?php

session_start();
if (isset($_SESSION['user_id'])){
	$user_id=$_SESSION['user_id'];
	/* Connect To Database*/
	require_once ("../../config/db.php");
	require_once ("../../config/conexion.php");
	require_once ("../../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../../currency.php");//Archivo que obtiene los datos de la moneda
	if (isset($_GET["id"])){
		$id=$_GET["id"];
		$purchase_id=intval($id);
		if (isset($_REQUEST['payment_id'])){
			$payment_id=intval($_REQUEST['payment_id']);
			$delete="delete from payments where payment_id='$payment_id'";
			
			$transaction_id=get_id('payments','transaction_id','payment_id',$payment_id);
			if ($drop=mysqli_query($con,$delete)){
				$message="Datos eliminados satisfactoriamente.";
				balance_null($transaction_id,$user_id);
				change_status($transaction_id);
			} else {
				$error="Error al eliminar los datos ".mysqli_error($con);
			}
		}
	?>
	<div class="pull-right" style="margin-top:-60px;">
		<button class='btn btn-primary' data-toggle="modal" data-target="#agregarPagoModal" data-id="<?php echo $purchase_id;?>"><i class='fa fa-plus'></i> <div class="hidden-xs" style="display:inline-block">Agregar pago</div></button>
	</div>
	<?php
		if (isset($message)){
			?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Aviso: </strong><?php echo $message; ?>
		</div>
			<?php
		} 
		if (isset($error)){
			?>
		<div class="alert alert-error alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>Error: </strong><?php echo $error; ?>
		</div>		
			<?php
		}
	?>
	<table class="table">
		<tr>
			<th class='text-center'>#</th>
			<th>Fecha</th>
			<th>Forma pago</th>
			<th class='text-right'>Total</th>
			<th class='text-right'>Acciones</th>
		</tr>
		<?php 
			$query=mysqli_query($con,"select * from payments where purchase_id='$purchase_id'");
			$nums=1;
			while($row=mysqli_fetch_array($query)){
				$payment_id=$row['payment_id'];
				$payment_date=date("d/m/Y",strtotime($row['payment_date']));
				$total=$row['total'];
				$payment_type=$row['payment_type'];
				if ($payment_type==1){
					$forma_pago="Efectivo";
				} else if ($payment_type==2){
					$forma_pago="Cheque";
				} else if ($payment_type==3){
					$forma_pago="Transferencia bancaria";
				} else if ($payment_type==4){
					$forma_pago="Nota de cr&eacute;dito";
				}
				$number_reference=$row['number_reference'];
		?>
		<tr>
			<td class='text-center'><?php echo $nums;?></td>
			<td><?php echo $payment_date;?></td>
			<td>
			<?php 
				echo $forma_pago;
				if (!empty($number_reference)){
					echo "<br><small>Nº: $number_reference</small>";
				}
			?>
			</td>
			<td class='text-right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			<td class='text-right'>
				<a href='#' class='btn btn-default btn-sm' data-toggle="modal" data-target="#editarPagoModal" data-id="<?php echo $purchase_id;?>" data-payment_id="<?php echo $payment_id;?>"><i class='fa fa-edit'></i>  </a>
				<a href='#' class='btn btn-danger btn-sm' onclick="eliminar_pago('<?php echo $purchase_id?>','<?php echo $payment_id;?>')"><i class='fa fa-trash'></i> </a>
			</td>
		</tr>
		<?php	
		$nums++;
			}
			$total_pagado=sum_payment($purchase_id);
			$total_compra=sum_purchase($purchase_id);
			$total_pendiente=$total_compra-$total_pagado;
			
			$due_date=get_id('purchases','due_date','purchase_id',$purchase_id);
			$due_date=date('Ymd',strtotime($due_date));
			$fecha_hoy=date('Ymd');
			if ($total_pendiente==0){
				mysqli_query($con,"update purchases set status=1 where purchase_id='$purchase_id'");
			} else if ($fecha_hoy>$due_date){
				mysqli_query($con,"update purchases set status=3 where purchase_id='$purchase_id'");
			} else {
				mysqli_query($con,"update purchases set status=2 where purchase_id='$purchase_id'");
			}
				
			
		?>
		<tr>
			<th class='text-right' colspan='3'>Monto</th>
			<td class='text-right'><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></th>
			<td></td>
		</tr>
		<tr>
			<th class='text-right' colspan='3'>Abono</th>
			<td class='text-right'><?php echo number_format($total_pagado,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></th>
			<td></td>
		</tr>
		<tr>
			<th class='text-right' colspan='3'>Saldo</th>
			<td class='text-right'><?php echo number_format($total_pendiente,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></th>
			<td></td>
		</tr>		
	</table>
	<?php 	
	}
}

?>


