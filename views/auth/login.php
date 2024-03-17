<?php
    if(session_status() == PHP_SESSION_NONE){
      session_start();
    }

    require_once 'model/user/user.php';
    require_once 'model/user/loginClass.php';  

    $user = new User();
    $login = new Login();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if ($_POST['action'] == 'login') {
        $login->login();
      }
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>FMWare</title>
      <link rel="icon" href="asset/images/store/logo.png" type="image/png">
      <!--Bootstrap CSS-->
      <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
      <!--Style-->
      <!--Fonts-->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
      <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
      <!--Google Captcha-->
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body class="bg-light">
    <!--Content-->
    <div class="container" style="height: 100vh">
      <div
        class="row d-flex align-items-center justify-content-center"
        style="height: 100vh"
      >
        <div
          class="col d-flex flex-column align-items-center justify-content-center"
        >
          <img src="asset/images/store/logo.png" alt="" style="width: 300px" />
          <h1 class="text-dark">FMWARE</h1>
          <h5 class="text-dark">
            RIGHT TOOLS AT THE RIGHT PRICE.
          </h5>
        </div>
        <div class="col shadow bg-light p-4 rounded">
          <form action="/fmware/login" method="POST">
            <div class="row mb-2">
              <h1>Login</h1>
            </div><div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center align-items-center mb-2">
                        <div id="login_response" class="text-danger"></div>
                    </div>
                </div>
              </div>
            <div class="form-floating mb-2">
              <input
                type="email"
                class="form-control"
                id="email"
                aria-describedby="emailHelp"
                name="email"
              />
              <div id="emailHelp" class="form-text">Enter your email here.</div>
              <label for="email">Email Address</label>
            </div>
            <div class="form-floating mb-2">
              <input
                type="password"
                class="form-control"
                id="password"
                aria-describedby="passwordHelp"
                name="password"
              />
              <div id="passwordHelp" class="form-text">
                Enter your password here.
              </div>
              <label for="password">Password</label>
            </div>
            <div class="row mb-2">
              <div class="col">
                <div class="mb-3 form-check">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="keepLogcheck"
                  />
                  <label class="form-check-label" for="keepLogcheck"
                    >Keep me logged in.</label
                  >
                </div>
              </div>
              <div class="col-auto text-end">
                <div><a href="">Forgot password</a></div>
              </div>
            </div>
              <!--Captcha-->
              <div class="container">
                <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center text-danger" id="captcha_response"></div>
                    <div class="col-12 d-flex justify-content-center align-items-center mb-2">
                      <div class="g-recaptcha" data-sitekey="6LfXNBMlAAAAAAL8J97bmb_be_LaBtwcAb6ZpJzJ"></div>
                    </div>
                </div>
              </div>
              <!--Captcha-->
            <div class="row mb-2">
              <input type="hidden" name="action" value="login">
              <button type="submit" class="btn btn-primary mb-2">Login</button>
              <a class="btn btn-danger" href="/fmware">Back</a>
            </div>
          </form>
            <div class="row text-center">
              <p>Don't have an account? <a href="/fmware/register">Register</a></p>
            </div>
        </div>
      </div>
    </div>
    <!--Content-->

    <!--jQuery-->
    <script src="vendor/jQuery/jquery-3.7.1.slim.min.js"></script>
    <!--Bootstrap JS-->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--Font Awesome-->
    <script src="vendor/fontawesome/js/all.min.js"></script>
    <!--Scripts-->
  </body>
</html>
