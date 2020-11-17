<?php

function get_total_all_records($connection){
	$statement = $connection->prepare("SELECT * FROM items");
	$statement->execute();
	$result = $statement->fetchAll();
	return $statement->rowCount();
}

$connection = new PDO("mysql:host=localhost;dbname=henry", "root", "");

$query = '';
$output = array();
$query .= "SELECT * FROM items ";
if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR description LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR on_hand LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR demand LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR on_ordered LIKE "%'.$_POST["search"]["value"].'%" ';
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
	
	$image = '';
	if($row["photo"] != '')
	{
		$image = '<center><img src="upload/'.$row["photo"].'" class="responsive-img materialboxed" width="70" height="80" style="border-radius: 5px;" /></center>';
	}
	else
	{
		$image = '';
	}
	
	$sub_array = array();
	$sub_array[] = $image;
	$sub_array[] = $row['name'];
	$sub_array[] = $row['description'];
	//$sub_array[] = "₱ " . number_format($row['price'], 2);
	$sub_array[] = number_format($row['on_hand'], 0);	
	$sub_array[] = number_format($row['demand'], 0);
	$sub_array[] = number_format($row['on_ordered'], 0);
	$sub_array[] = '<a class="btn-floating purple waves-effect waves-teal edit_item" id="'.$row['id'].'" photo_name="'.$row['photo'].'" item_desc="'.$row['description'].'" item_name="'.$row['name'].'"><i class="material-icons">border_color</i></a>';
	$sub_array[] = '<a class="btn-floating orange waves-effect waves-red delete_item" id="'.$row['id'].'" photo_name="'.$row['photo'].'"><i class="material-icons">delete_forever</i></a>';
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