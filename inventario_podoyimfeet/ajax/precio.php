<?php

	if (isset($_REQUEST['profit'])){
		$utilidad=intval($_REQUEST['profit']);
		$costo=floatval($_REQUEST['buying_price']);
		$utilidad=($costo * $utilidad) /100;
		$precio_venta=$costo + $utilidad;
		$precio_venta=number_format($precio_venta,2,'.','');

		$price[] = array('precio'=> $precio_venta);
		//Creamos el JSON
		$json_string = json_encode($price);
		echo $json_string;
	}
		
?>