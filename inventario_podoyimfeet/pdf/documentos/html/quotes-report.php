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
<page backtop="20mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 13px; font-family: helvetica" backimg="">
	<?php 
	if ($status=="todas"){
		$title_report='Reporte general de cotizaciones';
	} else if ($status==1){
		$title_report='Reporte de cotizaciones aceptadas';
	} else if ($status==2){
		$title_report='Reporte de cotizaciones en revisión';
	} else {
		$title_report='Reporte de cotizaciones pendientes';
	}
	
	include('page_header_footer.php');
	
	?>

	
	<div style='border-bottom: 3px solid #2ecc71;padding-bottom:10px'>
		Vendedor:  
		<?php
		$sql1=mysqli_query($con,"select fullname from users where user_id='".$employee_id."'");
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
			<th class='top bottom'  style="width: 10%;text-align:center">Nº</th>
            <th class='top bottom'  style="width: 40%;">Cliente</th>
			<th class='top bottom'  style="width: 25%;">Vendedor</th>
            <th class='top bottom'  style="width: 10%;text-align:center">Fecha</th>
          	<th class='top bottom'  style="width: 15%;text-align:right">Total</th>
            
        </tr>
		<?php
		$sumador_subtotal=0;
		$sumador_tax=0;
		$sumador_total=0;
		while($row=mysqli_fetch_array($query)){
			$quote_id=$row['quote_id'];
			$customer_id=$row['customer_id'];
			$fullname=$row['fullname'];
			$sql_customer=mysqli_query($con,"select name from customers where id='".$customer_id."'");
			$rw_customer=mysqli_fetch_array($sql_customer);
			$customer_name=$rw_customer['name'];
			$date_added=$row['quote_date'];
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
			
			?>
				<tr>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $quote_id;?></td>
					<td class='bottom' style="width: 40%;"><?php echo $customer_name;?></td>
					<td class='bottom' style="width: 25%;"><?php echo $fullname;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $fecha;?></td>
					<td class='bottom' style="width: 15%;text-align:right"><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
			<?php 
		}
		
		?>
		<tr>
				<td colspan=4 style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:14px'><strong>Totales <?php echo $moneda;?></strong></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:14px'><?php echo number_format($sumador_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
		</tr>
	 </table>
</page>

