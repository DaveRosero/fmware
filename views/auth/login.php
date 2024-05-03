<?php include_once 'session.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Login | FMWare</title>
      <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
      <?php 
        include_once 'vendor/Bootstrap/css/bundle.php'; 
      ?>
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
          <img src="/asset/images/store/logo.png" alt="" style="width: 300px" />
          <h1 class="text-dark">FMWARE</h1>
          <h5 class="text-dark">
            RIGHT TOOLS AT THE RIGHT PRICE.
          </h5>
        </div>
        <div class="col shadow bg-light p-4 rounded">
          <form id="login">
            <div class="row mb-2">
              <h1>Login</h1>
            </div><div class="container">
              <p class="text-danger" id="login_feedback"></p>
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
            <div class="row mb-2">
              <input type="hidden" name="action" value="login">
              <button type="submit" class="btn btn-primary mb-2">Login</button>
              <a class="btn btn-danger" href="/">Back</a>
            </div>
          </form>
            <div class="row text-center">
              <p>Don't have an account? <a href="/register">Register</a></p>
            </div>
        </div>
      </div>
    </div>
    <!--Content-->

    <?php
      include_once 'vendor/jQuery/bundle.php';
      include_once 'vendor/FontAwesome/kit.php';
      include_once 'vendor/Bootstrap/js/bundle.php'; 
    ?>
    <script src="/asset/js/auth/login.js"></script>
  </body>
</html>
