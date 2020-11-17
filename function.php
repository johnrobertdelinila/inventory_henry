<?php
	
	// HANDLE AJAX REQUEST
	
	// FUNCTIONS 
	session_start();

	function upload_image(){
		if(isset($_FILES["photo"])){
			$extension = explode('.', $_FILES['photo']['name']);
			$new_name = rand() . '.' . $extension[1];
			$destination = './upload/' . $new_name;
			move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
			return $new_name;
		}
	}

	function upload_image_supplier(){
		if(isset($_FILES["photo"])){
			$extension = explode('.', $_FILES['photo']['name']);
			$new_name = rand() . '.' . $extension[1];
			$destination = './supplier/' . $new_name;
			move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
			return $new_name;
		}
	}

	function get_image_name($connection, $product_id){
		$statement = $connection->prepare("SELECT photo FROM product WHERE id = '$product_id'");
		$statement->execute();
		$result = $statement->fetchAll();
		$image = '';
		foreach($result as $row)
		{
			$image = $row["photo"];
		}
		return $image;
	}

	function get_total_all_records($connection){
		$statement = $connection->prepare("SELECT * FROM items");
		$statement->execute();
		$result = $statement->fetchAll();
		return $statement->rowCount();
	}

	function get_current_on_hand($id, $connection){
		$data = '';
		$sql = $connection->prepare("SELECT * FROM items WHERE id = :id");
		$sql->execute(
			array(
				':id'	=>	$id
			)
		);
		$result = $sql->fetchAll();
		foreach($result as $row){
			$data = $row['on_hand'];
			break;
		}
		return $data;
	}

	function get_current_on_ordered($id, $connection){
		$data = '';
		$sql = $connection->prepare("SELECT * FROM items WHERE id = :id");
		$sql->execute(
			array(
				':id'	=>	$id
			)
		);
		$result = $sql->fetchAll();
		foreach($result as $row){
			$data = $row['on_ordered'];
			break;
		}
		return $data;
	}

	function get_current_sales($id, $connection){
		$data = '';
		$sql = $connection->prepare("SELECT * FROM items WHERE id = :id");
		$sql->execute(
			array(
				':id'	=>	$id
			)
		);
		$result = $sql->fetchAll();
		foreach($result as $row){
			$data = $row['demand'];
			break;
		}
		return $data;
	}

	function td_row($result, $quantities) {
		$output = '';
		$i = 0;
		foreach($result as $row){
			$output .= '
				<tr class="item-row">
					<td class="item-name"><div class="delete-wpr"><textarea>'. $row['name'] .'</textarea><a class="delete" href="javascript:;" title="Remove row">X</a></div></td>
					<td class="description"><textarea>'. $row['description'] .'</textarea></td>
					<td><textarea class="cost">₱ '. number_format($row['price'], 2) .' </textarea></td>
					<td><textarea class="qty">'. $quantities[$i] .'</textarea></td>
					<td><span class="price">₱ '. number_format(($row['price'] * $quantities[$i]), 2) .'</span></td>
				</tr>
			';
			$i++;
		}
		return $output;
	}

	
	// CONNECTION
	$connection = new PDO("mysql:host=localhost;dbname=henry", "root", "");

	if ($_POST['action'] == "add product") {
		$name = $_POST['item_name'];
		$desc = $_POST['item_desc'];
		$photo = "";
		if ($_FILES['photo']['name'] != "") {
			$photo = upload_image();
		}

		$sql = $connection->prepare("INSERT INTO items (name, description, photo, price, on_hand, demand, on_ordered) VALUES (:name, :description, :photo, :price, :on_hand, :demand, :on_ordered)");
		$result = $sql->execute(
			array(
				':name'	=>	$name,
				':description'	=>	$desc,
				':photo'	=>	$photo,
				':price'	=>	0,
				':on_hand'	=>	0,
				':demand'	=>	0,
				':on_ordered'	=>	0
			)
		);

		if (!empty($result)) {
			echo "hello";
		}else {
			echo "error";
		}

	}elseif($_POST['action'] == "delete product"){
		$sql = $connection->prepare("DELETE FROM items WHERE id = '".$_POST['id']."'");
		$result = $sql->execute();
		if (!empty($result)) {

			echo "Deleted";
			unlink("upload/" . $_POST["photo"]);
		}
	}elseif ($_POST['action'] == "update product") {
		$name = $_POST['item_name'];
		$desc = $_POST['item_desc'];
		$photo = "";
		if ($_FILES['photo']['name'] != "") {
			unlink("upload/" . $_POST["hidden_photo_name"]);
			$photo = upload_image();
		}else {
			$photo = $_POST["hidden_photo_name"];
		}

		$sql = $connection->prepare("UPDATE items SET name = :name, description = :description, photo = :photo WHERE id = :id");
		$result = $sql->execute(
			array(
				':name'	=>	$name,
				':description'	=>	$desc,
				':photo'	=>	$photo,
				':id'	=>	$_POST['item_id']
			)
		);

		if (!empty($result)) {
			echo "UPDATED";
		}else {
			echo "ERROR";
		}

	}elseif ($_POST['action'] == "update price") {
		$data = $_POST['data'];
		$arr_data = explode(",", $data);
		for ($i=0; $i < count($arr_data); $i+=2) {
			$quantity = $arr_data[$i+1];
			$id = $arr_data[$i];
			$sql = $connection->prepare("UPDATE items SET price='$quantity' WHERE id='$id'");
			$sql->execute();
		}
		echo "success";
	}elseif ($_POST['action'] == "add supplier") {

		$no_photo = "photo_default.png";

		$photo = '';
		if ($_FILES['photo']['name']) {
			$photo = upload_image_supplier();
		}else {
			$photo = $no_photo;
		}

		$sql = $connection->prepare("INSERT INTO suppliers (name, telephone, address, photo) VALUES (:name, :telephone, :address, :photo)");
		$result = $sql->execute(
			array(
				':name'	=>	$_POST['name'],
				':telephone'	=>	$_POST['telephone'],
				':address'	=>	$_POST['address'],
				':photo'	=>	$photo
			)
		);

		if (!empty($result)) {
			echo "supplier added";
		}else {
			echo "error";
		}

	}elseif ($_POST['action'] == "delete supplier") {
		$no_photo = "photo_default.png";

		$sql = $connection->prepare("DELETE FROM suppliers WHERE id = :id");
		$result = $sql->execute(
			array(
				':id'	=>	$_POST['id']
			)
		);

		if (!empty($result)) {
			if ($_POST["image"] != $no_photo) {
				unlink("supplier/" . $_POST["image"]);
			}
			
			echo "deleted";
		}
	}elseif ($_POST['action'] == "edit supplier") {
		$photo = '';
		if ($_FILES['photo']['name'] != '') {
			if ($_POST["hidden_photo_name"] != 'photo_default.png') {
				unlink("supplier/" . $_POST["hidden_photo_name"]);
			}	
			$photo = upload_image_supplier();
		}else {
			$photo = $_POST["hidden_photo_name"];
		}

		$sql = $connection->prepare("UPDATE suppliers SET name = :name, address = :address, telephone = :telephone, photo = :photo WHERE id = :id");
		$result = $sql->execute(
			array(
				':name'	=>	$_POST['name'],
				':address'	=>	$_POST['address'],
				':telephone'	=>	$_POST['telephone'],
				':photo'	=>	$photo,
				':id'	=>	$_POST['id']
			)
		);
		if (!empty($result)) {
			echo "supplier updated";
		}else {
			echo "error";
		}

	}elseif ($_POST['action'] == "create purchase order") {
		$sql = $connection->prepare("INSERT INTO purchase_orders (order_date, delivery_date, supplier, status, data, original_quantity) VALUES (:order_date, :delivery_date, :supplier, :status, :data, :original_quantity)");
		$result = $sql->execute(
			array(
				':order_date'	=>	$_POST['today'],
				':delivery_date'	=>	$_POST['datepicker'],
				':supplier'	=>	$_POST['select_supplier'],
				':status'	=>	"Authorized",
				':data'	=>	$_POST['data'],
				':original_quantity'	=>	$_POST['origStr']
			)
		);

		$dataStr = $_POST['data'];
		$data = explode(",", $dataStr);
		for ($i=0; $i < count($data); $i+=3) { 
			$sql = $connection->prepare("UPDATE items SET on_ordered = :on_ordered WHERE id = :id");
			$sql->execute(
				array(
					':on_ordered'	=>	$data[$i+1],
					'id'	=>	$data[$i]
				)
			);
		}

		if (!empty($result)) {
			echo "inserted";
		}else {
			echo "error";
		}

	}elseif ($_POST['action'] == "printed po") {
		$sql = $connection->prepare("UPDATE purchase_orders SET status = :status WHERE id = :id");
		$result = $sql->execute(
			array(
				':status'	=>	"Printed",
				':id'	=>	$_POST['id']
			)
		);

		if (!empty($result)) {
			echo "printed";
		}else {
			echo "error";
		}

	}elseif ($_POST['action'] == "recieve order") {
		$dataStr = $_POST['dataStr'];
		$updateStr = $_POST['updateStr'];

		$update = explode(",", $updateStr);

		for ($i=0; $i < count($update); $i+=2) { 
			$current_on_hand = get_current_on_hand($update[$i], $connection);
			$current_on_ordered = get_current_on_ordered($update[$i], $connection);

			$new_on_hand = $current_on_hand + $update[$i+1];
			$new_on_ordered = $current_on_ordered - $update[$i+1];

			$sql = $connection->prepare("UPDATE items SET on_hand = :on_hand, on_ordered = :on_ordered WHERE id = :id");
			$sql->execute(
				array(
					':on_hand'	=>	$new_on_hand,
					':on_ordered'	=>	$new_on_ordered,
					':id'	=>	$update[$i]
				)
			);
		}

		$sql = $connection->prepare("UPDATE purchase_orders SET data = :data WHERE id = :id");
		$result = $sql->execute(
			array(
				':data'	=>	$dataStr,
				':id'	=>	$_POST['po_id']
			)
		);

		if (!empty($result)) {
			echo "done";
		}


	}elseif ($_POST['action'] == "login admin") {
		$sql = $connection->prepare("SELECT * FROM admin WHERE username = :username && password = :password");
		$sql->execute(
			array(
				':username'	=>	$_POST['email'],
				':password'	=>	$_POST['password']
			)
		);
		$result = $sql->rowCount();

		if ($result > 0) {
			$_SESSION['admin'] = "Login";
		}

		echo $result;
	}elseif ($_POST['action'] == "create sales order") {
		$sql = $connection->prepare("INSERT INTO sales_orders (datee, customer, num, total_price, datas) VALUES (:datee, :customer, :num, :total_price, :datas)");
		$result = $sql->execute(
			array(
				':datee'	=>	$_POST['today'],
				':customer'	=>	$_POST['customer'],
				':num'	=>	$_POST['num_of_items'],
				':total_price'	=>	$_POST['grandtotal'],
				':datas' => $_POST['dataStr']
			)
		);

		$data = explode(",", $_POST['dataStr']);
		for ($i=0; $i < count($data); $i+=2) { 
			$quantity = $data[$i + 1];
			$current_sales = get_current_sales($data[$i], $connection);
			$current_on_hand = get_current_on_hand($data[$i], $connection);

			$new_on_hand = $current_on_hand - $quantity;
			$new_sales = $current_sales + $quantity;

			$sql = $connection->prepare("UPDATE items SET demand = :demand, on_hand = :on_hand WHERE id = :id");
			$sql->execute(
				array(
					':demand'	=>	$new_sales,
					':on_hand'	=>	$new_on_hand,
					':id'	=>	$data[$i]
				)
			);

		}


		if (!empty($result)) {
			echo "eut";
		}
	}elseif ($_POST['action'] == "fetch items") {
		$output = '';
		$sql = $connection->prepare("SELECT * FROM items");
		$sql->execute();
		$result = $sql->fetchAll();
		foreach($result as $row){
			$disabled = '';
			if ($row['price'] <= 0 || $row['on_hand'] <= 0) {
				$disabled = 'disabled';
			}

			$output = $output . '
				<li class="collection-item avatar">
					<img src="upload/'.$row["photo"].'" alt="" class="circle">
					<span class="title"><strong>'.$row['name'].'</strong></span>
					<p>₱ '.number_format($row['price'], 2).' <br>
						Available: '.number_format($row['on_hand'], 0).'
					</p>
					<div class="secondary-content"><input type="checkbox" class="check" item_name="'.$row["name"].'" item_price="'.$row["price"].'" item_stock="'.$row["on_hand"].'" '.$disabled.' id="'.$row["id"].'" /><label for="'.$row["id"].'">Select</label></div>
				</li>
			';

		}

		echo $output;

	}elseif ($_POST['action'] == "print sales order") {
		$datas = explode(",", $_POST['datas']);
		$ids = array();
		$quantity = array();

		for($i = 0; $i < COUNT($datas); $i++) {
			if($i == 0 || $i % 2 == 0) {
				array_push($ids, $datas[$i]);
			}else {
				array_push($quantity, $datas[$i]);
			}
		}

		$sql = $connection->prepare("SELECT * FROM items WHERE id IN (".implode(',',$ids).")");
		$sql->execute();
		$result = $sql->fetchAll();
		

		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<title>Editable Invoice</title>
	
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

</head>

<body>

	<div id="page-wrap">

		<textarea id="header">RECEIPT</textarea>
		
		<div id="identity">
		
            <textarea id="address">'. $_POST["customer"] .'
Lorma Colleges
San Juan La Union
Phone: (555) 555-5555</textarea>

            <div id="logo">

              <div id="logoctr">
                <a href="javascript:;" id="change-logo" title="Change logo">Change Logo</a>
                <a href="javascript:;" id="save-logo" title="Save changes">Save</a>
                |
                <a href="javascript:;" id="delete-logo" title="Delete logo">Delete Logo</a>
                <a href="javascript:;" id="cancel-logo" title="Cancel changes">Cancel</a>
              </div>

              <div id="logohelp">
                <input id="imageloc" type="text" size="50" value="" /><br />
                (max width: 540px, max height: 100px)
              </div>
              <img id="image" src="images/logo.png" alt="logo" />
            </div>
		
		</div>
		
		<div style="clear:both"></div>
		
		<div id="customer">

            <textarea id="customer-title">Inventory System</textarea>

            <table id="meta">
                <tr>
                    <td class="meta-head">Sales Order #</td>
                    <td><textarea>'. $_POST["sales_id"] .'</textarea></td>
                </tr>
                <tr>

                    <td class="meta-head">Date</td>
                    <td><textarea id="date">'. $_POST["date"] .'</textarea></td>
                </tr>
                <tr>
                    <td class="meta-head">Amount Due</td>
                    <td><div class="due">'. $_POST["price"] .'</div></td>
                </tr>

            </table>
		
		</div>
		
		<table id="items">
		
		  <tr>
		      <th>Item</th>
		      <th>Description</th>
		      <th>Unit Cost</th>
		      <th>Quantity</th>
		      <th>Price</th>
		  </tr>
		  
		 '. td_row($result, $quantity) .'
		  
		  <tr id="hiderow">
		    <td colspan="5"><a id="addrow" href="javascript:;" title="Add a row">Add a row</a></td>
		  </tr>
		  
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Subtotal</td>
		      <td class="total-value"><div id="subtotal">'. $_POST["price"] .'</div></td>
		  </tr>
		  <tr>

		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Total</td>
		      <td class="total-value"><div id="total">'. $_POST["price"] .'</div></td>
		  </tr>
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line">Amount Paid</td>

		      <td class="total-value"><textarea id="paid">'. $_POST["price"] .'</textarea></td>
		  </tr>
		  <tr>
		      <td colspan="2" class="blank"> </td>
		      <td colspan="2" class="total-line balance">Balance Due</td>
		      <td class="total-value balance"><div class="due">₱0</div></td>
		  </tr>
		
		</table>
		
		<div id="terms">
		  <h5>Terms</h5>
		  <textarea>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</textarea>
		</div>
	
	</div>
	
</body>

</html>

		';

		echo $html;
	}

	

?>