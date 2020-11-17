<?php
	session_start();
	if(!isset($_SESSION['admin']) || $_SESSION['admin'] != "Login"){
	  header("Location: login.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<!--Import Google Icon Font-
	<title>Home Page</title>->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--Import materialize.css-->
	  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<!-- DT -->
	<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>  
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" />

	<!-- JavaScript Alertify -->
	<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet">
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
		    font-family: Roboto Bold;
		    src: url(fonts/roboto/Roboto-Bold.woff2);
		}
		
	</style>
</head>
<body>
	

	<!-- START HERE -->
	<header>
		<nav>
			<div class="nav-wrapper">

				<a href="#" class="brand-logo right waves-effect waves-black"><img style="border-radius: 5px;" ></a>
				<ul class="nav navbar-nav">
					<li class="active waves-effect waves-black"><a href="#">Home</a></li>
					<li><a href="product.php">Product</a></li>
					<li><a href="price.php">Price</a></li>
					<li><a href="supplier.php">Supplier</a></li>
					<li><a href="purchase_order.php">Purchase Order</a></li>
					<li><a href="sales_order.php">Sales Order</a></li>
					<li><a class="" id="logout">LOGOUT <i class="material-icons left"></i></a></li>
				</ul>
			</div>
		</nav>
	</header>
	<main>
		<br>
		<div class="container">
			<div class="slider">
			   <ul class="slides">
			     <li>
			       <img src="">
			       <img src=""> <!-- random image -->
			       <img src="">
			       <img src="">
			       <div class="caption center-align">
			         <h3 style="font-family: Arkhip"></h3>
			         <h5 class="light grey-text text-lighten-3" style="color: ;"></h5>
			       </div>
			     </li>
			     <li>
			     	<img src=""> <!-- random image -->
			       <div class="caption right-align">
			      
			       </div>
			     </li>
			     <li>
			       <img src="" style="width: 960px; height: 600px;"> 
			       <div class="caption left-align">
			       </div>
			     </li>
			     <li>
			       <img src="" > <!-- random image -->
			       <div class="caption center-align">
			       </div>
			     </li>
			   </ul>
			 </div>

		</div>
		
	</main>
	<footer>
		
	</footer>
	     

</body>
</html>
<script type="text/javascript">
$(document).ready(function(){
	$('.slider').slider();

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