<?php
    if(session_status() == PHP_SESSION_NONE){
      session_start();
    }

    require_once 'model/user/registerClass.php';

    $register = new Register();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if ($_POST['action'] == 'register') {
        $register->register();
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
          <form action="/fmware/register" method="POST">
            <div class="row mb-2">
              <h1>Register</h1>
            </div>
            <div class="form-floating mb-2">
              <input
                type="text"
                class="form-control"
                id="fname"
                placeholder=""
                aria-describedby="nameHelp"
                name="fname"
              />
              <label for="fname">First Name</label>
            </div>
            <div class="form-floating mb-2">
              <input
                type="text"
                class="form-control"
                id="lname"
                placeholder=""
                aria-describedby="nameHelp"
                name="lname"
              />
              <label for="lname">Last Name</label>
            </div>
            <div class="form-floating mb-2">
              <input
                type="email"
                class="form-control"
                id="email"
                placeholder=""
                aria-describedby="emailHelp"
                name="email"
              />
              <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-2">
              <input
                type="password"
                class="form-control"
                id="password"
                placeholder=""
                aria-describedby="passwordHelp"
                name="password"
              />
              <label for="password">Password</label>
            </div>
            <div class="form-floating mb-2">
              <input
                type="number"
                class="form-control"
                id="phone"
                placeholder=""
                aria-describedby="phoneHelp"
                name="phone"
              />
              <label for="phone">Mobile Number (+63)</label>
            </div>
            <div class="form-floating mb-2">
              <select class="form-select" id="sex" name="sex">
                  <option value="0">Male</option>
                  <option value="1">Female</option>
              </select>
              <label for="sex">Sex</label>
            </div>
            <div class="row mb-2">
              <div class="col">
                <div class="mb-3 form-check">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="showPasscheck"
                  />
                  <label class="form-check-label" for="showPasscheck"
                    >Show password</label
                  >
                </div>
              </div>
              <div class="col-auto text-end">
                <p class="text-secondary">Already have an account? <a href="/fmware/login">Login</a></p>
              </div>
            </div>
            <div class="row mb-2">
              <input type="hidden" name="action" value="register">
              <button type="submit" class="btn btn-primary mb-2">Register</button>
              <a class="btn btn-danger" href="/fmware">Back</a>
            </div>
          </form>
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
