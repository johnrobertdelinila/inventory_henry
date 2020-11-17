<?php

  $connection = new PDO("mysql:host=localhost;dbname=henry", "root", "");
  session_start();
  if(isset($_SESSION['admin']) AND $_SESSION['admin'] == "Login"){
    header("Location: index.php");
  }

?>

<html>

<head>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <style>
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }

    main {
      flex: 1 0 auto;
    }

    body {
      background-color: #dcdcdc;
    }

    .input-field input[type=date]:focus + label,
    .input-field input[type=text]:focus + label,
    .input-field input[type=email]:focus + label,
    .input-field input[type=password]:focus + label {
      color: #e91e63;
    }

    .input-field input[type=date]:focus,
    .input-field input[type=text]:focus,
    .input-field input[type=email]:focus,
    .input-field input[type=password]:focus {
      border-bottom: 2px solid #e91e63;
      box-shadow: none;
    }
    @font-face {
        font-family: Arkhip;
        src: url(fonts/roboto/Arkhip_font.ttf);
    }
  </style>
</head>

<body>
  <div class="section"></div>
  <main>
    <center>
      <img class="responsive-img" style="width: 300px; height: 200px; border-radius: 10px;" src="ngek.jpg" />
      <div class="section"></div>

 
      <div class="container">
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

          <form class="col s12" method="post">
            <div class='row'>
              <div class='col s12'>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='email' name='email' id='email' />
                <label for='email'>Enter your email</label>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='password' name='password' id='password' />
                <label for='password'>Enter your password</label>
              </div>

            </div>

            <br />
            <center>
              <div class='row'>
                <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect waves-purple indigo' id="btn_login">Login</button>
              </div>
            </center>
          </form>
        </div>
      </div>
    </center>

    <div class="section"></div>
    <div class="section"></div>
  </main>

  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
</body>

</html>
<script type="text/javascript">
$(document).ready(function(){
  $('#btn_login').click(function(e){
    e.preventDefault();
    var email = $('#email').val().trim();
    var password = $("#password").val().trim();
    var action = "login admin";

    if (email == "" || password == "") {
      Materialize.toast('Username and password is required', 2000);
    }else {
      $.ajax({
        url: "function.php",
        method: "POST",
        data: {action: action, email: email, password: password},
        success: function(data){
          if (parseInt(data) <= 0) {
            Materialize.toast('Invalid username or password', 2000);
          }else {
            window.location.href = "index.php";
          }
        }
      });
    }
    

  });
});
</script>