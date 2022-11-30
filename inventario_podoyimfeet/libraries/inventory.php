<?php
	function add_inventory($product_id,$product_quantity,$branch_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from inventory where product_id='".$product_id."' and branch_id='".$branch_id."' ");//Consulta para verificar si el producto se encuentra reguistrado en  el inventario
		$count=mysqli_num_rows($sql);
		if ($count==0){
			$insert=mysqli_query($con,"insert into inventory (product_id, product_quantity,branch_id) values ('$product_id','$product_quantity','$branch_id')");//Ingresa un nuevo producto al inventario
		} else {
			$sql2=mysqli_query($con,"select * from inventory where product_id='".$product_id."' and branch_id='".$branch_id."'");
			$rw=mysqli_fetch_array($sql2);
			$old_qty=$rw['product_quantity'];//Cantidad encontrada en el inventario
			$new_qty=$old_qty+$product_quantity;//Nueva cantidad en el inventario
			$update=mysqli_query($con,"UPDATE inventory SET product_quantity='".$new_qty."' WHERE product_id='".$product_id."' and branch_id='".$branch_id."'");//Actualizo la nueva cantidad en el inventario
		}
	}
	
	function remove_inventory($product_id,$product_quantity,$branch_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from inventory where product_id='".$product_id."' and branch_id='".$branch_id."'");
		$rw=mysqli_fetch_array($sql);
		$old_qty=$rw['product_quantity'];//Cantidad encontrada en el inventario
		$new_qty=$old_qty-$product_quantity;//Nueva cantidad en el inventario
		$update=mysqli_query($con,"UPDATE inventory SET product_quantity='".$new_qty."' WHERE product_id='".$product_id."' and branch_id='".$branch_id."'");//Actualizo la nueva cantidad en el inventario
	}
	function update_buying_price($product_id,$buying_price){
		global $con;//Variable de conexion
		$update=mysqli_query($con,"UPDATE products SET buying_price='".$buying_price."' WHERE product_id='".$product_id."'");
	}
	
	function get_stock($product_id, $branch_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"SELECT 	product_quantity FROM inventory WHERE product_id='".$product_id."' and branch_id='".$branch_id."'");
		$rw=mysqli_fetch_array($sql);
		$stock=number_format($rw['product_quantity'],0,'.','');
		return $stock;
	}
	function is_service($product_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from products where product_id='".$product_id."' and is_service='1'");
		$count=mysqli_num_rows($sql);
		return $count;
	}
	function add_sale_product($sale_id,$product_id,$qty,$discount, $unit_price){
		global $con;//Variable de conexion
		$insert=mysqli_query($con, "INSERT INTO sale_product (sale_id,product_id,qty,discount,unit_price) VALUES ('$sale_id','$product_id','$qty','$discount','$unit_price')");
	}
	function add_purchase_product($purchase_id,$product_id,$qty, $unit_price,$branch_id){
		global $con;//Variable de conexion
		$insert=mysqli_query($con, "INSERT INTO purchase_product (purchase_id,product_id,qty,unit_price,branch_id) VALUES ('$purchase_id','$product_id','$qty','$unit_price','$branch_id')");
	}
	
	function add_inventory_tweaks_product($inventory_tweak_id,$product_id,$qty, $unit_price,$branch_id){
		global $con;//Variable de conexion
		$sql="INSERT INTO inventory_tweaks_product (id, inventory_tweak_id, product_id, qty, unit_price, branch_id) VALUES 
		(NULL, '$inventory_tweak_id', '$product_id', '$qty', '$unit_price', '$branch_id');";
		$insert=mysqli_query($con, $sql);
	}
	function orderToInvoice($order_id,$user_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con, "select * from products, order_product where products.product_id=order_product.product_id and order_product.order_id='$order_id'");
		while ($row=mysqli_fetch_array($sql)){
			$product_id=$row['product_id'];
			$qty=$row['qty'];
			$discount=$row['discount'];
			$unit_price=$row['unit_price'];
			add_tmp($product_id, $qty, $unit_price, $user_id, $discount);
		}
	
	}
	
	//La siguiente funcion obtine un campo de la base de datos pasando como
	// parametros el nombre de la tabla, columna a retorna el campo a buscar dentro de  la dba_close
	// y el termino de bussqueda en la base de datos. Retorna solo (1) resultado
	function get_id($table,$row,$condition,$equal){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select $row from $table where $condition='$equal' limit 0,1");
		$rw=mysqli_fetch_array($sql);
		$result= $rw[$row];
		return $result;
	} 
	function update_table($table,$row,$value,$condition,$equal){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"update $table SET $row='$value' where $condition='$equal'");
	}
	
	function quoteToInvoice($quote_id,$user_id,$branch_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con, "select * from products, quote_product where products.product_id=quote_product.product_id and quote_product.quote_id='$quote_id'");
		while ($row=mysqli_fetch_array($sql)){
			$product_id=$row['product_id'];
			$qty=$row['qty'];
			$discount=$row['discount'];
			$unit_price=$row['unit_price'];
			add_tmp($product_id, $qty, $unit_price, $user_id, $discount,$branch_id);
		}
	
	}
	
	
	function guiaToInvoice($referral_guide_id,$user_id,$branch_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con, "select * from products,  referral_guide_product where products.product_id= referral_guide_product.product_id and  referral_guide_product.referral_guide_id='$referral_guide_id'");
		while ($row=mysqli_fetch_array($sql)){
			$product_id=$row['product_id'];
			$qty=$row['qty'];
			$discount=$row['discount'];
			$unit_price=$row['unit_price'];
			add_tmp($product_id, $qty, $unit_price, $user_id, $discount,$branch_id);
		}
	}
	function get_data($table, $row,$value){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from $table where $row='$value' ");
		$rw=mysqli_fetch_array($sql);
		return $rw;
	}
	
	function orderToPurchase($purchase_order_id,$user_id){
		global $con;//Variable de conexion
		$sql=mysqli_query($con, "select * from products,  purchase_order_product where products.product_id= purchase_order_product.product_id and  purchase_order_product.purchase_order_id='$purchase_order_id' and oc='1'");
		while ($row=mysqli_fetch_array($sql)){
			$product_id=$row['product_id'];
			$qty=$row['qty'];
			$unit_price=$row['unit_price'];
			$branch_id=$row['branch_id'];
			add_tmp($product_id, $qty, $unit_price, $user_id,0,$branch_id);
			
		}
	
	}
	function nex_purchase_number(){
		global $con;
		$sql=mysqli_query($con,"select purchase_order_number from purchases order by purchase_id desc limit 0,1");
		$rw=mysqli_fetch_array($sql); 
		$purchase_number=$rw['purchase_order_number'];
		$nex_purchase_number=$purchase_number+1;
		
		return $nex_purchase_number;
		
	}
	//Agrega un nuevo registro a la tabla product_tmp
	function add_tmp($product_id, $qty, $unit_price, $user_id,$descuento=null,$branch_id){
		global $con;
		$query_tmp=mysqli_query($con,"select * from product_tmp where product_id='$product_id' and user_id='$user_id' and branch_id='$branch_id'");
		$count=mysqli_num_rows($query_tmp);
		if ($count==0){
			$sql=mysqli_query($con,"insert into product_tmp 
			(id_tmp, product_id, qty, unit_price, user_id, discount, branch_id)
			values (NULL, '$product_id','$qty','$unit_price','$user_id','$descuento','$branch_id')");
		} else {
			$rw=mysqli_fetch_array($query_tmp);
			$actual_qty=$rw['qty'];
			$new_qty=$actual_qty+$qty;
			$update=mysqli_query($con,"update product_tmp set qty='$new_qty' where product_id='$product_id' and user_id='$user_id' and branch_id='$branch_id'");
		}
		
	}
	//Elimina un registro de la tabla product_tmp
	function remove_tmp($id_tmp){
		global $con;
		$sql=mysqli_query($con,"DELETE FROM product_tmp WHERE id_tmp='$id_tmp'");
	}
	function count_tmp($user_id){
		global $con;
		$sql=mysqli_query($con,"select product_id from product_tmp where user_id='$user_id'");
		$count=mysqli_num_rows($sql); 
		return $count;
	}
	
	function count_guia($referral_guide_id){
		global $con;
		$sql=mysqli_query($con,"select product_id from referral_guide_product where referral_guide_id='$referral_guide_id'");
		$count=mysqli_num_rows($sql); 
		return $count;
	}
	
	//Guarda una compra
	function add_purchase($order_number, $supplier_id, $purchase_by,$purchase_date,$due_date,$payment_method,$status,$includes_tax,$currency_id){
		global $con;
		$sum=mysqli_query($con,"select sum(qty*unit_price) as subtotal from product_tmp where user_id='$purchase_by'");
		$rw_sum=mysqli_fetch_array($sum);
		$sumador_total=$rw_sum['subtotal'];
		$tax= get_tax();
		$total_parcial=number_format($sumador_total,2,'.','');
		$total_neto=$total_parcial;
		$total_neto=number_format($total_neto,2,'.','');
		$total_iva=($total_neto*$tax) / 100;
		$total_iva=number_format($total_iva,2,'.','');
		$total_compra=$total_neto+$total_iva;
		$total_compra=number_format($total_compra,2,'.','');
		$purchase_id=next_insert_id('purchases');
		
		$sql="INSERT INTO purchases
		(purchase_id, purchase_order_number	, supplier_id, purchase_by, subtotal, tax, total, purchase_date,due_date,payment_method,status,includes_tax,currency_id) 
		VALUES ('$purchase_id', '$order_number', '$supplier_id', '$purchase_by', '$total_neto', '$total_iva', '$total_compra', '$purchase_date','$due_date','$payment_method','$status','$includes_tax','$currency_id');";
		$query=mysqli_query($con,$sql);
		//echo mysqli_error($con);
		if ($query){
		 $true=1;
		} else {
			$true=0;
		}
		$sql_tmp=mysqli_query($con,"select * from product_tmp where user_id='$purchase_by'");
		while ($rw_tmp=mysqli_fetch_array($sql_tmp)){
			$id_tmp=$rw_tmp['id_tmp'];
			$product_id=$rw_tmp['product_id'];
			$qty=$rw_tmp['qty'];
			$unit_price=$rw_tmp['unit_price'];
			$branch_id=$rw_tmp['branch_id'];
			add_purchase_product($purchase_id,$product_id,$qty,$unit_price,$branch_id);//Agrego un registro  a la tabla purchase_product
			add_inventory($product_id,$qty,$branch_id);//Agrego la cantidad en el inventario;
			update_buying_price($product_id,$unit_price);//Actualizo precio de compra
			update_selling_price($product_id,$unit_price);//Actualizo precio de venta
			
			
			
			remove_tmp($id_tmp);//Elimina el item de la tabla temporal
		}
		if ($status==1){
			$insert=mysqli_query($con,"INSERT INTO payments (payment_id, purchase_id, total, payment_date, payment_type, number_reference, note, user_id) VALUES (NULL, '$purchase_id', '$total_compra', '$purchase_date', '$payment_method', '', '', '$purchase_by');");
			
			$get_balance=get_balance();//Ultimo balance
			$balance=$get_balance-$total_compra;
			save_finances("Pago a proveedor",2,$total_compra,$balance,$purchase_date,$purchase_by,1); 
			$id_finances=get_last_id('finances','id');//Obtengo el id de la finances ingresado
			$id_pago=get_last_id('payments','payment_id');//Obtengo el ultimo del pago ingresado
			update_transacion_id($id_pago,$id_finances,'payments','payment_id');//Actualiza el id del pago para vincular a tabla finances	
		}
			
		
		
		
		
		
		
		return $true;
		
	}
	
	
	//Guarda Ajuste
	function add_adjustment($type, $number_reference, $note, $user_id,$created_at){
		global $con;
		$sum=mysqli_query($con,"select sum(qty*unit_price) as subtotal from product_tmp where user_id='$user_id'");
		$rw_sum=mysqli_fetch_array($sum);
		$sumador_total=$rw_sum['subtotal'];
		$tax= get_tax();
		$total_parcial=number_format($sumador_total,2,'.','');
		$total_neto=$total_parcial;
		$total_neto=number_format($total_neto,2,'.','');
		$total_iva=($total_neto*$tax) / 100;
		$total_iva=number_format($total_iva,2,'.','');
		$total_ajuste=$total_neto+$total_iva;
		$total_ajuste=number_format($total_ajuste,2,'.','');
		$inventory_tweak_id=next_insert_id('inventory_tweaks');
		
		$sql="INSERT INTO inventory_tweaks (id, number_reference, user_id, subtotal, tax, total, created_at, type, note) VALUES 
		(NULL, '$number_reference', '$user_id', '$total_neto', '$total_iva', '$total_ajuste', '$created_at', '$type', '$note');";
		$query=mysqli_query($con,$sql);
		if ($query){
		 $true=1;
		} else {
			$true=0;
		}
		$sql_tmp=mysqli_query($con,"select * from product_tmp where user_id='$user_id'");
		while ($rw_tmp=mysqli_fetch_array($sql_tmp)){
			$id_tmp=$rw_tmp['id_tmp'];
			$product_id=$rw_tmp['product_id'];
			$qty=$rw_tmp['qty'];
			$unit_price=$rw_tmp['unit_price'];
			$branch_id=$rw_tmp['branch_id'];
			add_inventory_tweaks_product($inventory_tweak_id,$product_id,$qty,$unit_price,$branch_id);//Agrego un registro  a la tabla
			
			if ($type==1){
				add_inventory($product_id,$qty,$branch_id);//Agrego la cantidad en el inventario;
			} elseif ($type==2) {
				remove_inventory($product_id,$qty,$branch_id);//Elimino la cantidad del inventario;
			}
			
			
			
			remove_tmp($id_tmp);//Elimina el item de la tabla temporal
		}
		
		
		
		
		return $true;
		
	}
	
	//Guarda una venta
	function add_sale($sale_number, $sale_prefix, $customer_id, $sale_by,$sale_date, $due_date, $type,$branch_id, $status,$seller_id,$cashbox_id, $payment_method,$includes_tax,$currency_id,$guia_number){
		global $con;
		$sale_id=next_insert_id('sales');
		$sql_tmp=mysqli_query($con,"select * from product_tmp where user_id='$sale_by'");
		$sumador_descuento=0;
		$sumador_total=0;
		while ($rw_tmp=mysqli_fetch_array($sql_tmp)){
			$id_tmp=$rw_tmp['id_tmp'];
			$product_id=$rw_tmp['product_id'];
			$qty=$rw_tmp['qty'];
			$unit_price=$rw_tmp['unit_price'];
			$discount=$rw_tmp['discount'];
			$branch_id=$rw_tmp['branch_id'];
			include("../../currency.php");//Archivo que obtiene los datos de la moneda
			$precio_total=$unit_price*$qty;
			$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
			$descuento=($precio_total * $discount) / 100;
			$descuento=number_format($descuento,$precision_moneda,'.','');//Descuento Formateado
			$sumador_descuento+=$descuento;//Sumador
			$sumador_total+=$precio_total;//Sumador
			add_sale_product($sale_id,$product_id,$qty,$discount, $unit_price);//Agrego un registro  a la tabla sale_product
			$is_service= is_service($product_id);
			if ($is_service==0){//SINO es un servicio
				remove_inventory($product_id,$qty,$branch_id );//Disminuye la cantidad en el inventario;
			}
			remove_tmp($id_tmp);//Elimina el item de la tabla temporal
		}	
		$tax=get_tax();
		$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
		$sumador_descuento=number_format($sumador_descuento,$precision_moneda,'.','');
		$total_neto=$total_parcial-$sumador_descuento;
		$total_neto=number_format($total_neto,$precision_moneda,'.','');
		if ($includes_tax==0){
			$total_iva=($total_neto*$tax) / 100;
		} else if ($includes_tax==1){
			$tax_value=$tax/100 + 1;
			$tax_value=number_format($tax_value,$precision_moneda,'.','');	
			$neto=$total_neto/$tax_value;
			$neto=number_format($neto,$precision_moneda,'.','');
			$total_iva=$total_neto-$neto;
			$total_neto=number_format($neto,$precision_moneda,'.','');
			$total_iva=number_format($total_iva,$precision_moneda,'.','');
		}
		
		$total_iva=number_format($total_iva,$precision_moneda,'.','');
		$total_venta=$total_neto+$total_iva;
		$total_venta=number_format($total_venta,$precision_moneda,'.','');
		
		$insert="INSERT INTO sales (sale_id,sale_number, sale_prefix, customer_id, sale_by, subtotal, tax, total, sale_date, due_date, type, branch_id, status, seller_id,cashbox_id,payment_method, includes_tax, currency_id, guia_number) VALUES
		(NULL, '$sale_number', '$sale_prefix', '$customer_id', '$sale_by', '$total_neto', '$total_iva', '$total_venta', '$sale_date', '$due_date','$type', '$branch_id', '$status','$seller_id','$cashbox_id','$payment_method', '$includes_tax','$currency_id','$guia_number');";
	
		$query=mysqli_query($con,$insert);
		if ($query){
		 $true=1;
		} else {
			$true=0;
		}
		
		if ($payment_method==1){
			$insert=mysqli_query($con,"INSERT INTO charges (charge_id, sale_id, total, payment_date, payment_type, number_reference, note, user_id) VALUES (NULL, '$sale_id', '$total_venta', '$sale_date', '$payment_method', '', '', '$sale_by');");
		}
		
		return $true;
	}
	function get_tax(){
		global $con;
		$sql=mysqli_query($con,"SELECT tax FROM  business_profile where  business_profile.id=1");
		$row=mysqli_fetch_array($sql);
		$tax=$row["tax"];
		return $tax;
	}
	function next_insert_id($table){
		global $con;
		$next="SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_NAME."' AND   TABLE_NAME   = '$table'";
		$query_next=mysqli_query($con,$next);
		$rw_next=mysqli_fetch_array($query_next);
		$next_insert=$rw_next['AUTO_INCREMENT'];
	    return $next_insert;
	}
	
	function update_selling_price($product_id,$buying_price){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select profit from products where product_id='$product_id'");
		$rw=mysqli_fetch_array($sql);
		$utilidad=intval($rw['profit']);

		$utilidad=($buying_price * $utilidad) /100;
		$precio_venta=$buying_price + $utilidad;
		$selling_price=number_format($precio_venta,2,'.','');
		
		
		$update=mysqli_query($con,"UPDATE products SET selling_price='".$selling_price."' WHERE product_id='".$product_id."'");
	}
	
	function adjustment_inventory($product_id,$product_quantity){
		global $con;//Variable de conexion
		$sql=mysqli_query($con,"select * from inventory where product_id='".$product_id."'");//Consulta para verificar si el producto se encuentra reguistrado en  el inventario
		$count=mysqli_num_rows($sql);
		if ($count==0){
			$insert=mysqli_query($con,"insert into inventory (product_id, product_quantity) values ('$product_id','$product_quantity')");//Ingresa un nuevo producto al inventario
		} else {
			$update=mysqli_query($con,"UPDATE inventory SET product_quantity='".$product_quantity."' WHERE product_id='".$product_id."'");//Actualizo la nueva cantidad en el inventario
		}
	}
	
	function next_number($type,$branch_id){
		global $con;
		$sql=mysqli_query($con,"select sale_number from sales where type='$type' and branch_id='$branch_id'  order by sale_id desc limit 1");
		$row=mysqli_fetch_array($sql);
		$sale_number=$row['sale_number'] + 1;
		echo mysqli_error($con);
		return $sale_number;
	}
	
	function list_branch_offices(){
		global $con;
		$query=mysqli_query($con,"SELECT id, code, name FROM  branch_offices where status=1 order by id");
		return $query;
	}
	
	//Funcion para sumar dias
	function sumardias($fecha,$dias){
		$nuevafecha = strtotime ( $dias." day" , strtotime ( $fecha ) ); 
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha ); //formatea nueva fecha 
		return $nuevafecha; //retorna valor de la fecha 
	}
	
	//funcion para sumar los pagos a proveedoress
	function sum_payment($purchase_id){
		global $con;
		$query=mysqli_query($con,"select sum(total) as total from payments where purchase_id='$purchase_id'");
		$row=mysqli_fetch_array($query);
		$total=$row['total'];
		return $total;
	}
	
	//funcion para sumar los cobros a clientes
	function sum_charge($sale_id){
		global $con;
		$query=mysqli_query($con,"select sum(total) as total from charges where sale_id='$sale_id'");
		$row=mysqli_fetch_array($query);
		$total=$row['total'];
		return $total;
	}
	
	function sum_purchase($purchase_id){
		global $con;
		$query=mysqli_query($con,"select sum(total) as total from purchases where purchase_id='$purchase_id'");
		$row=mysqli_fetch_array($query);
		$total=$row['total'];
		return $total;
	}
	function sum_sale($sale_id){
		global $con;
		$query=mysqli_query($con,"select sum(total) as total from sales where sale_id='$sale_id'");
		$row=mysqli_fetch_array($query);
		$total=$row['total'];
		
		return $total;
	}
	
	/*Obtengo los datos de la resolucion de los documentos */
	function get_document_printing($type_document, $branch_id){
		global $con;
		$query=mysqli_query($con,"select * from document_printing where type_document='$type_document' and branch_id='$branch_id' order by id desc limit 0,1");
		$row=mysqli_fetch_array($query);
		return $row;
	}
	
	function total_ingresos($date_initial,$date_final,$user_id){
		global $con;
		$query=mysqli_query($con,"select sum(total) as total from charges where payment_date between '$date_initial' and '$date_final' and user_id='$user_id' ");
		$row=mysqli_fetch_array($query);
		return $row['total'];
	}
	
	function total_contado($date_initial,$date_final,$cashbox_id,$type){
		global $con;
		$query=mysqli_query($con,"select sum(total) as  total from sales where sale_date between '$date_initial' and '$date_final' and cashbox_id='$cashbox_id' and type='$type'  and payment_method=1");
		$row=mysqli_fetch_array($query);
		return $row['total'];
	}
	
	function total_credito($date_initial,$date_final,$cashbox_id,$type){
		global $con;
		$query=mysqli_query($con,"select sum(total) as  total from sales where sale_date between '$date_initial' and '$date_final' and cashbox_id='$cashbox_id' and type='$type'  and payment_method!=1");
		$row=mysqli_fetch_array($query);
		return $row['total'];
	}
	function total_cobros($date_initial,$date_final,$cashbox_id){
		global $con;
		$query=mysqli_query($con,"select sum(charges.total) as  total from charges, sales where charges.sale_id=sales.sale_id and charges.payment_date between '$date_initial' and '$date_final' and sales.cashbox_id='$cashbox_id'   and sales.payment_method!=1");
		$row=mysqli_fetch_array($query);
		return $row['total'];
	}
	
	function total_egresos($date_initial,$date_final,$cashbox_id){
		global $con;
		$query=mysqli_query($con,"select sum(total) as  total from cash_outflows where date_added between '$date_initial' and '$date_final' and cashbox_id='$cashbox_id' ");
		$row=mysqli_fetch_array($query);
		return $row['total'];
	}
	function guardar_corte($date_initial, $date_final, $opening_balance, $closing_balance, $user_id, $cashbox_id, 	$branch_id ){
		global $con;
		$sql="INSERT INTO cashier_closing (id, date_initial, date_final, opening_balance, closing_balance, user_id, cashbox_id, branch_id) 
		VALUES (NULL, '$date_initial', '$date_final', '$opening_balance', '$closing_balance', '$user_id', '$cashbox_id', '$branch_id');";
		$query=mysqli_query($con,$sql);
		$update=mysqli_query($con,"update cashbox set opening_balance='$closing_balance', last_close='$date_final' where user_id='$user_id' and branch_id='$branch_id'");
	}
	
	function add_transfer($origin,$destination,$create_at,$user_id){
		global $con;
		
		
		$transfer_id = next_insert_id('transfers');
		$sql_tmp=mysqli_query($con,"select * from product_tmp where user_id='$user_id'");
		
		while ($rw_tmp=mysqli_fetch_array($sql_tmp)){
			$id_tmp=$rw_tmp['id_tmp'];
			$product_id=$rw_tmp['product_id'];
			$qty=$rw_tmp['qty'];
			$unit_price=$rw_tmp['unit_price'];
			add_transfer_product($transfer_id,$product_id,$qty,$unit_price);//Agrego un registro
			add_inventory($product_id,$qty,$destination);//Agrego la cantidad en el inventario
			remove_inventory($product_id,$qty,$origin);//Elimina la cantidad en el inventario
			remove_tmp($id_tmp);//Elimina el item de la tabla temporal
		}
		
		$sql="INSERT INTO transfers (id, id_origin, id_destination, created_at,user_id) VALUES
		(NULL, '$origin', '$destination', '$create_at', '$user_id');";
		$query=mysqli_query($con,$sql);
		if ($query){
		return 1;
		} else {
			return 0;
		}
		
	}
	
	function add_transfer_product($transfer_id,$product_id,$qty,$unit_price){
		global $con;
		$sql="INSERT INTO transfers_product (id, transfer_id, product_id, qty, unit_price)
		VALUES (NULL, '$transfer_id', '$product_id', '$qty', '$unit_price');";
		$query=mysqli_query($con,$sql);
	}
	
	//Guarda una nota de credito
	function add_note($note_number, $note_prefix, $customer_id, $sale_by,$created_at,$branch_id,$seller_id,$cashbox_id,$includes_tax,$apply_to,$transaction_type,$currency_id){
		global $con;
		$note_id=next_insert_id('credit_notes');
		$sql_tmp=mysqli_query($con,"select * from product_tmp where user_id='$sale_by'");
		$sumador_descuento=0;
		$sumador_total=0;
		while ($rw_tmp=mysqli_fetch_array($sql_tmp)){
			$id_tmp=$rw_tmp['id_tmp'];
			$product_id=$rw_tmp['product_id'];
			$qty=$rw_tmp['qty'];
			$unit_price=$rw_tmp['unit_price'];
			$discount=$rw_tmp['discount'];
			$branch_id=$rw_tmp['branch_id'];
			include("../../currency.php");//Archivo que obtiene los datos de la moneda
			$precio_total=$unit_price*$qty;
			$precio_total=number_format($precio_total,$precision_moneda,'.','');//Precio total formateado
			$descuento=($precio_total * $discount) / 100;
			$descuento=number_format($descuento,$precision_moneda,'.','');//Descuento Formateado
			$sumador_descuento+=$descuento;//Sumador
			$sumador_total+=$precio_total;//Sumador
			add_note_product($note_id,$product_id,$qty,$discount, $unit_price);//Agrego un registro  a la tabla sale_product
			$is_service= is_service($product_id);
			if ($is_service==0){//SINO es un servicio
				
				if ($transaction_type==1){
					remove_inventory($product_id,$qty,$branch_id );//Elimino la cantidad del inventario;
				} else if($transaction_type==2){
					add_inventory($product_id,$qty,$branch_id );//Agrego la cantidad en el inventario;
				}
				
			}
			remove_tmp($id_tmp);//Elimina el item de la tabla temporal
		}	
		$tax=get_tax();
		$total_parcial=number_format($sumador_total,$precision_moneda,'.','');
		$sumador_descuento=number_format($sumador_descuento,$precision_moneda,'.','');
		$total_neto=$total_parcial-$sumador_descuento;
		$total_neto=number_format($total_neto,$precision_moneda,'.','');
		if ($includes_tax==0){
			$total_iva=($total_neto*$tax) / 100;
		} else if ($includes_tax==1){
			$tax_value=$tax/100 + 1;
			$tax_value=number_format($tax_value,$precision_moneda,'.','');	
			$neto=$total_neto/$tax_value;
			$neto=number_format($neto,$precision_moneda,'.','');
			$total_iva=$total_neto-$neto;
			$total_neto=number_format($neto,$precision_moneda,'.','');
			$total_iva=number_format($total_iva,$precision_moneda,'.','');
		}
		
		$total_iva=number_format($total_iva,$precision_moneda,'.','');
		$total_venta=$total_neto+$total_iva;
		$total_venta=number_format($total_venta,$precision_moneda,'.','');
		
		$insert="INSERT INTO credit_notes (id, note_number, note_prefix, customer_id, sale_by, subtotal, tax, total, created_at, branch_id, seller_id, cashbox_id, includes_tax,apply_to,transaction_type,currency_id) 
		VALUES (NULL, '$note_number', '$note_prefix', '$customer_id', '$seller_id', '$total_neto', '$total_iva', '$total_venta', '$created_at', '$branch_id', '$seller_id', '$cashbox_id', '$includes_tax','$apply_to','$transaction_type',$currency_id);";
	
		$query=mysqli_query($con,$insert);
		if ($query){
		 $true=1;
		} else {
			$true=0;
		}
		
		if ($transaction_type==1){
			$insert=mysqli_query($con,"INSERT INTO payments (payment_id, purchase_id, total, payment_date, payment_type, number_reference, note, user_id) 
			VALUES (NULL, '$apply_to', '$total_venta', '$created_at', '4', '', 'Nota de crédito Nº: $note_number', '$sale_by');");
		} else if ($transaction_type==2){
			$insert=mysqli_query($con,"INSERT INTO charges (charge_id, sale_id, total, payment_date, payment_type, number_reference, note, user_id) VALUES (NULL, '$apply_to', '$total_venta', '$created_at', '4', '', 'Nota de crédito Nº: $note_number', '$sale_by');");
		}
			
		
		
		return $true;
	}
	
	function add_note_product($note_id,$product_id,$qty,$discount, $unit_price){
		global $con;//Variable de conexion
		$insert=mysqli_query($con, "INSERT INTO note_product (note_id,product_id,qty,discount,unit_price) VALUES ('$note_id','$product_id','$qty','$discount','$unit_price')");
	}
	
	function currencyConverter($from_Currency,$to_Currency,$amount) {
		if ($from_Currency==$to_Currency){
			return $amount;
		}
	$from_Currency = urlencode($from_Currency);
	$to_Currency = urlencode($to_Currency);
	$encode_amount = $amount;
	$get = file_get_contents("https://www.google.com/finance/converter?a=$encode_amount&from=$from_Currency&to=$to_Currency");
	$get = explode("<span class=bld>",$get);
	$get = explode("</span>",$get[1]);
	$converted_currency = preg_replace("/[^0-9\.]/", null, $get[0]);
	return $converted_currency;
	}
	
	function add_tmp_guia($id_guia,$user_id){
		global $con;
		$query=mysqli_query($con,"INSERT INTO tmp_guia (id, id_guia, user_id) VALUES (NULL, '$id_guia','$user_id');");
	}
	function get_tmp_guia($number_document,$user_id){
		global $con;
		$query=mysqli_query($con,"SELECT * FROM tmp_guia where user_id='$user_id'");
		while ($rw=mysqli_fetch_array($query)){
			$id_guia=$rw['id_guia'];
			$update=mysqli_query($con,"update referral_guides set comprobante='$number_document', status='1' where id='$id_guia'");
		}
		delete_tmp_guia($user_id);//Elimino las guias de la tabla temporal
	} 
	function delete_tmp_guia($user_id){
		global $con;
		$delete=mysqli_query($con,"delete from tmp_guia where user_id='$user_id'");
	}
	function ingresar_documento($id_fk,$motivo,$fecha,$tabla){
		global $con;
		$query=mysqli_query($con,"INSERT INTO documentos_anulados(id, id_tabla, motivo, fecha, tabla) VALUES (NULL,'$id_fk','$motivo','$fecha','$tabla')");
		
	}
	/*Obtener todo el stock*/
	function get_all_stock($product_id){
		global $con;
		$query=mysqli_query($con,"select sum(product_quantity) as total from inventory where product_id='$product_id'");
		$row=mysqli_fetch_array($query);
		return $row['total'];
	}
	//Guardar en la tabla log
	function save_log($modulo,$accion,$user_id){
		global $con;
		$fecha=date("Y-m-d H:i:s");
		$sql="INSERT INTO log (id, modulo, accion, fecha, user_id) VALUES (NULL, '$modulo', '$accion', '$fecha', '$user_id');";
		$query=mysqli_query($con,$sql);
	}
	
	//Obtener ultimo saldo
	function get_balance(){
		global $con;
		$sql=mysqli_query($con,"select balance from finances order by id desc limit 0,1");
		$rw=mysqli_fetch_array($sql);
		return $rw['balance'];
	}
	//Anular transaccion de balance
	function balance_null($id,$user_id){
		global $con;
		$created_at=date('Y-m-d H:i:s');
		$actual_type=get_id('finances','type','id',$id);
		$actual_amount=get_id('finances','amount','id',$id);
		$get_balance=get_balance();
		if ($actual_type==1){
			$nuevo_balance=$get_balance-$actual_amount;
			$type=2;
		} else if ($actual_type==2){
			$nuevo_balance=$get_balance+$actual_amount;
			$type=1;
		}
		$description="Anulación de transacción: $id ";
		$status=1;
		
		return save_finances($description,$type,$actual_amount,$nuevo_balance,$created_at,$user_id,$status);
		
	}
	//Guardar balance
	function save_finances($description,$type,$amount,$balance,$created_at,$user_id,$status){
		global $con;
		$sql="INSERT INTO finances (id, description, type, amount, balance, created_at, user_id,status)
		VALUES (NULL, '$description', '$type', '$amount', '$balance', '$created_at', '$user_id','$status');";
		$query=mysqli_query($con,$sql);
		if ($query){
			return true;
		} else {
			return false;
		}
	}
	//Cambiar estado a la transaccion
	function change_status($id){
		global $con;
		$update=mysqli_query($con,"update finances set status=0 where id='$id'");
	}
	//Ultimo ID ingresado
	function get_last_id($table,$id){
		global $con;
		$query=mysqli_query($con,"select $id from $table order by $id desc limit 0,1");
		$rw=mysqli_fetch_array($query);
		return $rw[$id];
	}
	//Actualizar transacion id del corte
	function update_transacion_id($id,$transaction_id,$table,$campo){
		global $con;
		mysqli_query($con,"update $table set transaction_id='$transaction_id' where $campo='$id'");
	}
	function fecha_sp($date){
	$dia= date ("d", strtotime($date));
	$mes= date ("n", strtotime($date));
	switch($mes){
	  case 1:
      $mes="Enero";
      break;
	  case 2:
      $mes="Febrero";
      break;
	  case 3:
      $mes="Marzo";
      break;
	  case 4:
      $mes="Abril";
	  break;
	  case 5:
      $mes="Mayo";
	  break;
	  case 6:
      $mes="Junio";
	  break;
	  case 7:
      $mes="Julio";
	  break;
	  case 8:
      $mes="Agosto";
	  break;
	  case 9:
      $mes="Septiembre";
	  break;
	  case 10:
      $mes="Octubre";
	  break;
	  case 11:
      $mes="Noviembre";
	  break;
	  case 12:
      $mes="Diciembre";
	  break;
}
	$anio= date ("Y", strtotime($date));
	$txt_fecha="$mes";
	return $txt_fecha;
}
	
?>	