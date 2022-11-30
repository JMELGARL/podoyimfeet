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
    width: 38mm;
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
  border: 1px solid #337AB7;
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
<page  backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 13px; font-family: helvetica" >
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
            <td style="width:26%">
               <?php 
				if (!empty($logo_url)){
					?>
					<img src="<?php echo $logo_url;?>" style="width: 100%;">
					<?php 
				}	
				?>
                			
                
            </td>
			<td style="width:48%;text-align:center">
				<span style="font-size:13pt"><strong><?php echo $bussines_name;?></strong></span><br>
				<span style="color:#555"><?php echo $industry;?></span><br>
				<span style="color:#555">RUC: <?php echo $number_id;?></span><br>
				<span style="color:#555"><?php echo $address.", ". $city.",  ".$state.", ". $postal_code;?></span><br>
				<span style="color:#555">Tel.: <?php echo $phone;?></span><br>
				<span style="color:#555">Email: <?php echo $email;?></span><br>
			</td>
			<td style="width:3%">
			</td>
			
            <td style="width:23%;">
				
                <div class="zone zone_over" style="text-align: center; vertical-align: top; ">
					<strong>RECIBO DE INGRESO</strong>
				<p style="font-size:14pt;font-weight:bold">Nº: <?php echo $charge_id;?></p> 
				
				</div>
               <span style="font-size:14px;color:#444;margin-top:5px">Fecha: <?php echo $payment_date;?></span>
            </td>
            
        </tr>
        
    </table>
	<?php 
		include("num_letras.php");
		$V=new EnLetras();
		$monto_letras= $V->ValorEnLetras($total,"$currency_name");
	
	?>
	<br>
	<table style="width:100%;margin-top:5px" class='table-bordered'>
		<tr>
			<td style="width:20%;padding-top:2mm">RECIBÍ DE:</td>
			<td style="width:80%;padding-top:2mm"><?php echo $cliente;?></td>
		</tr>
		<tr>
			<td style="width:20%;padding-top:2mm" >LA SUMA DE: </td>
			<td style="width:80%;padding-top:2mm"><?php echo $monto_letras;?></td>
		</tr>
		<tr>
			<td style="width:20%;padding-top:2mm" >EN CONCEPTO DE: </td>
			<td style="width:80%;padding-top:2mm">Abono a documento Nº: <?php echo "$sale_prefix $sale_number";?></td>
		</tr>
		<tr>
			<td style="width:20%;padding-top:2mm;text-align:right" >TOTAL <?php echo $simbolo_moneda;?>  </td>
			<td style="width:80%;padding-top:2mm"> <?php echo number_format($total,$precision_moneda,$sepador_decimal_moneda,$sepador_millar_moneda);?></td>
		</tr>
		
	</table>
	
	<table class="table-bordered" style="width:100%;margin-top:10px">
		<tr>
			<td style="width:100%;text-align:center;padding:1mm">Este comprobante es v&aacute;lido sin enmendaduras ni tachaduras y debe tener firma y sello de la persona autorizada.</td>
		</tr>
	</table>
	
<table style="width:100%;margin-top:40px">	
	<tr>
		<td style="width:25%;border-top: solid 1px #337AB7;text-align:center">
			 Hecho por
		</td>
		<td style="width:12.5%;"></td>
		<td style="width:25%;border-top: solid 1px #337AB7;text-align:center">
			  Recibido
		</td>
		<td style="width:12.5%;"></td>
		<td style="width:25%;border-top: solid 1px #337AB7;text-align:center">
			    Autorizado
		</td>
	</tr>
	
</table>   
	
	
	

	  
	
	
	  
</page>

