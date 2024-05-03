<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FMWare</title>
    <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
    <?php 
        include_once 'vendor/Bootstrap/css/bundle.php'; 
    ?>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
    }

    .logo {
      width: 475px; /* Adjust the size as needed */
      height: 475px; /* Adjust the size as needed */
    }
    
    .number {
      font-size: 6rem;
    }
  </style>
</head>
<body>
  <div class="container text-center py-5">
    <div class="d-flex justify-content-center align-items-center mb-4">
        <img src="/asset/images/store/logo.png" alt="Logo" class="logo mx-2">
      <h1 class="number">Your FMWare Account has been Activated!</h1>
    </div>
    <p class="lead">You will be redirected to the login page in five (5) seconds.</p>
    <p class="lead">Thank you!</p>
    <a href="/login" class="btn btn-primary">Go to Login</a>
  </div>

  <script>
    setTimeout(function(){
      window.location.href = "/login";
    }, 5000);
  </script>
</body>
</html>
