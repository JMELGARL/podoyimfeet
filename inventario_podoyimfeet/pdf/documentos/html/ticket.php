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
	border-top:dashed 1px;
}
.bottom{
	border-bottom: dashed 1px;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
.page-header {
    margin: 10px 0 20px 0;
    font-size: 16px;
}
-->
</style>
<?php 
	include('num_letras.php');
?>
<page backtop="0mm" backbottom="0mm" backleft="4mm" backright="4mm" style="font-size: 11px; font-family: helvetica" backimg="">
		
		<table border=0 style="width:100%;margin:5mm 0px" cellspacing=0>
			<tr>
				<td style="width:100%; text-align:center"><?php echo $bussines_name;?></td>
			</tr>
			<tr>
				<td style="width:100%; text-align:center"><?php echo $address." ".$city;?></td>
			</tr>
			<tr>
				<td style="width:100%; text-align:center"><?php echo $state." CP: ".$postal_code;?></td>
			</tr>
			<tr>
				<td style="width:100%; text-align:center"><?php echo " RUC: ".$postal_code;?></td>
			</tr>
			<tr>
				<td style="width:100%; text-align:center">*** TICKET ***</td>
			</tr>
			<tr>
				<td style="width:100%; text-align:center"><?php echo $branch_office_name;?></td>
			</tr>
			<tr>
				<td style="width:100%; text-align:center"><?php echo $branch_office_address;?></td>
			</tr>
		</table>
		
		<table border=0 style="width:100%;margin:5mm 0px" cellspacing=0>
			<tr>
				<td style="width:100%;">TICKET: <?php echo $sale_prefix." - ".$sale_number;?></td>
			</tr>
			<tr>
				<td style="width:100%;">FECHA: <?php echo $sale_date;?></td>
			</tr>
			<tr>
				<td style="width:100%;">CLIENTE: <?php echo $tax_number;?><br> <?php echo $customer_name;?><br></td>
			</tr>
			
		</table>
		
		<table border=0 style="width:100%;margin:2mm 0px" cellspacing=0>
			<tr>
				<td style="width:7mm;text-align:center;" class='top bottom'>Cant.</td>
				<td style="width:34mm;text-align:left;" class='top bottom'>Descripción</td>
				<td style="width:10mm;text-align:right;" class='top bottom'>P.U</td>
				<td style="width:10mm;text-align:right;" class='top bottom'>Parcial</td>
			</tr>
			<?php
		$sumador_total=0;
		$sumador_descuento=0;
		$item=1;
		$sql=mysqli_query($con, "select * from products, sale_product where products.product_id=sale_product.product_id and sale_product.sale_id='$sale_id'");
		while ($row=mysqli_fetch_array($sql)){
			$product_code=$row['product_code'];
			$qty=$row['qty'];
			$discount=intval($row['discount']);
			$product_name=$row['product_name'];
			$presentation=$row['presentation'];
			$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');
			$precio_total=$unit_price*$qty;
			$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
			$descuento=($precio_total * $discount) / 100;
			$descuento=number_format($descuento,$precision_moneda ,'.','');//Descuento Formateado
			$sumador_descuento+=$descuento;//Sumador
			$sumador_total+=$precio_total;//Sumador
			?>
			<tr>
				<td style="width:7mm;text-align:center;" ><?php echo $qty;?></td>
				<td style="width:34mm;text-align:left;" ><?php echo $product_name;?></td>
				<td style="width:10mm;text-align:right;" ><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				<td style="width:10mm;text-align:right;"><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
            </tr>	
			<?php
			$item++;
		}
		$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
				$sumador_descuento=number_format($sumador_descuento,$precision_moneda,'.','');
				$total_neto=$total_parcial-$sumador_descuento;
				$total_neto=number_format($total_neto,$precision_moneda,'.','');
				
				if ($includes_tax==0){
					$total_iva=($total_neto*$tax) / 100;
					$total_iva=number_format($total_iva,$precision_moneda,'.','');	
				} else if ($includes_tax==1){
					$tax_value=$tax/100 + 1;
					$tax_value=number_format($tax_value,$precision_moneda,'.','');	
					$neto=$total_neto/$tax_value;
					$neto=number_format($neto,$precision_moneda,'.','');
					$total_iva=$total_neto-$neto;
					$total_neto=number_format($neto,$precision_moneda,'.','');
					$total_iva=number_format($total_iva,$precision_moneda,'.','');	
				}
				
				$total_compra=$total_neto+$total_iva;
				$total_compra=number_format($total_compra,$precision_moneda,'.','');
				if ($total_parcial>0){
					$percent=($sumador_descuento / $total_parcial) * 100;
				} else {	
					$percent=0;
				}	
				
		?>
		<tr>
			<td colspan="4" class='top'></td>
		</tr>
		<tr>
			<td style='text-align:right' colspan="3" ><b>VALOR VENTA <?php echo $simbolo_moneda;?></b> </td>
			<td style='text-align:right' ><b><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></b> </td>
		</tr>
		<tr>
			<td style='text-align:right' colspan="3" ><b>IGV <?php echo $simbolo_moneda;?></b></td>
			<td style='text-align:right' ><b> <?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></b></td>
		</tr>
		<tr>
			<td style='text-align:right' class='bottom' colspan="3" ><b>TOTAL <?php echo $simbolo_moneda;?></b></td>
			<td style='text-align:right' class='bottom' ><b><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></b></td>
		</tr>
			
		</table>
		
	
		
		<table border=0 style="width:100%;" cellspacing=0>
			<tr>
				<td style="width:100%;text-align:left;" >VENDEDOR: <?php echo get_id('users','fullname','user_id',$seller_id);?></td>
			</tr>
			<tr>
				<td style="width:100%;text-align:left;" class='bottom'>CAJERO: <?php echo get_id('users','fullname','user_id',$sale_by);?> </td>
			</tr>
		</table>
		<br>
		<table border=0 style="width:100%;" cellspacing=0>
			<tr>
				<td style="width:100%;text-align:center;" >CAMBIOS DENTRO DE LOS 7 DÍAS SIGUIENTES PRESENTANDO SU COMPROBANTE DE PAGO</td>
			</tr>
			
		</table>

			<p style="width:100%;text-align:center">*** GRACIAS POR SU COMPRA ***</p>
	
		
     

   
	
	
	


			
	
	
    
	
	
	
	
	
	  

</page>

