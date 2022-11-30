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
               	<small>Fecha: <?php echo $created_at;?></small>
			</td>
            
        </tr>
        
    </table>
	<br>
	
	
	<table style="width:100%">
		<tr>
			<td colspan=2 style='text-align:center;width:100%'>Tipo de ajuste: <strong><?php echo $txt_type; ?></strong></td>
		</tr>
        <tr style="vertical-align: top">
            <td style="width:50%;">
				Proveedor<br>
			<address>
              <strong><?php echo $bussines_name;?></strong><br>
              <?php echo $address.", ". $city;?><br>
              <?php echo $state.", ". $postal_code;?><br>
              Teléfono: <?php echo $phone;?><br>
              Email: <?php echo $email;?>
            </address> 
			</td>
           
			<td style="width:50%;text-align:right;font-size:14px;">
               	<b>AJUSTE DE INVENTARIO # <?php echo "$number_reference";?></b><br>
			</td>
            
        </tr>
        
    </table>
   
    <br>
	
	
	
  
    <table class="table-bordered" style="width:100%;" cellspacing=0>
        <tr>
			<th class='top bottom'  style="width: 10%;text-align:center"><small>CODIGO</small></th>
            <th class='top bottom'  style="width: 10%;text-align:center"><small>CANT.</small></th>
            <th class='top bottom'  style="width: 45%"><small>DESCRIPCION</small></th>
			<th class='top bottom'  style="width: 15%"><small>SUCURSAL</small></th>
            <th class='top bottom'  style="width: 10%;text-align:right"><small>PRECIO UNIT.</small></th>
			<th class='top bottom'  style="width: 10%;text-align:right"><small>TOTAL</small></th>
            
        </tr>
		<?php
		$sumador_total=0;
		$sumador_descuento=0;
		$sql=mysqli_query($con, "select * from products, inventory_tweaks_product where products.product_id=inventory_tweaks_product.product_id and inventory_tweaks_product.inventory_tweak_id='$adjustment_id'");
		while ($row=mysqli_fetch_array($sql)){
			$product_id=$row['product_id'];
			$purchase_product_id=$row["id"];
			$product_code=$row['product_code'];
			$qty=$row['qty'];
			$product_name=$row['product_name'];
			$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');
			

			$precio_total=$unit_price*$qty;
			$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
			$sumador_total+=$precio_total;//Sumador
			
			$branch_id=$row['branch_id'];
			$nombre_sucursal=get_id('branch_offices','name','id',$branch_id);
			?>
			<tr>
                 <td class='bottom' style='width: 10%;text-align:center'><?php echo $product_code;?></td>
				 <td class='bottom' style='width: 10%;text-align:center'><?php echo $qty;?></td>
				 <td class='bottom' style='width: 45%;'><?php echo $product_name;?></td>
				 <td class='bottom' style='width: 15%;'><?php echo $nombre_sucursal;?></td>
				 <td class='bottom' style='width: 10%;text-align:right'><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				 <td class='bottom' style='width: 10%;text-align:right'><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				
            </tr>	
			<?php
		}
			$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
			$total_neto=$total_parcial;
			$total_neto=number_format($total_neto,$precision_moneda,'.','');
			$total_iva=($total_neto*$tax) / 100;
			$total_iva=number_format($total_iva,$precision_moneda,'.','');
			$total_compra=$total_neto+$total_iva;
			$total_compra=number_format($total_compra,$precision_moneda,'.','');	
		?>
			<tr>
				<td style='text-align:right' colspan=5>NETO <?php echo $moneda;?></td>
				<td style='text-align:right'><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				
			</tr>
			<tr>
				<td style='text-align:right'colspan=5><?php echo strtoupper(tax_txt);?> <?php echo "$tax% $moneda";?></td>
				<td style='text-align:right'><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				
			</tr>
			<tr>
				<td style='text-align:right' colspan=5>TOTAL <?php echo $moneda;?></td>
				<td style='text-align:right'><?php echo number_format($total_compra,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				
			</tr>

	
	 </table>
    
	
	
	<p>
		NOTA: <?php echo $note;?>
	</p>
	
	
	  

</page>

