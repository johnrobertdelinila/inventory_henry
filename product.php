<?php
	session_start();
	if(!isset($_SESSION['admin']) || $_SESSION['admin'] != "Login"){
	  header("Location: login.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Product Page</title>
	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

	<!-- Jquery CDN -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Material Design Lite -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
	<!--Import jQuery before materialize.js-->
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
	<!-- DT -->
	<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>  
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" />

	<!-- JavaScript Alertify -->
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>

	<!-- CSS Alertify -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
	<!-- Default theme -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/default.min.css"/>
	<!-- Semantic UI theme -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/semantic.min.css"/>
	<!-- Bootstrap theme -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/bootstrap.min.css"/>

	<style type="text/css">
		@font-face {
		    font-family: Arkhip;
		    src: url(fonts/roboto/Arkhip_font.ttf);
		}
		@font-face {
		    font-family: Avenir;
		    src: url(fonts/roboto/Avenir.otf);
		}
		@font-face {
		    font-family: Roboto Bold;
		    src: url(fonts/roboto/Roboto-Bold.woff2);
		}body {
			background-color: #F5F5F5;
		}td {
			text-overflow: ellipsis; 
			white-space: nowrap; 
			overflow: hidden;
		}
	</style>
</head>
<body>
	

	<!-- START HERE -->
	<header>
	<nav>
			<div class="nav-wrapper">
				
				<ul class="left hide-on-med-and-down">
					<li><a href="index.php">Home</a></li>
					<li class="active waves-effect waves-black"><a href="#">Product</a></li>
					<li><a href="price.php">Price</a></li>
					<li><a href="supplier.php">Supplier</a></li>
					<li><a href="purchase_order.php">Purchase Order</a></li>
					<li><a href="sales_order.php">Sales Order</a></li>
					<!--<li><a class="waves-effect waves-orange btn" id="logout">LOGOUT <i class="material-icons left">exit_to_app</i></a></li>-->
				</ul>
			</div>
		</nav>
	</header>
	<main>
		<br>
		<div class="container">
			        
		
				<h4 style="font-family:Times New Roman">Inventory Table</h4>
		

			<div id="filter" class="rex" style="float: right;"></div>
			<table id="product_table" class="responsive-table highlight centered bordered hoverable	z-depth-2" style="background-color: #FFFFFF; table-layout: fixed; width: 100%; font-family: Avenir;">
				<thead>
					<tr>
						<th width="20%">Image</th>
						<th width="14%">Item Name</th>
						<th width="21%">Description</th>
						<th width="9%">On Hand</th>
						<th width="9%">Sales</th>
						<th width="9%">onOrdered</th>
						<th width="9%">Update</th>
						<th width="9%">Delete</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>

			<br><br><br><br>

		</div> 

		<div class="fixed-action-btn" style="position: fixed;">
			<a class="btn-floating btn-large red waves-effect waves-yellow pulse tooltipped" id="add_product" data-position="left" data-delay="50" data-tooltip="Add new product">
				<i class="large material-icons">mode_edit</i>
			</a>
		</div>

	</main>

	<div id="product_modal" class="modal modal-fixed-footer">
		<form id="item_form">
		<div class="modal-content">
			<h4 style="font-family: Avenir;" id="modal_title"></h4>
		
			<div class="container">
				<div class="input-field">
					<input id="item_name" type="text" name="item_name" class="validate">
					<label for="item_name">Item Name</label>
				</div>
				<div class="input-field">
					<input id="item_desc" type="text" name="item_desc" class="validate">
					<label for="item_desc">Item Description</label>
				</div>
				<div class="file-field input-field">
					<div class="btn">
						<span id="file_label">Upload Item Image</span>
						<input type="file" id="photo" name="photo">
					</div>
						<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
				<center><div id="image_holder"></div></center>
				<input type="hidden" name="item_id" id="item_id">
			</div>
		
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Cancel</a>
			<button type="submit" class="modal-action waves-effect waves-green btn-flat" id="done">Done</button>
			<input type="hidden" name="action" id="action">
		</div>
		</form>
	</div>

</body>
</html>
<script type="text/javascript">
$(document).ready(function(){

	// init modal
	$('.modal').modal();
	// init dt
	var product_table = $('#product_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[0, 6, 7],
				"orderable":false,
			},
		],
	});

	$('div.dataTables_filter').appendTo("#filter");
	$('div.dataTables_filter').addClass("waves-effect waves-teal");

	$('#add_product').click(function(){
		$('#item_form')[0].reset();
		$('#action').val("add product");
		Materialize.updateTextFields();
		$('#modal_title').text("Add Product");
		$('#image_holder').html('');
		$('#file_label').text("Upload Item IMAGE");
		$('#product_modal').modal("open");
		
	});

	function takki(formData){
		var result = "";
		$.ajax({
			async: false,
			url: "function.php",
			method: "POST",
			data: formData,
			contentType:false,
		    processData:false,
			success: function(data){
				result = data;
			}
		});
		return result;
	}

	$(document).on('submit', '#item_form', function(e){
		e.preventDefault();

		var action = $("#action").val();
		if (action == "add product") {
			var name = $('#item_name').val().trim();
			var desc = $('#item_desc').val().trim();
			var file_extension = $('#photo').val().split('.').pop().toLowerCase();
			
			if (name == "" || desc == "" || file_extension == "") {
				Materialize.toast('Form is incomplete! Please fill up the form properly.', 2000);
			}else {
				if(jQuery.inArray(file_extension, ['gif','png','jpg','jpeg']) == -1){
					Materialize.toast('Input file is not an image.', 3000, 'rounded');
				}else {
					$('#product_modal').modal("close");
					alertify.confirm("Admin Alert", "Are you sure to add this product?", function(){
							var formData = new FormData($('#item_form')[0]);
							var result = takki(formData);
							if (result == "hello") {
								product_table.ajax.reload();
								alertify.success('New Item has been added!');
							}else {
								alertify.error('There\'s something wrong in adding item');
							}
						},
						function(){
							$('#product_modal').modal("open");
							alertify.error('Cancelled');
					});
				}
			}
		}else {
			var name = $('#item_name').val().trim();
			var desc = $('#item_desc').val().trim();
			var file_extension = $('#photo').val().split('.').pop().toLowerCase();
			if (name == "" || desc == "") {
				Materialize.toast('Form is incomplete! Please fill up the form properly.', 2000);
			}else {

				if (file_extension != "") {
					if(jQuery.inArray(file_extension, ['gif','png','jpg','jpeg']) == -1){
						Materialize.toast('Input file is not an image.', 3000, 'rounded');
						return false;
					}
				}

				$('#product_modal').modal("close");
				alertify.confirm("Admin Alert", "Are you sure to update this product?", function(){
						var formData = new FormData($('#item_form')[0]);
						var result = takki(formData);
						if (result == "UPDATED") {
							product_table.ajax.reload();
							alertify.success('Item has been updated successfully!');
						}else {
							alertify.error('There\'s something wrong in updating item');
						}
					},
					function(){
						$('#product_modal').modal("open");
						alertify.error('Cancelled');
				});
			}
		}
		
	});

	$(document).on('click', '.delete_item', function(){
		var id = $(this).attr("id");
		var photo = $(this).attr("photo_name");
		alertify.confirm("Admin Alert", "Are you sure to delete this product?", function(){
				
				var action = "delete product";
				$.ajax({
					async: false,
					url: "function.php",
					method: "POST",
					data: {action: action, id: id, photo: photo},
					success: function(data){
						if (data == "Deleted") {
							product_table.ajax.reload();
							alertify.success('Item deleted successfully!');
						}
					}
				});
				
			},
			function(){
				// null
		});
	});

	$(document).on('click', '.edit_item', function(){
		var name = $(this).attr("item_name");
		var desc = $(this).attr("item_desc");
		var photo = $(this).attr("photo_name");
		var id = $(this).attr("id");
		$('#item_form')[0].reset();
		$('#item_name').val(name);
		$('#item_desc').val(desc);
		Materialize.updateTextFields();
		$('#image_holder').html('<img src="upload/'+photo+'" class="responsive-img" width="150" height="150" /><input type="hidden" name="hidden_photo_name" value="'+photo+'" />');
		$('#modal_title').text("Update Product");
		$('#file_label').text("uPDATE Item IMAGE");
		$('#action').val("update product");
		$('#item_id').val(id);
		$('#product_modal').modal("open");
	});

	$(document).on("click", '.materialboxed', function(){
		$('.materialboxed').materialbox();
	});

	$('#logout').click(function(){
		alertify.confirm("Admin Alert", "Do you want to logout?", function(){
				
				window.location.href="logout.php";
				
			},
			function(){
				// null
		});
	});

});

</script>