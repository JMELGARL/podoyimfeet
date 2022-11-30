<style type="text/css">
<!--
div.zone
{
    border: solid 0.5mm red;
    border-radius: 2mm;
    padding: 1mm;
    background-color: #FFF;
    color: #440000;
}
div.zone_over
{
    width: 30mm;
    height: 20mm;
    
}
.bordeado
{
	border: solid 0.5mm #eee;
	border-radius: 1mm;
	padding: 0mm;
	font-size:12px;
}
.table {
  border-spacing: 0;
  border-collapse: collapse;
}
.table-bordered td, .table-bordered th {
  padding: 3px;
  text-align: left;
  vertical-align: top;
}
.table-bordered {
  border: 0px solid #eee;
  border-collapse: separate;
  
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}
.left{
	border-left: 1px solid #eee;
	
}
.top{
	border-top: 1px solid #eee;
}
.bottom{
	border-bottom: 1px solid #eee;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}

-->
</style>
<page backtop="20mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 13px; font-family: helvetica" backimg="">
	<?php 
	$title_report='Reporte de compras';
	include('page_header_footer.php');
	?>

	
	<div style='border-bottom: 3px solid #2ecc71;padding-bottom:10px'>
		Usuario:  
		<?php
		$sql1=mysqli_query($con,"select fullname from users where user_id='".$purchase_by."'");
		$rw1=mysqli_fetch_array($sql1);
		$fullname=$rw1['fullname']; 
		if (empty($fullname)){
			echo "Todos";
		}else {
			echo $fullname;
		}
		?>
	</div>

	
	
	
  
    <table class="table-bordered" style="width:100%;" cellspacing=0>
        <tr>
			<th class='top bottom'  style="width: 12%;text-align:center">Compra Nº</th>
			<th class='top bottom'  style="width: 12%;text-align:center">Fecha</th>
            <th class='top bottom'  style="width: 30%;">Proveedor</th>
            <th class='top bottom'  style="width: 10%;text-align:center">Estado</th>
            <th class='top bottom'  style="width: 12%;text-align:right">Monto</th>
			<th class='top bottom'  style="width: 12%;text-align:right">Monto pend.</th>
			<th class='top bottom'  style="width: 12%;text-align:center">Vence</th>
            
        </tr>
		<?php
		$sumador_subtotal=0;
		$sumador_pte=0;
		$sumador_tax=0;
		$sumador_total=0;
		while($row=mysqli_fetch_array($query)){
			$purchase_id=$row['purchase_id'];
			$purchase_order_number=$row['purchase_order_number'];
			$supplier_id=$row['supplier_id'];
			$sql_supplier=mysqli_query($con,"select name from suppliers where id='".$supplier_id."'");
			$rw_supplier=mysqli_fetch_array($sql_supplier);
			$supplier_name=$rw_supplier['name'];
			$date_added=$row['purchase_date'];
			$user_fullname=$row['fullname'];
			$subtotal=$row['subtotal'];
			$tax=$row['tax'];
			$total=$row['total'];
			
			$sumador_subtotal+=$subtotal;
			$sumador_tax+=$tax;
			$sumador_total+=$total;
			
			
			list($date,$hora)=explode(" ",$date_added);
			list($Y,$m,$d)=explode("-",$date);
			$fecha=$d."-".$m."-".$Y;
			$status=$row['status'];
			if ($status==1){$text_status="Pagada";$label_class="label-success";}
			else if ($status==2){$text_status="Pendiente";$label_class="label-warning";}
			else if ($status==3){$text_status="Vencida";$label_class="label-danger";}
			
			if ($status!=1){
				$sum_payment=sum_payment($purchase_id);	
				$pendiente=$total-$sum_payment;
				$sumador_pte+=$pendiente;
				$due_date=date("d/m/Y", strtotime($row['due_date']));
			} else {
					$pendiente=0;
					$due_date="";
			}
			
			?>
				<tr>
					<td class='bottom' style="width: 12%;text-align:center"><?php echo $purchase_order_number;?></td>
					<td class='bottom' style="width: 12%;text-align:center"><?php echo $fecha;?></td>
					<td class='bottom' style="width: 30%;"><?php echo $supplier_name;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $text_status;?></td>
					<td class='bottom' style="width: 12%;text-align:right"><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					<td class='bottom' style="width: 12%;text-align:right"><?php echo number_format($pendiente,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					<td class='bottom' style="width: 12%;text-align:center"><?php echo $due_date;?></td>
				</tr>
			<?php 
		}
		
		?>
		<tr>
				<td colspan=4 style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:14px'><strong>Totales <?php echo $moneda;?></strong></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:14px'><?php echo number_format($sumador_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:14px'><?php echo number_format($sumador_pte,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:14px'><?php ?></td>
		</tr>
	 </table>
</page>

