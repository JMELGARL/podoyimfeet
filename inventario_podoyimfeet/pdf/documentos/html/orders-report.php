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
	font-size:10px;
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
<page backtop="20mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 11px; font-family: helvetica" backimg="">
	<?php 
	$title_report='Reporte de ordenes de servicio';
	include('page_header_footer.php');
	?>

	
	<div style='border-bottom: 3px solid #2ecc71;padding-bottom:10px;text-align:center'>
		REPORTE DESDE EL <?php echo $f_inicio;?> HASTA <?php echo $f_final;?>
	</div>

	
	
	
  
    <table class="table-bordered" style="width:100%;" cellspacing=0>
        <tr>
			<th class='top bottom'  style="width: 8%;text-align:center">Nº</th>
			<th class='top bottom'  style="width: 9%;text-align:center">Fecha</th>		
            <th class='top bottom'  style="width: 20%;">Cliente</th>
			<th class='top bottom'  style="width: 15%;">T&eacute;cnico</th>
			<th class='top bottom'  style="width: 15%;">Equipo</th>
			<th class='top bottom'  style="width: 10%;">Estado</th>
            <th class='top bottom'  style="width: 8%;text-align:right">Neto</th>
			<th class='top bottom'  style="width: 7%;text-align:right">IVA</th>
			<th class='top bottom'  style="width: 8%;text-align:right">Total</th>
            
        </tr>
		<?php
		$sumador_subtotal=0;
		$sumador_tax=0;
		$sumador_total=0;
		while($row=mysqli_fetch_array($query)){
			$order_id=$row['order_id'];
			$order_date=$row['order_date'];
			$fecha=date("d/m/Y", strtotime($order_date));
			$name=$row['name'];
			$work_phone=$row['work_phone'];
			$website=$row['website'];
			$customer_id=$row['customer_id'];
			$sql_contacto=mysqli_query($con,"select first_name, last_name, phone, email from contacts where client_id='$customer_id'");
			$rw=mysqli_fetch_array($sql_contacto);
			$contact=$rw['first_name']." ".$rw['last_name'];
			$phone=$rw['phone'];
			$email=$rw['email'];
			$fullname=$row['fullname'];
			$status=$row['status'];
			$subtotal=number_format($row['subtotal'],2,'.','');
			$tax=number_format($row['tax'],2,'.','');
			$total=$subtotal+$tax;
			$total=number_format($total,2,'.','');
			if ($status==1){$estado="En proceso";$label="label-warning";}
			else if ($status==2) {$estado="Presupuesto";$label="label-info";}
			else if ($status==3) {$estado="Reparado";$label="label-success";}
			else if ($status==4) {$estado="No reparado";$label="label-danger";}
			$product_description=$row['product_description'];
			$brand=$row['brand'];
			$model=$row['model'];
			$replace_http=str_replace("https://","",$website);
			$replace_http=str_replace("http://","",$replace_http);
			
			
			$sumador_subtotal+=$subtotal;
			$sumador_tax+=$tax;
			$sumador_total+=$total;
			?>
				<tr>
					<td class='bottom' style="width: 8%;text-align:center"><?php echo $order_id;?></td>
					<td class='bottom' style="width: 9%;text-align:center"><?php echo $fecha;?></td>
					<td class='bottom' style="width: 20%;">
						<?php echo $name;?><br>
						<?php echo $work_phone;?><br>
						<a href="<?php echo $website;?>" target="_blank"><?php echo $website;?></a>
					</td>
					<td class='bottom' style="width: 15%;"><?php echo $fullname;?></td>
					<td class='bottom' style="width: 15%;">
						<?php echo $product_description." ".$model;?><br>
						<small class='text-muted'><?php echo $brand;?></small>
					</td>
					<td class='bottom' style="width: 10%;">
						<span class="<?php echo $label;?>"><?php echo $estado;?></span>
					</td>
					<td class='bottom' style="width: 8%;text-align:right"><?php echo number_format($subtotal,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					<td class='bottom' style="width: 7%;text-align:right"><?php echo number_format($tax,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					<td class='bottom' style="width: 8%;text-align:right"><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
			<?php 
		}
			
		?>
		<tr>
				<td colspan=6 style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:12px'><strong>Totales <?php echo $moneda;?></strong></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:12px'><?php echo number_format($sumador_subtotal,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:12px'><?php echo number_format($sumador_tax,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:12px'><?php echo number_format($sumador_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
		</tr>
	 </table>
</page>

