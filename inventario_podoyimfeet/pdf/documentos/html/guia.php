<style type="text/css">
<!--
div.zone
{
    border: solid 0.5mm #337AB7;
    border-radius: 2mm;
    padding: 1mm;
    background-color: #FFF;
    color: #337AB7;
}
div.zone_over
{
    width: 35mm;
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
.left{border-left: 1px solid #999;}
.right{border-right: 1px solid #999;}
.top-border{
	border-top: 1px solid #999;
}
.bottom{
	border-bottom: 1px solid #999;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
.fondo-sky{
	background-color:#337AB7;
	color: white;
	padding:4px;
	font-size:14px;
	font-weight: bold;
}
.text-center{text-align:center;}
.text-right{text-align:right;}
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
            <td style="width:80%">
			<table style='width:100%' class='table'>
				<tr>
					<td style='width:35%'>
					<?php 
						if (!empty($logo_url)){
							?>
							<img src="<?php echo $logo_url;?>" style="width: 100%;">
							<?php 
						}	
					?>
					
					</td>
					<td style='width:65%' class='text-center'>
						<span style="font-size:15px;color:#444;margin-top:2px"><?php echo $bussines_name;?></span>
						<br>
				
						Dirección: <?php echo $address;?><br>
						Ciudad: <?php echo $city;?><br>
						Teléfono: <?php echo $phone;?><br>
						E-mail: <?php echo $email;?><br>
					</td>
					
				</tr>
			</table>	
               
              
				
				
                
            </td>
			
			
            <td style="width:20%;">
				
                <div class="zone zone_over" style="text-align: center; vertical-align: top; ">
					<strong>GUIA DE REMISION</strong>
				<p style="font-size:14pt;font-weight:bold">Nº: <?php echo $number;?>
				<br><b style='margin-top:18px;font-size:13px'>R.U.C. <?php echo $number_id;?> </b>
				</p> 
				
				</div>
               <span style="font-size:14px;color:#444;margin-top:5px">Fecha: <?php echo $created_at;?></span>
			   
            </td>
            
        </tr>
        
    </table>
	<table style="width:100%;margin-top:5px">
		<tr>
			<td style="width:45%;" class='fondo-sky text-center'>DIRECCION DE PARTIDA</td>
			<td style="width:10%;"></td>
			<td style="width:45%;" class='fondo-sky text-center'>DIRECCION DE LLEGADA</td>
		</tr>
		<tr>
			<td style="width:45%;vertical-align:top" >
				<?php 
					echo $sender_address;
				?>
			</td>
			<td style="width:10%;"></td>
			<td style="width:45%;vertical-align:top">
				<?php 
					echo $receiver_address;
				?>
			</td>
		</tr>
	</table>
	
	<table style="width:100%;margin-top:10px" class='table'>
		<tr>
			<td style="width:45%;" class='fondo-sky text-center'>DESTINATARIO</td>
			<td style="width:10%;"></td>
			<td style="width:45%;" class='fondo-sky text-center'>UNIDAD DE TRANSPORTE / CONDUCTOR</td>
		</tr>
		<tr>
			<td style="width:45%;vertical-align:top" class='left bottom right top'>
				<?php echo $receiver;?><br>

			</td>
			<td style="width:10%;"></td>
			<td style="width:45%;vertical-align:top" class='bottom right top'>
				<?php  echo $transport;?><br>

			</td>
		</tr>
	</table>
	
	<table style="width:100%;margin-top:10px" class='table-bordered' cellspacing=0>
		<tr>
			<td style="width:15%;" class='fondo-sky text-center'><small>CODIGO</small></td>
			<td style="width:40%;" class='fondo-sky'><small>DESCRIPCION</small></td>
			<td style="width:7.5%;" class='fondo-sky text-center'><small>CANT.</small></td>
			<td style="width:7.5%;" class='fondo-sky text-center'><small>U.M.</small></td>
			<td style="width:15%;" class='fondo-sky text-right'><small>PRECIO UNIT.</small></td>
			<td style="width:15%;" class='fondo-sky text-right'><small>TOTAL</small></td>
		</tr>	
		<?php
			$sql=mysqli_query($con, "select * from products, referral_guide_product where products.product_id=referral_guide_product.product_id and  referral_guide_product.referral_guide_id='$referral_guide_id' ");
			$sumador_total=0;
			$sumador_descuento=0;
			while ($row=mysqli_fetch_array($sql)){
				$product_code=$row['product_code'];
				$product_name=$row['product_name'];
				$qty=$row['qty'];
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
					<td style="width:15%;" class='text-center '><?php echo $row['product_code'];?></td>
					<td style="width:40%;" class=''><?php echo $product_name;?></td>
					<td style="width:7.5%;"  class='text-center'><?php echo $qty;?></td>
					<td style="width:7.5%;"  class='text-center'><?php echo $presentation;?></td>
					<td style="width:15%;" class='text-right'><?php echo number_format($unit_price,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					<td style="width:15%;" class='text-right '><?php echo number_format($precio_total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
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
			$total_cotizacion=$total_neto+$total_iva;
		
			?>
				<tr>
					<td class='text-left top-border' colspan='6' style='width:100%'></td>
				</tr>
			<?php 
				if ($sumador_descuento>0){
			?>		
				<tr>
					<td class='text-left ' colspan='4' style='width:70%'></td>
					<td class='text-right ' >PARCIAL <?php echo $simbolo_moneda;?></td>
					<td class='text-right '><?php echo number_format($total_parcial,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
				
				<tr>
					<td class='text-left' colspan='4' style='width:70%'></td>
					<td class='text-right' >DESCUENTO <?php echo $simbolo_moneda;?></td>
					<td class='text-right'><?php echo number_format($sumador_descuento,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
			<?php }?>	
				<tr>
					<td class='text-left' colspan='4' style='width:70%'></td>
					<td class='text-right' ><?php echo ucfirst(neto_txt);?> <?php echo $simbolo_moneda;?></td>
					<td class='text-right'><?php echo number_format($total_neto,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
				
				<tr>
					<td class='text-left' colspan='4' style='width:70%'></td>
					<td class='text-right' ><?php echo strtoupper(tax_txt);?> <?php echo $simbolo_moneda;?></td>
					<td class='text-right'><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
				
				<tr>
					<td class='text-left' colspan='4' style='width:70%'></td>
					<td class='text-right' ><?php echo ucfirst(total_txt);?> <?php echo $simbolo_moneda;?></td>
					<td class='text-right'><?php echo number_format($total_cotizacion,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
			
			

	</table>
    
	
	<table style="width:100%;margin-top:10px" class='table'>
		<tr>
			<td style="width:32%;" class='fondo-sky text-center'>TRANSPORTISTA</td>
			<td style="width:2%;" class='left right'></td>
			<td style="width:34%;" class='fondo-sky text-center'>MOTIVO DE TRASLADO</td>
			<td style="width:2%;" class='left right'></td>
			<td style="width:30%;" class='fondo-sky text-center'>COMPROBANTE DE PAGO</td>
		</tr>
		<tr>
			<td style="width:32%;vertical-align:top " class='left bottom right top text-center'>
				<?php echo $carrier;?>

			</td>
			<td style="width:2%;" class='left right'></td>
			<td style="width:34%;vertical-align:top " class='left bottom right top text-center'>
				
				<?php echo get_id('motivos_traslado','descripcion','id',$reason);?>
			</td>
			<td style="width:2%;" class='left right'></td>
			<td style="width:30%;vertical-align:top " class='left bottom right top text-center'>
				<?php  echo $comprobante;?>

			</td>
		</tr>
		
	</table>
	
	<br><br>
		 <table cellspacing="10" style="width: 100%; text-align: left; font-size: 11pt;">
			 <tr>
                <td style="width:33%;text-align: center;border-top:solid 0px"></td>
               <td style="width:33%;text-align: center;border-top:solid 0px"></td>
               <td style="width:33%;text-align: center;border-top:solid 0px"></td>
            </tr>		
			 <tr>
                <td style="width:33%;text-align: center;border-top:dotted 1px">
					
					FIRMA
				</td>
               <td style="width:33%;text-align: center;border-top:dotted 1px">Conformidad Cliente</td>
               <td style="width:33%;text-align: center;border-top:dotted 1px">Sr(a)(ita)</td>
            </tr>
        </table>
	

	  
	
	
	  
</page>

