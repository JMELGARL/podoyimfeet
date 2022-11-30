<?php

session_start();
if (isset($_SESSION['user_id'])){
	/* Connect To Database*/
	require_once ("../../config/db.php");
	require_once ("../../config/conexion.php");
	require_once ("../../libraries/inventory.php");//Contiene funcion que controla stock en el inventario
	include("../../currency.php");//Archivo que obtiene los datos de la moneda
	if (isset($_GET["id"])){
		$id=$_GET["id"];
		$sale_id=intval($id);
		if (isset($_REQUEST['charge_id'])){
			$charge_id=intval($_REQUEST['charge_id']);
			$delete="delete from charges where charge_id='$charge_id'";
			
			if ($drop=mysqli_query($con,$delete)){
				$message="Datos eliminados satisfactoriamente.";
			} else {
				$error="Error al eliminar los datos ".mysqli_error($con);
			}
		}
	$user_id=$_SESSION['user_id'];
	$id_sucursal = get_id('cashbox','branch_id','user_id',$user_id);//Obtengo el id de la sucursal
	$nombre_sucursal = get_id('branch_offices','name','id',$id_sucursal);//Obtengo el nombre de la sucursal
	$id_sucursal=intval($id_sucursal );
	?>

	<?php 
		if ($id_sucursal>0){
	?>
	<div class="pull-right" style="margin-top:-60px;">
		<button class='btn btn-primary' data-toggle="modal" data-target="#agregarCobroModal" data-id="<?php echo $sale_id;?>"><i class='fa fa-plus'></i> <div class="hidden-xs" style="display:inline-block">Agregar cobro</div></button>
	</div>
	<?php }?>
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
			$query=mysqli_query($con,"select * from charges where sale_id='$sale_id'");
			$nums=1;
			while($row=mysqli_fetch_array($query)){
				$charge_id=$row['charge_id'];
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
				<a href='#' class='btn btn-default btn-sm' onclick="print_charge('<?php echo $charge_id;?>');"><i class='fa fa-print'></i>  </a>
				<a href='#' class='btn btn-info btn-sm' data-toggle="modal" data-target="#editarCobroModal" data-id="<?php echo $sale_id;?>" data-charge_id="<?php echo $charge_id;?>"><i class='fa fa-edit'></i>  </a>
				<a href='#' class='btn btn-danger btn-sm' onclick="eliminar_cobro('<?php echo $sale_id?>','<?php echo $charge_id;?>')"><i class='fa fa-trash'></i> </a>
			</td>
		</tr>
		<?php	
		$nums++;
			}
			$total_pagado=sum_charge($sale_id);
			$total_venta=sum_sale($sale_id);
			$total_pendiente=$total_venta-$total_pagado;
			
			$due_date=get_id('sales','due_date','sale_id',$sale_id);
			$due_date=date('Ymd',strtotime($due_date));
			$fecha_hoy=date('Ymd');
			if ($total_pendiente==0){
				mysqli_query($con,"update sales set status=1 where sale_id='$sale_id'");
			} else if ($fecha_hoy>$due_date){
				mysqli_query($con,"update sales set status=3 where sale_id='$sale_id'");
			} else {
				mysqli_query($con,"update sales set status=2 where sale_id='$sale_id'");
			}
				
			
		?>
		<tr>
			<th class='text-right' colspan='3'>Monto</th>
			<td class='text-right'><?php echo number_format($total_venta,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></th>
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


