<?php 
	//ini_set("display_errors", 1);
	if (isset($_GET['id_venta'])){
		/* Connect To Database*/
		require_once ("../config/db.php");
		require_once ("../config/conexion.php");
		$id_venta=intval($_GET['id_venta']);
		?>
		<table width='100%' class='table table-striped'>
			<?php
			$key=1;
			$sqlStr = "SELECT * FROM nota_remision, user where user.user_id=nota_remision.id_vendedor and id_nota_remision='$id_venta'";	
			$query = mysqli_query($con,$sqlStr);	
			
			while($row = mysqli_fetch_array($query))
			{
			$id_venta=$row['nota_remision'];
			$id_venta=base64_encode($id_venta);
			$id_client=$row['id_client'];
			$date_time=$row['fecha_nota_remision'];
			$vendedor=$row['firstname']." ".$row['lastname'];
			$id_vendedor=$row['id_vendedor'];
			$forma_pago=$row['forma_pago'];
			$tipo_documento=$row['tipo_documento'];
			if ($tipo_documento==1){$documento="FACTURA N&deg;";$letra="F";}
			if ($tipo_documento==2){$documento="FACTURA DE EXPORTACI&Oacute;N N&deg;";$letra="X";}
			if ($tipo_documento==3){$documento="COMPROBANTE DE CREDITO FISCAL N&deg;";$letra="C";} 
			if ($tipo_documento==4){$documento="NOTA DE CREDITO N&deg;";$letra="N";}
			if ($tipo_documento==5){$documento="NOTA DE DEBITO N&deg;";$letra="D";}
			if ($tipo_documento==6){$documento="NOTA DE REMISION N&deg;";$letra="R";}
			if ($tipo_documento==7){$documento="RECIBO DE INGRESO N&deg;";$letra="";}
			
			if ($forma_pago==1){$condicion_pago='Contado';}
			else if ($forma_pago==2){$condicion_pago='Cr&eacute;dito 30 d&iacute;as';}
			else if ($forma_pago==3){$condicion_pago='Cr&eacute;dito 45 d&iacute;as';}
			else if ($forma_pago==4){$condicion_pago='Cr&eacute;dito 60 d&iacute;as';}
			else if ($forma_pago==5){$condicion_pago='Cr&eacute;dito 90 d&iacute;as';}
			list($date, $time)=explode(" ",$date_time);
			list ($year,$month,$day)=explode("-",$date);
			$fecha=$day."-".$month."-".$year;
			if (!empty($id_client));
			{
			$sql_cliente=mysqli_query($con,"select * from clients where id_client='$id_client'");
			$rw_cliente=mysqli_fetch_array($sql_cliente);
			$nombres_cliente=$rw_cliente['name_client'];
			$direccion_cliente=$rw_cliente['dir_office_client'];
			$nit_cliente=$rw_cliente['nit_client'];
			$nrc_cliente=$rw_cliente['nrc_client'];
			$giro_cliente=$rw_cliente['giro_client'];
			}
			
			echo "<tr>";
			echo "<td style='text-align:right' colspan='6'><strong>$documento</strong></td>";
			echo "<td>$row[codigo_documento_venta]$letra$row[numero_documento_venta] <a class=\"btn btn-mini\" href=\"#myModal5\"  onclick=\"edit_doc('$id_venta','$row[codigo_documento_venta]','$row[numero_documento_venta]')\" data-toggle=\"modal\"><i class=\"icon-ok\"></i></a></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td><strong>CLIENTE</strong></td>";
			echo "<td colspan=4>$nombres_cliente</td>";
			echo "<td style='text-align:right'><strong>FECHA</strong></td>";
			echo "<td>$fecha $time</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td><strong>DIRECCION</strong></td>";
			echo "<td colspan=4>$direccion_cliente</td>";
			echo "<td style='text-align:right'><strong>VENDEDOR</strong></td>";
			echo "<td>$vendedor <a class=\"btn btn-mini\" href=\"#myModal3\" id=\"$id_venta\" onclick=\"edit_saler('$id_venta','$id_vendedor')\" data-toggle=\"modal\"><i class=\"icon-ok\"></i></a></td>";
			echo "</tr>";
			if ($row['tipo_documento']==1 or $row['tipo_documento']==2)
			{
			$cols=2;
			$celda_uno="VENTA A CUENTA DE";
			$celda_dos="";
			$celda_tres="NIT";
			$celda_cuatro=$nit_cliente;
			}
			else if ($row['tipo_documento']==3 or $row['tipo_documento']==4)
			{
			$cols=2;
			$celda_uno="NIT";
			$celda_dos=$nit_cliente;
			$celda_tres="NRC";
			$celda_cuatro=$nrc_cliente;
			}
			echo "<tr>";
			echo "<td><strong>$celda_uno</strong></td>";
			echo "<td colspan=2>$celda_dos</td>";
			echo "<td style='text-align:right'><strong>$celda_tres</strong></td>";
			echo "<td >$celda_cuatro</td>";
			echo "<td style='text-align:right'><strong>COND. DE PAGO</strong></td>";
			echo "<td >$condicion_pago <a class=\"btn btn-mini\" href=\"#myModal4\" id=\"$id_venta\" onclick=\"edit_cond('$id_venta','$forma_pago','$nombres_cliente','$id_client')\" data-toggle=\"modal\"><i class=\"icon-ok\"></i></a></td>";
			echo "</tr>";			
			
			echo "<tr>";
			echo "<td><strong>GIRO</strong></td>";
			echo "<td colspan=6>$giro_cliente</td>";
			echo "</tr>";	
			
						
			$codigo_documento_venta=$row['codigo_documento_venta'];
			$numero_documento_venta=$row['numero_documento_venta'];
			$id_transaccion=$row['id_transaccion'];
			$tipo_documento=$row['tipo_documento'];
			$venta_exenta=$row['venta_exenta'];
			$retiene_iva=$row['retiene_iva'];
			echo "<tr class=\"info\">
			<td>C&oacute;digo</td>
			<td>Cant.</td>
			<td>Descripci&oacute;n</td>
			<td class='text-right'>P. unitario</td>
			<td class='text-right'>V. no sujetas</td>
			<td class='text-right'>V. exentas</td>
			<td class='text-right'>V. gravadas</td>
			</tr>";
			$sql_detalle=mysqli_query($con,"select * from productos, detalle_nota_remision  where productos.id_producto=detalle_nota_remision.id_producto and detalle_nota_remision.codigo_documento_venta='$codigo_documento_venta' and detalle_nota_remision.numero_documento_venta='$numero_documento_venta'");
			$sumador_ventas_gravadas=0;	
			$ventas_exentas_formateado=0;
			$sumador_ventas_gravadas_sin_comas=0;
			$iva_sin_comas=0;	
			$iva_retenido_sin_comas=0;
			$iva_formateado=0;
			$iva_retenido_formateado=0;
			while ($rw_detalle=mysqli_fetch_array($sql_detalle))
			{
			$codigo_producto=$rw_detalle['codigo_producto'];
			$nombre_producto=$rw_detalle['nombre_producto'];
			$cantidad_producto=$rw_detalle['cantidad'];
			$precio_unitario=$rw_detalle['precio_unitario'];
			$precio_unitario_formateado=number_format($precio_unitario,4);
			$precio_unitario_sin_comas=str_replace(",","",$precio_unitario_formateado);
			if ($venta_exenta==1)
				{
			$ventas_exentas=$precio_unitario_sin_comas*$cantidad_producto;
			$ventas_exentas_formateado=number_format($ventas_exentas,4);
			$ventas_exentas_sin_comas=str_replace(",","",$ventas_exentas_formateado);	
			$sumador_ventas_gravadas+=$ventas_exentas_sin_comas;
				}
			else
				{
			$ventas_gravadas=$precio_unitario_sin_comas*$cantidad_producto;
			$ventas_gravadas_formateado=number_format($ventas_gravadas,4);
			$ventas_gravadas_sin_comas=str_replace(",","",$ventas_gravadas_formateado);
			$sumador_ventas_gravadas+=$ventas_gravadas_sin_comas;
				}
			echo "<tr>";
			echo "<td>$codigo_producto</td>";
			echo "<td>$cantidad_producto</td>";
			echo "<td>$nombre_producto</td>";
			echo "<td style='text-align:right'>$precio_unitario_formateado</td>";
			echo "<td></td>";
			echo "<td style='text-align:right'>".$ventas_exentas_formateado."</td>";
			echo "<td style='text-align:right'>".$ventas_gravadas_formateado."</td>";
			echo "</tr>";
			}
			$id_venta_decode=base64_decode($id_venta);
			echo "<input type='hidden' id='nf_$id_venta_decode' value='$codigo_documento_venta$letra$numero_documento_venta'>";
			}
			
			$sumador_ventas_gravadas_formateado=number_format($sumador_ventas_gravadas,4);
			$sumador_ventas_gravadas_sin_comas=str_replace(",","",$sumador_ventas_gravadas_formateado);	
	if (($tipo_documento==6 and $venta_exenta==0) OR ($tipo_documento==4 and $venta_exenta==0))
	{
	$iva=$sumador_ventas_gravadas_sin_comas*0.13;
	$iva_formateado=number_format($iva,4);
	$iva_sin_comas=str_replace(",","",$iva_formateado);
	}
	$sub_total=$sumador_ventas_gravadas_sin_comas+$iva_sin_comas;
	$sub_total_formateado=number_format($sub_total,4);
	$sub_total_sin_comas=str_replace(",","",$sub_total_formateado);
	if ($retiene_iva==1 and $sumador_ventas_gravadas_sin_comas>100)
	{
	$iva_retenido=$sumador_ventas_gravadas_sin_comas*0.01;
	$iva_retenido_formateado=number_format($iva_retenido,4);
	$iva_retenido_sin_comas=str_replace(",","",$iva_retenido_formateado);
	}
	if ($venta_exenta==1)
	{
	$ventas_exentas=$sub_total_sin_comas;
	$ventas_exentas_formateado=number_format($ventas_exentas,4);
	$ventas_exentas_sin_comas=str_replace(",","",$ventas_exentas_formateado);
	}
	$total_pagar=$sub_total_sin_comas-$iva_retenido_sin_comas;
	$total_pagar_formateado=number_format($total_pagar,2);
	$total_pagar_sin_comas=str_replace(",","",$total_pagar_formateado);
	
	
		  $partir=explode(".",$total_pagar_sin_comas);//Partir la cantidad total
		  $entero=$partir[0];//Obtengo la cantidad entera
		  $decimal=$partir[1];//Obtengo la cantidad decimal
		  $formateo_total=number_format($total_pagar_sin_comas,2);
		  $dos_decimal_total=explode(".",$formateo_total);
		  $decimal=$dos_decimal_total[1];
		  $son= "";
		  echo "<input type='hidden' value='$son' id='son'>";
		  $son=base64_encode($son);
		  $id_venta_decode=base64_decode($id_venta); 	 	
		  
	echo 	"<tr>
	<td colspan=6 style='text-align:right'>Sumas $</td>
	<td style='text-align:right'>$sumador_ventas_gravadas_formateado</td>
	</tr>";
	echo "	<tr>
	<td colspan=6 style='text-align:right'>18% IGV</td>
	<td style='text-align:right'>$iva_formateado</td>
		</tr>";
	echo "<tr>
	<td colspan=6 style='text-align:right'>Sub-total $</td>
	<td style='text-align:right'>$sub_total_formateado</td>
		</tr>";
	echo "<tr>
	<td colspan=6 style='text-align:right'>1% IGV retenido $</td>
	<td style='text-align:right'>$iva_retenido_formateado</td>
		</tr>";
	echo 	"<tr>
	<td colspan=6 style='text-align:right'>Ventas exentas $</td>
	<td style='text-align:right'>$ventas_exentas_formateado</td>
		</tr>";
	echo 	"<tr>
	<td colspan=6 style='text-align:right'>Total a pagar $</td>
	<td style='text-align:right'>$total_pagar_formateado</td>
	<td></td>
	</tr>";
	
	
	
			?>
		</table>		
		<?php
	}
?>