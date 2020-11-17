<?php

function get_total_all_records($connection){
	$statement = $connection->prepare("SELECT * FROM sales_orders");
	$statement->execute();
	$result = $statement->fetchAll();
	return $statement->rowCount();
}

$connection = new PDO("mysql:host=localhost;dbname=henry", "root", "");

$query = '';
$output = array();
$query .= "SELECT * FROM sales_orders ";
if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE datee LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR customer LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR num LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR total_price LIKE "%'.$_POST["search"]["value"].'%" ';
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
	$price = "₱ " . number_format($row['total_price'], 2);
	
	$sub_array = array();
	$sub_array[] = $row['datee'];
	$sub_array[] = $row['customer'];
	$sub_array[] = number_format($row['num'], 0);
	$sub_array[] = $price;
	$sub_array[] = '<a class="waves-effect waves-light btn print_receipt" sales_id="'. $row["id"] .'" price="'.$price.'" customer="'. $row["customer"] .'" date="'. $row["datee"] .'" datas="'. $row["datas"] .'">PRINT</a>';
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