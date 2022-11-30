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
<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 13px; font-family: helvetica" backimg="">
		<div>
			<img src="<?php echo $logo_url;?>" style="width: 175px;">
		</div>
       <table style="width:100%" class='page-header' cellspacing=0>
        <tr style="vertical-align: top">
            <td style="width:70%;border-bottom: 3px solid #2ecc71;padding:4px">
				
				 <?php echo $bussines_name;?>
			</td>
            <td style="width:30%;text-align:right;border-bottom: 3px solid #2ecc71;">
               	<small>Fecha: <?php echo $note_date;?></small>
			</td>
            
        </tr>
        
    </table>
	<br>
	
	<table style="width:100%">
        <tr style="vertical-align: top">
            <td style="width:38%;">
				Proveedor<br>
			<address>
              <strong><?php echo $bussines_name;?></strong><br>
              <?php echo $address.", ". $city;?><br>
              <?php echo $state.", ". $postal_code;?><br>
              Teléfono: <?php echo $phone;?><br>
              Email: <?php echo $email;?>
            </address>
			</td>
            <td style="width:38%;">
               Cliente<br>
             <address>
              <strong><?php echo $customer_name;?></strong><br>
             <?php echo $customer_address." ". $customer_city;?><br>
             <?php echo $customer_state." ". $customer_postal_code;?><br>
              Teléfono: <?php echo $customer_work_phone;?><br>
              Email: <?php echo $customer_email;?>
            </address>
             	
			</td>
			<td style="width:24%;text-align:right;font-size:16px;">
               	<b>Nota de crédito # <?php echo $note_number;?></b><br>
			</td>
            
        </tr>
        
    </table>
   
    <br>
	
	
	
  
    <table class="table-bordered" style="width:100%;" cellspacing=0>
        <tr>
			<th class='top bottom'  style="width: 10%;text-align:center"><small>CODIGO</small></th>
            <th class='top bottom'  style="width: 10%;text-align:center"><small>CANT.</small></th>
            <th class='top bottom'  style="width: 50%"><small>DESCRIPCION</small></th>
            <th class='top bottom'  style="width: 10%;text-align:right"><small>PRECIO UNIT.</small></th>
			<th class='top bottom'  style="width: 10%;text-align:right"><small>DESCUENTO</small></th>
		    <th class='top bottom'  style="width: 10%;text-align:right"><small>TOTAL</small></th>
            
        </tr>
		<?php
		$sumador_total=0;
		$sumador_descuento=0;
		$sql=mysqli_query($con, "select * from products, note_product where products.product_id=note_product.product_id and note_product.note_id='$note_id'");
		while ($row=mysqli_fetch_array($sql)){
			$product_code=$row['product_code'];
			$qty=$row['qty'];
			$discount=intval($row['discount']);
			$product_name=$row['product_name'];
			$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');
			$precio_total=$unit_price*$qty;
			$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
			$descuento=($precio_total * $discount) / 100;
			$descuento=number_format($descuento,$precision_moneda ,'.','');//Descuento Formateado
			$sumador_descuento+=$descuento;//Sumador
			$sumador_total+=$precio_total;//Sumador
			?>
			<tr>
                 <td class='bottom' style='width: 10%;text-align:center'><?php echo $product_code;?></td>
				 <td class='bottom' style='width: 10%;text-align:center'><?php echo $qty;?></td>
				 <td class='bottom' style='width: 50%;text-align:left'><?php echo $product_name;?></td>
				 <td class='bottom' style='width: 10%;text-align:right'><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				 <td class='bottom' style='width: 10%;text-align:right'><?php echo number_format($descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				 <td class='bottom' style='width: 10%;text-align:right;'><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
            </tr>	
			<?php
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
				}	
					$percent=0;
		?>
			<tr><td> </td></tr>
			<?php if ($sumador_descuento>0){?>
			<tr>
				<td  colspan=5 style='text-align:right'><strong>Parcial <?php echo $simbolo_moneda;?></strong></td>
				<td style='text-align:right'><?php echo number_format($total_parcial,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			</tr>
			<tr>
				<td  colspan=5 style='text-align:right'><strong>Descuento <?php echo number_format($percent,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?>% <?php echo $simbolo_moneda;?></strong></td>
				<td style='text-align:right'><?php echo number_format($sumador_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			</tr>
			<?php }?>
			<tr>
				<td  colspan=5 style='text-align:right'><strong>Subtotal <?php echo $simbolo_moneda;?></strong></td>
				<td style='text-align:right'><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			</tr>
			<tr>
				<td colspan=5 style='text-align:right'><strong><?php echo strtoupper(tax_txt);?> <?php echo $simbolo_moneda;?></strong></td>
				<td style='text-align:right'><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			</tr>
			<tr>
				<td colspan=5 style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:16px'><strong>Total <?php echo $simbolo_moneda;?></strong></td>
				<td style='text-align:right;border-top:3px solid #2ecc71;padding:4px;padding-top:4px;font-size:16px'><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			</tr>

	
	 </table>
    
	
	
	
	
	
	  

</page>

