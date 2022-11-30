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
.page-header {
    margin: 10px 0 20px 0;
    font-size: 16px;
}
-->
</style>
<?php 
	include('num_letras.php');
?>
<page backtop="0mm" backbottom="0mm" backleft="0mm" backright="0mm" style="font-size: 13px; font-family: helvetica" backimg="">
		<table border=0 style="width:100%;margin:50mm 10px" cellspacing=0>
			<tr>
				<td style="width:28mm"> </td>
				<td style="width:180mm"><?php echo $customer_name;?> </td>
				
			</tr>	
		</table>
		<table border=0 style="width:100%;margin:5mm" cellspacing=0>
			<tr>
				<td style="width:28mm"> </td>
				<td style="width:30mm"><?php echo $tax_number;?> </td>
				<td style="width:20mm"> </td>
				<td style="width:40mm"><?php echo $guia_number;?> </td>
			</tr>	
		</table>
		<table border=0 style="width:100%;margin:3.5mm 10px" cellspacing=0>
			<tr>
				<td style="width:28mm"> </td>
				<td style="width:100mm"><?php echo $customer_address." ". $customer_city;?> <?php echo $customer_state." ". $customer_postal_code;?></td>
				<td style="width:10mm"> </td>
				<td style="width:20mm"><?php echo date("d",strtotime($rw_sale['sale_date']));?> </td>
				<td style="width:20mm"><?php echo fecha_sp($rw_sale['sale_date']);?> </td>
				<td style="width:15mm"> </td>
				<td style="width:10mm"><?php echo date("y",strtotime($rw_sale['sale_date']));?> </td>
				
			</tr>	
		</table>
		
	
		
     

   
	
	
	
  
    <table  style="width:100%;margin:9mm 0mm" cellspacing=0 border=0>
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
                 <td  style='width: 9mm;text-align:center'></td>
				 <td style='width: 20mm;text-align:center'><?php echo $qty;?></td>
				 <td  style='width: 120mm;text-align:left'><?php echo $product_name;?></td>
				 <td  style='width: 20mm;text-align:right'><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				 <td  style='width: 30mm;text-align:right;'><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
            </tr>	
			<?php
			$item++;
		}
		
		while ($item<13){
			?>
			<tr>
                 <td  style='width: 9mm;text-align:center'>&nbsp;</td>
				 <td  style='width: 20mm;text-align:center'>&nbsp;</td>
				 <td  style='width: 120mm;text-align:left'>&nbsp;</td>
				 <td  style='width: 20mm;text-align:right'>&nbsp;</td>
				 <td  style='width: 30mm;text-align:right;'>&nbsp;</td>
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
				<td style="height:7mm"> </td>
				<td> </td>
				<td colspan='3'> 
				<?php
					$numero_con_decimales=$total_compra;
					$son=number_format($numero_con_decimales,2,".","");
					$V=new EnLetras();
					echo strtoupper($V->ValorEnLetras($son,$currency_name));
				?>
				</td>
			</tr>
		 </table>
		 
		 <table border=0>
			<tr>
				<td style='width:145mm;height:5mm'></td>
				<td style='width:27mm'></td>
				<td style='width:28mm;text-align:right'><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			</tr>
			<tr>
				<td style='width:145mm;height:5mm'></td>
				<td style='width:27mm'></td>
				<td style='width:28mm;text-align:right'><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			</tr>
			<tr>
				<td style='width:145mm;height:5mm'></td>
				<td style='width:27mm'></td>
				<td style='width:28mm;text-align:right'><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			</tr>
		 </table>
			
	
	
    
	
	
	
	
	
	  

</page>

