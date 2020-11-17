<?php
	session_start();
	if(!isset($_SESSION['admin']) || $_SESSION['admin'] != "Login"){
	  header("Location: login.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Price Page</title>
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
	<header>
		<nav>
			<div class="nav-wrapper">
				
				<ul class="left hide-on-med-and-down">
					<li><a href="index.php">Home</a></li>
					<li><a href="product.php">Product</a></li>
					<li class="active waves-effect waves-black"><a href="#">Price</a></li>
					<li><a href="supplier.php">Supplier</a></li>
					<li><a href="purchase_order.php">Purchase Order</a></li>
					<li><a href="sales_order.php">Sales Order</a></li>
					<!--
					<li><a class="waves-effect waves-orange btn" id="logout">LOGOUT <i class="material-icons left">exit_to_app</i></a></li>-->
				</ul>
			</div>
		</nav>
	</header>
	<main>
		<div class="container">
			<br>
			
				<h4 style="font-family: Times New Roman">Price Table</h4>
		

			<div id="filter" class="rex" style="float: right;"></div>

			<table id="price_table" class="responsive-table highlight centered bordered hoverable z-depth-2" style="background-color: #FFFFFF; table-layout: fixed; width: 100%; font-family: Avenir;">
				<thead>
					<tr>
						<th width="15%">Checkbox</th>
						<th width="20%">Current Price</th>
						<th width="15%">Image</th>
						<th width="20%">Name</th>
						<th width="30%">Description</th>
						
					</tr>
				</thead>
				<tbody></tbody>
			</table>

			<br><br><br><br>

			<div class="fixed-action-btn" style="position: fixed;">
				<a class="btn-floating btn-large green waves-effect waves-orange pulse tooltipped" id="set_price" data-position="left" data-delay="50" data-tooltip="Set Price">
					<i class="material-icons">assessment</i>
				</a>
			</div>

		</div>
	</main>

	<!-- Modal Price -->
	<div id="modal1" class="modal bottom-sheet" style="background-color: #F5F5F5;">
		<div class="modal-content">
			<h4 style="font-family: Avenir;">Set New Price</h4>
			<center>
				<div class="container">
					<table style="table-layout: fixed; background-color: #FFFFFF;" class="responsive-table centered">
						<thead>
							<tr>
								<th width="30%">Item Name</th>
								<th width="30%">Current Price</th>
								<th width="40%">New Price</th>
							</tr>
						</thead>
						<tbody id="price_container"></tbody>
					</table>
				</div>
			</center>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Cancel</a>
			<a href="#!" class="modal-action waves-effect waves-green btn-flat" id="new_price_submit">Done</a>
		</div>
	</div>

</body>
</html>
<script type="text/javascript">
$(document).ready(function(){

	$('.modal').modal();

	function numberWithCommas(x) {
	    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	var price_table = $('#price_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"fetch_price.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[0, 2],
				"orderable":false,
			},
		],
	});

	$('div.dataTables_filter').appendTo("#filter");
	$('div.dataTables_filter').addClass("waves-effect waves-teal");
	/*
	$(document).on('click', '.check', function(){
		if ($(this).is(":checked")) {
			var name = $(this).attr("item_name");
			Materialize.toast('\"' + name + '\" has been added to edit list', 3500, 'rounded');
		}
	});
	*/
	$('#set_price').click(function(){
		if ($('.check:checkbox:checked').length <= 0) {
			Materialize.toast('Please select 1 or more item to set the price.', 2000);
			return false;	
		}else {
			//$('#modal1').modal("open");
			$('#price_container').html('');

			$('.check:checkbox:checked').each(function(){
				var id = $(this).attr("id");
				var price = $(this).attr("current_price");
				var name = $(this).attr("item_name");
				
				$('<tr><td style="font-family: Avenir;"><i>'+name+'</i></td><td>₱ '+numberWithCommas(price)+'.00</td><td><div class="input-field inline"><input id="input_'+id+'" type="number" class="validate new_price" item_id="'+id+'"><label for="input_'+id+'" data-error="Not a number!" data-success="Done">Price</label></div></td></tr>').appendTo('#price_container');

			});
			$('#modal1').modal("open");
			
		}
	});

	function updatePrice(datas){
		var action = "update price";
		var data = datas;
		var result = "";
		$.ajax({
			async: false,
			url: "function.php",
			method: "POST",
			data: {action: action, data: data},
			success: function(dataReturn){
				result = dataReturn;
			}
		});
		return result;
	}

	$('#new_price_submit').click(function(){

		var isEmpty = false;
		$('.new_price').each(function(){
			if ($(this).val().trim() == "" || parseInt($(this).val()) <= 0) {
				isEmpty = true;
			}
		});

		if (isEmpty) {
			Materialize.toast('Failed. Some inputs are invalid or empty.', 3000);
		}else {
			var data = [];
			$('.new_price').each(function(){
				var id = $(this).attr("item_id");
				var quantity = $(this).val();
				data.push(id, quantity);
			});
			var dataStr = data.toString();
			
			alertify.confirm("Admin Alert", "Are you sure to set this price?", function(){
					var result = updatePrice(dataStr);
					if (result == "success") {
						alertify.success('Price set successfully!');
						$('#modal1').modal("close");
						price_table.ajax.reload();
					}
				},
				function(){
					// intentionally null;
			});

		}

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