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
	$title_report='Reporte de ingresos y egresos';
	include('page_header_footer.php');
	
	?>
   <table class="table-bordered" style="width:100%;font-size:11px" cellspacing=0>
        <tr>
			<th class='top bottom'  style="width: 10%;text-align:center">ID</th>
			<th class='top bottom'  style="width: 10%;text-align:center">Fecha</th>
			<th class='top bottom'  style="width: 10%;text-align:center">Hora</th>
			<th class='top bottom'  style="width: 34%;">Descripción</th>
            <th class='top bottom'  style="width: 12%;text-align:right">Egresos	</th>
			<th class='top bottom'  style="width: 12%;text-align:right">Ingresos	</th>
			<th class='top bottom'  style="width: 12%;text-align:right">Saldo</th>
            
        </tr>
		<?php
		$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere");
		while($row=mysqli_fetch_array($query)){
			$id=$row['id'];	
			$fecha=date("d/m/Y", strtotime($row['created_at']));
			$hora=date("H:i:s", strtotime($row['created_at']));
			$description=$row['description'];
			$type=$row['type'];
			$amount=$row['amount'];
			$status=$row['status'];
			if ($type==1){
				$txt_type='Ingresos';
				$abono=number_format($amount,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);
			} else {
				$abono="";
			} 
			if ($type==2){
				$txt_type='Egresos';
				$cargo=number_format($amount,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);
			} else {
				$cargo="";
			}
			$balance=$row['balance'];
			?>
				<tr>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $id;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $fecha;?></td>
					<td class='bottom' style="width: 10%;text-align:center"><?php echo $hora;?></td>
					<td class='bottom' style="width: 34%;"><?php echo $description;?></td>
					<td class='bottom' style="width: 12%;text-align:right"><?php echo $cargo;?></td>
					<td class='bottom' style="width: 12%;text-align:right"><?php echo $abono;?></td>
					<td class='bottom' style="width: 12%;text-align:right"><?php echo number_format($balance,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					
				</tr>
			<?php 
		}
		
		?>
	
	 </table>
</page>

