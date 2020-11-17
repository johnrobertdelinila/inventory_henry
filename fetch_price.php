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
	$query .= 'OR price LIKE "%'.$_POST["search"]["value"].'%" ';
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
		$image = '<center><img src="upload/'.$row["photo"].'" class="responsive-img" width="70" height="80" /></center>';
	}
	else
	{
		$image = '';
	}
	
	$sub_array = array();
	$sub_array[] = '<input type="checkbox" id="'.$row["id"].'" current_price="'.$row["price"].'" item_name="'.$row["name"].'" class="check" /><label for="'.$row["id"].'"> Select</label>';
	$sub_array[] = "₱ " . number_format($row['price'], 2);
	$sub_array[] = $image;
	$sub_array[] = $row['name'];
	$sub_array[] = $row['description'];
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