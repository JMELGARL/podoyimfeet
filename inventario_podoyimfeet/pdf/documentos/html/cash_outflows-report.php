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
	$title_report='Reporte de egresos';
	include('page_header_footer.php');
	
	?>

	
	
	<div style='border-bottom: 3px solid #2ecc71;padding-bottom:10px'>
	
	</div>
	
	
	
  
    <table class="table-bordered" style="width:100%;font-size:12px" cellspacing=0>
        <tr>
			<th class='top bottom'  style="width: 45%;">Concepto</th>
			<th class='top bottom'  style="width: 10%;">Fecha</th>
			<th class='top bottom'  style="width: 15%;">Sucursal</th>
			<th class='top bottom'  style="width: 15%;">Caja</th>
            <th class='top bottom'  style="width: 15%;text-align:right">Monto</th>
        </tr>
		<?php
		$sumador_total=0;
		while($row=mysqli_fetch_array($query)){
			$note=$row['note'];
			$date_added=date('d/m/Y', strtotime($row['date_added']));
			$branch_office=$row['name'];
			$cashbox_id=$row['cashbox_id'];
			$cashbox_name=get_id('cashbox','cashbox_name','id',$cashbox_id);
			$total=$row['total'];
			$sumador_total+=$total;
			?>
				<tr>
					<td class='bottom' style="width: 45%;"><?php echo "$note";?></td>
					<td class='bottom' style="width: 10%;"><?php echo $date_added;?></td>
					<td class='bottom' style="width: 15%;"><?php echo $branch_office;?></td>
					<td class='bottom' style="width: 15%;"><?php echo $cashbox_name;?></td>
					<td class='bottom' style="width: 15%;text-align:right"><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					
					
				</tr>
			<?php 
		}
		
		?>
		<tr>
				<td colspan=4 style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:14px'><strong>Total <?php echo $moneda;?></strong></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:14px'><?php echo number_format($sumador_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
		</tr>
	 </table>
</page>

