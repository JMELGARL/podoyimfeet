<style type="text/css">
<!--
div.head
{
    font-size:12p;
    padding: 1mm;
    color: #000;
	text-align:center;
	
}
div.fecha
{
text-align:right;
padding-top: 1mm;
}
.border
{
margin-top:5px;
width:100%;

}
table td.bold
{
width:46%;
font-weight:bold;
}
td.right
{
text-align:right;
width:18%
}
td
{
height:20px;
}
.bordered
{
border: 1px solid #dddddd;
height:10px;

}
.ancho_40
{
width:40%;
}
.fma{
 float:left;
 display:inline;
 width: 22%;
 text-align:center;
 margin-left: 20px;
 border-top:1px solid #000;
}	
.top
{
margin-top: 20px;
}
.inn
{
width:100%;
}


-->
</style>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm" style="font-size: 10pt; font-family: arial" >
 <div class="head">
 <?php echo $bussines_name;?><br>
 <?php echo $nombre_sucursal; ?><br>
 Corte diario de Ingresos y Egresos
 </div>
 <div class="fecha">
 <?php //echo $F." ".$hora;?>
 </div>
 
	<table class="border" >
		<tr>
			<th style="width:55%">CONCEPTO</th>
			<th style="width:15%;text-align: right">CONTADO</th>
			<th style="width:15%; text-align: right">CREDITO</th>
			<th style="width:15%;text-align:right">SALDO</th>
		</tr>
		<tr>
			<td>Fondo Fijo</td>
			<td></td>
			<td></td>
			<td style="width:15%;text-align:right"><strong><?php echo number_format($opening_balance,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
		</tr>
		<tr>
			<td>Ingresos</td>
			<td></td>
			<td></td>
			<td style="width:15%; text-align:right;"><strong><?php echo number_format($total_ingresos,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
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
			<td style="width:55%">Ventas con <?php echo $rw1['name_document'];?> </td>
			<td style="width:15%;text-align: right"><?php echo number_format($total_contado,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			<td style="width:15%;text-align: right"><?php echo number_format($total_credito,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			<td style="width:15%;text-align: right"><strong></strong></td>
		</tr>	
		<?php
			}
						
						$subtotal_contado=$total_cobros+$sumador_contado;
						$subtotal_credito=$sumador_credito;
						$fondo_fijo_ingresos=$opening_balance+$subtotal_contado;
						$total_egresos=total_egresos($date_initial,$date_final,$cashbox_id);
						$valor_entregar=$fondo_fijo_ingresos-$total_egresos-$closing_balance;
		?>
		<tr>
			<td style="width:55%">INGRESOS (Cuentas por cobrar)</td>
			<td style="width:15%;text-align: right"><?php echo number_format($total_cobros,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"><strong></strong></td>
		</tr>
		<tr>
			<th style="width:55%">SUB TOTAL</th>
			<th style="width:15%;text-align: right"><?php echo number_format($subtotal_contado,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></th>
			<th style="width:15%;text-align: right"><?php echo number_format($subtotal_credito,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></th>
			<th style="width:15%;text-align: right"><strong></strong></th>
		</tr>
		<tr>
			<td style="width:55%">TOTAL FONDO FIJO MAS INGRESOS</td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"><strong><?php echo number_format($fondo_fijo_ingresos,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
		</tr>
		<tr>
			<td style="width:55%">MENOS EGRESOS</td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"><strong><?php echo number_format($total_egresos,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
		</tr>
		<tr>
			<td style="width:55%">MENOS FONDO FIJO</td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"><strong><?php echo number_format($closing_balance,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
		</tr>
		<tr>
			<td style="width:55%">VALOR A ENTREGAR</td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"></td>
			<td style="width:15%;text-align: right"><strong><?php echo $simbolo_moneda;?> <?php echo number_format($valor_entregar,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
		</tr>
	</table> 
	 <div class="top">
	 </div>
	 
	 <div class='fma'>
	 <?php echo $fullname;?><br>
	 Cajero
	 </div> 
	 <div class='fma'>
	 Revisado
	 </div>
	  <div class='fma'>
	 Auxiliar
	 </div>
	  <div class='fma'>
	 Autorizado
	 </div>
	 
	 
</page>

