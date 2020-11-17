<?php

function get_total_all_records($connection){
	$statement = $connection->prepare("SELECT * FROM suppliers");
	$statement->execute();
	$result = $statement->fetchAll();
	return $statement->rowCount();
}

$connection = new PDO("mysql:host=localhost;dbname=henry", "root", "");

$query = '';
$output = array();
$query .= "SELECT * FROM suppliers ";
if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR telephone LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR address LIKE "%'.$_POST["search"]["value"].'%" ';
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
		$image = '<center><img src="supplier/'.$row["photo"].'" class="circle responsive-img" width="70" height="80" /></center>';
	}
	else
	{
		$image = '';
	}
	
	$sub_array = array();
	$sub_array[] = $image;
	$sub_array[] = $row['name'];
	$sub_array[] = $row['address'];
	$sub_array[] = $row['telephone'];
	$sub_array[] = '<a class="btn-floating green waves-effect waves-red edit_supplier" supplier_id="'.$row['id'].'" supplier_name="'.$row['name'].'" supplier_address="'.$row['address'].'" supplier_phone="'.$row['telephone'].'" supplier_image="'.$row['photo'].'"><i class="material-icons">border_color</i></a>';
	$sub_array[] = '<a class="btn-floating red waves-effect waves-green delete_supplier" supplier_id="'.$row['id'].'" supplier_image="'.$row['photo'].'"><i class="material-icons">delete_forever</i></a>';
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