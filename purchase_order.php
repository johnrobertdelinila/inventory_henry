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
	<title>Purchase Order</title>
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

		#invoice-POS{
		box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
		padding:2mm;
		margin: 0 auto;
		width: 154mm;
		background: #FFF;
		
		h1{
		font-size: 1.5em;
		color: #222;
		}
		h2{font-size: .9em;}
		h3{
		font-size: 1.2em;
		font-weight: 300;
		line-height: 2em;
		}
		p{
		font-size: .7em;
		color: #666;
		line-height: 1.2em;
		}
		
		#top, #mid,#bot{ /* Targets all id with 'col-' */
		border-bottom: 1px solid #EEE;
		}

		#top{min-height: 100px;}
		#mid{min-height: 80px;} 
		#bot{ min-height: 50px;}

		#top .logo{
		float: left;
			height: 60px;
			width: 60px;
			background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
			background-size: 60px 60px;
		}
		.clientlogo{
		float: left;
			height: 60px;
			width: 60px;
			background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
			background-size: 60px 60px;
		border-radius: 50px;
		}
		.info{
		display: block;
		float:left;
		margin-left: 0;
		}
		.title{
		float: right;
		}
		.title p{text-align: right;} 
		table{
		width: 100%;
		border-collapse: collapse;
		}
		td{
		padding: 5px 0 5px 15px;
		border: 1px solid #EEE
		}
		.tabletitle{
		padding: 5px;
		font-size: .5em;
		background: #EEE;
		}
		.service{border-bottom: 1px solid #EEE;}
		.item{width: 24mm;}
		.itemtext{font-size: .5em;}

		#legalcopy{
		margin-top: 5mm;
		}

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
					<li><a href="price.php">Price</a></li>
					<li><a href="supplier.php">Supplier</a></li>
					<li class="active waves-effect waves-black"><a href="#">Purchase Order</a></li>
					<li><a href="sales_order.php">Sales Order</a></li>
					<!--<li><a class="waves-effect waves-orange btn" id="logout">LOGOUT <i class="material-icons left">exit_to_app</i></a></li>-->
				</ul>
			</div>
		</nav>
	</header>

	<main>
		<div class="container">
				
			<br>
			
				<h4 style="font-family: Times New Roman">Purchase Order Table</h4>
	

			<div id="filter" class="rex" style="float: right;"></div>

			<table id="po_table" class="responsive-table highlight centered bordered hoverable z-depth-2" style="background-color: #FFFFFF; table-layout: fixed; width: 100%; font-family: Avenir;">
				<thead>
					<tr>
						<th>Print</th>
						<th>Recieve</th>
						<th>Status</th>
						<th>Order Date</th>
						<th>Delivery Date</th>
						<th>Supplier</th>
						<th>Delivery Report</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>

			<div class="fixed-action-btn" style="position: fixed;">
				<a class="btn-floating btn-large teal waves-effect waves-purple pulse tooltipped" id="create_po" data-position="left" data-delay="50" data-tooltip="Create Purchase Order">
					<i class="material-icons">note_add</i>
				</a>
			</div>

			<input type="text" class="datepicker" id="pickerhide" style="display: none;">


			<br><br><br><br>

		</div>
	</main>

	<div id="po_modal" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h4 style="font-family: Avenir;">Select Supplier</h4>
			<form id="select_supplier_form">
				<div class="container">

					<a class="btn-floating red waves-effect waves-purple" id="choose_date"><i class="material-icons">date_range</i></a>
					<div class="input-field inline">
						<input disabled type="text" class="datepicker" id="datepicker">
						<label for="datepicker">Delivery Date</label>
					</div>

					<div class="input-field col s12 m6">
						<select class="icons" id="supplier_select">
							<option value="" disabled selected>Choose your supplier</option>
						<?php

							$sql = $connection->prepare("SELECT * FROM suppliers");
							$sql->execute();
							$result = $sql->fetchAll();
							foreach($result as $row){
						?>
							<option value="<?php echo $row['name']; ?>" data-icon="supplier/<?php echo $row['photo']; ?>" class="left circle"><?php echo $row['name']; ?></option>
						<?php
							}
						?>
						</select>
						<label>Select supplier</label>
					</div>

				</div>
			</form>
		</div>
		<div class="modal-footer">
			<a class="modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
			<a class="modal-action waves-effect waves-purple btn-flat" id="next_button">Next</a>
		</div>
	</div>

	<div id="dr_modal" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h4 style="font-family: Avenir;">Delivery Report</h4>
			<form>
				<div class="container">

				<div id="invoice-POS">
    
					<center id="top">
						<div class="logo"></div>
					</center>
					
					<div id="mid">
						<div class="info">
							<h4>Supplier Info</h4>
							<p id="supplier_name"></p>
						</div>
					</div>
					
					<div id="bot">

									<div id="table">
										<table>
											<thead>
												<tr class="tabletitle">
													<td class="item"><h4>Item</h4></td>
													<td class="Hours"><h4>Remaining Qty</h4></td>
													<td class="Rate"><h4>Delivered</h4></td>
												</tr>
											</thead>
											<tbody id="row_container"></tbody>

										</table>
									</div><!--End Table-->

									<div id="legalcopy">
										<p class="legal"><strong>Thank you for your business!</strong>
										</p>
									</div>

								</div><!--End InvoiceBot-->
				</div><!--End Invoice-->
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<a class="modal-action modal-close waves-effect waves-green btn-flat">Cancel</a>
		</div>
	</div>

	<div id="bottom_item" class="modal bottom-sheet modal-fixed-footer">
		<div class="modal-content">
			<h4 style="font-family: Avenir;">Select Item</h4>
			<div class="container">

				<ul class="collection with-header">
				<?php

					$sql = $connection->prepare("SELECT * FROM items");
					$sql->execute();
					$result = $sql->fetchAll();
					foreach($result as $row){
				?>
					<li class="collection-item"><div><?php echo $row['name']; ?><div class="secondary-content"><input item_id="<?php echo $row['id']; ?>" item_name="<?php echo $row['name']; ?>" type="checkbox" class="filled-in check" id="<?php echo $row['id']; ?>" /><label for="<?php echo $row['id']; ?>">Choose</label></div></div></li>
				<?php
					}
				?>
					
				</ul>

			</div>
		</div>
		<div class="modal-footer">
			<a class="modal-action waves-effect waves-green btn-flat" id="back_item">Back</a>
			<a class="modal-action waves-effect waves-green btn-flat" id="agree">Select</a>
		</div>
	</div>

	<div id="quantity_item" class="modal">
		<div class="modal-content">
			<h4 style="font-family: Avenir;">Enter Order Quantity</h4>
			<div class="container">

				<div class="container" id="quantity_container">
					
				</div>

			</div>
		</div>
		<div class="modal-footer">
			<a class="modal-action waves-effect waves-green btn-flat" id="back_back">Back</a>
			<a class="modal-action waves-effect waves-green btn-flat" id="done_done">Done</a>
		</div>
	</div>

	<div id="recieve_modal" class="modal">
		<div class="modal-content">
			<h4 style="font-family: Avenir;">Recieve Order</h4>
			<div class="container">
				<input type="hidden" id="po_id">
				<div class="container" id="recieve_container">
					
				</div>

			</div>
		</div>
		<div class="modal-footer">
			<a class="modal-action modal-close waves-effect waves-green btn-flat" id="back_back">Cancel</a>
			<a class="modal-action waves-effect waves-green btn-flat" id="recieve_done">Done</a>
		</div>
	</div>
	

</body>
</html>
<script type="text/javascript">
$(document).ready(function(){

	$('.modal').modal();
	$('select').material_select();

	var po_table = $('#po_table').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"fetch_purchase_order.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[0, 1],
				"orderable":false,
			},
		],
	});

	$('div.dataTables_filter').appendTo("#filter");
	$('div.dataTables_filter').addClass("waves-effect waves-teal");


	var input = $('.datepicker').pickadate({
		min: new Date(),
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 15, // Creates a dropdown of 15 years to control year,
		today: 'Today',
		clear: 'Clear',
		close: 'Ok',
		closeOnSelect: false // Close upon selecting a date,
	});

	var picker = input.pickadate('picker');


	/*
	
	*/

	$('#choose_date').click(function(e){
		e.stopPropagation();
		picker.open();
	});

	picker.on("open", function(){
		Materialize.updateTextFields();
		$('#po_modal').modal("close");
	});

	picker.on('close', function(){
		var value = $('#pickerhide').val();
		$('#datepicker').val(value);
		Materialize.updateTextFields();
		$('#po_modal').modal("open");
	});

	$('#create_po').click(function(e){
		$('#select_supplier_form')[0].reset();
		$('#supplier_select').prop('selectedIndex',0);
		Materialize.updateTextFields();
		$('#po_modal').modal("open");
	});

	$('#next_button').click(function(){

		var select_supplier = $('#supplier_select').val();
		var datepicker = $('#datepicker').val();

		if (select_supplier == null || datepicker == "") {
			Materialize.toast('Please select delivery date and supplier.', 2000);
		}else {
			$('#po_modal').modal("close");
			$('.check').prop("checked", false);
			$('#bottom_item').modal("open");
		}

	});
	
	$('#back_item').click(function(){
		
		$('#bottom_item').modal("close");
		$('#po_modal').modal("open");
	});

	$('#agree').click(function(){
		if ($('.check:checkbox:checked').length <= 0) {
			Materialize.toast('Please select 1 or more item.', 2000);	
		}else {
			$('#quantity_container').html('');
			$('.check:checkbox:checked').each(function(){
				var id = $(this).attr("item_id");
				var name = $(this).attr("item_name");
				$('<div class="input-field col s12"><input id="input_'+id+'" type="number" class="validate order_quantity" item_id="'+id+'" item_name="'+name+'"><label for="input_'+id+'">Order quantity for '+name+'</label></div>').appendTo('#quantity_container');
			});
			$('#bottom_item').modal("close");
			$('#quantity_item').modal("open");
		}
	});

	$('#back_back').click(function(){
		$('#quantity_item').modal("close");
		$('#bottom_item').modal("open");
	});

	$('#done_done').click(function(){
		var isEmpty = false;
		$('.order_quantity').each(function(){
			if ($(this).val().trim() == "" || parseInt($(this).val()) <= 0) {
				isEmpty = true;
			}
		});

		if (isEmpty) {
			Materialize.toast('Failed. Some order quantity are invalid or empty.', 3000);
		}else {
			var d = new Date();
			var date = d.getDate();
			var year = d.getFullYear();
			var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
			var month = months[d.getMonth()];

			var today = date + " " + month + ", " + year;
			var select_supplier = $('#supplier_select').val();
			var datepicker = $('#datepicker').val();

			var data = [];
			var orig_quantity = [];
			$('.order_quantity').each(function(){
				var id = $(this).attr("item_id");
				var name = $(this).attr("item_name");
				var quantity = $(this).val();
				data.push(id, quantity, name);
				orig_quantity.push(quantity);
			});
			var dataStr = data.toString();
			var origStr = orig_quantity.toString();
			var action = "create purchase order";
			$('#quantity_item').modal("close");
			alertify.confirm("Admin Alert", "Submit this Purchase Order?", function(){
					
					$.ajax({
						async: false,
						url: "function.php",
						method: "POST",
						data: {data: dataStr, action: action, datepicker: datepicker, select_supplier: select_supplier, today: today, origStr: origStr},
						success: function(data){
							if (data == "inserted") {
								po_table.ajax.reload();
								alertify.success('Purchase order created successfully!');
							}
						}
					});
					
				},
				function(){
					$('#quantity_item').modal("open");
			});
			
		}
	});

	$(document).on('click', '.print_hehe', function(){
		var id = $(this).attr("po_id");
		var action = "printed po"
		alertify.confirm("Admin Alert", "Print this Purchase Order?", function(){

				$.ajax({
					async: false,
					url: "function.php",
					method: "POST",
					data: {action: action, id: id},
					success: function(data){
						if (data == "printed") {
							po_table.ajax.reload();
							alertify.success('Purchase order has been printed!');
						}
					}
				});
				
			},
			function(){
				// intentionally null
		});
	});

	$(document).on('click', '.receive_hehe', function(){
		var id = $(this).attr("po_id");
		$('#po_id').val(id);
		var data = $(this).attr("po_data");
		var original_data = $(this).attr("original_data");
		var data_arr = data.split(",");
		var original_data_arr = original_data.split(",");
		var q = 0
		$('#recieve_container').html('');
		for(var i = 0; i < data_arr.length; i+=3){
			var disabled = '';
			if (parseInt(data_arr[i+1]) <= 0) {
				disabled = 'disabled';
			}
			$('<div class="input-field col s6"><input '+disabled+' placeholder="Remaining quantity: '+data_arr[i+1]+'/'+original_data_arr[q]+'" remaining="'+data_arr[i+1]+'" id="recieve_'+data_arr[i]+'" original="'+original_data_arr[q]+'" item_id="'+data_arr[i]+'" item_name="'+data_arr[i+2]+'" type="text" class="validate recieve_input"><label for="recieve_'+data_arr[i]+'">'+data_arr[i+2]+'</label></div>').appendTo('#recieve_container');
			q++;
		}
		Materialize.updateTextFields();
		$('#recieve_modal').modal("open");
	});

	$('#recieve_done').click(function(){
		var isInvalid = false;
		$('.recieve_input').each(function(){
			var remaining = $(this).attr("remaining");
			if (parseInt($(this).val()) > parseInt(remaining)) {
				$(this).next("label").attr('data-error','Invalid. Check the remaining quantity');
				$(this).removeClass("valid");
				$(this).addClass("invalid");
				isInvalid = true;
			}
		});

		if (isInvalid) {
			Materialize.toast('Some inputs have higher recieve quantity than the remaining quantity', 2000);	
		}else {
			var data = [];
			var update = [];
			$('.recieve_input').each(function(){
				if ($(this).val().trim() != "" && parseInt($(this).val()) >= 1) {
					var remaining = parseInt($(this).attr("remaining"));
					var quantity = parseInt($(this).val());
					
					var id = $(this).attr("item_id");
					var new_quantity = remaining - quantity;
					var name = $(this).attr("item_name");
					data.push(id, new_quantity, name);
					update.push(id, quantity);
					
				}else {
					var id = $(this).attr("item_id");
					var new_quantity = parseInt($(this).attr("remaining"));
					var name = $(this).attr("item_name");
					data.push(id, new_quantity, name);
				}
			});

			var dataStr = data.toString();
			var updateStr = update.toString();
			var action = "recieve order";
			var po_id = $('#po_id').val();

			$.ajax({
				async: false,
				url: "function.php",
				method: "POST",
				data: {action: action, updateStr: updateStr, dataStr: dataStr, po_id: po_id},
				success: function(data){
					if (data == "done") {
						$('#recieve_modal').modal("close");
						po_table.ajax.reload();
						alertify.success('Admin successfully recieve item orders');
					}
				}
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

	$(document).on('click', '.delivery_report', (e) => {
		const data = $(e.currentTarget).attr('data').split(",");
		const orig = $(e.currentTarget).attr('orig').split(",");
		const supplier = $(e.currentTarget).attr('supplier');
		
		const products = [];
		
		var orig_index = 0;
		for(var i = 2; i < data.length; i+=3) {
			const product = {
				id: data[i - 2],
				curr_stock: data[i - 1],
				name: data[i],
				max_stock: orig[orig_index],
				delivered: parseInt(orig[orig_index]) - parseInt(data[i - 1])
			}
			products.push(product);
			orig_index++;
		}

		$('#supplier_name').text(supplier);

		var rows = '';
		products.forEach(product => {
			const row = '\
				<tr class="service">\
					<td class="tableitem"><p class="itemtext">'+ product.name +'</p></td>\
					<td class="tableitem"><p class="itemtext">' + product.curr_stock + '</p></td>\
					<td class="tableitem"><p class="itemtext">' + product.delivered + '</p></td>\
				</tr>\
			';
			rows += row;
		});

		$('#row_container').html(rows);

		$('#dr_modal').modal("open");

	});

});
</script>