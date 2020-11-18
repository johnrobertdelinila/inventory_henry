<?php

function get_total_all_records($connection){
	$statement = $connection->prepare("SELECT * FROM purchase_orders");
	$statement->execute();
	$result = $statement->fetchAll();
	return $statement->rowCount();
}

$connection = new PDO("mysql:host=localhost;dbname=henry", "root", "");

$query = '';
$output = array();
$query .= "SELECT * FROM purchase_orders ";
if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE order_date LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR delivery_date LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR supplier LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR status LIKE "%'.$_POST["search"]["value"].'%" ';
}
if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id DESC ';
}
if($_POST["length"] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{
	$receive = '';
	if ($row['status'] == "Printed") {
		$receive = '<a href="#" class="chip receive_hehe" po_id="'.$row["id"].'" po_data="'.$row["data"].'" original_data="'.$row["original_quantity"].'">Recieve</a>';
	}
	
	$sub_array = array();
	$sub_array[] = '<a href="#" class="chip print_hehe" po_id="'.$row["id"].'">Print</a>';
	$sub_array[] = $receive;
	$sub_array[] = $row['status'];
	$sub_array[] = $row['order_date'];
	$sub_array[] = $row['delivery_date'];
	$sub_array[] = $row['supplier'];
	$sub_array[] = '<a href="#" supplier="'. $row["supplier"] .'" orig="'. $row["original_quantity"] .'" data="'. $row["data"] .'" class="button delivery_report">Show</a>';
	$data[] = $sub_array;
}
$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=> 	$filtered_rows,
	"recordsFiltered"	=>	get_total_all_records($connection),
	"data"				=>	$data
);
echo json_encode($output);
?>