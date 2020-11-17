<?php
	session_start();
	if(!isset($_SESSION['admin']) || $_SESSION['admin'] != "Login"){
	  header("Location: login.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Supplier Page</title>
	<!--Import Google Icon Font-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
<nav>
	<header>
			<div class="nav-wrapper">
			
				<ul class="left hide-on-med-and-down">
					<li><a href="index.php">Home</a></li>
					<li><a href="product.php">Product</a></li>
					<li><a href="price.php">Price</a></li>
					<li class="active waves-effect waves-black"><a href="#">Supplier</a></li>
					<li><a href="purchase_order.php">Purchase Order</a></li>
					<li><a href="sales_order.php">Sales Order</a></li>
					<!--<li><a class="waves-effect waves-orange btn" id="logout">LOGOUT <i class="material-icons left">exit_to_app</i></a></li>-->
				</ul>
			</div>
		</nav>
	</header>

	<main>
		<div class="container">

			<br>
		
				<h4  style="font-family: Times New Roman">Supplier Table</h4>
		

			<div id="filter" class="rex" style="float: right;"></div>

			<table id="supplier_table" class="responsive-table highlight centered bordered hoverable z-depth-2" style="background-color: #FFFFFF; table-layout: fixed; width: 100%; font-family: Avenir;">
				<thead>
					<tr>
						<th>Photo</th>
						<th>Supplier Name</th>
						<th>Address</th>
						<th>Telephone</th>
						<th>Update</th>
						<th>Delete</th>
						
					</tr>
				</thead>
				<tbody></tbody>
			</table>

			<br><br><br><br>

			<div class="fixed-action-btn" style="position: fixed;">
				<a class="btn-floating btn-large purple waves-effect waves-green pulse tooltipped" id="add_supplier" data-position="left" data-delay="50" data-tooltip="Add New Supplier">
					<i class="material-icons">person_add</i>
				</a>
			</div>

		</div>
	</main>

	<div id="supplier_modal" class="modal">
		<form id="supplier_form">
			<div class="modal-content">
				<h4 style="font-family: Avenir;" id="modal_title"></h4>
				<div class="container">
					<div class="input-field">
						<i class="material-icons prefix">account_circle</i>
						<input id="name" type="text" name="name" class="validate">
						<label for="name">Full Name</label>
					</div>
					<div class="input-field">
						<i class="material-icons prefix">phone</i>
						<input id="telephone" name="telephone" type="tel" class="validate">
						<label for="telephone">Telephone</label>
					</div>
					<div class="input-field">
						<i class="material-icons prefix">add_location</i>
						<input id="address" name="address" type="tel" class="validate">
						<label for="address">Address</label>
					</div>
					<div class="file-field input-field">
						<div class="btn">
							<span id="file_label">Upload Supplier Photo</span>
							<input type="file" id="photo" name="photo">
						</div>
							<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
					</div>

					<input type="hidden" name="action" id="action">

					<center><div id="supplier_image_container"></div></center>

					<input type="hidden" name="id" id="supplier_id">

				</div>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Cancel</a>
				<a href="#!" class="modal-action waves-effect waves-green btn-flat" id="okay">okay</a>
			</div>
		</form>
	</div>

</body>
</html>
<script type="text/javascript">
$(document).ready(function(){

	$('.modal').modal();

	var supplier_table = $('#supplier_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"fetch_supplier.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[0, 4, 5],
				"orderable":false,
			},
		],
	});

	$('div.dataTables_filter').appendTo("#filter");
	$('div.dataTables_filter').addClass("waves-effect waves-teal");

	$('#add_supplier').click(function(){
		$('#supplier_form')[0].reset();
		$('#action').val("add supplier");
		Materialize.updateTextFields();
		$('#modal_title').text("Add Supplier");
		$('#supplier_image_container').html("");
		$('#supplier_modal').modal("open");
	});

	$('#okay').click(function(){

		var action = $('#action').val();

		var name = $('#name').val().trim();
		var telephone = $('#telephone').val().trim();
		var address = $('#address').val().trim();
		var file_extension = $('#photo').val().split('.').pop().toLowerCase();

		if (name == "" || telephone == "" || address == "") {
			Materialize.toast('Form is incomplete.', 2000);
		}else {
			if (file_extension != "") {
				if(jQuery.inArray(file_extension, ['gif','png','jpg','jpeg']) == -1){
					Materialize.toast('Input file is not an image.', 3000, 'rounded');
					return false;
				}
			}

			$.ajax({
				async: false,
				url: "function.php",
				method: "POST",
				data: new FormData($('#supplier_form')[0]),
				contentType:false,
			    processData:false,
			    success: function(data){
			    	if (data == "supplier added") {
			    		supplier_table.ajax.reload();
			    		$('#supplier_modal').modal("close");
			    		alertify.success('Supplier added successfully!');
			    	}else if (data == "supplier updated") {
			    		supplier_table.ajax.reload();
			    		$('#supplier_modal').modal("close");
			    		alertify.success('Supplier updated successfully!');
			    	}else {
			    		alertify.warning('Error!');
			    	}
			    }
			});

		}

	});


	$(document).on('click', '.edit_supplier', function(){
		var name = $(this).attr("supplier_name");
		var address = $(this).attr("supplier_address");
		var phone = $(this).attr("supplier_phone");
		var photo_name = $(this).attr("supplier_image");
		var id = $(this).attr("supplier_id");

		var image = '';
		if (photo_name != 'photo_default.png') {
			image = '<img src="supplier/'+photo_name+'" class="circle responsive-img" width="150" height="150" /><input type="hidden" name="hidden_photo_name" value="'+photo_name+'" />';
		}else {
			image = '<input type="hidden" name="hidden_photo_name" value="'+photo_name+'" />';
		}

		$('#supplier_form')[0].reset();
		$('#action').val("edit supplier");
		$('#name').val(name);
		$("#address").val(address);
		$('#telephone').val(phone);
		$('#supplier_id').val(id);
		Materialize.updateTextFields();
		$('#modal_title').text("Update Supplier");
		$('#supplier_image_container').html(image);
		$('#supplier_modal').modal("open");
	});

	$(document).on('click', '.delete_supplier', function(){
		var id = $(this).attr("supplier_id");
		var image = $(this).attr("supplier_image");
		var action = "delete supplier";

		alertify.confirm("Admin Alert", "Are you sure to set this price?", function(){
				$.ajax({
					async: false,
					url: "function.php",
					method: "POST",
					data: {action: action, id: id, image: image},
					success: function(data){
						if (data == "deleted") {
							supplier_table.ajax.reload();
							alertify.success('Supplier deleted successfully!');
						}
					}
				});
			},
			function(){
				// intentionally null;
		});
		
		
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