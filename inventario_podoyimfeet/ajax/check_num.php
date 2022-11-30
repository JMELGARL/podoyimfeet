<?php
	include("is_logged.php");//Archivo comprueba si el usuario esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");
	require_once ("../config/conexion.php");
	require_once ("../libraries/inventory.php");
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$type=intval($_REQUEST['type']);
	$branch_id=intval($_REQUEST['branch_id']);
	$next_number=next_number($type,$branch_id);
	$get_document_printing=get_document_printing($type,$branch_id);
	$prefix=$get_document_printing['code'];
		
	$number[] = array('number'=> $next_number,'prefix'=>$prefix);
	//Creamos el JSON
	$json_string = json_encode($number);
	echo $json_string;
	
}
?>          
		  
