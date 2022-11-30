<?php 
#compruebo si esta logueado
session_start();
if (!isset($_SESSION['user_id'])){
	header("location: ../");//Redirecciona 
	exit;
}
?>