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
	border: solid 0.5mm #999;
	border-radius: 1mm;
	padding: 0mm;
	font-size:12px;
}
.table {
  border-spacing: 0;
  border-collapse: collapse;
}
.table-bordered td, .table-bordered th {
  padding: 2px;
  text-align: left;
  vertical-align: top;
}
.table-bordered {
  border: 1px solid #999;
  border-collapse: separate;
  
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}
.left{
	border-left: 1px solid #999;
	
}
.top{
	border-top: 1px solid #999;
}
.bottom{
	border-bottom: 1px solid #999;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
-->
</style>
<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 13px; font-family: helvetica" >
	<page_footer>
        <table class="page_footer">
            <tr>
               <td style="width: 50%; text-align: left">
                    P&aacute;gina [[page_cu]]/[[page_nb]]
                </td>
                <td style="width: 50%; text-align: right">
                    &copy; <?php echo $bussines_name." "; echo  $anio=date('Y'); ?>
                </td>
            </tr>
        </table>
    </page_footer>
       <table style="width:100%">
        <tr style="vertical-align: top">
            <td style="width:25%">
               <?php 
				if (!empty($logo_url)){
					?>
					<img src="<?php echo $logo_url;?>" style="width: 100%;">
					<?php 
				}	
				?>
                
                
            </td>
			<td style="width:55%;text-align:center">
				<span style="font-size:13pt"><strong><?php echo $bussines_name;?></strong></span><br>
				<span style="color:#555"><?php echo $industry;?></span><br>
				<span style="color:#555"><?php echo $address.", ". $city.",  ".$state.", ". $postal_code;?></span><br>
				<span style="color:#555">Tel.: <?php echo $phone;?></span><br>
				<span style="color:#555">Email: <?php echo $email;?></span><br>
			</td>
            <td style="width:20%">
               
                <div class="zone zone_over" style="text-align: center; vertical-align: top; ">
				ORDEN
				<p style="color:red;font-size:14pt;font-weight:bold">Nº: <?php echo $order_number;?></p> 
				
				</div>
               
            </td>
            
        </tr>
        
    </table>
    <p style="width:100%;text-align:right;margin-right:10mm"><strong>Fecha:</strong> <?php echo $order_date;?></p>
    
	<table style="width:100%" class="table-bordered">
		<tr>
			<td colspan=2 ><small><strong >DATOS DEL CLIENTE</strong></small></td>
		</tr>
		
		<tr style="vertical-align: top">
            <td style="width:75%"><strong>Cliente: </strong><?php echo $customer_name;?></td>
			<td style="width:25%;"><strong>Teléfono: </strong><?php echo $customer_phone;?></td>
		</tr>
		<tr style="vertical-align: top">
            <td style="width:75%"><strong>Contacto: </strong><?php echo $contact_name;?></td>
			<td style="width:25%;"><strong>Teléfono: </strong><?php echo $contact_phone;?></td>
		</tr>
		<tr style="vertical-align: top">
            <td style="width:75%"><strong>E-mail: </strong><?php echo $contact_email;?></td>
			
		</tr>
		 
        
    </table>
	<br>
	<table style="width:100%" class="table-bordered">
		<tr>
			<td colspan=3 ><small><strong >DATOS DEL EQUIPO</strong></small></td>
		</tr>
		<tr style="vertical-align: top">
            <td style="width:33%"><strong>Modelo: </strong><?php echo $model;?></td>
			<td style="width:30%;"><strong>Marca: </strong><?php echo $brand;?></td>
			<td style="width:37%;"><strong>Nº de serie: </strong><?php echo $serial_number;?></td>
		</tr>
		<tr style="vertical-align: top">
            <td style="width:63%" colspan=2><strong>Equipo: </strong><?php echo $product_description;?></td>
			<td style="width:37%" ><strong>Accesorios: </strong><?php echo $accessories;?></td>
		</tr>
		<tr style="vertical-align: top">
            <td style="width:100%" colspan=3><strong>Problema: </strong><?php echo $issue;?></td>
			
		</tr>
	</table>
	
	<?php
	$sql=mysqli_query($con, "select * from products, order_product where products.product_id=order_product.product_id and order_product.order_id='".$order_id."'");
	$nums=mysqli_num_rows($sql);
	if ($nums>0){
	?>
	<p><small><strong>PRESUPUESTO</strong></small></p>
	<table class="table-bordered" style="width:100%;" cellspacing=0>
        <tr>
            <th class="bottom" style="width: 10%;text-align:center">CANT.</th>
            <th class="bottom left" style="width: 40%">DESCRIPCION</th>
			<th class="bottom left" style="width: 10%;text-align:center">UNIDAD</th>
            <th class="bottom left" style="width: 14%;text-align:right">PRECIO UNIT.</th>
			<th class="bottom left" style="width: 12%;text-align:right">DESC.</th>
            <th class="bottom left" style="width: 14%;text-align:right">TOTAL</th>
            
        </tr>
   
<?php
$sumador_descuento=0;
$sumador_total=0;
while ($row=mysqli_fetch_array($sql))
	{
	$qty=$row['qty'];
	$product_name=$row['product_name'];
	$model=$row['model'];
	$descripcion=$row['note'];
	$presentation=$row['presentation'];
	$unit_price=number_format($row['unit_price'],$precision_moneda,'.','');
	$porcentaje=$row['discount'] / 100;
	$precio_total=$unit_price*$qty;
	$total_descuento=$precio_total*$porcentaje;//Total descuento
	$total_descuento=number_format($total_descuento,$precision_moneda,'.','');//Formateo de numeros sin separador de miles (,)
	$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
	$sumador_descuento+=$total_descuento;//Sumador descuento
	$sumador_total+=$precio_total;//Sumador
	
	?>
	
        <tr>
            <td class="" style="width: 10%; text-align: center"><?php echo $qty; ?></td>
            <td class="left" style="width: 40%; text-align: left">
				<?php 
					echo $product_name." ".$model;
					if (!empty($descripcion)){
						echo "<br> $descripcion";
					}
				?>
			</td>
			<td class="left" style="width: 10%; text-align: center"><?php echo $presentation; ?></td>
            <td class="left" style="width: 14%; text-align: right"><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
			<td class="left" style="width: 12%; text-align: right"><?php echo number_format($total_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
            <td class="left" style="width: 14%; text-align: right"><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
            
        </tr>
   
	<?php 
	}
	$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
	$sumador_descuento=number_format($sumador_descuento,$precision_moneda,'.','');
	$total_neto=$total_parcial-$sumador_descuento;
	$total_neto=number_format($total_neto,$precision_moneda,'.','');
	$total_iva=($total_neto*$tax) / 100;
	$total_iva=number_format($total_iva,$precision_moneda,'.','');
	$total_cotizacion=$total_neto+$total_iva;

	
?>
			
			<tr style="vertical-align: top">
			<td class="top" colspan=4 >
				SON: 
				<?php
				$numero_con_decimales=$total_cotizacion;
				$son=number_format($numero_con_decimales,2,".","");
				$V=new EnLetras();
				echo strtoupper($V->ValorEnLetras($son,$currency_name));
				?>
			</td>
			
			<td class="top" colspan=0 style="text-align:right">
				PARCIAL <?php echo $simbolo_moneda;?>
			</td>
			<td class="top left " style="text-align:right">
			<?php echo number_format($total_parcial,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?>
			</td>
		</tr>
		<tr style="vertical-align: top">
			<td class="" colspan=5 style="text-align:right">
				DESCUENTO <?php echo $simbolo_moneda;?>
			</td>
			<td class="left " style="text-align:right">
			<?php echo number_format($sumador_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?>
			</td>
		</tr>
		<tr style="vertical-align: top">
			<td class="" colspan=5 style="text-align:right">
				<?php echo ucfirst(neto_txt);?> <?php echo $simbolo_moneda;?>
			</td>
			<td class="left " style="text-align:right">
			<?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?>
			</td>
		</tr>
		<tr style="vertical-align: top">
			<td class="" colspan=5 style="text-align:right">
				<?php echo strtoupper(tax_txt);?> <?php echo $simbolo_moneda;?>
			</td>
			<td class="left " style="text-align:right">
			<?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?>
			</td>
		</tr>
		<tr style="vertical-align: top">
			<td class="" colspan=5 style="text-align:right">
				<?php echo ucfirst(total_txt);?> <?php echo $simbolo_moneda;?>
			</td>
			<td class="left " style="text-align:right">
			<?php echo number_format($total_cotizacion,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?>
			</td>
		</tr>	
	 </table>
	<?php }?>
	  <?php if (!empty($note)){?>
		<p>
			<strong>OBSERVACIONES:</strong><br>
			<?php echo $note;?>
		</p>
	<?php }?>

	<p>
		<strong>NOTA</strong><br>
		Todo trabajo realizado tiene 30 días de garantía y debe presentarse este comprobante para hacerse válida la Garantía. Después de 60 días de la fecha de la Orden de Servicio no nos hacemos responsables del estado del mismo, y se donará a una fundación o se venderá para la recuperación de la inversión de la reparación. </p>
    <br><br>
	
	
	  <table cellspacing="10" style="width: 100%; text-align: left; font-size: 11pt;">
			 <tr>
                <td style="width:33%;text-align: center;border-top:solid 0px"></td>
               <td style="width:33%;text-align: center;border-top:solid 0px"></td>
               <td style="width:33%;text-align: center;border-top:solid 0px"></td>
            </tr>		
			 <tr>
                <td style="width:33%;text-align: center;border-top:solid 1px">Asesor de venta</td>
               <td style="width:33%;text-align: center;border-top:solid 0px"></td>
               <td style="width:33%;text-align: center;border-top:solid 1px">Aceptado Cliente</td>
            </tr>
        </table>
</page>

