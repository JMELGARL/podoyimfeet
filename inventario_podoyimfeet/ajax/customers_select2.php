<?php
include("is_logged.php");//Archivo comprueba si el usuario esta logueado
// connect to database
 require_once ("../config/db.php");
 require_once ("../config/conexion.php");

// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
$search = strip_tags(trim($_GET['q'])); 

// Do Prepared Query
$query = mysqli_query($con, "SELECT * FROM customers WHERE name LIKE '%$search%' LIMIT 40");

 echo mysqli_error($con);

// Do a quick fetchall on the results
$list = array();


while ($list=mysqli_fetch_array($query)){
	$sq_contact=mysqli_query($con,"select * from contacts where client_id='".$list['id']."'");
	$rw_contact=mysqli_fetch_array($sq_contact);
	$contact_name=$rw_contact['first_name']." ".$rw_contact['last_name'];
	$contact_phone=$rw_contact['phone'];
	$contact_email=$rw_contact['email'];
	$data[] = array('id' => $list['id'], 'text' => $list['name'],'work_phone'=>$list['work_phone'], 'contact_name'=>$contact_name,'contact_phone'=>$contact_phone,'contact_email'=>$contact_email);
}
// return the result in json
echo json_encode($data);
?>