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
.top{
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
<page backtop="15mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 13px; font-family: helvetica" >
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
            <td style="width:50%">
               <?php 
				if (!empty($logo_url)){
					?>
					<img src="<?php echo $logo_url;?>" style="width: 70%;">
					<?php 
				}	
				?>
                <br><span style="font-size:16px;color:#444;margin-top:5px"><?php echo $bussines_name;?></span>
				<br><br>
				
					Dirección: <?php echo $address;?><br>
					Ciudad: <?php echo $city;?><br>
					Teléfono: <?php echo $phone;?><br>
					E-mail: <?php echo $email;?><br>
				
                
            </td>
			<td style="width:30%;"></td>
			
            <td style="width:20%;">
				
                <div class="zone zone_over" style="text-align: center; vertical-align: top; ">
					<strong>ORDEN DE COMPRA</strong>
				<p style="font-size:14pt;font-weight:bold">Nº: <?php echo $purchase_order_id;?></p> 
				
				</div>
               <span style="font-size:14px;color:#444;margin-top:5px">Fecha: <?php echo $created_at;?></span>
            </td>
            
        </tr>
        
    </table>
	<table style="width:100%;margin-top:5px">
		<tr>
			<td style="width:45%;" class='fondo-sky'>PROVEEDOR</td>
			<td style="width:10%;"></td>
			<td style="width:45%;" class='fondo-sky'>ENVIAR A</td>
		</tr>
		<tr>
			<td style="width:45%;vertical-align:top" >
				<?php echo $nombre_proveedor;?><br>
				Contacto: <?php echo $nombres_contacto." ".$apellidos_contacto;?><br>
				Dirección: <?php echo $direccion_proveedor;?><br>
				Ciudad: <?php echo $ciudad_proveedor;?><br>
				Teléfono: <?php echo $telefono_proveedor;?><br>
				E-mail: <?php echo $email_contacto;?>
			</td>
			<td style="width:10%;"></td>
			<td style="width:45%;vertical-align:top">
				<?php echo $bussines_name;?><br>
				Contacto: <?php echo $fullname;?><br>
				Dirección: <?php echo $address;?><br>
				Ciudad: <?php echo $city;?><br>
				Teléfono: <?php echo $phone;?><br>
				E-mail: <?php echo $email;?>
			</td>
		</tr>
	</table>
	
	<table style="width:100%;margin-top:10px" class='table'>
		<tr>
			<td style="width:50%;" class='fondo-sky'>CONDICIONES DE COMPRA</td>
			
			<td style="width:50%;" class='fondo-sky'>ENVIAR POR</td>
		</tr>
		<tr>
			<td style="width:50%;vertical-align:top" class='left bottom right top'>
				<?php echo $terms;?><br>

			</td>
			
			<td style="width:50%;vertical-align:top" class='bottom right top'>
				<?php echo $ship_via;?><br>

			</td>
		</tr>
	</table>
	
	<table style="width:100%;margin-top:10px" class='table'>
		<tr>
			<td style="width:14%;" class='fondo-sky text-center'><small>Código</small></td>
			<td style="width:8%;" class='fondo-sky text-center'><small>Cantidad <br>Despachada</small></td>
			<td style="width:8%;" class='fondo-sky text-center'><small>Cantidad<br>Solicitada</small></td>
			<td style="width:30%;" class='fondo-sky'><small>Descripción</small></td>
			<td style="width:10%;" class='fondo-sky'><small>Status</small></td>
			<td style="width:15%;" class='fondo-sky text-right'><small>Precio Unit.</small></td>
			<td style="width:15%;" class='fondo-sky text-right'><small>Total</small></td>
		</tr>	
		<?php
			$sql_detalle=mysqli_query($con,"select * from purchase_order_product, products where products.product_id=purchase_order_product.product_id and purchase_order_id='$purchase_order_id'");
			$sumador=0;
			while ($row=mysqli_fetch_array($sql_detalle)){
				$cantidad=intval($row['qty']);
				$descripcion=$row['note'];
				$presentation=$row['presentation'];
				$qty_rec=intval($row['qty_rec']);
				$status_oc=$row['status_oc'];
				$precio_unitario=number_format($row['unit_price'],$precision_moneda,'.','');
				$total=$cantidad*$precio_unitario;
				$total=number_format($total,$precision_moneda,'.','');
				$sumador+=$total;
				?>
				<tr>
					<td style="width:14%;" class='text-center left bottom'><?php echo $row['product_code'];?></td>
					<td style="width:8%;"  class='text-center bottom'><?php echo $qty_rec;?></td>
					<td style="width:8%;"  class='text-center bottom'><?php echo $cantidad;?></td>
					<td style="width:30%;" class='bottom'>
						<?php echo $row['product_name']." ($presentation)";?>
						<?php 
						if (!empty($descripcion)){
							echo "<br> $descripcion";
						}
							
						?>
					</td>
					<td style="width:10%;" class='bottom'><?php echo $status_oc;?></td>
					<td style="width:15%;" class='text-right bottom'><?php echo number_format($precio_unitario,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
					<td style="width:15%;" class='text-right bottom right'><?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
				<?php 
			}
				$subtotal=number_format($sumador,$precision_moneda,'.','');
				if ($includes_tax==0){
					$total_iva=($subtotal*$tax) / 100;
					$total_iva=number_format($total_iva,$precision_moneda,'.','');
				} else {
					$tax_value=$tax/100 + 1;
					$tax_value=number_format($tax_value,$precision_moneda,'.','');	
					$neto=$subtotal/$tax_value;
					$neto=number_format($neto,$precision_moneda,'.','');
					$total_iva=$subtotal-$neto;
					$subtotal=number_format($neto,$precision_moneda,'.','');
					$total_iva=number_format($total_iva,$precision_moneda,'.','');
				}
				
				$total_oc=$subtotal+$total_iva;
		?>
				<tr>
					<td class='text-left bottom' colspan='5' style='width:70%'>
						SON: 
						<?php
							$numero_con_decimales=$total_oc;
							$son=number_format($numero_con_decimales,2,".","");
							$V=new EnLetras();
							echo strtoupper($V->ValorEnLetras($son,$currency_name));
						?>
					</td>
					
					<td class='text-right' ><?php echo ucfirst(neto_txt);?> <?php echo $simbolo_moneda;?></td>
					<td class='text-right'><?php echo number_format($subtotal,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
				<tr>
					<td style="background-color:#a8a8a8;" class='text-center bottom left right' colspan='5' >Comentarios o instrucciones especiales</td>
					<td class='text-right'><?php echo strtoupper(tax_txt);?> <?php echo $simbolo_moneda;?></td>
					<td class='text-right '><?php echo number_format($total_iva,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
				</tr>
				<tr>
					<td class='bottom left right' style="width:70%;" colspan='5'>
						<?php echo $note;?>
					
					
					</td>
					<td class='text-right' style="width:15%;vertical-align:top"><?php echo ucfirst(total_txt);?> <?php echo $simbolo_moneda;?></td>
					<td class='text-right ' style="width:15%;vertical-align:top"><strong><?php echo number_format($total_oc,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></strong></td>
				</tr>
		

	</table>
    
	<p class='text-center'>Si usted tiene alguna pregunta sobre esta orden de compra, por favor, póngase en contacto con<br>
	<?php echo $fullname;?>, Teléfono: <strong><?php echo $phone;?></strong>, Correo electrónico: <strong><?php echo $user_email;?></strong> 
	</p>
	

	  
	
	
	  
</page>

