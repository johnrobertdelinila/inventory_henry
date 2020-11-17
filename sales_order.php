<?php
	session_start();
	if(!isset($_SESSION['admin']) || $_SESSION['admin'] != "Login"){
	  header("Location: login.php");
	}

	$connection = new PDO("mysql:host=localhost;dbname=henry", "root", "");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sales Order</title>
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
			<div class="nav-wrapper">
				
				<ul class="left hide-on-med-and-down">
					<li><a href="index.php">Home</a></li>
					<li><a href="product.php">Product</a></li>
					<li><a href="price.php">Price</a></li>
					<li><a href="supplier.php">Supplier</a></li>
					<li><a href="purchase_order.php">Purchase Order</a></li>
					<li class="active waves-effect waves-black"><a href="#">Sales Order</a></li>
					<!--<li><a class="waves-effect waves-orange btn" id="logout">LOGOUT <i class="material-icons left">exit_to_app</i></a></li>-->
				</ul>
			</div>
		</nav>
	</header>

	<main>
		<div class="container">
				
			<br>
			
				<h4 style="font-family: Times New Roman">Sales Order Table</h4>
			

			<div id="filter" class="rex" style="float: right;"></div>

			<table id="so_table" class="responsive-table highlight centered bordered hoverable z-depth-2" style="background-color: #FFFFFF; table-layout: fixed; width: 100%; font-family: Avenir;">
				<thead>
					<tr>
						<th>Date</th>
						<th>Customer</th>
						<th>Number of Items</th>
						<th>Total Price</th>
						<th>Receipt</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>

			<div class="fixed-action-btn" style="position: fixed;">
				<a class="btn-floating btn-large yellow waves-effect waves-purple pulse tooltipped" id="create_so" data-position="left" data-delay="50" data-tooltip="Create Sales Order">
					<i class="material-icons">attach_money</i>
				</a>
			</div>

		</div>
	</main>


	<div id="item_sales" class="modal bottom-sheet">
		<div class="modal-content">
			<h4 style="font-family: Avenir;">Select Items</h4>
			<div class="container">
				
			<ul class="collection" id="collection">
				
			</ul>
				          
			</div>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
			<a href="#!" class="modal-action waves-effect waves-green btn-flat" id="done_done">Done</a>
		</div>
	</div>

	<div id="customer_modal" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h4>Enter Quantity</h4>
			<div class="container">
				Customer Name:
				<div class="input-field inline">
					<input id="customer_name" type="text" class="validate">
					<label for="customer_name">Full Name</label>
				</div>
				<table>
					<thead>
						<tr>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Price</th>
						</tr>
					</thead>
					<tbody id="item_container"></tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action waves-effect waves-green btn-flat" id="back_back">Back</a>
			<a href="#!" class="modal-action waves-effect waves-green btn-flat" id="next">Next</a>
		</div>
	</div>

	<div id="final_modal" class="modal">
	    <div class="modal-content">
	      <h4 style="font-family: Avenir;">Purchase Summary</h4>
	      <p>Customer Name: <span id="customer_name_name"></span></p>
	      <table>
	      	<thead>
	      		<tr>
	      			<th>Product</th>
	      			<th>Quantity</th>
	      			<th>Total Price</th>
	      		</tr>
	      	</thead>
	      	<tbody id="final_summary"></tbody>
	      </table>
	    </div>
	    <div class="modal-footer">
	      <a href="#!" class="modal-action waves-effect waves-green btn-flat" id="final_back">Back</a>
	      <a href="#!" class="modal-action waves-effect waves-green btn-flat" id="finish">Finish</a>
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

	$('#logout').click(function(){
		alertify.confirm("Admin Alert", "Do you want to logout?", function(){
				
				window.location.href="logout.php";
				
			},
			function(){
				// null
		});
	});

	var so_table = $('#so_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"fetch_sales_order.php",
			type:"POST"
		}
	});

	$('div.dataTables_filter').appendTo("#filter");
	$('div.dataTables_filter').addClass("waves-effect waves-teal");

	$('#create_so').click(function(){
		
		var action = "fetch items";
		$.ajax({
			async: false,
			url: "function.php",
			method: "POST",
			data: {action: action},
			success: function(data){
				$('#collection').html(data);
			}
		});
		
		$('#item_sales').modal("open");
	});

	$(document).on('click', '.check', function(){
		if ($(this).is(":checked")) {
			var name = $(this).attr("item_name");
			Materialize.toast('\"' + name + '\" has been added to cart', 1500, 'rounded');
		}
	});

	$('#done_done').click(function(){
		if ($('.check:checkbox:checked').length <= 0) {
			Materialize.toast('Please select 1 or more item to proceed in order.', 2000);
		}else {
			$('#item_sales').modal("close");
			$('#customer_name').val('');
			$('#item_container').html('');
			$('.check:checkbox:checked').each(function(){
				var name = $(this).attr("item_name");
				var stock = $(this).attr("item_stock");
				var price = $(this).attr("item_price");
				var id = $(this).attr("id");
				$('<tr><td>'+name+'</td><td><div class="input-field col s6"><input item_name="'+name+'" max_quantity="'+stock+'" item_price="'+price+'" item_id="'+id+'" placeholder="Stock: '+numberWithCommas(stock)+'" id="first_name" type="text" class="validate order_quantity"><label for="first_name">Quantity</label></div></td><td>₱ <span>'+numberWithCommas(price)+'.00</span></td></tr>').appendTo('#item_container');
			});

			Materialize.updateTextFields();
			$('#customer_modal').modal("open");
		}
	});

	$('#back_back').click(function(){
		$('#customer_modal').modal("close");
		$('#item_sales').modal("open");
	});

	$('#next').click(function(){
		if ($('#customer_name').val().trim() == "") {
			Materialize.toast('Customer name is required', 2000);
		}else {
			var fuck = false;
			$('.order_quantity').each(function(){
				if ($(this).val().trim() == "" || parseInt($(this).val()) <= 0) {
					$(this).next("label").attr('data-error','Empty');
					$(this).removeClass("valid");
					$(this).addClass("invalid");
					fuck = true;
				}
			});

			if (fuck) {
				Materialize.toast('Some inputs is invalid', 2000);
				return false;
			}

			var isInvalid = false;
			$('.order_quantity').each(function(){
				var max_quantity = $(this).attr("max_qu			ntity");
				if (parseInt($(this).val()) > parseInt(max_quantity)) {
					$(this).next("label").attr('data-error','Invalid');
					$(this).removeClass("valid");
					$(this).addClass("invalid");
					isInvalid = true;
				}
			});

			if (isInvalid) {
				Materialize.toast('Some inputs have higher quantity than stock', 2000);
			}else {
				$('#customer_modal').modal("close");
				$('#customer_name_name').text($('#customer_name').val());
				$('#final_summary').html('');
				var grandTotal = 0;
				$('.order_quantity').each(function(){
					var quantity = $(this).val();
					var price = $(this).attr("item_price");
					var total = parseInt(quantity) * parseInt(price);
					var name = $(this).attr("item_name");
					grandTotal = grandTotal + total;
					$('<tr><td>'+name+'</td><td>'+numberWithCommas(quantity)+'</td><td>₱ '+numberWithCommas(total)+'.00</td></tr>').appendTo('#final_summary');
					
				});

				$('<tr><td></td><td><h5>Grandtotal:</h5></td><td><h5>₱ '+numberWithCommas(grandTotal)+'.00</h5></td></tr>').appendTo('#final_summary');

				

				$('#final_modal').modal("open");
			}
		}
	});

	$('#final_back').click(function(){
		$('#final_modal').modal("close");
		$('#customer_modal').modal("open");
		
	});

	$('#finish').click(function(){
		var num_of_items = $('.order_quantity').length;
		var customer = $('#customer_name').val();
		var data = [];
		var grandtotal = 0;
		$('.order_quantity').each(function(){
			var quantity = $(this).val();
			var price = $(this).attr("item_price");
			var total = parseInt(quantity) * parseInt(price);
			var id = $(this).attr("item_id");
			grandtotal = grandtotal + total;
			data.push(id, quantity);
		});
		var dataStr = data.toString();

		var d = new Date();
		var date = d.getDate();
		var year = d.getFullYear();
		var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
		var month = months[d.getMonth()];
		var today = date + " " + month + ", " + year;

		var action = "create sales order";
		$.ajax({
			url: "function.php",
			method: "POST",
			data: {action: action, dataStr: dataStr, num_of_items: num_of_items, grandtotal: grandtotal, today: today, customer: customer},
			success: function(data){
				if (data == "eut") {
					so_table.ajax.reload();
					alertify.success('Sales Order transaction complete');
					$('#final_modal').modal("close");
				}
			}
		});
	});

	$(document).on('click', '.print_receipt', (e) => {
		const datas = $(e.currentTarget).attr('datas');
		const customer = $(e.currentTarget).attr('customer');
		const date = $(e.currentTarget).attr('date');
		const price = $(e.currentTarget).attr('price');
		const sales_id = $(e.currentTarget).attr('sales_id');

		var action = "print sales order";
		$.ajax({
			url: "function.php",
			method: "POST",
			data: {action: action, datas: datas, customer: customer, date: date, price: price, sales_id: sales_id},
			success: function(data){
				
				var mywindow = window.open('', 'Reciept', 'height=800,width=1000');

				mywindow.document.write(data);

				mywindow.document.close(); // necessary for IE >= 10
				mywindow.focus(); // necessary for IE >= 10*/

				mywindow.print();
				// mywindow.close();

			}
		});

	});

});
</script>
