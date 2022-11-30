<?php

	# conectare la base de datos
    $con=@mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if(!$con){
        die("imposible conectarse: ".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        die("Conexión falló: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
	# obtengo la zona horaria registrada en la db
	function get_timezone(){
		global $con,$timezone;
		$sql=mysqli_query($con, "select timezones.name from business_profile, timezones where business_profile.timezone_id=timezones.id and business_profile.id=1");	
		$rw=mysqli_fetch_array($sql);
		$timezone_name=$rw['name'];
		$timezone=date_default_timezone_set($timezone_name);
		return $timezone;
	}
	get_timezone();
	# obtengo la moneda actual del sistema
	function get_currency($id_moneda){
		global $con;
		$sql_currencies=mysqli_query($con,"SELECT * FROM currencies where id='$id_moneda'");
		while ($rw=mysqli_fetch_array($sql_currencies))
		{
			$array_currency=array("currency_name"=>$rw['name'],"currency_symbol"=>$rw['symbol'], 'currency_precision'=>$rw['precision'],'currency_thousand_separator'=>$rw['thousand_separator'], 'currency_decimal_separator'=>$rw['decimal_separator']);
		}
		return $array_currency;
	}
	function get_skin(){
		global $con;
		$query=mysqli_query($con,"select skins.value from business_profile, skins where business_profile.skin_id=skins.id and business_profile.id=1 ");
		$row=mysqli_fetch_array($query);
		return $skin=$row['value'];
	}	
	$skin=get_skin();
	
	/*Inicio Global variables */
		define('tax_txt', 'igv');
		define('neto_txt', 'neto');
		define('total_txt', 'total');
	/*Fin Global variables */
	
?>

